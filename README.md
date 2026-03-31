# QuickMedAI

Tibbiy maslahat beruvchi sun'iy intellekt platformasi. Foydalanuvchi o'z simptomlarini yozadi yoki ovoz orqali aytadi — tizim kasallikni aniqlaydi, shifoxona va shifokor tavsiya qiladi.

## Loyiha haqida

QuickMedAI — bu O'zbekiston bozoriga mo'ljallangan tibbiy AI platformasi. Odamlar ko'pincha kasal bo'lganda qaysi shifokorga borishni, qaysi shifoxonaga murojaat qilishni bilishmaydi. Biz shu muammoni hal qilamiz.

Foydalanuvchi chatga kirib o'z holatini yozadi yoki ovozli xabar yuboradi. Tizim OpenAI orqali simptomlarni tahlil qiladi va ma'lumotlar bazasidagi kasalliklar bilan solishtiradi. Natijada tegishli kasallik, unga ixtisoslashgan shifokorlar va eng yaqin shifoxonalar tavsiya qilinadi.

Platformada ikki turdagi admin panel mavjud:
- **Super Admin** — barcha shifoxonalar, kasalliklar, shifokorlar va bannerlarni boshqaradi
- **Klinika Admin** — faqat o'z shifoxonasi doirasida kasallik, shifokor, tavsiya va bannerlarni boshqaradi

## Texnologiyalar

**Backend:**
- Laravel 13 (PHP 8.3+)
- PostgreSQL
- OpenAI GPT-4o (simptom tahlili)
- OpenAI Whisper (ovozni matnga o'girish, o'zbek tilida)

**Frontend:**
- Vue 3.5 + TypeScript
- Inertia.js v3 (SPA tajribasi, backend routing)
- Tailwind CSS 4
- Vite 8

**Infratuzilma:**
- Docker (PHP-FPM + Nginx)
- Laravel Fortify (autentifikatsiya, 2FA)

## Mobil ilova

Hozirda QuickMedAI mobil ilovasi ustida ham ish olib borilmoqda. Mobil versiya React Native (Expo) asosida ishlab chiqilmoqda va asosiy chat funksiyasi, ovozli kiritish, shifokor va shifoxona tavsiyalari mobil qurilmalarda ham ishlaydi. Ilova iOS va Android uchun chiqariladi.

## Ishga tushirish

```bash
# Docker bilan
docker-compose build
docker-compose up -d

# Migratsiya
docker exec quickmedai-app php artisan migrate

# .env faylda OPENAI_API_KEY ni to'ldiring
```

Sayt: http://localhost:8082

## Loyiha tuzilmasi

```
app/
  Models/          — Eloquent modellar (User, Clinic, Disease, Doctor, ...)
  Services/        — OpenAI bilan ishlash (GPT-4o, Whisper)
  Http/Controllers — API va web controllerlar
resources/js/
  pages/           — Vue sahifalar (Welcome, Chat, Auth, Admin, Clinic)
  layouts/         — Admin va Klinika layoutlari
  components/      — Qayta ishlatiladigan UI komponentlar
database/
  migrations/      — Ma'lumotlar bazasi strukturasi
docker-compose.yml — Docker konfiguratsiya
```

## Muallif

Loyiha O'zbekiston uchun ishlab chiqilgan. Savollar bo'lsa, bog'laning.
