<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BulletinController;

Route::get('/', function () {
    return 'Bienvenue sur AdminPlus 🚀';
});

Route::get('/bulletins/{id}', [BulletinController::class, 'show'])->name('bulletin.show');
Route::get('/bulletins/{id}/download', [BulletinController::class, 'download'])->name('bulletin.download');
