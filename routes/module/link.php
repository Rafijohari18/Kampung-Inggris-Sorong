<?php

use App\Http\Controllers\Admin\Link\LinkController;
use App\Http\Controllers\Admin\Link\LinkMediaController;
use App\Http\Controllers\LinkViewController;
use Illuminate\Support\Facades\Route;

/**
 * backend
 */
Route::group(['middleware' => ['auth']], function () {

    Route::group(['prefix' => 'admin'], function () {

        Route::group(['prefix' => 'management/link', 'as' => 'link.'], function () {
            //link
            Route::get('/', [LinkController::class, 'index'])
                ->name('index')
                ->middleware('permission:links');
            Route::get('/create', [LinkController::class, 'create'])
                ->name('create')
                ->middleware('permission:link_create');
            Route::post('/', [LinkController::class, 'store'])
                ->name('store')
                ->middleware('permission:link_create');
            Route::get('/{id}/edit', [LinkController::class, 'edit'])
                ->name('edit')
                ->middleware('permission:link_edit');
            Route::put('/{id}', [LinkController::class, 'update'])
                ->name('update')
                ->middleware('permission:link_edit');
            Route::put('/{id}/publish', [LinkController::class, 'publish'])
                ->name('publish')
                ->middleware('permission:link_edit');
            Route::put('/{id}/position/{position}', [LinkController::class, 'position'])
                ->name('position')
                ->middleware('permission:link_edit');
            Route::delete('/{id}', [LinkController::class, 'destroy'])
                ->name('destroy')
                ->middleware('permission:link_delete');

            //media
            Route::get('/{linkId}/media', [LinkMediaController::class, 'index'])
                ->name('media')
                ->middleware('permission:link_media');
            Route::get('/{linkId}/media/create', [LinkMediaController::class, 'create'])
                ->name('media.create')
                ->middleware('permission:link_media');
            Route::post('/{linkId}/media', [LinkMediaController::class, 'store'])
                ->name('media.store')
                ->middleware('permission:link_media');
            Route::get('/{linkId}/media/{id}/edit', [LinkMediaController::class, 'edit'])
                ->name('media.edit')
                ->middleware('permission:link_media');
            Route::put('/{linkId}/media/{id}', [LinkMediaController::class, 'update'])
                ->name('media.update')
                ->middleware('permission:link_media');
            Route::put('/{linkId}/media/{id}/position/{position}', [LinkMediaController::class, 'position'])
                ->name('media.position')
                ->middleware('permission:link_media');
            Route::delete('/{linkId}/media/{id}', [LinkMediaController::class, 'destroy'])
                ->name('media.destroy')
                ->middleware('permission:link_media');

        });

    });

});

/**
 * frontend
 */
if (config('custom.language.multiple') == true) {

    Route::group(['prefix' => '{locale?}', 'middleware' => 'languages'], function () {

        //link
        Route::get('/link/list', [LinkViewController::class, 'viewLinkList'])
            ->name('link.list');
        Route::get('/link/view/{id}/{slug}', [LinkViewController::class, 'viewWithLang'])
            ->name('link.view');
    });

} else {

    //link
    Route::get('/link/list', [LinkViewController::class, 'viewLinkList'])
        ->name('link.list');
    Route::get('/link/view/{id}/{slug}', [LinkViewController::class, 'viewWithoutLang'])
        ->name('link.view');
}
