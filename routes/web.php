<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\ActiveOrganizationController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

Route::middleware(['auth', 'verified'])
    ->group(function () {
        Route::get('/organizations', [
            OrganizationController::class,
            'index',
        ])->name('organizations.index');

        Route::post('/organizations', [
            OrganizationController::class,
            'store',
        ])->name('organizations.store');
        Route::post(
            '/organizations/active',
            [ActiveOrganizationController::class, 'update']
        )->name('organizations.active.update');
    });
