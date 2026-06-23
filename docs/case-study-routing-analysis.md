# Case Study Routing Analysis

**Дата:** 2026-06-23  
**Статус:** Ожидает утверждения

---

## 1. Текущая архитектура данных

### CPT: `case_study`

Файл: [inc/cases-cpt.php](../inc/cases-cpt.php)

| Параметр | Значение |
|---|---|
| Post type slug | `case_study` |
| URL slug (rewrite) | `cases` |
| Архив | `/cases/` (has_archive: true) |
| Single post | `/cases/{post-slug}/` |
| REST API | включён (Gutenberg) |
| Поддерживает | title, editor, thumbnail, excerpt, revisions |

### Таксономии

#### `case_study_category` — Industries

| Параметр | Значение |
|---|---|
| Slug | `case-study-category` |
| URL | `/case-study-category/{term-slug}/` |
| Иерархическая | да |
| REST | включён |

#### `case_study_service` — Services

| Параметр | Значение |
|---|---|
| Slug | `case-study-service` |
| URL | `/case-study-service/{term-slug}/` |
| Иерархическая | да |
| REST | включён |

### ACF-поля на постах `case_study`

Файл: `acf-json/group_case_study_meta.json`

| Поле | Тип | Описание |
|---|---|---|
| `date_from` | text | Год/дата начала |
| `date_to` | text | Год/дата окончания или "ongoing" |
| `country` | text | Страна проекта |

---

## 2. Текущая реализация фильтрации

### Блок: `choose_cases_grid`

Файл: [template-parts/sections/choose_cases_grid.php](../template-parts/sections/choose_cases_grid.php)

Блок размещается на WordPress-странице (`/case-study/`). Это **не** CPT-архив — это обычная страница с блоком.

**Начальная загрузка (PHP):**
- Выполняет `WP_Query` без фильтров — 6 последних кейсов
- Рендерит все pills из `get_terms('case_study_category')` и `get_terms('case_study_service')`
- Передаёт `data-total`, `data-offset`, `data-per-page` в DOM

### AJAX-обработчик: `smplfy_ccg_ajax`

Файл: [inc/ccg_ajax.php](../inc/ccg_ajax.php)

| Параметр | Описание |
|---|---|
| Экшн | `ccg_ajax` (авторизованный и нет) |
| Защита | nonce `ccg_ajax` |
| `action_type` | `filter` или `load_more` |
| `taxonomy` | `case_study_category` или `case_study_service` |
| `term_ids` | JSON-массив ID выбранных терминов |
| `offset` | Смещение пагинации |
| `per_page` | Постов на страницу (по умолчанию 6) |

Ответ: `{ html, count, total, offset, has_more }`

### JavaScript: `choose_cases_grid.js`

Файл: [src/js/sections/choose_cases_grid.js](../src/js/sections/choose_cases_grid.js)

**Текущее поведение:**
- Переключение вкладок (Industries / Services) → сбрасывает фильтр → AJAX запрос
- Клик по pill → multi-select → AJAX запрос → заменяет innerHTML грида
- Load More → AJAX запрос → дополняет грид
- URL **не меняется** (нет `history.pushState`)
- Прямое открытие `/case-study/domain-information/` — работать **не будет**

---

## 3. Требуемые URL

| URL | Назначение | Фильтр |
|---|---|---|
| `/case-study/` | Все кейсы | нет |
| `/case-study/domain-information/` | По отрасли | Industry: information |
| `/case-study/domain-agriculture/` | По отрасли | Industry: agriculture |
| `/case-study/domain-healthcare/` | По отрасли | Industry: healthcare |
| `/case-study/services-mobile-development/` | По сервису | Service: mobile-development |
| `/case-study/services-web-development/` | По сервису | Service: web-development |

**Паттерн:** `/case-study/{prefix}-{term-slug}/`  
- Prefix `domain-` → таксономия `case_study_category`  
- Prefix `services-` → таксономия `case_study_service`

---

## 4. Варианты реализации URL-архитектуры

### Вариант A: Кастомные rewrite-правила + taxonomy archives ✅ (Рекомендуется)

