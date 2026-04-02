from aiogram.fsm.state import State, StatesGroup


class ChatState(StatesGroup):
    gender = State()          # Jins tanlash
    age = State()             # Yosh tanlash
    disease = State()         # Kasallik tanlash / qidirish
    symptoms = State()        # Simptom kiritish (matn yoki ovoz)
