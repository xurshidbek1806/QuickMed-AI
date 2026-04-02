import os
from dotenv import load_dotenv

load_dotenv()

BOT_TOKEN = os.getenv("BOT_TOKEN", "")
BOT_USERNAME = os.getenv("BOT_USERNAME", "quickmedai_bot")
ADMIN_IDS = [int(x) for x in os.getenv("ADMIN_IDS", "").split(",") if x.strip()]
DATABASE_URL = os.getenv("DATABASE_URL", "")

# QuickMedAI API — nginx container on same docker network
API_BASE_URL = os.getenv("API_BASE_URL", "http://quickmedai-nginx")
