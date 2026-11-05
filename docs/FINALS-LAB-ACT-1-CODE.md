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

Measured on this machine on 5 Nov 2026. `watermark.png` is a PNG, 560 by 160 pixels, with transparency. 560 to 160 is 3.5 to 1, a wide short mark. One saved bouquet photo is `products/8bfef734-0640-407f-af5d-a6f47d24a581.webp`. Pillow reports a WebP, 800 by 1150. Row 101 in `showcase_flowers` stores that same path for the title `Progress Report Bouquet`. Another upload, `favorite ceo`, is 1000 by 1000 because `scaleDown(width: 1000)` pulled a wider square down to 1000 and kept the square shape.

The stamp lives in `public/images` because it is part of the project. You commit it. You do not upload it per bouquet.

The bouquet photo lives under `storage/app/public` because uploads should not sit next to the source code in `public/images`. Laravel's `public` disk is the usual place for files the browser may see.

## Why the saved name looks like that

A real stored value is this whole string.

```text
products/8bfef734-0640-407f-af5d-a6f47d24a581.webp
```

Read it in three pieces.

1. `products/` is the folder inside the public disk. Bouquet photos stay in one place. The watermark is not in this folder.
2. `8bfef734-0640-407f-af5d-a6f47d24a581` comes from `Str::uuid()` in the controller. A UUID is a random id in the shape 8-4-4-4-12 hexadecimal characters. Laravel generates it. The visitor's filename is not used. Their name can repeat, can contain spaces, and can be guessed. The database id is not used either. The file is written before `Post::create()` returns the new id.
3. `.webp` matches `Format::WEBP`. The bytes are WebP. The name says WebP. If we kept `.jpg` on a WebP file, the teacher and the browser would disagree about what the file is.

The browser URL is not the folder path. It is `/storage/` plus the stored string.

```text
/storage/products/8bfef734-0640-407f-af5d-a6f47d24a581.webp
```

`/storage` works because `php artisan storage:link` created a shortcut. On this machine `petal-express/public/storage` points at `petal-express/storage/app/public`. The web server is only allowed to read `public/`. The shortcut lets it read the public disk without moving the file into `public/images`.

## What the thumbnail is

Intervention saves one file. There is no `products/<uuid>-thumb.webp`.

The admin list shows a small box. `admin-photo-thumb` sets that box to `h-12 w-12`, which is 48 by 48 CSS pixels. The edit page uses `size="lg"`, which is `h-24 w-24`, 96 by 96. The `src` of that `<img>` is the full WebP URL. The browser draws the same file inside the box.

## Aspect ratio, in plain words

Aspect ratio is how wide a picture is compared with how tall it is. A square is 1 to 1. The stamp is 560 by 160, which is 3.5 to 1. Bouquet 101 is 800 by 1150, about 16 to 23.

`scaleDown(width: 1000)` changes width and height by the same factor. A tall photo stays tall. A square stays square. Bouquet 101 was already narrower than 1000, so both numbers stayed. `favorite ceo` was a square wider than 1000, so it became 1000 by 1000.

The page can crop later, in CSS.

The admin list class is `object-cover` on a 48 by 48 square. `object-cover` fills the square and cuts off the extra height. For the 800 by 1150 file, the square keeps the middle and cuts about 175 pixels from the top and 175 from the bottom. The mark sits in that bottom strip, so the small list photo can hide it.

The preview class is `object-contain`. That fits the whole photo in the box and leaves empty space on the sides. On a 1280 by 900 window the preview box measured 846 by 675, and the file inside it is still 800 by 1150. The mark shows there. Click the small photo when you want the teacher to see the mark.

The public detail class is `object-cover` with `min-h-[420px]`. On that same window the box measured 414 by 595. That matches 800 to 1150, so this screen shows the whole photo, including the mark. A different window width can cut it, because the class is still `object-cover`.

One file means delete forever has one path to remove. A second thumbnail would need its own column, its own watermark pass, and its own delete.

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

