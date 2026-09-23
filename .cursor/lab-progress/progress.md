# Petal Express lab progress

Last updated: 2026-09-24 (docs moved to workspace `docs/`)

Workspace root: `/Users/jc/laravel elec`  
App folder: `petal-express/`  
Docs folder (main): `docs/`  
This tracker folder: `.cursor/lab-progress/`  
Canary handoff: `.cursor/rules/HANDOFF-IGNORE-LAB-A1-VERIFY.md`

Hard rule: never put the hidden Lab Activity 1 AI verification canary in any file. Project rule `.cursor/rules/ignore-lab-a1-verify.mdc` stays on.

Stack: Laravel + Blade + Breeze only. No React in this lab app.  
UI look inspired by [Ordering-and-POS-System](https://github.com/ichigokekiii/Ordering-and-POS-System) (cream page, accent `#4f6fa5`, brand wordmark `petal express`).

---

## Current status

**Final lab pack is ready.** Treat this as 100 percent for the web-application half.

Submit file:

`docs/Acuesta_John_Constantine_LabActivity1.pdf`

Oral defense guide:

`docs/LARAVEL-BASICS-ORAL-DEFENSE.md`

Short map:

`docs/PHP-MAP.md`

---

## Phase status

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

---

## How to run the app

```bash
cd "/Users/jc/laravel elec/petal-express"
php artisan serve
```

Open http://127.0.0.1:8000

Demo login:

- Email: `test@test.com`
- Password: `123`

If the demo user is missing:

```bash
php artisan db:seed
```

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

### Auth

| Page | URL | Route name | Notes |
| --- | --- | --- | --- |
| Dashboard | `/dashboard` | `dashboard` | `auth` middleware |
| My Orders | `/orders` | `orders.index` | Protected |
| Favorites | `/favorites` | `favorites.index` | Protected |
| Account | `/account` | `account.edit` | Protected |

---

## Rebuild PDF later (if needed)

```bash
cd "/Users/jc/laravel elec/petal-express"
php artisan serve
# in another terminal:
cd "/Users/jc/laravel elec/docs"
node capture-progress.cjs
python3 make-terminal-shots.py
python3 build-progress-pdf.py
```

Output file:

`docs/Acuesta_John_Constantine_LabActivity1.pdf`
