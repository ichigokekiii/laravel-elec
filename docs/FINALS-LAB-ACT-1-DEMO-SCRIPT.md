# Demo the watermarked WebP upload

Use this while you present. The code explanation is in [FINALS-LAB-ACT-1-CODE.md](FINALS-LAB-ACT-1-CODE.md). The same explanation with figures is [Acuesta_John_Constantine_Finals_LabActivity1_HOW_IT_WORKS.pdf](Acuesta_John_Constantine_Finals_LabActivity1_HOW_IT_WORKS.pdf). Do the steps in order. After each step, say the line under **Say**. Then show the thing under **Show**.

Log in as `admin@test.com` with password `123`. That user comes from `DatabaseSeeder`. A member (`test@test.com` / `123`) cannot open the admin form.

## Before you walk in

Run these from `petal-express` once, before class, not during the demo unless something is missing.

```bash
composer install
php artisan migrate
php artisan db:seed
php artisan storage:link
php artisan test --filter=LabActFourInterventionTest
php artisan serve --host=127.0.0.1 --port=8000
```

Check three things.

1. `public/images/watermark.png` exists.
2. `public/storage` is a symlink to `storage/app/public`.
3. The test command prints three passing tests.

Bring one JPG and one PNG on the laptop. Phone photos are fine if each file is under 3 MB. The create form accepts JPG, PNG, or WebP.

Open the app at `http://127.0.0.1:8000`. Use that host, not a different port, so the screenshots and this script match.

## 1. Show the gate

Log out if a member session is open. Log in as `test@test.com` / `123`. Visit `http://127.0.0.1:8000/admin/posts`.

**Say.** The upload route is behind `auth` and `role:admin`. A member gets 403 before `AdminPostController` runs, so Intervention never sees their request.

**Show.** The 403 page.

Log out. Log in as `admin@test.com` / `123`. Laravel sends an admin who hits `/dashboard` to the admin bouquet list.

## 2. Show the form rules

Open **Add bouquet**.

**Say.** The form uses `enctype="multipart/form-data"`, so the file arrives in the request. `PostRequest` requires a photo on create. The allowed types are JPG, PNG, and WebP. The max size is 3 MB. The helper text on the form says the same thing.

**Show.** The file input and the line under it that says JPG, PNG, or WebP.

Submit the form empty.

**Say.** Validation fails in `PostRequest`. `saveWatermarkedImage()` does not run. No file is written.

**Show.** The error under the photo field. The message in code is `Please upload a photo for this bouquet.` Other empty fields show their own messages.

## 3. Upload a JPG

Fill the form.

- Name: `Demo JPG to WebP`
- Price: `1599` (digits only)
- Category: any real category
- Description: one sentence you can read aloud
- Photo: your JPG

Click **Save bouquet**.

**Say.** `store()` calls `saveWatermarkedImage()`. Intervention decodes the JPG, scales the width down to at most 1000 pixels, stamps `public/images/watermark.png` at the bottom right, and encodes WebP at quality 80. The database stores a path like `products/<uuid>.webp`. The original JPG is not kept.

**Show.** The success text `Bouquet added. Watermark applied.` and the new row on the admin list.

## 4. Show the watermark

On that row, click the thumbnail.

**Say.** The thumb is too small to judge the mark. The preview reads the same URL from `Post::displayImageUrl()`. The mark sits bottom-right, 12 pixels in, at 55 percent opacity.

**Show.** The modal. Point at the bottom-right corner. Close it with **Close** or Escape.

## 5. Prove the file is WebP

In a terminal, from `petal-express`:

```bash
ls -t storage/app/public/products/*.webp | head -1 | xargs file
```

**Say.** The browser sent a JPG. The path ends in `.webp`, and `file` reports a WebP image. `storage:link` is why `/storage/products/...` can read this folder.

**Show.** The terminal line that says `Web/P` or `WebP`.

Copy that filename. You will delete this exact file in step 9. Do not guess a different UUID.

## 6. Upload a PNG

Go back to **Add bouquet**. Repeat step 3 with a PNG and the name `Demo PNG to WebP`.

**Say.** PNG uses the same method. `Image::decode()` accepts either format. `encodeUsingFormat(Format::WEBP)` is what forces the output. The input type does not choose the stored type.

**Show.** A second success toast and a second row.

## 7. Show it on the public page

Open `http://127.0.0.1:8000/bouquets`. Open `Demo PNG to WebP` or `Demo JPG to WebP`. Do not open a seeded bouquet such as one of the original six. Those use `public/images/bouquets/0N.jpg` and have no watermark.

**Say.** The public page does not call Intervention. `displayImageUrl()` returns `/storage/products/<uuid>.webp` when that file exists. The detail page prints that URL in an `img` tag.

**Show.** The large photo and the mark in the corner. If the list row looks unmarked, say that the row thumbnail is small and move to this detail page.

## 8. Replace the photo

In the admin list, click **Edit** on `Demo JPG to WebP`. Change the price. Leave the file input empty. Save.

**Say.** On update the photo rule is `nullable`. No new file means `hasFile('image')` is false. The old WebP path stays.

**Show.** The success text `Bouquet updated.` Then edit again, choose a new JPG or PNG, and save.

**Say.** A new file is written first. The row is updated. Then the old path is deleted from the `public` disk. A failed save does not delete the old file first.

**Show.** The new thumbnail. If you still have the old UUID from step 5, `ls` that exact path and show that it is gone after this replacement.

## 9. Soft delete, then force delete

On `Demo PNG to WebP`, note the WebP filename from the thumbnail URL or from `ls`. Click **Delete**.

**Say.** `destroy()` only sets `deleted_at`. The WebP stays. Trash can still show the thumbnail.

**Show.** The row is gone from the main list. Open **Trash**. The row is there. In the terminal, `file` on that same path still reports WebP.

Click **Restore**.

**Say.** `restore()` clears `deleted_at`. The file never moved.

**Show.** The row back on the main list.

Click **Delete** again, open **Trash**, and click **Delete forever**. Confirm the prompt.

**Say.** `forceDelete()` removes the row, then `Storage::disk('public')->delete()` removes that WebP. The route uses `withTrashed()` so Laravel can still find the trashed model.

**Show.** The row is gone from Trash. `ls` on the path you wrote down fails.

## 10. If something looks wrong

| What you see | What to check |
| --- | --- |
| 403 on the admin form | You are on `test@test.com`. Log in as `admin@test.com`. |
| Broken image icon | `public/storage` is missing. Run `php artisan storage:link`. |
| Photo with no watermark | You opened a seeded bouquet. Open `Demo JPG to WebP` or `Demo PNG to WebP`. |
| `Watermark not found` | `public/images/watermark.png` is missing. |
| Validation error on a real photo | The file is over 3 MB, or it is not JPG, PNG, or WebP. |
| Empty list after delete | Look in **Trash**. Soft delete does not remove the file. |

## 11. Close the demo

**Say.** Create and edit go through `AdminPostController::saveWatermarkedImage()`. Any allowed upload becomes one WebP with the Petal Express mark. Soft delete keeps that file. Force delete removes it. `Post::displayImageUrl()` is the only method the public pages need.

If you have time, show the passing test output from before class. Do not rerun it in the middle of the demo unless the teacher asks. `RefreshDatabase` on that test resets the test database, not your demo SQLite file, but a long test pause breaks the room.
