# Lab Act 4 demo checklist (Intervention + CRUD)

App path: `petal-express/`. Bouquets = instructor “products.”

## Before you record

1. `cd petal-express`
2. `composer install` (includes `intervention/image-laravel`)
3. `php artisan migrate`
4. `php artisan storage:link`
5. Confirm `public/images/watermark.png` exists
6. `php artisan serve` and log in as admin

## Demo flow (matches instructor PDF)

1. **Create** — Admin → Showcase → Add bouquet. Upload a JPG or PNG. Save.
   - Success toast mentions watermark.
   - File under `storage/app/public/products/*.webp` (also visible via `/storage/products/...`).

2. **Read** — Open the bouquet on the public showcase. Photo should load.

3. **Update** — Edit the bouquet. Change price. Optionally upload a new photo.
   - Old WebP file should be removed when you replace the image.

4. **Soft delete** — Delete from admin index.
   - Row leaves the list; `deleted_at` is set; image file stays on disk.

5. **Trash** — Open Trash. Bouquet appears with thumbnail.

6. **Restore** — Restore. Bouquet returns to the showcase.

7. **Force delete** — Soft-delete again, then Delete forever in Trash.
   - Row gone from database; WebP file removed from storage.

## Oral defense mapping

| PDF | Petal Express |
| --- | --- |
| `Product` | `Post` (`showcase_flowers`) |
| `image_path` | `image` column |
| `ProductController` | `AdminPostController` + `saveWatermarkedImage()` |
| `/products` | `/admin/posts` (behind admin role) |

## Quick test command

```bash
php artisan test --filter=LabActFourInterventionTest
```