**Принцип:** WordPress по-прежнему использует taxonomy archives, но URL генерируется через кастомные rewrite rules.

**Изменения в CPT:**
```php
'rewrite' => ['slug' => 'case-study'],  // было: 'cases'
```
Архив CPT становится `/case-study/`.

**Кастомные rewrite rules:**
```php
add_rewrite_rule(
    '^case-study/(domain-[^/]+)/?$',
    'index.php?case_study_category=$matches[1]',
    'top'
);
add_rewrite_rule(
    '^case-study/(services-[^/]+)/?$',
    'index.php?case_study_service=$matches[1]',
    'top'
);
```

**Слаги терминов в БД** (нужно переименовать):
- Industry "Information" → slug: `domain-information`
- Industry "Agriculture" → slug: `domain-agriculture`
- Service "Mobile Development" → slug: `services-mobile-development`

**Разрешение URL-конфликтов:**
- `/case-study/domain-information/` → захватывается custom rule → `?case_study_category=domain-information` → taxonomy archive → **корректно**
- `/case-study/client-post-slug/` → не подходит под prefix правила → default CPT rule → single post → **корректно**
- `/case-study/` → CPT archive rule → `?post_type=case_study` → **корректно**

**Шаблоны, которые создаст WordPress:**
1. `taxonomy-case_study_category.php` — для industry фильтров
2. `taxonomy-case_study_service.php` — для service фильтров
3. `archive-case_study.php` — для `/case-study/` (вместо текущей страницы с блоком)

**Плюсы:**
- Полноценные WordPress taxonomy archives
- Правильные `get_term_link()` URLs (при правильной настройке)
- Корректный `get_queried_object()` в шаблонах
- SEO-правильные canonical теги
- Работает pagination (`/page/2/`)

**Минусы:**
- Нужно переименовать слаги всех терминов в БД (контентное изменение)
- Нужно удалить или редиректнуть старую страницу `/case-study/`
- Нужно сбросить permalink cache после изменений
- Потенциальный риск: если слаг нового поста начинается с `domain-` или `services-`, он перехватится taxonomy rule (решается документацией)

---

### Вариант B: Страница с кастомными query vars (Проще, но менее чисто)

**Принцип:** Оставить `/case-study/` как WordPress-страницу. Добавить rewrite rules, которые redirect к той же странице с дополнительными query vars.

```php
add_rewrite_rule(
    '^case-study/(domain-[^/]+)/?$',
    'index.php?pagename=case-study&case_filter=$matches[1]',
    'top'
);
```

Шаблон страницы читает `get_query_var('case_filter')` и применяет фильтр.

**Плюсы:**
- Не нужно менять CPT slug
- Не нужно трогать taxonomy archive templates
- Меньше изменений в структуре WordPress

**Минусы:**
- Не настоящие taxonomy archives → SEO-проблемы (canonical, Rank Math не распознаёт как taxonomy)
- `get_queried_object()` возвращает Page, не Term → нельзя использовать стандартные SEO-хелперы
- Нужны ручные meta description / canonical для каждого URL
- Не работают стандартные breadcrumbs для таксономий
- Сложнее поддерживать

---

### Вариант C: Два уровня URL через taxonomy slug (Альтернатива)

**Принцип:** Использовать нативные taxonomy archives с вложенными URL.

```php
// case_study_category
'rewrite' => ['slug' => 'case-study/domain'],
// case_study_service
'rewrite' => ['slug' => 'case-study/services'],
```

URL становятся:
- `/case-study/domain/information/`
- `/case-study/services/mobile-development/`

**Плюсы:**
- Полностью нативный WordPress, без кастомных rules
- Слаги терминов не нужно менять

**Минусы:**
- URL не соответствует требованиям заказчика (двойной слеш, а не дефис)
- Может конфликтовать с CPT archive `/case-study/` (WordPress видит `/case-study/domain/information/` как 3-уровневый путь)

---

## 5. Риски конфликтов маршрутизации

### Основная проблема: один base slug для двух таксономий

Если обе таксономии имеют один и тот же base slug в rewrite, WordPress регистрирует одинаковые regex-паттерны для разных query vars. Побеждает первая из registered (порядок определяется порядком вызова `register_taxonomy`).

