# What happens when you upload a bouquet photo

Read this before you present. You do not need earlier Laravel knowledge. The demo clicks are in [FINALS-LAB-ACT-1-DEMO-SCRIPT.md](FINALS-LAB-ACT-1-DEMO-SCRIPT.md). The PDF of this explanation, with the screenshots, is [Acuesta_John_Constantine_Finals_LabActivity1_HOW_IT_WORKS.pdf](Acuesta_John_Constantine_Finals_LabActivity1_HOW_IT_WORKS.pdf).

One sentence for the lab. You upload a JPG, a PNG, or a WebP. Laravel does not keep that file. Intervention Image builds a new picture, stamps the shop mark on it, and saves one WebP. The database remembers the path.

## Words Laravel uses

A **route** is a URL. `POST /admin/posts` is the URL the Add bouquet form sends.

A **controller** is a PHP class that runs for that URL. Ours is `AdminPostController` in `petal-express/app/Http/Controllers/AdminPostController.php`.

A **form request** checks the form before the controller trusts it. Ours is `PostRequest` in `petal-express/app/Http/Requests/PostRequest.php`.

A **model** is the PHP class for one database table. `Post` in `petal-express/app/Models/Post.php` uses the table `showcase_flowers`. The class is not named `Bouquet`. The table is not named `posts`. Say that if the teacher looks for those names.

A **migration** created the table. The photo column is in `petal-express/database/migrations/2026_09_23_234450_create_showcase_flowers_table.php`. The column name is `image`. It holds text, not the picture itself.

**Blade** is the HTML template. The form is `petal-express/resources/views/admin/posts/create.blade.php`.

A **disk** is Laravel's name for a folder plus the rules for writing into it. The `public` disk is configured in `petal-express/config/filesystems.php`. Its folder on disk is `petal-express/storage/app/public`.

## Two different picture files

These are not the same file. Do not mix them up in the demo.

| What | Exact path | Name style | Why that name |
| --- | --- | --- | --- |
| The stamp | `petal-express/public/images/watermark.png` | Fixed name `watermark.png` | There is one mark for every bouquet. The controller always opens this path. A random name would make that line fail. |
| The saved bouquet photo | `petal-express/storage/app/public/products/<uuid>.webp` | New random name every upload | The visitor's file might also be called `rose.jpg`. Two uploads must not overwrite each other. The extension is `.webp` because that is the format we encode, not the format they uploaded. |

Measured on this machine on 5 Nov 2026. `watermark.png` is a PNG, 560 by 160 pixels, with transparency. One saved bouquet photo is `products/0f95b48d-2d8d-4a6e-8406-0aa6522e34ec.webp`. The `file` command reports a WebP image, 800 by 800. Row 107 in `showcase_flowers` stores that same path for the title `Manual PDF PNG Upload`.

The stamp lives in `public/images` because it is part of the project. You commit it. You do not upload it per bouquet.

The bouquet photo lives under `storage/app/public` because uploads should not sit next to the source code in `public/images`. Laravel's `public` disk is the usual place for files the browser may see.

## Why the saved name looks like that

A real stored value is this whole string.

```text
products/0f95b48d-2d8d-4a6e-8406-0aa6522e34ec.webp
```

Read it in three pieces.

1. `products/` is the folder inside the public disk. Bouquet photos stay in one place. The watermark is not in this folder.
2. `0f95b48d-2d8d-4a6e-8406-0aa6522e34ec` comes from `Str::uuid()` in the controller. A UUID is a random id in the shape 8-4-4-4-12 hexadecimal characters. Laravel generates it. We do not use the visitor's filename. Their name can repeat, can contain spaces, and can be guessed. We do not use the database id either. The file is written before `Post::create()` returns the new id.
3. `.webp` matches `Format::WEBP`. The bytes are WebP. The name says WebP. If we kept `.jpg` on a WebP file, the teacher and the browser would disagree about what the file is.

The browser URL is not the folder path. It is `/storage/` plus the stored string.

```text
/storage/products/0f95b48d-2d8d-4a6e-8406-0aa6522e34ec.webp
```

`/storage` works because `php artisan storage:link` created a shortcut. On this machine `petal-express/public/storage` points at `petal-express/storage/app/public`. The web server is only allowed to read `public/`. The shortcut lets it read the public disk without moving the file into `public/images`.

## What the thumbnail is

Intervention does not save a thumbnail file. There is no `products/<uuid>-thumb.webp`.

The admin list shows a small box. The CSS classes on `admin-photo-thumb` set that box to about 80 by 80 pixels, or 96 by 96 on a wider screen. The `src` of that `<img>` is the full WebP URL. The browser shrinks it. The edit page and the public detail page use the same URL in a larger box.

Click the small photo and the preview modal loads that same `src` again. You see the watermark because the box is large, not because a second file exists.

We do it this way so there is one file to delete later. A second thumbnail would need its own column, its own watermark pass, and its own delete.

## The full path of one upload

Follow one JPG from the button to the folder.

### 1. The form sends the file

`create.blade.php` posts to the named route `admin.posts.store`. Two attributes matter.

- `@csrf` sends Laravel's token. Without it, Laravel rejects the post.
- `enctype="multipart/form-data"` is what puts the file in the request. A normal form does not send file bytes.

The file input is `name="image"`. `PostRequest` looks for that exact name.

