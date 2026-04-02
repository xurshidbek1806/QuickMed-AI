import os
import tempfile
from aiogram import Router, F, Bot
from aiogram.types import Message, CallbackQuery
from aiogram.filters import CommandStart, Command
from aiogram.fsm.context import FSMContext

from states import ChatState
from api import api
from database import save_user, get_users_count
from keyboards import (
    gender_kb,
    age_kb,
    diseases_kb,
    doctors_kb,
    restart_kb,
    main_menu_kb,
    AGE_CATEGORIES,
)
from config import ADMIN_IDS

router = Router()


# ─── /start ────────────────────────────────────────────────────
@router.message(CommandStart())
async def cmd_start(message: Message, state: FSMContext):
    await state.clear()
    await save_user(message.from_user.id, message.from_user.full_name, message.from_user.username)
    await message.answer(
        "🏥 <b>Assalomu alaykum!</b>\n\n"
        "Men <b>QuickMedAI</b> — sun'iy intellekt yordamchingizman.\n"
        "Simptomlaringizni kiriting va dastlabki tashxis oling.\n\n"
        "⚠️ <i>Bu tizim shifokor maslahatini almashtirmaydi.</i>",
        reply_markup=main_menu_kb(),
    )


# ─── Bot haqida ────────────────────────────────────────────────
@router.message(F.text == "ℹ️ Bot haqida")
async def about(message: Message):
    await message.answer(
        "🤖 <b>QuickMedAI Bot</b>\n\n"
        "• AI yordamida dastlabki tashxis\n"
        "• Ovozli va matnli kiritish\n"
        "• Shifokor tavsiyalari\n\n"
        "Ishlab chiquvchi: @xurshidbek1806",
    )


# ─── Admin: statistika ────────────────────────────────────────
@router.message(Command("stats"))
async def stats(message: Message):
    if message.from_user.id not in ADMIN_IDS:
        return
    count = await get_users_count()
    health = await api.health()
    status = "✅ Ishlayapti" if health else "❌ Ishlamayapti"
    await message.answer(
        f"📊 <b>Statistika</b>\n\n"
        f"👥 Foydalanuvchilar: <b>{count}</b>\n"
        f"🖥 API: <b>{status}</b>",
    )


# ─── Tashxis boshlash ─────────────────────────────────────────
@router.message(F.text.in_({"💬 Tashxis olish", "/diagnose"}))
async def start_diagnose(message: Message, state: FSMContext):
    await state.clear()
    await state.set_state(ChatState.gender)
    await message.answer(
        "👤 <b>Jinsingizni tanlang:</b>",
        reply_markup=gender_kb(),
    )


# ─── 1. Jins ──────────────────────────────────────────────────
@router.callback_query(ChatState.gender, F.data.startswith("gender:"))
async def on_gender(call: CallbackQuery, state: FSMContext):
    gender = call.data.split(":")[1]
    label = "👨 Erkak" if gender == "male" else "👩 Ayol"
    await state.update_data(gender=gender)
    await state.set_state(ChatState.age)
    await call.message.edit_text(
        f"✅ Jins: <b>{label}</b>\n\n"
        "📅 <b>Yosh guruhingizni tanlang:</b>",
        reply_markup=age_kb(),
    )


# ─── 2. Yosh ──────────────────────────────────────────────────
@router.callback_query(ChatState.age, F.data.startswith("age:"))
async def on_age(call: CallbackQuery, state: FSMContext):
    age_val = call.data.split(":")[1]
    age_label = next((l for v, l in AGE_CATEGORIES if v == age_val), age_val)
    await state.update_data(age_category=age_val)

    # Kasalliklarni yuklash
    await call.message.edit_text("⏳ <i>Kasalliklar yuklanmoqda...</i>")
    diseases = await api.get_diseases(age_category=age_val)

    if not diseases:
        await call.message.edit_text(
            "❌ Kasalliklar topilmadi. /start bosing.",
            reply_markup=restart_kb(),
        )
        return

    await state.update_data(diseases=diseases, disease_page=0)
    await state.set_state(ChatState.disease)
    await call.message.edit_text(
        f"✅ Yosh: <b>{age_label}</b>\n\n"
        "🔍 <b>Kasallikni tanlang</b> yoki nomini yozing:",
        reply_markup=diseases_kb(diseases, page=0),
    )


# ─── 3a. Kasallik tanlash (tugma) ─────────────────────────────
@router.callback_query(ChatState.disease, F.data.startswith("disease:"))
async def on_disease_select(call: CallbackQuery, state: FSMContext):
    disease_id = call.data.split(":")[1]
    data = await state.get_data()
    diseases = data.get("diseases", [])
    disease = next((d for d in diseases if d["id"].startswith(disease_id)), None)
    name = disease["name"] if disease else "—"

    await state.update_data(disease_id=disease["id"] if disease else disease_id, disease_name=name)
    await state.set_state(ChatState.symptoms)
    await call.message.edit_text(
        f"✅ Kasallik: <b>{name}</b>\n\n"
        "📝 Endi <b>simptomlaringizni</b> batafsil yozing\n"
        "yoki 🎤 <b>ovozli xabar</b> yuboring:",
    )


# ─── 3b. Kasallik sahifalash ──────────────────────────────────
@router.callback_query(ChatState.disease, F.data.startswith("dpage:"))
async def on_disease_page(call: CallbackQuery, state: FSMContext):
    page = int(call.data.split(":")[1])
    data = await state.get_data()
    diseases = data.get("diseases", [])
    await state.update_data(disease_page=page)
    await call.message.edit_reply_markup(reply_markup=diseases_kb(diseases, page=page))


