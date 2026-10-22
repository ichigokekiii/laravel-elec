<?php

use App\Http\Controllers\AdminPostController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserPostController;
use Illuminate\Support\Facades\Route;

// guest pages (no login needed)
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/services', [PageController::class, 'services'])->name('services');
Route::get('/bouquets', [PageController::class, 'bouquetsIndex'])->name('bouquets.index');

// {id} is the route parameter for one bouquet
Route::get('/bouquets/{id}', [PageController::class, 'bouquetsShow'])->name('bouquets.show');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// auth only (not verified). drop verified so demo login just works
// old dashboard URL — send members to orders, admins to showcase
Route::get('/dashboard', function () {
    if (auth()->user()->isAdmin()) {
        return redirect()->route('admin.posts.index');
    }

    return redirect()->route('orders.index');
})->middleware(['auth'])->name('dashboard');

// protected member pages. guests get sent to login
Route::middleware('auth')->group(function () {
    Route::get('/orders', [MemberController::class, 'orders'])->name('orders.index');
    Route::get('/favorites', [MemberController::class, 'favorites'])->name('favorites.index');
    Route::get('/account', [MemberController::class, 'account'])->name('account.edit');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// admin post pages (must be logged in AND role = admin)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/posts', [AdminPostController::class, 'index'])->name('posts.index');
    Route::get('/posts/create', [AdminPostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [AdminPostController::class, 'store'])->name('posts.store');
    // trash before {post} so "trash" is not treated as an id
    Route::get('/posts/trash', [AdminPostController::class, 'trash'])->name('posts.trash');
    Route::get('/posts/{post}/edit', [AdminPostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [AdminPostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [AdminPostController::class, 'destroy'])->name('posts.destroy');
    Route::patch('/posts/{post}/restore', [AdminPostController::class, 'restore'])
        ->withTrashed()
        ->name('posts.restore');
    Route::delete('/posts/{post}/force', [AdminPostController::class, 'forceDelete'])
        ->withTrashed()
        ->name('posts.force');
});

// user post pages (must be logged in AND role = user)
Route::middleware(['auth', 'role:user'])->group(function () {
    // register /posts/submit BEFORE /posts/{post} so "submit" is not treated as an id
    Route::get('/posts/submit', [UserPostController::class, 'create'])->name('posts.submit');
    Route::post('/posts/submit', [UserPostController::class, 'store'])->name('posts.submit.store');
    Route::get('/posts/{post}', [UserPostController::class, 'show'])->name('posts.show');
});

require __DIR__.'/auth.php';
