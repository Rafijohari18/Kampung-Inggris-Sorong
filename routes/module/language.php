<?php

use App\Http\Controllers\Admin\LanguageController;
use Illuminate\Support\Facades\Route;

/**
 * backend
 */
Route::group(['middleware' => ['auth']], function () {

    Route::group(['prefix' => 'admin'], function () {

        Route::group(['middleware' => ['role:super']], function () {
            Route::group(['prefix' => 'management/language', 'as' => 'language.'], function () {
                Route::get('/', [LanguageController::class, 'index'])
                    ->name('index');
                Route::get('/create', [LanguageController::class, 'create'])
                    ->name('create');
                Route::post('/store', [LanguageController::class, 'store'])
                    ->name('store');
                Route::get('/{id}/edit', [LanguageController::class, 'edit'])
                    ->name('edit');
                Route::put('/{id}', [LanguageController::class, 'update'])
                    ->name('update');
                Route::put('/{id}/status', [LanguageController::class, 'status'])
                    ->name('status');
                Route::delete('/{id}/soft', [LanguageController::class, 'softdelete'])
                    ->name('soft');
                Route::delete('/{id}/permanent', [LanguageController::class, 'permanentDelete'])
                    ->name('permanent');
                Route::put('/{id}/restore', [LanguageController::class, 'restore'])
                    ->name('restore');
            });
        });

    });
});