# ─── 3c. Kasallik qidirish (matn) ─────────────────────────────
@router.message(ChatState.disease, F.text)
async def on_disease_search(message: Message, state: FSMContext):
    query = message.text.strip().lower()
    data = await state.get_data()
    diseases = data.get("diseases", [])

    # So'zlar bo'yicha qidiruv
    words = [w for w in query.split() if len(w) >= 3]
    if not words:
        await message.answer("Kamida 3 harfdan iborat so'z kiriting.")
        return

    matches = []
    for d in diseases:
        name_lower = d["name"].lower()
        cat_lower = (d.get("category") or "").lower()
        score = sum(1 for w in words if w in name_lower or w in cat_lower)
        if score > 0:
            matches.append((score, d))

    matches.sort(key=lambda x: -x[0])
    found = [d for _, d in matches]

    if len(found) == 1:
        d = found[0]
        await state.update_data(disease_id=d["id"], disease_name=d["name"])
        await state.set_state(ChatState.symptoms)
        await message.answer(
            f"✅ Kasallik: <b>{d['name']}</b>\n\n"
            "📝 Endi <b>simptomlaringizni</b> batafsil yozing\n"
            "yoki 🎤 <b>ovozli xabar</b> yuboring:",
        )
    elif found:
        await state.update_data(disease_page=0)
        await message.answer(
            f"🔍 <b>{len(found)}</b> ta kasallik topildi. Birini tanlang:",
            reply_markup=diseases_kb(found[:20], page=0),
        )
    else:
        await message.answer(
            f"❌ \"{message.text}\" bo'yicha kasallik topilmadi.\n"
            "Qayta yozing yoki ro'yxatdan tanlang:",
            reply_markup=diseases_kb(diseases, page=0),
        )


# ─── 4a. Simptom — matn ───────────────────────────────────────
@router.message(ChatState.symptoms, F.text)
async def on_symptoms_text(message: Message, state: FSMContext, bot: Bot):
    symptoms = message.text.strip()
    if len(symptoms) < 3:
        await message.answer("Simptomlarni batafsil yozing (kamida 3 belgi).")
        return

    await _process_symptoms(message, state, symptoms)


# ─── 4b. Simptom — ovoz ───────────────────────────────────────
@router.message(ChatState.symptoms, F.voice)
async def on_symptoms_voice(message: Message, state: FSMContext, bot: Bot):
    wait_msg = await message.answer("⏳ <i>Ovoz tanib olinmoqda...</i>")

    # Yuklab olish
    file = await bot.get_file(message.voice.file_id)
    tmp = tempfile.NamedTemporaryFile(suffix=".ogg", delete=False)
    await bot.download_file(file.file_path, tmp.name)
    tmp.close()

    try:
        text = await api.transcribe_voice(tmp.name)
        await wait_msg.delete()

        if text:
            await message.answer(f"🎤 Matn: <i>{text}</i>")
            await _process_symptoms(message, state, text)
        else:
            await message.answer(
                "❌ Ovozni tanib bo'lmadi. Qayta yuboring yoki matn yozing."
            )
    finally:
        os.unlink(tmp.name)


# ─── Tahlil ───────────────────────────────────────────────────
async def _process_symptoms(message: Message, state: FSMContext, symptoms: str):
    data = await state.get_data()
    wait_msg = await message.answer("⏳ <i>AI tahlil qilmoqda...</i>")

    result = await api.analyze(
        disease_id=data["disease_id"],
        gender=data["gender"],
        age_category=data["age_category"],
        symptoms=symptoms,
    )

    await wait_msg.delete()

    if not result:
        await message.answer(
            "❌ Xatolik yuz berdi. Qayta urinib ko'ring.",
            reply_markup=restart_kb(),
        )
        await state.clear()
        return

    # AI javob
    analysis = result.get("analysis", "Natija olinmadi.")
    disease_name = result.get("disease", {}).get("name", data.get("disease_name", ""))
    doctors = result.get("doctors", [])

    text = (
        f"🏥 <b>Kasallik:</b> {disease_name}\n\n"
        f"🤖 <b>AI tahlil:</b>\n{analysis}"
    )

    # Telegram message limit
    if len(text) > 4000:
        text = text[:4000] + "..."

    await message.answer(text)

    # Shifokorlar
    if doctors:
        doc_text = "👨‍⚕️ <b>Tavsiya etilgan shifokorlar:</b>\n\n"
        for d in doctors[:5]:
            name = d.get("name", "")
            spec = d.get("specialization", "")
            clinic = d.get("clinic_name", "")
            phone = d.get("phone_number", "")
            doc_text += f"• <b>{name}</b> — {spec}\n"
            if clinic:
                doc_text += f"  🏥 {clinic}\n"
            if phone:
                doc_text += f"  📞 {phone}\n"
            doc_text += "\n"

        await message.answer(doc_text, reply_markup=restart_kb())
    else:
        await message.answer(
            "ℹ️ Shifokor topilmadi.",
            reply_markup=restart_kb(),
        )

    await state.clear()


# ─── Qayta boshlash ───────────────────────────────────────────
@router.callback_query(F.data == "restart")
async def on_restart(call: CallbackQuery, state: FSMContext):
    await state.clear()
    await call.message.answer(
        "👤 <b>Jinsingizni tanlang:</b>",
        reply_markup=gender_kb(),
    )
    await state.set_state(ChatState.gender)


# ─── noop ─────────────────────────────────────────────────────
@router.callback_query(F.data == "noop")
async def noop(call: CallbackQuery):
    await call.answer()
