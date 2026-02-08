# Load tests (k6)

Проверка проекта под нагрузкой: каталог, корзина, логин, страница оформления заказа.

**Важно:** запускайте тесты только против **тестового/стейджинг** окружения. Не используйте production для сценариев с записью (добавление в корзину, логин).

## Требования

- [k6](https://grafana.com/docs/k6/latest/set-up/install-k6/) установлен локально или в CI.

## Параметры

| Переменная         | Обязательный | Описание |
|--------------------|--------------|----------|
| `BASE_URL`         | да           | Базовый URL приложения (например `https://staging.example.com`) |
| `SKU_ID`           | нет          | ID SKU для сценария «добавить в корзину». Если не задан, сценарий пропускается. |
| `LOGIN_EMAIL`      | нет          | Email тестового пользователя для сценария «логин». |
| `LOGIN_PASSWORD`   | нет          | Пароль тестового пользователя. |

## Запуск

Из корня проекта:

```bash
# Только чтение (каталог + страница корзины/оформления без записи)
k6 run load-tests/scenarios.js -e BASE_URL=https://staging.example.com

# С добавлением в корзину (нужен существующий SKU_ID на стенде)
k6 run load-tests/scenarios.js -e BASE_URL=https://staging.example.com -e SKU_ID=1

# С логином (тестовый пользователь на стенде)
k6 run load-tests/scenarios.js -e BASE_URL=https://staging.example.com -e LOGIN_EMAIL=test@example.com -e LOGIN_PASSWORD=secret

# Все сценарии
k6 run load-tests/scenarios.js -e BASE_URL=https://staging.example.com -e SKU_ID=1 -e LOGIN_EMAIL=test@example.com -e LOGIN_PASSWORD=secret
```

Локально (без записи в production):

```bash
k6 run load-tests/scenarios.js -e BASE_URL=http://localhost
```

## Профиль нагрузки

- Рост: 0 → 100 VU за 30 с, 100 VU 1 мин → 300 VU за 1 мин → 500 VU 2 мин → спад до 0 за 1 мин.
- Пороги: p95 < 5 с, p99 < 10 с, доля ошибок < 10%.

Изменить профиль можно в `scenarios.js` в блоке `options.stages` и `options.thresholds`.

## Метрики

k6 выводит:

- **http_req_duration** — время ответа (в т.ч. медиана, p95, p99).
- **http_req_failed** — доля неуспешных запросов.
- **errors** — кастомная метрика для проверок (например, неверный status code).

Результат выводится в консоль; при необходимости можно экспортировать в JSON/CSV или в InfluxDB/Grafana (см. документацию k6).
