<?php

use App\Http\Controllers\Admin\ConfigurationController;
use Illuminate\Support\Facades\Route;

/**
 * backend
 */
Route::group(['middleware' => ['auth']], function () {

    Route::group(['prefix' => 'admin'], function () {

        Route::get('/visitor', [ConfigurationController::class, 'visitor'])
            ->name('visitor')
            ->middleware('permission:visitor');
        Route::get('/filemanager', [ConfigurationController::class, 'filemanager'])
            ->name('filemanager')
            ->middleware('permission:filemanager');

        Route::group(['prefix' => 'configuration', 'as' => 'configuration.'], function () {
            Route::get('/web', [ConfigurationController::class, 'web'])
                ->name('web')
                ->middleware('permission:configurations');
            Route::put('/web', [ConfigurationController::class, 'update'])
                ->name('web.update')
                ->middleware('permission:configurations');
            Route::put('/web/{name}/upload', [ConfigurationController::class, 'upload'])
                ->name('web.upload')
                ->middleware('permission:configurations');
            Route::get('/common/{lang}', [ConfigurationController::class, 'common'])
                ->name('common')
                ->middleware('permission:commons');
            Route::get('/maintenance', [ConfigurationController::class, 'maintenance'])
                ->name('maintenance')
                ->middleware('role:super');
        });

    });
});
