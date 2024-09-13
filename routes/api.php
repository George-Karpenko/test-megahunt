<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\HistoriesController;
use App\Http\Controllers\API\UsersController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::prefix('users/v1')
    ->name('users.')
    ->controller(UsersController::class)
    // ->middleware('auth:sanctum')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/basket', 'indexOnlyTrashed')->name('indexOnlyTrashed');
        Route::get('/show/{user}', 'show')->name('show');
        Route::post('/update/{user}', 'update')->name('update');
        Route::delete('/destroy/{user}', 'destroy')->name('destroy');
        Route::delete('/hardDestroy/{user}', 'hardDestroy')->name('hardDestroy');
        Route::post('/multiRestore', 'multiRestore')->name('multiRestore');
        Route::post('/multiDestroy', 'multiDestroy')->name('multiDestroy');
        Route::post('/multiHardDestroy', 'multiHardDestroy')->name('multiHardDestroy');
        Route::post('/restorationFromHistories/{history}', 'restorationFromHistories')->name('restorationFromHistories');
    });
Route::prefix('histories/v1')
    ->name('histories.')
    ->controller(HistoriesController::class)
    // ->middleware('auth:sanctum')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/basket', 'indexOnlyTrashed')->name('indexOnlyTrashed');
        Route::get('/show/{history}', 'show')->name('show');
        Route::delete('/destroy/{history}', 'destroy')->name('destroy');
        Route::delete('/hardDestroy/{history}', 'hardDestroy')->name('hardDestroy');
        Route::post('/multiRestore', 'multiRestore')->name('multiRestore');
        Route::post('/multiDestroy', 'multiDestroy')->name('multiDestroy');
        Route::post('/multiHardDestroy', 'multiHardDestroy')->name('multiHardDestroy');
        Route::post('/restorationFromHistories/{history}', 'restorationFromHistories')->name('restorationFromHistories');
    });
