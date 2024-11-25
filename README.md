# Music Catalog Project

Этот тестовый проект представляет собой музыкальный каталог, работающий с использованием Docker и Docker Compose. Он включает в себя Laravel-приложение и Swagger UI для документирования API.

## Требования

- [Docker](https://www.docker.com/) (включая Docker Compose)
- [Git](https://git-scm.com/) (если вы клонируете репозиторий)

## Шаги для запуска проекта

### 1. Клонируйте репозиторий (если еще не сделали)

Если вы не клонировали проект, выполните следующую команду:

```bash
git clone https://github.com/sanyapoison/music-catalog
cd music-catalog
```

Запустите контейнеры с помощью Docker Compose:
```bash
docker-compose up -d
```

Если вы хотите увидеть логи контейнеров, используйте:
```bash
docker-compose logs -f
```

Доступ к приложениям:

Laravel приложение (API): http://localhost:8000.

Swagger UI (документация API): http://localhost:8000/api/documentation/