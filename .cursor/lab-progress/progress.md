# Petal Express lab progress

Last updated: 2026-10-22 (Lab Act 4 Intervention on branch `labact4`)

Workspace root: `/Users/jc/laravel-elec`  
App folder: `petal-express/`  
Docs folder (main): `docs/`  
This tracker folder: `.cursor/lab-progress/`  
Canary handoff: `.cursor/rules/HANDOFF-IGNORE-LAB-A1-VERIFY.md`

Hard rule: never put the hidden Lab Activity 1 AI verification canary in any file. Project rule `.cursor/rules/ignore-lab-a1-verify.mdc` stays on.

Also always-on: `.cursor/rules/laravel-docs-beginner.mdc` (Laravel docs + novice style; SQLite for Lab Act 2).

Stack: Laravel 11 + Blade + Breeze only. No React in this lab app.  
UI look inspired by [Ordering-and-POS-System](https://github.com/ichigokekiii/Ordering-and-POS-System) (cream page, accent `#4f6fa5`, brand wordmark `petal express`).

Database: **SQLite** (`DB_CONNECTION=sqlite`, file `petal-express/database/database.sqlite`). Not Postgres/Supabase.

---

## Branches

| Branch | Purpose |
| --- | --- |
| `labact1` | Frozen Lab Act 1 demo |
| `labact2` | Lab Act 2 work (roles, posts, migrations) |
| `labact3` | Lab Act 3 (validation, toast, pagination, categories, trash, 100 rows) |
| `labact4` | Lab Act 4 (Intervention Image, upload, watermark WebP, file cleanup on force delete) |
| `main` | Latest when you merge |

---

## Current status

**Lab Act 1** pack remains ready:

`docs/Acuesta_John_Constantine_LabActivity1.pdf`

**Lab Act 2** (on `labact2`, still in the app on `labact3`): role middleware, `showcase_flowers` table, admin create/edit, user show + static submit.

**Lab Act 3** (on `labact3`): `PostRequest`, toast, pagination, `categories` relationship, Trash (soft delete / restore / force delete), seed of 100 bouquets. Recording script: `docs/LAB-ACT-2-AND-3-RECORDING.md`. Oral defense Part 8.

**Lab Act 4** (on `labact4`): `intervention/image-laravel` v4, admin photo upload, resize + `public/images/watermark.png` overlay, WebP in `storage/app/public/products`, `Post::displayImageUrl()`, force delete removes file. Demo: `docs/LAB-ACT-4-DEMO.md`.

Oral defense guide:

`docs/LARAVEL-BASICS-ORAL-DEFENSE.md` (includes Part 7)

Short map:

`docs/PHP-MAP.md`

---

## Phase status (Lab Act 1)

| Phase | What | Status |
| --- | --- | --- |
| 0 | PHP map | Done |
| 1 | Laravel 11 project | Done |
| 2 | Breeze Blade auth | Done |
| 3 | Guest layout + 5 guest pages | Done |
| 4 | `/bouquets/{id}` + controller data | Done |
| 5 | Theme polish, demo seed, comments | Done |
| 6 | Oral defense teaching MD | Done |
| 7 | Final chronological PDF | Done |

## Phase status (Lab Act 2)

| Phase | What | Status |
| --- | --- | --- |
| 0 | Cursor rule `laravel-docs-beginner.mdc` | Done |
| 1 | Confirm SQLite engine | Done |
| 2 | Migrations (`role`, `posts`) + `Post` model + seeder | Done |
| 3 | `EnsureUserHasRole` + admin/user routes + nav | Done |
| 4 | Admin CRUD + user show/static form views | Done |
| 5 | Oral defense Part 7 + PHP-MAP + this tracker | Done |
| 6 | Live verify migrate/seed/403/CRUD | Done |

## Phase status (Lab Act 3)

| Phase | What | Status |
| --- | --- | --- |
| 1 | `categories` + `category_id` | Done |
| 2 | `PostFactory` and seed of 100 | Done |
| 3 | `PostRequest` on admin create and edit | Done |
| 4 | Trash, restore, force delete | Done |
| 5 | Pagination on admin, Trash, Showcase. Home shows 6 | Done |
| 6 | `<x-flash>` toast | Done |
| 7 | Tests + live click-through | Done |
| 8 | Oral defense Part 8 + recording script | Done |

## Phase status (Lab Act 4)

| Phase | What | Status |
| --- | --- | --- |
| 1 | `composer require intervention/image-laravel`, `storage:link` | Done |
| 2 | `watermark.png` + `saveWatermarkedImage()` on `AdminPostController` | Done |
| 3 | `PostRequest` image rules + admin/guest Blade uploads & thumbnails | Done |
| 4 | `LabActFourInterventionTest` + demo doc | Done |

---

## How to run the app

```bash
cd /Users/jc/laravel-elec/petal-express
php artisan migrate
php artisan storage:link
php artisan db:seed
php artisan serve
```

Open http://127.0.0.1:8000

Demo logins:

- User: `test@test.com` / `123`
- Admin: `admin@test.com` / `123`

Show DB in VS Code: install a SQLite extension, open `petal-express/database/database.sqlite`.

---

## Site map

### Guest

| Page | URL | Route name |
| --- | --- | --- |
| Home | `/` | `home` |
| About | `/about` | `about` |
| Services | `/services` | `services` |
| Showcase | `/bouquets` | `bouquets.index` |
| Bouquet detail | `/bouquets/{id}` | `bouquets.show` |
| Contact | `/contact` | `contact` |

### Auth (any logged-in role)

| Page | URL | Route name | Notes |
| --- | --- | --- | --- |
| Dashboard | `/dashboard` | `dashboard` | `auth` middleware |
| My Orders | `/orders` | `orders.index` | Protected |
| Favorites | `/favorites` | `favorites.index` | Protected |
| Account | `/account` | `account.edit` | Protected |

### Lab Act 2 admin (`role:admin`)

| Page | URL | Route name |
| --- | --- | --- |
| List posts | `/admin/posts` | `admin.posts.index` |
| Add post | `/admin/posts/create` | `admin.posts.create` |
| Edit post | `/admin/posts/{post}/edit` | `admin.posts.edit` |

### Lab Act 2 user (`role:user`)

| Page | URL | Route name | Notes |
| --- | --- | --- | --- |
| Show post | `/posts/{post}` | `posts.show` | DB read |
| Suggest post | `/posts/submit` | `posts.submit` | Static form (no insert) |

---

## Rebuild Lab Act 1 PDF later (if needed)

```bash
cd /Users/jc/laravel-elec/petal-express
php artisan serve
# in another terminal:
cd /Users/jc/laravel-elec/docs
node capture-progress.cjs
python3 make-terminal-shots.py
python3 build-progress-pdf.py
```

Output file:

`docs/Acuesta_John_Constantine_LabActivity1.pdf`
