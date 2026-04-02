#!/bin/bash
set -e

# ══════════════════════════════════════════════════════════════════
# QuickMedAI — Server Deploy Script
# Server: 167.86.69.50
# Domain: ai.sysmasters.uz
# ══════════════════════════════════════════════════════════════════

APP_DIR="/root/quickmedai"
REPO_URL="https://gitlab.com/sysmasters/web/quickmedai.git"

echo "🚀 QuickMedAI deploy boshlandi..."

# ─── 1. Clone yoki pull ──────────────────────────────────────────
if [ ! -d "$APP_DIR" ]; then
    echo "📦 Repo clone qilinmoqda..."
    git clone "$REPO_URL" "$APP_DIR"
    cd "$APP_DIR"
else
    echo "📥 Yangi kod olinmoqda..."
    cd "$APP_DIR"
    git pull origin main
fi

# ─── 2. ENV fayllar ──────────────────────────────────────────────
if [ ! -f .env ]; then
    echo "⚙️  .env fayl yaratilmoqda..."
    cp .env.production .env
fi

if [ ! -f bot/.env ]; then
    echo "⚙️  bot/.env fayl yaratilmoqda..."
    cp bot/.env.production bot/.env
fi

# ─── 3. Database yaratish ────────────────────────────────────────
echo "🗄️  Database tekshirilmoqda..."
docker exec pgsql psql -U postgres -tc "SELECT 1 FROM pg_database WHERE datname='db_quickmed'" | grep -q 1 || \
    docker exec pgsql psql -U postgres -c "CREATE DATABASE db_quickmed;"

docker exec pgsql psql -U postgres -tc "SELECT 1 FROM pg_database WHERE datname='db_quickmedai_bot'" | grep -q 1 || \
    docker exec pgsql psql -U postgres -c "CREATE DATABASE db_quickmedai_bot;"

echo "✅ Database tayyor."

# ─── 4. Docker konteynerlarni ishga tushirish ────────────────────
echo "🐳 Docker konteynerlar ishga tushmoqda..."
docker compose up -d --build

# ─── 5. Composer install ─────────────────────────────────────────
echo "📦 PHP paketlar o'rnatilmoqda..."
docker compose exec -T app composer install --no-dev --optimize-autoloader --no-interaction

# ─── 6. Frontend build ───────────────────────────────────────────
echo "🎨 Frontend build qilinmoqda..."
docker run --rm -v "$(pwd)":/app -w /app node:20-alpine sh -c "npm ci && SKIP_WAYFINDER=1 npm run build"

# ─── 7. Laravel sozlash ──────────────────────────────────────────
echo "🔧 Laravel sozlanmoqda..."

# APP_KEY generatsiya (faqat bo'sh bo'lsa)
if grep -q "APP_KEY=$" .env; then
    docker compose exec -T app php artisan key:generate --force
fi

# Storage link
docker compose exec -T app php artisan storage:link --force 2>/dev/null || true

# Migrations
docker compose exec -T app php artisan migrate --force

# Permissions
docker compose exec -T app chown -R www-data:www-data storage bootstrap/cache
docker compose exec -T app chmod -R 775 storage bootstrap/cache

# Cache
docker compose exec -T app php artisan config:cache
docker compose exec -T app php artisan route:cache
docker compose exec -T app php artisan view:cache

# ─── 8. Bot ishga tushirish ──────────────────────────────────────
echo "🤖 Bot ishga tushmoqda..."
cd bot
docker compose up -d --build
cd ..

# ─── 9. Tekshirish ───────────────────────────────────────────────
echo ""
echo "══════════════════════════════════════════════════════"
echo "✅ QuickMedAI muvaffaqiyatli deploy qilindi!"
echo ""
echo "🌐 Web:  https://ai.sysmasters.uz"
echo "🤖 Bot:  @quick_med_ai_bot"
echo ""
echo "📊 Konteynerlar:"
docker ps --filter "name=quickmedai" --format "table {{.Names}}\t{{.Status}}\t{{.Ports}}"
echo "══════════════════════════════════════════════════════"
