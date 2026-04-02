from aiogram.types import (
    InlineKeyboardButton,
    InlineKeyboardMarkup,
    ReplyKeyboardMarkup,
    KeyboardButton,
)

# ─── Jins tanlash ──────────────────────────────────────────────
def gender_kb() -> InlineKeyboardMarkup:
    return InlineKeyboardMarkup(
        inline_keyboard=[
            [
                InlineKeyboardButton(text="👨 Erkak", callback_data="gender:male"),
                InlineKeyboardButton(text="👩 Ayol", callback_data="gender:female"),
            ]
        ]
    )


# ─── Yosh tanlash ──────────────────────────────────────────────
AGE_CATEGORIES = [
    ("godak", "👶 Chaqaloq (0-1)"),
    ("bola", "🧒 Bola (1-12)"),
    ("osmir", "🧑 O'smir (12-18)"),
    ("yoshlar", "👤 Yoshlar (18-30)"),
    ("orta_yosh", "🧔 O'rta yosh (30-60)"),
    ("keksalar", "👴 Keksalar (60+)"),
]


def age_kb() -> InlineKeyboardMarkup:
    rows = []
    for i in range(0, len(AGE_CATEGORIES), 2):
        row = []
        for val, label in AGE_CATEGORIES[i : i + 2]:
            row.append(InlineKeyboardButton(text=label, callback_data=f"age:{val}"))
        rows.append(row)
    return InlineKeyboardMarkup(inline_keyboard=rows)


# ─── Kasalliklar (inline keyboard, 2 columned) ─────────────────
def diseases_kb(diseases: list[dict], page: int = 0, per_page: int = 8) -> InlineKeyboardMarkup:
    start = page * per_page
    chunk = diseases[start : start + per_page]

    rows = []
    for i in range(0, len(chunk), 2):
        row = []
        for d in chunk[i : i + 2]:
            row.append(
                InlineKeyboardButton(
                    text=d["name"],
                    callback_data=f"disease:{d['id'][:36]}",
                )
            )
        rows.append(row)

    # Pagination
    nav = []
    if page > 0:
        nav.append(InlineKeyboardButton(text="⬅️ Oldingi", callback_data=f"dpage:{page - 1}"))
    if start + per_page < len(diseases):
        nav.append(InlineKeyboardButton(text="Keyingi ➡️", callback_data=f"dpage:{page + 1}"))
    if nav:
        rows.append(nav)

    return InlineKeyboardMarkup(inline_keyboard=rows)


# ─── Shifokorlar ───────────────────────────────────────────────
def doctors_kb(doctors: list[dict]) -> InlineKeyboardMarkup:
    rows = []
    for d in doctors[:10]:
        phone = d.get("phone_number", "")
        name = d.get("name", "")
        spec = d.get("specialization", "")
        text = f"👨‍⚕️ {name} — {spec}"
        if phone:
            rows.append(
                [InlineKeyboardButton(text=text, callback_data=f"doc:{d['id'][:36]}")]
            )
            rows.append(
                [InlineKeyboardButton(text=f"📞 {phone}", url=f"tel:{phone}")]
            )
        else:
            rows.append(
                [InlineKeyboardButton(text=text, callback_data="noop")]
            )
    return InlineKeyboardMarkup(inline_keyboard=rows)


# ─── Qayta boshlash ───────────────────────────────────────────
def restart_kb() -> InlineKeyboardMarkup:
    return InlineKeyboardMarkup(
        inline_keyboard=[
            [InlineKeyboardButton(text="🔄 Yangi suhbat", callback_data="restart")],
        ]
    )


# ─── Asosiy menu ──────────────────────────────────────────────
def main_menu_kb() -> ReplyKeyboardMarkup:
    return ReplyKeyboardMarkup(
        keyboard=[
            [KeyboardButton(text="💬 Tashxis olish")],
            [KeyboardButton(text="ℹ️ Bot haqida")],
        ],
        resize_keyboard=True,
    )
