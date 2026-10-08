# Recording script — Lab Act 2 then Lab Act 3

Read this while you record. Click first, then say the line, then open the file if the line names one.

Branch for this recording is `labact3`. Lab Act 2 is still in the same app. You do not switch branches mid-recording.

```bash
cd /Users/jc/laravel-elec/petal-express
php artisan migrate
php artisan db:seed
php artisan serve
```

Open http://127.0.0.1:8000

Logins:

- Admin: `admin@test.com` / `123`
- User: `test@test.com` / `123`

Have the SQLite file open too: `petal-express/database/database.sqlite`.

---

## Part A — Lab Act 2

### A1. What Lab Act 2 is

Say:

> “Lab Act 2 moved the showcase into the database and split admin from a normal member. The URL hits `routes/web.php`. `auth` checks that I am logged in. `role` checks `users.role`. The controller uses the `Post` model, which reads the `showcase_flowers` table.”

Open `routes/web.php`. Point at the `role:admin` group and the `role:user` group.

Open `bootstrap/app.php`. Say:

> “Laravel 11 does not use `app/Http/Kernel.php`. The short name `role` is registered here, and the class lives in `app/Http/Middleware/EnsureUserHasRole.php`.”

Open `EnsureUserHasRole.php`. Say:

> “It reads the logged-in user and compares `role` to the word on the route. Wrong role is a 403.”

### A2. Show the 403

Stay logged out, or log in as `test@test.com`. Visit http://127.0.0.1:8000/admin/posts.

Say:

> “This user has `role = user`, so the admin group rejects the request before the controller runs.”

Log out. Log in as `admin@test.com`. Visit http://127.0.0.1:8000/posts/submit.

Say:

> “Admin is blocked from the member custom-order page the same way.”

### A3. The table and the relationship that was already there

Open `database/migrations/2026_09_23_234450_create_showcase_flowers_table.php`.

Say:

> “This migration creates `showcase_flowers`. `user_id` is a foreign key to `users`. `softDeletes()` adds `deleted_at`. Lab Act 2 prepared that column. Lab Act 3 is what uses it.”

Open `app/Models/Post.php`. Point at `$table`, `$fillable`, `user()`, and `SoftDeletes`.

Say:

> “The model is named `Post`, but the table is `showcase_flowers`. `user()` is `belongsTo`. That means one bouquet belongs to the user who added it. `$fillable` is the list `create` is allowed to set.”

Open `database/migrations/2026_09_23_234450_add_role_to_users_table.php`.

Say:

> “`role` is a string on `users`. Default is `user`. The seeder sets one admin and one member.”

### A4. Admin create (the Lab Act 2 save)

Log in as admin. Click Showcase in the nav. Click Add bouquet. Fill name, price with a peso sign, description, and a category. Save.

Say:

> “The form is `POST` with `@csrf`. The route runs the controller `store` method. It validates, then `Post::create`. `user_id` comes from `auth()->id()`, not from the form. Then it redirects with a success message.”

Open `app/Http/Controllers/AdminPostController.php` and point at `store`. For Lab Act 2 you can say the rules now live in `PostRequest` because Lab Act 3 moved them. That is honest.

In SQLite, show a new row in `showcase_flowers` with `user_id` pointing at the admin.

### A5. Member pages that do not save

Log in as `test@test.com`. Open a bouquet from the dashboard or from `/posts/1`.

Say:

> “`show` receives the bouquet through route model binding. `{post}` becomes a `Post`. The view only reads it.”

Open Custom order. Submit it.

Say:

> “This one validates and flashes a thank-you. It does not call `Post::create`. Same idea as the contact form in Lab Act 1. The row count does not change.”

Open `app/Http/Controllers/UserPostController.php` `store` and show that there is no `Post::create`.

---

## Part B — Lab Act 3

Say this once before the clicks:

> “Lab Act 3 adds six things on top of that. A shared form request, a toast, pagination, a category relationship, soft delete with restore and permanent delete, and a seed of 100 bouquets.”

### B1. Seed 100

In a terminal:

```bash
cd /Users/jc/laravel-elec/petal-express
php artisan db:seed
```

Say:

> “The seeder makes 5 categories, keeps 3 named bouquets, then `Post::factory()->count(97)` makes the rest. 3 plus 97 is 100. It deletes old showcase rows first, including Trash, so running it again still ends at 100.”

