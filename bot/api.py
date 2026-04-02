import aiohttp
from config import API_BASE_URL


class QuickMedAPI:
    """QuickMedAI backend API client."""

    def __init__(self, base_url: str = API_BASE_URL):
        self.base_url = base_url.rstrip("/")

    async def health(self) -> dict | None:
        async with aiohttp.ClientSession() as s:
            async with s.get(f"{self.base_url}/api/health") as r:
                if r.status == 200:
                    return await r.json()
        return None

    async def get_diseases(
        self, age_category: str | None = None, search: str | None = None
    ) -> list[dict]:
        params: dict[str, str] = {"per_page": "100"}
        if age_category:
            params["age_category"] = age_category
        if search:
            params["search"] = search

        async with aiohttp.ClientSession() as s:
            async with s.get(
                f"{self.base_url}/api/diseases", params=params
            ) as r:
                if r.status == 200:
                    data = await r.json()
                    return data.get("data", [])
        return []

    async def analyze(
        self,
        disease_id: str,
        gender: str,
        age_category: str,
        symptoms: str,
    ) -> dict | None:
        payload = {
            "disease_id": disease_id,
            "gender": gender,
            "age_category": age_category,
            "symptoms": symptoms,
        }
        headers = {
            "Content-Type": "application/json",
            "Accept": "application/json",
        }
        async with aiohttp.ClientSession() as s:
            async with s.post(
                f"{self.base_url}/api/chat/analyze",
                json=payload,
                headers=headers,
            ) as r:
                if r.status == 200:
                    return await r.json()
        return None

    async def transcribe_voice(self, file_path: str) -> str | None:
        async with aiohttp.ClientSession() as s:
            data = aiohttp.FormData()
            data.add_field(
                "audio",
                open(file_path, "rb"),
                filename="voice.ogg",
                content_type="audio/ogg",
            )
            async with s.post(
                f"{self.base_url}/api/chat/voice",
                data=data,
                headers={"Accept": "application/json"},
            ) as r:
                if r.status == 200:
                    result = await r.json()
                    return result.get("text")
        return None


api = QuickMedAPI()