**Решение в Варианте A:** Кастомные rules с prefix-паттернами (`domain-` vs `services-`) разводят таксономии на уровне regex, поэтому конфликта нет.

### Конфликт: CPT single post vs taxonomy term URL

При slug CPT = `case-study`:
- Single: `/case-study/my-case/`
- Taxonomy: `/case-study/domain-information/`

Кастомные rules с priority `'top'` исполняются первыми. Если slug не начинается с `domain-` или `services-`, он не попадёт под taxonomy rule и будет обработан как CPT post. Конфликта нет при соблюдении именования.

### Конфликт: существующая страница vs CPT archive

Если WordPress-страница со slug `case-study` существует в базе, и CPT имеет rewrite slug `case-study`, возникает конфликт. WordPress приоритизирует страницы над CPT archives.

**Решение:** Страницу с slug `case-study` нужно либо удалить, либо переименовать, и настроить 301-редирект.

---

## 6. Архитектура шаблонов

### Текущие файлы

| Файл | Назначение |
|---|---|
| `page.php` (+ блок `choose_cases_grid`) | `/case-study/` — текущая реализация |
| `single-case_study.php` | Single case post |

### Новые файлы (Вариант A)

| Файл | Назначение | URL |
|---|---|---|
| `archive-case_study.php` | Все кейсы без фильтра | `/case-study/` |
| `taxonomy-case_study_category.php` | Кейсы по Industry | `/case-study/domain-{slug}/` |
| `taxonomy-case_study_service.php` | Кейсы по Service | `/case-study/services-{slug}/` |

**Стратегия переиспользования:** Все три шаблона могут включать один shared partial `template-parts/cases-listing.php`, который:
- Определяет контекст (`is_tax()`, `is_post_type_archive()`)
- Выполняет правильный `WP_Query` (с `tax_query` или без)
- Рендерит те же карточки через `template-parts/partials/case-card.php`
- Показывает фильтр-tabs с активной pill (подсвеченной)
- Рендерит Load More кнопку

### Переиспользование `choose_cases_grid.php`

**Проблема:** Сейчас блок читает ACF-поля (padding, marquee_text, decor). Эти поля не будут доступны в taxonomy archive context (нет `$block`).

**Решение:** Разделить блок на два компонента:
1. `choose_cases_grid.php` — остаётся для ACF-блока на страницах
2. Новый partial `template-parts/cases-listing.php` — для taxonomy archives, принимает аргументы через PHP

Оба выводят одинаковый HTML с одинаковыми CSS классами.

---

## 7. Изменения JavaScript

### Что остаётся без изменений
- Load More (AJAX-пагинация)
- Tabs UI (переключение между Industries и Services)
- CSS-классы, анимация, загрузка

### Что удаляется
- `doFilter()` функция — AJAX-фильтрация
- Обработчики кликов по `.ccg-pill` → AJAX

### Что заменяется

**Было:** клик по pill → AJAX → обновление innerHTML грида

**Станет:** клик по pill → навигация по URL

Каждый pill будет содержать ссылку:
```html
<a href="/case-study/domain-information/" class="ccg-pill">Information</a>
```

Активная pill (соответствующая текущему URL) подсвечивается классом `is-active`, который добавляется PHP, а не JS.

### Новая логика JS (упрощённая)

```js
// Только:
// 1. Переключение вкладок (показ/скрытие групп pills) — без AJAX
// 2. Load More — остаётся AJAX (тот же обработчик)
// 3. Восстановление активной вкладки из URL (при загрузке страницы)
```

**Определение активной вкладки при загрузке:**
```js
// PHP уже добавил is-active к нужной pill и tab
// JS только обеспечивает, что правильная группа pills видима
```

---

## 8. SEO-последствия

### Sitemap

Taxonomy archives автоматически попадают в sitemap большинства SEO-плагинов.

| Плагин | Поведение |
|---|---|
| Rank Math | Распознаёт taxonomy archives, добавляет в sitemap, генерирует meta из taxonomy description |
| Yoast | Аналогично |

При использовании Варианта B (page + custom query vars) — sitemap придётся настраивать вручную.

