# Отчёт по оптимизации (production)

## ЭТАП 1. Анализ — что было найдено

- **Лишние загрузки:** категории запрашивались в каждом контроллере (MainController::index/category, BasketController::basket/basketConfirm, ShopController::index) и дополнительно в CategoriesComposer без кеша.
- **Повторные запросы к БД:** на страницах категории и магазина при выводе карточек товаров — N+1 по `product`, `product.category`, `propertyOptions`; в карточке — `file_exists(public_path(...))` в цикле на каждое изображение.
- **Синхронный JS:** все скрипты в конце страницы без `defer` — блокировали разбор и могли влиять на FCP.
- **Внешние ресурсы:** шрифты и иконки (Google Fonts, Bootstrap Icons, Font Awesome) в `<head>` — без отложенной загрузки.

## ЭТАП 2–4. Внесённые правки

| Файл | Строка/блок | Что изменено | Зачем | Ожидаемый эффект |
|------|-------------|--------------|--------|-------------------|
| **app/ViewComposers/CategoriesComposer.php** | `compose()` | Категории загружаются через `Cache::remember('view_categories', 3600, ...)` с `Category::orderBy('name')->get()` | Убрать запрос категорий на каждый рендер | −1 запрос к БД на каждый запрос страницы (десятки запросов/мин в пике) |
| **app/Providers/ViewServiceProvider.php** | View::composer для currencySymbol | Символ валюты через `Cache::remember('currency_symbol_'.$currencyCode, 3600, ...)` по коду из сессии | Не вызывать Currency::get() на каждый запрос | −1 запрос к БД на запрос, быстрее отдача HTML |
| **app/Http/Controllers/MainController.php** | `category()` | Добавлен `->with(['product','product.category','propertyOptions'])` к запросу SKU перед `paginate(32)` | Устранить N+1 при выводе карточек в категории | С ~33+ запросов до 2–3 на страницу категории (десятки мс) |
| **app/Http/Controllers/MainController.php** | `index()` | Убрана загрузка категорий; в `randomSkus` и `newSkus` добавлен `propertyOptions` в `with()` | Категории только из композера (кеш); нет N+1 в карточках на главной | −1 запрос, меньше запросов при рендере карточек |
| **app/Http/Controllers/MainController.php** | `category()` | Удалены загрузка и передача `$categories` (меню берётся из композера) | Один источник категорий и кеш | Меньше дублирования, стабильный кеш |
| **app/ViewComposers/BestProductsComposer.php** | `compose()` | В `with()` добавлен `propertyOptions` для bestSkus | Убрать N+1 по propertyOptions в карточках на главной | Несколько запросов меньше на главной |
| **app/Http/Controllers/ShopController.php** | `index()` | Добавлен `->with(['product','product.category','propertyOptions'])` к пагинации SKU; убрана передача `$categories` | N+1 в магазине; категории из композера | С десятков запросов до 2–3 на страницу магазина |
| **app/Http/Controllers/BasketController.php** | `basket()`, `basketConfirm()` | Убраны `Category::all()` и передача `$categories` в view | Категории для меню из композера (кеш) | −2 запроса на корзину и оформление |
| **app/Http/Controllers/Admin/CategoryController.php** | `store()`, `update()`, `destroy()` | После изменений категорий вызывается `Cache::forget('view_categories')` | Инвалидация кеша при изменении категорий в админке | Актуальное меню без перезапуска |
| **resources/views/card.blade.php** | Блок изображения | Удалён `file_exists(public_path(...))`; для img добавлены `loading="lazy"`, `decoding="async"`, при отсутствии файла — `onerror` на placeholder | Не дергать диск в цикле; отложенная загрузка изображений | Меньше блокировок, быстрее FCP, меньше трафика |
| **resources/views/index.blade.php** | Баннеры (desktop и mobile) | Для img баннеров добавлены `loading="lazy"`, `decoding="async"` | Отложенная загрузка ниже первого экрана | Лучше LCP/FCP для первого экрана |
| **resources/views/layouts/master.blade.php** | Подключение скриптов | Удалён `popper.js` (уже в bootstrap.bundle); порядок: jquery → bootstrap → swiper → glightbox → script → cart; всем тегам script добавлен `defer` | Не блокировать парсинг; корректный порядок (jQuery до Bootstrap) | Быстрее FCP, стабильная работа скриптов |
| **resources/views/layouts/master.blade.php** | head | Добавлен preload для style.css перед основными CSS | Раньше начать загрузку основного стиля | Несколько мс выигрыша по отрисовке |

## Что не менялось (без изменения логики и вида)

- Логика расчёта корзины, избранного, фильтров, оплаты.
- Количество и состав подключаемых CSS (bootstrap, swiper, glightbox, style.css) — один основной кастомный файл по-прежнему `style.css`.
- Внешний вид (классы, inline-стили в шаблонах оставлены).
- jQuery оставлен и подключён первым из скриптов (на случай зависимостей Bootstrap/плагинов).

## Рекомендации для production (без правок кода)

Выполнять на сервере после деплоя:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

- **config:cache** — уменьшает время ответа за счёт кеша конфигурации.
- **route:cache** — меньше времени на разбор маршрутов.
- **view:cache** — скомпилированные Blade-шаблоны.

После изменения конфигурации, маршрутов или шаблонов — перегенерировать кеш или сбросить:

```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## Ожидаемый суммарный эффект

- **First Contentful Paint:** быстрее за счёт `defer` у скриптов, preload для style.css и lazy-изображений.
- **Количество запросов к БД:** на главной и в списках товаров — с десятков до 2–5 на страницу за счёт кеша категорий/валюты и eager loading.
- **Время ответа сервера:** меньше за счёт кеша категорий и символа валюты и отсутствия лишних запросов в циклах (N+1 и file_exists).

Если какая-то правка даст сомнительный выигрыш при риске поломки — её не делали (например, не трогали порядок/набор CSS и не объединяли внешние шрифты/иконки без тестов).
