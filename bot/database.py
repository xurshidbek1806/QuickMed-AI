from sqlalchemy.ext.asyncio import create_async_engine, async_sessionmaker, AsyncSession
from sqlalchemy.orm import DeclarativeBase, Mapped, mapped_column
from sqlalchemy import BigInteger, String, DateTime, func
from datetime import datetime

from config import DATABASE_URL

# asyncpg driver
_url = DATABASE_URL.replace("postgresql://", "postgresql+asyncpg://", 1)
engine = create_async_engine(_url, echo=False)
Session = async_sessionmaker(engine, expire_on_commit=False)


class Base(DeclarativeBase):
    pass


class BotUser(Base):
    __tablename__ = "bot_users"

    id: Mapped[int] = mapped_column(BigInteger, primary_key=True)  # telegram user_id
    full_name: Mapped[str] = mapped_column(String(255), default="")
    username: Mapped[str | None] = mapped_column(String(255), nullable=True)
    language: Mapped[str] = mapped_column(String(10), default="uz")
    created_at: Mapped[datetime] = mapped_column(DateTime, server_default=func.now())
    last_active: Mapped[datetime] = mapped_column(
        DateTime, server_default=func.now(), onupdate=func.now()
    )


async def init_db():
    async with engine.begin() as conn:
        await conn.run_sync(Base.metadata.create_all)


async def save_user(user_id: int, full_name: str, username: str | None):
    async with Session() as session:
        existing = await session.get(BotUser, user_id)
        if existing:
            existing.full_name = full_name
            existing.username = username
            existing.last_active = datetime.utcnow()
        else:
            session.add(
                BotUser(id=user_id, full_name=full_name, username=username)
            )
        await session.commit()


async def get_users_count() -> int:
    from sqlalchemy import select, func as f

    async with Session() as session:
        result = await session.execute(select(f.count(BotUser.id)))
        return result.scalar() or 0