### Canonical

При Варианте A: WordPress + SEO плагин автоматически устанавливают canonical на правильный taxonomy URL.

При Варианте B: нужно вручную реализовать canonical через хук `wp_head`.

### Индексация

- Taxonomy archives индексируются как отдельные страницы
- Каждый URL `/case-study/domain-information/` — самостоятельная страница с уникальным контентом
- Нет проблемы duplicate content (у каждой страницы свой набор кейсов)

### Breadcrumbs (Rank Math)

При Варианте A: Rank Math правильно определяет breadcrumb как `Home > Case Study > Domain > Information`.

При Варианте B: Rank Math видит это как обычную страницу, breadcrumb будет неправильным.

### 301-редиректы

Необходимо настроить редиректы для старых URLs:
- `/cases/` → `/case-study/` (старый CPT archive)
- `/case-study-category/{term}/` → `/case-study/domain-{term}/` (старые taxonomy URLs)
- `/case-study-service/{term}/` → `/case-study/services-{term}/` (старые taxonomy URLs)

---

## 9. Полный план внедрения

### Шаг 1 — Переименование taxonomy term slugs в БД

**Риск: средний** (изменение контента, нужен backup)

- В WP Admin → Cases → Industries: для каждого термина изменить slug
  - `information` → `domain-information`
  - `agriculture` → `domain-agriculture`
  - `healthcare` → `domain-healthcare`
- В WP Admin → Cases → Services:
  - `mobile-development` → `services-mobile-development`
  - `web-development` → `services-web-development`
- Добавить 301-редиректы для старых слагов (через Rank Math редиректы или `.htaccess`)

**Условие завершения:** все термины переименованы, старые URLs редиректят на новые

---

### Шаг 2 — Изменение CPT rewrite slug

**Риск: средний** (ломает существующие URLs)

В [inc/cases-cpt.php](../inc/cases-cpt.php):
```php
// Было:
'rewrite' => ['slug' => 'cases'],
// Станет:
'rewrite' => ['slug' => 'case-study'],
```

- Удалить или переименовать WordPress-страницу со slug `case-study` (конфликт с CPT archive)
- Сбросить permalinks: WP Admin → Settings → Permalinks → Save

**Условие завершения:** `/case-study/` открывает CPT archive, `/cases/` → 404 или redirect

---

### Шаг 3 — Добавление кастомных rewrite rules

**Риск: низкий** (аддитивное изменение)

Добавить в [inc/cases-cpt.php](../inc/cases-cpt.php):
```php
function theme_cases_rewrite_rules(): void {
    add_rewrite_rule(
        '^case-study/(domain-[^/]+)/?$',
        'index.php?case_study_category=$matches[1]',
        'top'
    );
    add_rewrite_rule(
        '^case-study/(services-[^/]+)/?$',
        'index.php?case_study_service=$matches[1]',
        'top'
    );
}
add_action('init', 'theme_cases_rewrite_rules');
```

- Сбросить permalinks после добавления

**Условие завершения:** `/case-study/domain-information/` открывает taxonomy archive term `domain-information`

---

### Шаг 4 — Создание шаблонов

**Риск: низкий** (новые файлы, не затрагивают существующие)

Создать:
1. `archive-case_study.php` — template для CPT archive
2. `taxonomy-case_study_category.php` — template для industry filter
3. `taxonomy-case_study_service.php` — template для service filter
4. `template-parts/cases-listing.php` — shared partial (грид + фильтры)

Логика `cases-listing.php`:
- `is_post_type_archive('case_study')` → query без `tax_query`, все pills без `is-active`
- `is_tax('case_study_category')` → query с `tax_query` по текущему терму, нужная pill с `is-active`
- `is_tax('case_study_service')` → аналогично

Активная вкладка определяется по таксономии текущего архива.

**Условие завершения:** все три URL рендерят правильно отфильтрованные списки кейсов

---

### Шаг 5 — Рефакторинг JavaScript

**Риск: низкий** (изолированный файл)

