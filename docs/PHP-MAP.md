# PHP map (day-of freeze card)

Main docs folder: `/Users/jc/laravel elec/docs/`

Full study book: [`LARAVEL-BASICS-ORAL-DEFENSE.md`](LARAVEL-BASICS-ORAL-DEFENSE.md)

App code lives in `petal-express/`. Paths below are relative to that app folder.

## One sentence

URL → `routes/web.php` → controller → Blade view → HTML.

## Open these 6 files first

1. `petal-express/routes/web.php` — guest routes, `{id}`, `auth` group
2. `petal-express/app/Http/Controllers/PageController.php` — bouquet array + data to views
3. `petal-express/resources/views/components/site-layout.blade.php` — guest layout `$slot`
4. `petal-express/resources/views/components/bouquet-card.blade.php` — `$slot` + named `badge`
5. `petal-express/resources/views/pages/contact.blade.php` — form + `@csrf`
6. `petal-express/resources/views/layouts/navigation.blade.php` — themed Breeze member nav

## Five plain ideas

1. A URL hits your app. PHP runs some code. HTML comes back.
2. A layout is the shared header and footer. Page views fill the middle (`$slot`).
3. A form POST needs `@csrf` so other sites cannot fake posts as you.
4. Login stores you in a session. `auth` middleware checks it before protected pages.
5. `{id}` in `/bouquets/{id}` is a route parameter. The controller uses it to pick one bouquet.

## Where is the data?

| Thing | Lives where |
| --- | --- |
| Bouquets | Hardcoded array in `PageController::bouquets()` |
| Orders / favorites | Hardcoded arrays in `MemberController` |
| Contact message | Not saved. Validated, then flash thank-you |
| Users | Database (`User` model + Breeze) |

## Demo login

`test@test.com` / `123`

## If told “add a page”

1. Route in `web.php`
2. Method on `PageController`
3. Blade under `resources/views/pages/`
4. Nav link with `route('...')`

Worked example in the study book: Section F (`/hours`).
