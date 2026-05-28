<?php

use App\Contact\Controllers\ContactController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\LocaleController;
use App\Imprint\Controllers\ImprintController;
use App\Menu\Controllers\PublicMenuController;
use App\Reservations\Controllers\PublicReservationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicReservationController::class, 'welcome'])->name('home');

Route::get('/reservierung', [PublicReservationController::class, 'create'])->name('reservation.create');
Route::post('/reservierung', [PublicReservationController::class, 'store'])->name('reservation.store')->middleware('throttle:3,1');

Route::get('/speisekarte', [PublicMenuController::class, 'show'])->name('menu.show');

Route::get('/kontakt', [ContactController::class, 'create'])->name('contact.create');
Route::post('/kontakt', [ContactController::class, 'store'])->name('contact.store');

Route::get('/impressum', [ImprintController::class, 'show'])->name('imprint.show');

Route::get('/locale/{locale}', [LocaleController::class, 'switch'])->name('locale.switch');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store']);

    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
});

Route::post('/logout', [AuthController::class, 'destroy'])->name('logout')->middleware('auth');
