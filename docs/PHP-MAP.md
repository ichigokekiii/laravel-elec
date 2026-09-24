# PHP map (day-of freeze card)

Main docs folder: `/Users/jc/laravel-elec/docs/`

Full from-zero teacher book: [`LARAVEL-BASICS-ORAL-DEFENSE.md`](LARAVEL-BASICS-ORAL-DEFENSE.md)

App code lives in `petal-express/`. Paths below are relative to that app folder.

Branches: `labact1` (frozen Lab Act 1) · `labact2` (current Lab Act 2 work) · `main` (latest when merged)

## One sentence

**Lab Act 1:** URL → `routes/web.php` → controller → Blade → HTML.

**Lab Act 2:** URL → `auth` → `role` middleware → controller → Eloquent/`showcase_flowers` in SQLite → Blade.

## MVC in one line

Routes = door map · Controller = waiter · Model = kitchen data · View = the plate (HTML).

## Open these files first

### Lab Act 1

1. `petal-express/routes/web.php` — guest routes, `{id}`, `auth` group
2. `petal-express/app/Http/Controllers/PageController.php` — bouquet array + data to views
3. `petal-express/resources/views/components/site-layout.blade.php` — guest layout `$slot`
4. `petal-express/resources/views/components/bouquet-card.blade.php` — `$slot` + named `badge`
5. `petal-express/resources/views/pages/contact.blade.php` — form + `@csrf`
6. `petal-express/resources/views/layouts/navigation.blade.php` — themed Breeze member nav

### Lab Act 2 (add these)

1. `petal-express/bootstrap/app.php` — `role` middleware alias
2. `petal-express/app/Http/Middleware/EnsureUserHasRole.php`
3. `petal-express/app/Models/Post.php` + `User.php` (`role`, relationships)
4. `petal-express/app/Http/Controllers/AdminPostController.php`
5. `petal-express/app/Http/Controllers/UserPostController.php`
6. `petal-express/database/migrations/*_create_showcase_flowers_table.php` and `*_add_role_to_users_table.php`

## Five plain ideas

1. A URL hits your app. PHP runs some code. HTML comes back.
2. A layout is the shared header and footer. Page views fill the middle (`$slot`).
3. A form POST needs `@csrf` so other sites cannot fake posts as you.
4. Login stores you in a session. `auth` middleware checks it. `role` middleware checks admin vs user.
5. Migrations build/change SQLite tables. Eloquent `Post` reads and writes `showcase_flowers` rows.

## Where is the data?

| Thing | Lives where |
| --- | --- |
| Bouquets | Hardcoded array in `PageController::bouquets()` |
| Orders / favorites | Hardcoded arrays in `MemberController` |
| Contact message | Not saved. Validated, then flash thank-you |
| Users + `role` | SQLite `users` table (`User` model + Breeze) |
| Showcase flowers | SQLite `showcase_flowers` table (`Post` model, Eloquent) |
| User “suggest post” form | Not saved (static, like contact) |

Database file: `petal-express/database/database.sqlite` (`DB_CONNECTION=sqlite`).

Show tables in VS Code with a SQLite extension (open that file).

## Demo logins

| Role | Email | Password |
| --- | --- | --- |
| User | `test@test.com` | `123` |
| Admin | `admin@test.com` | `123` |

```bash
cd /Users/jc/laravel-elec/petal-express
php artisan migrate
php artisan db:seed
php artisan serve
```

Admin posts: http://127.0.0.1:8000/admin/posts  
User show: http://127.0.0.1:8000/posts/1  
User static form: http://127.0.0.1:8000/posts/submit

## If told “add a page”

1. Route in `web.php`
2. Method on a controller
3. Blade under `resources/views/`
4. Nav link with `route('...')`

Worked Lab Act 1 example in the teacher book: **Section 5.6** (`/hours`).  
Lab Act 2 posts: **Part 7** in [`LARAVEL-BASICS-ORAL-DEFENSE.md`](LARAVEL-BASICS-ORAL-DEFENSE.md).
