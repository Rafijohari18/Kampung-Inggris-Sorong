<?php

use App\Http\Controllers\Admin\Banner\BannerCategoryController;
use App\Http\Controllers\Admin\Banner\BannerController;
use Illuminate\Support\Facades\Route;

/**
 * backend
 */
Route::group(['middleware' => ['auth']], function () {

    Route::group(['prefix' => 'admin'], function () {

        Route::group(['prefix' => 'management/banner', 'as' => 'banner.'], function () {
            //category
            Route::get('/', [BannerCategoryController::class, 'index'])
                ->name('category.index')
                ->middleware('permission:banner_categories');
            Route::get('/create', [BannerCategoryController::class, 'create'])
                ->name('category.create')
                ->middleware('permission:banner_category_create');
            Route::post('/', [BannerCategoryController::class, 'store'])
                ->name('category.store')
                ->middleware('permission:banner_category_create');
            Route::get('/{id}/edit', [BannerCategoryController::class, 'edit'])
                ->name('category.edit')
                ->middleware('permission:banner_category_edit');
            Route::put('/{id}', [BannerCategoryController::class, 'update'])
                ->name('category.update')
                ->middleware('permission:banner_category_edit');
            Route::delete('/{id}', [BannerCategoryController::class, 'destroy'])
                ->name('category.destroy')
                ->middleware('permission:banner_category_delete');

            //banner
            Route::get('/category/{categoryId}', [BannerController::class, 'index'])
                ->name('index')
                ->middleware('permission:banners');
            Route::get('/category/{categoryId}/create', [BannerController::class, 'create'])
                ->name('create')
                ->middleware('permission:banner_create');
            Route::post('/category/{categoryId}', [BannerController::class, 'store'])
                ->name('store')
                ->middleware('permission:banner_create');
            Route::get('/category/{categoryId}/{id}/edit', [BannerController::class, 'edit'])
                ->name('edit')
                ->middleware('permission:banner_edit');
            Route::put('/category/{categoryId}/{id}', [BannerController::class, 'update'])
                ->name('update')
                ->middleware('permission:banner_edit');
            Route::put('/category/{categoryId}/{id}/publish', [BannerController::class, 'publish'])
                ->name('publish')
                ->middleware('permission:banner_edit');
            Route::put('/category/{categoryId}/{id}/position/{position}', [BannerController::class, 'position'])
                ->name('position')
                ->middleware('permission:banner_edit');
            Route::post('/category/{categoryId}/sort', [BannerController::class, 'sort'])
                ->name('sort')
                ->middleware('permission:banner_edit');
            Route::delete('/category/{categoryId}/{id}', [BannerController::class, 'destroy'])
                ->name('destroy')
                ->middleware('permission:banner_delete');
        });

    });
});
