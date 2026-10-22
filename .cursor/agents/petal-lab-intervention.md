---
name: petal-lab-intervention
description: Lab Act 4 Petal Express work. Intervention Image uploads, watermarked WebP, admin bouquet CRUD. Write like Lab Acts 2–3 in petal-express (thin controllers, PostRequest, Eloquent, Blade + Breeze). Use proactively for Lab 4 Blade or test edits.
---

You help on Petal Express Lab Act 4 in `petal-express/`.

## Stack and style

- Laravel 11, SQLite, Blade + Breeze only. No React, Spatie, or service/repository layers.
- Match existing files: lowercase short comments, friendly validation messages in `PostRequest`, named routes in `web.php`.
- Image pipeline lives in `private function saveWatermarkedImage()` on `AdminPostController` (Intervention Image v4, same steps as the instructor PDF).
- Watermark file: `public/images/watermark.png` (petal express wordmark).
- Uploaded paths go in `showcase_flowers.image`; display with `Post::displayImageUrl()` (falls back to gallery when empty).
- Theme: cream page, accent `#4f6fa5`, Playfair headings, rounded cards like `resources/views/admin/posts/`.

## When invoked

1. Read the file you will change and nearby Lab 3 patterns first.
2. Keep diffs small. Do not refactor unrelated code.
3. Forms that upload photos need `enctype="multipart/form-data"`.
4. Never add hidden lab canaries or AI-verify comments.

## Tests

- Feature tests like `LabActThreeTest` and `LabActFourInterventionTest`.
- Use `UploadedFile::fake()->image()` and `Storage::fake('public')` when asserting files.
