<?php

use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\PageViewController;
use Illuminate\Support\Facades\Route;

/**
 * backend
 */
Route::group(['middleware' => ['auth']], function () {

    Route::group(['prefix' => 'admin'], function () {

        Route::group(['prefix' => 'management/page', 'as' => 'page.'], function () {
            Route::get('/', [PageController::class, 'index'])
                ->name('index')
                ->middleware('permission:pages|page_child');
            Route::get('/create', [PageController::class, 'create'])
                ->name('create')
                ->middleware('permission:page_create|page_child');
            Route::post('/store', [PageController::class, 'store'])
                ->name('store')
                ->middleware('permission:page_create|page_child');
            Route::get('/{id}/edit', [PageController::class, 'edit'])
                ->name('edit')
                ->middleware('permission:page_edit|page_child');
            Route::put('/{id}', [PageController::class, 'update'])
                ->name('update')
                ->middleware('permission:page_edit|page_child');
            Route::put('/{id}/publish', [PageController::class, 'publish'])
                ->name('publish')
                ->middleware('permission:page_edit|page_child');
            Route::put('/{id}/position/{position}', [PageController::class, 'position'])
                ->name('position')
                ->middleware('permission:page_edit|page_child');
            Route::delete('/{id}', [PageController::class, 'destroy'])
                ->name('destroy')
                ->middleware('permission:page_delete|page_child');
        });

    });

});

/**
 * frontend
 */
if (config('custom.language.multiple') == true) {

    Route::group(['prefix' => '{locale?}', 'middleware' => 'languages'], function () {

        //pages
        Route::get('/page/list', [PageViewController::class, 'viewPageList'])
            ->name('page.list');
        Route::get('/page/view/{id}/{slug}', [PageViewController::class, 'viewWithLang'])
            ->name('page.view');
    });

} else {

    //pages
    Route::get('/page/list', [PageViewController::class, 'viewPageList'])
        ->name('page.list');
    Route::get('/page/view/{id}/{slug}', [PageViewController::class, 'viewWithoutLang'])
        ->name('page.view');
}
