# Отчёт по оптимизации SQL и нагрузки на БД

## ЭТАП 1. Анализ — что было найдено

- **N+1:** в корзине при проверке наличия — по одному `Sku::find()` на каждый товар; на странице заказа (show) — загрузка `product` для каждого SKU и `currency`/`coupon` у заказа; в Wishlist — отдельный запрос на каждую категорию для рекомендаций.
- **Запросы в циклах:** `Basket::countAvailable()` — цикл по `order->skus` с `Sku::find($id)`; WishlistController — цикл по категориям с запросом SKU по категории.
- **Лишняя загрузка:** `Coupon::availableForUse()` — `$this->orders->count() === 0` загружал все заказы купона.
- **Индексы:** в `order_sku` не было индексов по `order_id`/`sku_id`; в `categories` — по `code`; в `skus` — по `product_id`; в `orders` — составного индекса по `(status, created_at)` для списка заказов в админке.

---

## ЭТАП 2–5. Внесённые правки

| Файл | Строка | SQL / Eloquent ДО | SQL / Eloquent ПОСЛЕ | Ожидаемый выигрыш |
|------|--------|-------------------|----------------------|--------------------|
| **app/Classes/Basket.php** | `countAvailable()` | В цикле: `Sku::find($orderSku->id)` — N запросов | `Sku::whereIn('id', $ids)->get()->keyBy('id')`, затем выборка из коллекции | С N запросов до 1 при оформлении заказа |
| **app/Http/Controllers/Admin/OrderController.php** | `show()` | `$order->skus()->withTrashed()->get()`; в шаблоне: `$sku->product`, `$order->currency`, `$order->coupon` | `$order->load(['currency','coupon'])`; `$order->skus()->withTrashed()->with('product')->get()` | Нет N+1 по product/currency/coupon; меньше запросов на страницу заказа |
| **app/Http/Controllers/Admin/OrderController.php** | `confirm()` | Обращение к `$order->user` без предзагрузки | `$order->load('user')` в начале метода | Один явный запрос вместо lazy load |
| **app/Http/Controllers/Admin/OrderController.php** | `cancel()` | `foreach($order->skus as $sku)` — lazy load skus | `$order->load('skus')` перед циклом | Один запрос на загрузку skus |
| **app/Http/Controllers/Person/OrderController.php** | `show()` | `$skus = $order->skus`; в шаблоне: `$sku->product`, `$order->currency`, `$order->coupon` | `$order->load(['skus.product','currency','coupon'])`, затем `$skus = $order->skus` | Нет N+1 по product/currency/coupon |
| **app/Models/Coupon.php** | `availableForUse()` | `$this->orders->count() === 0` — загрузка всех заказов купона | `!$this->orders()->exists()` | Один запрос EXISTS вместо SELECT + COUNT |
| **app/Http/Controllers/WishlistController.php** | `index()` | В цикле по `$categoryIds`: каждый раз `Sku::with(...)->whereHas(...)->whereNotIn(...)->take(2)->get()` | Один запрос: `Sku::with('product.category')->whereHas(..., whereIn('category_id', $categoryIds))->whereNotIn(...)->limit($limit)->get()`, затем в PHP до 2 SKU на категорию | С N запросов (по числу категорий) до 1 на странице избранного |
| **database/migrations/2025_09_13_120000_add_query_indexes_for_performance.php** | — | Нет индексов на `order_sku(order_id, sku_id)`, `categories(code)`, `skus(product_id)`, `orders(status, created_at)` | Добавлены: `order_sku` — index `order_id`, index `sku_id`; `categories` — index `code`; `skus` — index `product_id`; `orders` — index `(status, created_at)` | Быстрее JOIN/WHERE по этим полям; быстрее список заказов по статусу и дате |

---

## Кеширование (уже было сделано ранее)

- **Категории:** `Cache::remember('view_categories', 3600, ...)` в CategoriesComposer — TTL 1 ч, т.к. меняются редко; сброс при изменении в админке.
- **Символ валюты:** `Cache::remember('currency_symbol_' . $code, 3600, ...)` в ViewServiceProvider — TTL 1 ч по коду валюты.

Дополнительное кеширование для этой задачи не вводилось.

---

## Что не менялось

- Логика корзины, заказов, купонов, избранного.
- Редко вызываемый код: админка баннеров/визитов, API Skus (один запрос с `with('product')`), дерево продуктов, форма создания продукта — без изменений.
- Raw SQL не использовался; только Eloquent и миграции.

---

## Применение миграции

```bash
php artisan migrate
```

Чтобы откатить индексы:

```bash
php artisan migrate:rollback --step=1
```

---

## Итог

- **N+1 убраны:** корзина (оформление), страница заказа (админ и пользователь), избранное (рекомендации), проверка купона.
- **Количество запросов снижено:** оформление заказа (с N+1 до 1 по SKU), страница заказа (eager load), избранное (с N до 1), купон (exists вместо count).
- **Скорость частых запросов:** индексы по `order_sku`, `categories.code`, `skus.product_id`, `orders(status, created_at)` уменьшают время выборок и JOIN’ов.