### 2. Middleware runs first

In `petal-express/routes/web.php` the admin routes use `auth` and `role:admin`. A guest is sent to login. A member gets 403. `saveWatermarkedImage()` does not run for them.

### 3. PostRequest accepts or rejects the file

On create, the `image` rules are `required`, `image`, `mimes:jpg,jpeg,png,webp`, and `max:3072`.

`max:3072` is kilobytes. The cap is 3 MB.

`image` means PHP must recognize the upload as a picture. `mimes` lists the types we allow. JPG and JPEG are the same type with two extensions.

On edit, the same rules apply, except `required` becomes `nullable`. An empty file input is allowed. The old photo stays.

If a rule fails, Laravel redirects back to the form. The controller method that saves the picture never starts. No WebP is written.

### 4. store() asks for a path, then inserts the row

`AdminPostController::store()` does not resize anything itself. It calls the private method and stores the string that comes back.

```php
'image' => $this->saveWatermarkedImage($request->file('image')),
```

`Post::create()` writes one row in `showcase_flowers`. The `image` column receives that string. `user_id` is `auth()->id()`, the logged-in admin. Then the browser is redirected to the list with the flash text `Bouquet added. Watermark applied.`

### 5. saveWatermarkedImage() builds the new picture

This method is the Intervention part. It lives at the bottom of `AdminPostController.php`. The imports at the top of that file are `Image`, `Alignment`, and `Format`.

`Image::decode($file)` reads the upload. After this line the code no longer cares that the original was JPG or PNG. Intervention's GD driver on this machine does the pixel work. The PHP `gd` extension was loaded when this was checked. The controller does not name GD. Intervention picks the driver.

`$image->scaleDown(width: 1000)` shrinks the photo only when it is wider than 1000 pixels. A smaller photo stays at its own size. Height keeps the same shape. This is why one saved file was 800 by 800. The upload was not wider than 1000, so the width stayed 800.

The method then opens `public_path('images/watermark.png')`. `public_path()` is Laravel for "file inside the `public` folder". If that PNG is missing, it throws `Watermark not found` and `Post::create()` has not run yet, so you get no row and no WebP.

The mark is scaled to 38 percent of the photo width, and never wider than 320 pixels. `scaleDown` will not stretch a mark that is already smaller than that target.

`$image->insert($watermark, 12, 12, Alignment::BOTTOM_RIGHT, 0.55)` paints the mark on the photo.

- `12` and `12` are pixel gaps from the corner.
- `Alignment::BOTTOM_RIGHT` chooses the bottom-right corner.
- `0.55` keeps 55 percent of the mark's opacity. In Intervention v4 that argument is named `transparency`, but the GD driver treats `1` as full strength. The math is in `vendor/intervention/image/src/Drivers/Gd/Modifiers/InsertModifier.php`.

Then the method builds the path and writes bytes.

```php
$path = 'products/'.Str::uuid().'.webp';
$encoded = $image->encodeUsingFormat(Format::WEBP, quality: 80);
Storage::disk('public')->put($path, (string) $encoded);
```

`quality: 80` is the WebP quality passed to the encoder. `Storage::disk('public')->put()` writes those bytes under `storage/app/public`. The method returns `$path`. That return value is what `store()` puts in the database.

The original upload is discarded. PHP's temp upload is removed at the end of the request. We never call `store()` on the `UploadedFile`, which would have saved the original JPG.

### 6. The page finds the file again

`Post::displayImageUrl()` in `app/Models/Post.php` does this.

- If `image` is empty, or the file is missing on the public disk, return a packed JPG from `public/images/bouquets/01.jpg` through `06.jpg`. Those six files are not watermarked. Seeded rows use them.
- If the file exists, return `/storage/` plus the stored path.

The method does not call `Storage::url()`. That helper prefixes `APP_URL`. A demo opened at `http://127.0.0.1:8000` would point at `http://localhost` and the image would break. A root-relative `/storage/...` URL follows whatever host you actually opened.

Every Blade screen that shows a bouquet photo calls `displayImageUrl()`. The admin list, the edit thumb, trash, the public list, the detail page, home, and favorites all share that one method. None of them call Intervention.

## Edit, trash, and delete

**Edit with no new file.** `update()` sees `hasFile('image')` is false. It does not call Intervention. The old path stays in the column.

**Edit with a new file.** The new WebP is written first. The row is updated to the new path. The old file is deleted only after that update succeeds. If the database update throws, the new file is deleted and the old path remains. You never point the row at a file you already erased.

**Delete on the list.** `destroy()` calls `$post->delete()`. `Post` uses `SoftDeletes`, so this only sets `deleted_at`. The WebP stays in `storage/app/public/products`. Trash can still show it.

**Delete forever.** `forceDelete()` copies the path, removes the row, then runs `Storage::disk('public')->delete($imagePath)`. The route uses `withTrashed()` so Laravel can still load a row that is already in the trash. The method refuses a bouquet that is not trashed.

## What you can say if you forget a name

The stamp is `public/images/watermark.png`.

The saved photo is `storage/app/public/products/` plus a UUID plus `.webp`.

The database column `showcase_flowers.image` stores `products/<uuid>.webp` with no `/storage` prefix.

The browser asks for `/storage/products/<uuid>.webp`.

The small admin photo is that same WebP, drawn small. Intervention saved it once.
