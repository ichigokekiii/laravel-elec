<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
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
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

// protected member pages. guests get sent to login
Route::middleware('auth')->group(function () {
    Route::get('/orders', [MemberController::class, 'orders'])->name('orders.index');
    Route::get('/favorites', [MemberController::class, 'favorites'])->name('favorites.index');
    Route::get('/account', [MemberController::class, 'account'])->name('account.edit');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