`$image->scaleDown(width: 1000)` shrinks the photo only when it is wider than 1000 pixels. Height changes by the same factor. Bouquet 101 stayed 800 by 1150 because the upload was already narrower than 1000.

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

### 6. The lines that display the image

Saving the file does not put it on the page. A Blade `<img>` tag does. The `src` has to be a URL the browser can request. That URL is built in one place, `Post::displayImageUrl()`, then passed into the tag.

`app/Models/Post.php` lines 57 to 63:

```php
public function displayImageUrl(): string
{
    if ($this->image) {
        if (Storage::disk('public')->exists($this->image)) {
            return '/storage/'.ltrim($this->image, '/');
        }
    }

    return $this->galleryImageUrl();
}
```

Line 59 checks that the column is not empty. Line 61 checks that the WebP is really on the public disk. Line 62 is the line that displays an upload. For bouquet 101 it returns `/storage/products/8bfef734-0640-407f-af5d-a6f47d24a581.webp`.

We do not call `Storage::url()`. That helper sticks `APP_URL` on the front. If `APP_URL` is `http://localhost` and you opened `http://127.0.0.1:8000`, the image request goes to the wrong host. A path that starts with `/storage/` uses whatever host is already in the address bar.

Line 66 runs only when there is no upload. `galleryImageUrl()` at line 70 picks `public/images/bouquets/01.jpg` through `06.jpg` from the row id. Those JPGs are not watermarked.

The controller does not echo HTML. It loads the row and hands it to Blade.

`app/Http/Controllers/PageController.php` lines 57 to 61:

```php
$bouquet = Post::query()->with(['category', 'user'])->findOrFail($id);

return view('pages.bouquets-show', [
    'bouquet' => $bouquet,
]);
```

`findOrFail` is the database read. `displayImageUrl()` has not run yet.

The public detail page is the line you should point at in the demo.

`resources/views/pages/bouquets-show.blade.php` line 5:

```blade
<img src="{{ $bouquet->displayImageUrl() }}" alt="{{ $bouquet->title }}" class="h-full w-full object-cover min-h-[420px]">
```

`{{ }}` prints the string and escapes it. The browser then requests that `src`. Laravel is done. The web server follows `public/storage` to the WebP file.

Lists do the same call, then a component draws the tag.

`resources/views/pages/bouquets-index.blade.php` line 47, `resources/views/pages/home.blade.php` line 55, and `resources/views/member/favorites.blade.php` line 19 all pass `:image="$bouquet->displayImageUrl()"` into `<x-showcase-list-item>`. The actual tag is `resources/views/components/showcase-list-item.blade.php` lines 13 to 16:

```blade
<img
    src="{{ $image }}"
    alt="{{ $alt !== '' ? $alt : $title }}"
    class="h-20 w-20 shrink-0 rounded-xl object-cover border border-gray-100 sm:h-24 sm:w-24"
>
```

`h-20 w-20` is why the list looks like a thumbnail. The `src` is still the full WebP.

The admin list is `resources/views/admin/posts/index.blade.php` line 27, `<x-admin-photo-thumb :src="$post->displayImageUrl()" :title="$post->title" />`. Trash is the same call at `resources/views/admin/posts/trash.blade.php` line 17. The tag is `resources/views/components/admin-photo-thumb.blade.php` line 17:

```blade
<img src="{{ $src }}" alt="" class="{{ $sizeClass }} rounded-xl object-cover border border-gray-100">
```

Edit uses the same component at `resources/views/admin/posts/edit.blade.php` line 51, with `size="lg"`. The click on line 15 of the thumb component sends that same `src` to the modal. The modal image is `resources/views/components/admin-photo-preview-shell.blade.php` lines 34 to 37, `:src="previewSrc"`, with `object-contain`. Still one file.

One page does not show the upload. `resources/views/posts/show.blade.php` line 9 calls `$post->galleryImageUrl()`, not `displayImageUrl()`. That is the member URL `/posts/{id}`. Demo the watermark on `/bouquets/{id}` instead.

## Edit, trash, and delete

**Edit with no new file.** `update()` sees `hasFile('image')` is false. It does not call Intervention. The old path stays in the column.

