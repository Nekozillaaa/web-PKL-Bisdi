<?php

use App\Http\Controllers\Admin\LabDocumentController;
use App\Http\Controllers\Admin\LabItemController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\LogbookController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicDashboardController::class, 'index'])->name('home');
Route::post('/logbook', [LogbookController::class, 'store'])->name('logbook.store');
Route::get('/documents/{document}/download', [LabDocumentController::class, 'download'])->name('documents.download');
Route::get('/documents/{document}/view', [LabDocumentController::class, 'view'])->name('documents.view');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [BookingController::class, 'index'])->name('dashboard');

    Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::patch('/bookings/{booking}/status', [BookingController::class, 'updateStatus'])->name('bookings.status');
    Route::get('/bookings/{booking}/attachment', [BookingController::class, 'downloadAttachment'])->name('bookings.attachment');

    // Notifikasi
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::match(['get', 'patch'], '/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Only Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('items', LabItemController::class);
    Route::get('documents', [LabDocumentController::class, 'index'])->name('documents.index');
    Route::post('documents', [LabDocumentController::class, 'store'])->name('documents.store');
    Route::delete('documents/{document}', [LabDocumentController::class, 'destroy'])->name('documents.destroy');
});

require __DIR__.'/auth.php';
