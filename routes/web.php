<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'login')->name('login');
    Route::post('/login', 'authenticate')->name('authenticate');
    Route::get('/register', 'register')->name('register');
    Route::post('/register', 'store')->name('register.store');
    Route::post('/logout', 'logout')->name('logout');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DocumentController::class, 'index'])->name('dashboard');
    
    // Secure storage route - intercepts /storage/documents/* URLs
    Route::get('/storage/documents/{filename}', [App\Http\Controllers\DocumentController::class, 'serveFile'])->name('documents.serve');
    
    Route::get('/documents/{document}/download', [App\Http\Controllers\DocumentController::class, 'download'])->name('documents.download');
    Route::resource('documents', App\Http\Controllers\DocumentController::class)->only(['store', 'destroy']);

    Route::middleware(['is_admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\AdminDocumentController::class, 'index'])->name('dashboard');
        Route::get('/history', [App\Http\Controllers\AdminDocumentController::class, 'history'])->name('history');
        Route::post('/documents/{document}/approve', [App\Http\Controllers\AdminDocumentController::class, 'approve'])->name('documents.approve');
        Route::post('/documents/{document}/reject', [App\Http\Controllers\AdminDocumentController::class, 'reject'])->name('documents.reject');
    });
});