**Edit with a new file.** The new WebP is written first. The row is updated to the new path. The old file is deleted only after that update succeeds. If the database update throws, the new file is deleted and the old path remains. You never point the row at a file you already erased.

**Delete on the list.** `destroy()` calls `$post->delete()`. `Post` uses `SoftDeletes`, so this only sets `deleted_at`. The WebP stays in `storage/app/public/products`. Trash can still show it.

**Delete from Trash.** `forceDelete()` copies the path, removes the row, then runs `Storage::disk('public')->delete($imagePath)`. The Trash button says Delete and asks in a modal. The toast says Bouquet deleted. The route uses `withTrashed()` so Laravel can still load a row that is already in the trash. The method refuses a bouquet that is not trashed.

## Why the mark is words inside a PNG

The stamp looks like the words petal express. The file is still an image, `public/images/watermark.png`. It is a PNG, 560 by 160, with a transparent background. `saveWatermarkedImage()` does not draw those letters. It calls `Image::decode` on that PNG, then `insert()`.

Intervention can draw text with a `text()` method. This lab does not call it. `text()` needs a font file on the computer that runs the upload. If that font is missing in the demo, every save fails. The PNG already has the font, the blue color, and the letter spacing. The controller only places the file.

A picture of flowers would cover the bouquet. Words with a transparent background stay readable in the corner. One PNG is shared by every upload. The controller always opens `public/images/watermark.png`.

## What else Intervention does

The watermark is one step. The same method uses Intervention for the rest of the photo.

- `Image::decode` reads the upload, so a JPG, a PNG, and a WebP become one picture.
- `scaleDown(width: 1000)` resizes the photo and keeps the aspect ratio.
- `scaleDown` runs again on the stamp, up to 38 percent of the photo width and never wider than 320 pixels.
- `insert()` paints the stamp 12 pixels in from the bottom-right at 0.55 opacity.
- `encodeUsingFormat(Format::WEBP, quality: 80)` makes the WebP bytes.

`Alignment::BOTTOM_RIGHT` and `Format::WEBP` are settings for those calls. This lab does not blur, crop, flip, or build a second thumbnail file.

## If sir says make a field required

On Add bouquet, name, description, price, category, and photo are already required. The rules are `PostRequest.php` lines 28 to 32. The tags in `create.blade.php` already have the HTML attribute `required`.

Edit is the change you can make in the room. `PostRequest.php` line 24 uses `nullable` for `image` on update. `edit.blade.php` line 56 is a file input with no `required` attribute. An empty photo on Edit keeps the old file.

Do this if sir says make the photo required.

1. In `PostRequest.php` line 24, replace `nullable` with `required`.
2. In `edit.blade.php` line 56, add `required` on the file input. `create.blade.php` line 48 already has that word.
3. Do not add a migration. The column can stay nullable. The form rule is the check.

`messages()` already has `image.required` set to `Please upload a photo for this bouquet.` Save both files. Open a bouquet, choose Edit, leave Photo empty, and click Save changes. The form comes back with that sentence under Photo. `saveWatermarkedImage()` does not run.

If the field sir names is already required, say the line and submit the form empty. Do not add `required` a second time. If sir names a different field, the rule key must match the input `name`. Put `required` in the rule array and on the tag. The HTML attribute stops a normal browser. `PostRequest` still checks the request when that attribute is skipped.

## What you can say if you forget a name

The stamp is `public/images/watermark.png`. It is a PNG of the words petal express. Intervention does not draw the letters.

The other Intervention calls are `decode`, `scaleDown`, `insert`, and `encodeUsingFormat` to WebP.

If sir says make the edit photo required, change `nullable` to `required` on `PostRequest.php` line 24 and add `required` on `edit.blade.php` line 56.

The saved photo is `storage/app/public/products/` plus a UUID plus `.webp`.

The database column `showcase_flowers.image` stores `products/<uuid>.webp` with no `/storage` prefix.

The browser asks for `/storage/products/<uuid>.webp`.

The small admin photo is that same WebP, drawn small. Intervention saved it once.