Open `database/seeders/DatabaseSeeder.php` and `database/factories/PostFactory.php`.

In SQLite:

```sql
SELECT COUNT(*) FROM showcase_flowers WHERE deleted_at IS NULL;
SELECT COUNT(*) FROM categories;
```

You want 100 and 5.

### B2. Relationship

Open the public Showcase at http://127.0.0.1:8000/bouquets while logged out.

Say:

> “Each chip is a row in `categories`. The number is `withCount('posts')`, so it counts bouquets in that category. Clicking a chip adds `?category=` to the URL. The controller adds `where('category_id', ...)` before it paginates.”

Click Birthday. Point at the URL.

Open one bouquet. Point at the category name and “Added by”.

Say:

> “`category()` is `belongsTo`. `user()` was already there from Lab Act 2. `with(['category', 'user'])` loads both related rows so the page does not query inside the loop.”

Open `app/Models/Category.php`. Say:

> “The other direction is `posts()`, which is `hasMany`. One category, many bouquets.”

Open `database/migrations/2026_10_08_102011_add_category_id_to_showcase_flowers_table.php`.

Say:

> “`category_id` is nullable and `nullOnDelete`. Deleting a category would clear the column. It would not delete the bouquet.”

### B3. Pagination

On `/bouquets`, scroll to Previous and Next. Open page 2.

Say:

> “`paginate(9)` means the SQL only fetches 9 rows, plus a count for the page links. `withQueryString()` keeps the category filter when I change page. The links are our copy of Laravel’s Tailwind pagination view.”

Go home. Point at “100 bouquets” and the six cards.

Say:

> “Home uses `latest()->take(6)`. The sentence uses `Post::count()`, so the number is the whole showcase, not just the six cards.”

Log in as admin. Open Showcase. Point at the page links under the list.

Say:

> “The admin list is `paginate(10)`.”

Open `app/Http/Controllers/PageController.php` `bouquetsIndex` and `home`.

### B4. Validation and the toast

As admin, open Add bouquet. Leave the fields empty, or type a price without `₱`. Submit.

Say:

> “`store` type-hints `PostRequest`. Laravel runs those rules before my method. This failed, so `Post::create` did not run. I was redirected back. `old()` puts back what I typed. The red line under each input is `$errors`. The toast is the same failure, once, at the top.”

Open `app/Http/Requests/PostRequest.php`. Read the rules out loud.

Say:

> “Edit uses the same class. I do not keep two copies of the rules.”

Submit a valid bouquet. Point at the toast.

Say:

> “`redirect()->with('success', ...)` stores that string in the session for the next request only. `<x-flash>` in the layout prints `session('success')`, `session('error')`, or a fix-the-fields line. Refresh and the toast is gone. That is flash data.”

Open `resources/views/components/flash.blade.php`.

### B5. Soft delete, restore, delete forever

On the admin list, click Delete on the bouquet you just added.

Say:

> “The button is a form, not a link. `POST`, `@csrf`, and `@method('DELETE')`. `destroy` calls `$post->delete()`. That sets `deleted_at`. The row is still in the table.”

Show the public Showcase and confirm that bouquet is gone.

Say:

> “The shop query does not use `withTrashed()`, so a filled `deleted_at` hides the row.”

Open Trash. Point at “Deleted … ago”.

Say:

> “Trash is `Post::onlyTrashed()`. `diffForHumans()` turns `deleted_at` into that sentence.”

Click Restore. Show it back on the list and on the public page.

Say:

> “`restore()` sets `deleted_at` back to null. The route uses `withTrashed()` because a normal `{post}` lookup would 404 on a trashed row.”

Delete it again. In Trash, click Delete forever and confirm.

Say:

> “`forceDelete()` removes the row. It refuses if the bouquet is not already in Trash. After this, SQLite has no row with that id.”

Open the four methods at the bottom of `AdminPostController.php` while you say that.

### B6. Close

Say:

> “Lab Act 2 built the table, the role middleware, and the admin save. Lab Act 3 checks the form in one request class, tells me with a toast, pages the lists, groups bouquets with categories, and uses the soft-delete column we already had. The seed makes 100 rows so pagination is visible.”

---

## If something looks empty

```bash
cd /Users/jc/laravel-elec/petal-express
php artisan migrate
php artisan db:seed
```

Demo rows come back. Trash is cleared by the seeder on purpose.