В [src/js/sections/choose_cases_grid.js](../src/js/sections/choose_cases_grid.js):
- Удалить `doFilter()` и AJAX-обработчики для pills
- Переключение tabs оставить (только показ/скрытие groupы pills, без AJAX)
- Load More оставить полностью (AJAX)
- Добавить логику определения активной вкладки по DOM (PHP уже проставит `is-active`)

**Условие завершения:** клик по pill → переход по URL, Load More → AJAX pagination работает

---

### Шаг 6 — Обновление HTML разметки pills

**Риск: низкий**

В `template-parts/cases-listing.php` (и в `choose_cases_grid.php` для страниц-блоков):
- `<button class="ccg-pill">` → `<a href="{taxonomy_term_url}" class="ccg-pill">` (или `<button onclick="navigate()">`)
- Активная pill получает `is-active` через PHP на основе `is_tax()` + `get_queried_object()`

**Условие завершения:** links кликабельны, вызывают навигацию, не AJAX

---

### Шаг 7 — 301-редиректы и SEO

**Риск: низкий**

- `/cases/` → `/case-study/` (301)
- `/case-study-category/information/` → `/case-study/domain-information/` (301)
- `/case-study-service/mobile-development/` → `/case-study/services-mobile-development/` (301)

Реализация через Rank Math Redirections или через `wp_redirect` в `functions.php`.

Проверить в Rank Math:
- Taxonomy archives включены в sitemap
- Canonical URLs генерируются правильно

**Условие завершения:** старые URLs редиректят, Rank Math показывает taxonomy pages в sitemap

---

### Шаг 8 — AJAX load more адаптация

**Риск: низкий**

AJAX-обработчик `smplfy_ccg_ajax` уже поддерживает `tax_query`. Нужно:
- При server-rendered taxonomy page передавать в DOM активную таксономию и терм для Load More
- JS читает `data-active-taxonomy` и `data-active-term` из `.ccg-grid` (уже есть в разметке)
- AJAX load more использует эти значения для pagination

**Условие завершения:** Load More на `/case-study/domain-information/` грузит следующие 6 кейсов из той же таксономии

---

## 10. Итоговая рекомендация

**Рекомендуется Вариант A** — кастомные rewrite rules + taxonomy archives.

**Почему:**
1. Настоящие taxonomy archives → Rank Math / Yoast корректно генерируют meta, canonical, sitemap
2. Правильный `get_queried_object()` в шаблонах → breadcrumbs работают автоматически
3. Load More AJAX уже поддерживает `tax_query` — минимальные изменения в PHP
4. URL-паттерн разводит таксономии через prefix, конфликтов нет
5. Пагинация работает нативно (`/case-study/domain-information/page/2/`)

**Ключевые изменения:**
- `inc/cases-cpt.php`: slug `cases` → `case-study`, добавить custom rewrite rules
- Новые PHP шаблоны (3 файла + 1 partial)
- Переименование term slugs в БД
- Рефакторинг JS (удалить AJAX filter, оставить Load More + tabs)
- 301-редиректы

**Что НЕ меняется:**
- `choose_cases_grid.php` блок (остаётся для использования на страницах через Gutenberg)
- `inc/ccg_ajax.php` (Load More продолжает использовать тот же обработчик)
- `template-parts/partials/case-card.php` (карточка без изменений)
- Все single case templates
- SCSS (структура классов та же)

---

## Приложение: Дерево файлов после внедрения

```
inc/
  cases-cpt.php           ← ИЗМЕНЁН: slug 'cases'→'case-study', +custom rewrite rules

archive-case_study.php    ← НОВЫЙ: template для /case-study/
taxonomy-case_study_category.php  ← НОВЫЙ: /case-study/domain-{slug}/
taxonomy-case_study_service.php   ← НОВЫЙ: /case-study/services-{slug}/

template-parts/
  cases-listing.php       ← НОВЫЙ: shared partial (грид + фильтры, server-rendered)
  sections/
    choose_cases_grid.php ← БЕЗ ИЗМЕНЕНИЙ (ACF-блок на страницах)
  partials/
    case-card.php         ← БЕЗ ИЗМЕНЕНИЙ

src/js/sections/
  choose_cases_grid.js    ← ИЗМЕНЁН: удалить AJAX filter, оставить tabs + load more
```
