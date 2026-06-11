<?php

use App\Contact\Controllers\AdminContactController;
use App\Dashboard\Controllers\DashboardController;
use App\Menu\Controllers\AdminMenuController;
use App\OpeningHours\Controllers\AdminHolidayController;
use App\OpeningHours\Controllers\AdminOpeningHoursController;
use App\OpeningHours\Controllers\AdminVacationController;
use App\Ordering\Controllers\AdminCategoryController;
use App\Ordering\Controllers\AdminMenuItemController;
use App\Ordering\Controllers\AdminMenuItemImportController;
use App\Ordering\Controllers\AdminOrderController;
use App\Reservations\Controllers\AdminReservationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/reservations', [AdminReservationController::class, 'index'])->name('reservations.index');
    Route::patch('/reservations/{reservation}/status', [AdminReservationController::class, 'updateStatus'])->name('reservations.status');
    Route::delete('/reservations/{reservation}', [AdminReservationController::class, 'destroy'])->name('reservations.destroy');

    Route::get('/contacts', [AdminContactController::class, 'index'])->name('contacts.index');
    Route::patch('/contacts/{contact}/read', [AdminContactController::class, 'markAsRead'])->name('contacts.read');

    Route::get('/menu', [AdminMenuController::class, 'edit'])->name('menu.edit');
    Route::post('/menu', [AdminMenuController::class, 'update'])->name('menu.update');
    Route::delete('/menu', [AdminMenuController::class, 'destroy'])->name('menu.destroy');

    Route::get('/opening-hours', [AdminOpeningHoursController::class, 'index'])->name('opening-hours.index');
    Route::put('/opening-hours', [AdminOpeningHoursController::class, 'update'])->name('opening-hours.update');

    Route::get('/holidays', [AdminHolidayController::class, 'index'])->name('holidays.index');
    Route::post('/holidays', [AdminHolidayController::class, 'store'])->name('holidays.store');
    Route::delete('/holidays/{holiday}', [AdminHolidayController::class, 'destroy'])->name('holidays.destroy');

    Route::get('/vacations', [AdminVacationController::class, 'index'])->name('vacations.index');
    Route::post('/vacations', [AdminVacationController::class, 'store'])->name('vacations.store');
    Route::delete('/vacations/{vacation}', [AdminVacationController::class, 'destroy'])->name('vacations.destroy');

    Route::prefix('ordering')->name('ordering.')->group(function () {
        Route::resource('menu-items', AdminMenuItemController::class)->except('show');
        Route::get('/menu-items/import', [AdminMenuItemImportController::class, 'create'])->name('menu-items.import');
        Route::post('/menu-items/import/preview', [AdminMenuItemImportController::class, 'preview'])->name('menu-items.import.preview');
        Route::post('/menu-items/import/execute', [AdminMenuItemImportController::class, 'store'])->name('menu-items.import.execute');
        Route::resource('categories', AdminCategoryController::class)->except(['create', 'edit', 'show']);
        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
    });
});
