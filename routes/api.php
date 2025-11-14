<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/migrate', function () {
    Illuminate\Support\Facades\Artisan::call('config:clear');
    Illuminate\Support\Facades\Artisan::call('cache:clear');
    Illuminate\Support\Facades\Artisan::call('migrate --force');
    shell_exec('npm run build');
    Illuminate\Support\Facades\Artisan::call('optimize');

    return 'Migration completed successfully.';
})->name('migrate');
