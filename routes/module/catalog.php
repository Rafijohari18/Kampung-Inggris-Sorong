<?php

use App\Http\Controllers\Admin\Catalog\CatalogCategoryController;
use App\Http\Controllers\Admin\Catalog\CatalogProductController;
use App\Http\Controllers\Admin\Catalog\CatalogProductImageController;
use App\Http\Controllers\CatalogViewController;
use Illuminate\Support\Facades\Route;

/**
 * backend
 */
Route::group(['middleware' => ['auth']], function () {

    Route::group(['prefix' => 'admin'], function () {

        Route::group(['prefix' => 'management/catalog', 'as' => 'catalog.'], function () {
            //category
            Route::get('/category', [CatalogCategoryController::class, 'index'])
                ->name('category.index')
                ->middleware('permission:catalog_categories');
            Route::get('/category/create', [CatalogCategoryController::class, 'create'])
                ->name('category.create')
                ->middleware('permission:catalog_category_create');
            Route::post('/category/store', [CatalogCategoryController::class, 'store'])
                ->name('category.store')
                ->middleware('permission:catalog_category_create');
            Route::get('/category/{id}/edit', [CatalogCategoryController::class, 'edit'])
                ->name('category.edit')
                ->middleware('permission:catalog_category_edit');
            Route::put('/category/{id}', [CatalogCategoryController::class, 'update'])
                ->name('category.update')
                ->middleware('permission:catalog_category_edit');
            Route::put('/category/{id}/position/{position}', [CatalogCategoryController::class, 'position'])
                ->name('category.position')
                ->middleware('permission:catalog_category_edit');
            Route::delete('/category/{id}', [CatalogCategoryController::class, 'destroy'])
                ->name('category.destroy')
                ->middleware('permission:catalog_category_delete');

             //product
             Route::get('/category/{categoryId}/product', [CatalogProductController::class, 'index'])
                ->name('product.index')
                ->middleware('permission:catalog_products');
            Route::get('/category/{categoryId}/product/create', [CatalogProductController::class, 'create'])
                ->name('product.create')
                ->middleware('permission:catalog_product_create');
            Route::post('/category/{categoryId}/product/store', [CatalogProductController::class, 'store'])
                ->name('product.store')
                ->middleware('permission:catalog_product_create');
            Route::get('/category/{categoryId}/product/{id}/edit', [CatalogProductController::class, 'edit'])
                ->name('product.edit')
                ->middleware('permission:catalog_product_edit');
            Route::put('/category/{categoryId}/product/{id}', [CatalogProductController::class, 'update'])
                ->name('product.update')
                ->middleware('permission:catalog_product_edit');
            Route::put('/category/{categoryId}/product/{id}/position/{position}', [CatalogProductController::class, 'position'])
                ->name('product.position')
                ->middleware('permission:catalog_product_edit');
            Route::put('/category/{categoryId}/product/{id}/publish', [CatalogProductController::class, 'publish'])
                ->name('product.publish')
                ->middleware('permission:catalog_product_edit');
            Route::put('/category/{categoryId}/product/{id}/selection', [CatalogProductController::class, 'selection'])
                ->name('product.selection')
                ->middleware('permission:catalog_product_edit');
            Route::delete('/category/{categoryId}/product/{id}', [CatalogProductController::class, 'destroy'])
                ->name('product.destroy')
                ->middleware('permission:catalog_product_delete');

            //images
            Route::get('/product/{productId}/images', [CatalogProductImageController::class, 'index'])
                ->name('product.image')
                ->middleware('permission:catalog_product_image');
            Route::post('/product/{productId}/image', [CatalogProductImageController::class, 'store'])
                ->name('product.image.store')
                ->middleware('permission:catalog_product_image');
            Route::put('/product/{productId}/image/{id}', [CatalogProductImageController::class, 'update'])
                ->name('product.image.update')
                ->middleware('permission:catalog_product_image');
            Route::put('/product/{productId}/image/{id}/position/{position}', [CatalogProductImageController::class, 'position'])
                ->name('product.image.position')
                ->middleware('permission:catalog_product_image');
            Route::post('/product/{productId}/image/sort', [CatalogProductImageController::class, 'sort'])
                ->name('product.image.sort')
                ->middleware('permission:catalog_product_image');
            Route::delete('/product/{productId}/image/{id}', [CatalogProductImageController::class, 'destroy'])
                ->name('product.image.destroy')
                ->middleware('permission:catalog_product_image');
        });

    });
});

/**
 * frontend
 */
if (config('custom.language.multiple') == true) {

    Route::group(['prefix' => '{locale?}', 'middleware' => 'languages'], function () {

        Route::group(['prefix' => 'catalogue', 'as' => 'catalog.'], function () {
            Route::get('/view', [CatalogViewController::class, 'viewCatalog'])
                ->name('view');
            //category
            Route::get('/category/list', [CatalogViewController::class, 'viewCatalogCategoryList'])
                ->name('category.list');
            Route::get('/category/view/{id}/{slug}', [CatalogViewController::class, 'viewCatalogCategoryWithLang'])
                ->name('category.view');
            //product
            Route::get('/product/list', [CatalogViewController::class, 'viewCatalogProductList'])
                ->name('product.list');
            Route::get('/product/view/{id}/{slug}', [CatalogViewController::class, 'viewCatalogProductWithLang'])
                ->name('product.view');
        });

    });

} else {

    Route::group(['prefix' => 'catalogue', 'as' => 'catalog.'], function () {
        Route::get('/view', [CatalogViewController::class, 'viewCatalog'])
            ->name('view');
        //category
        Route::get('/category/list', [CatalogViewController::class, 'viewCatalogCategoryList'])
            ->name('category.list');
        Route::get('/category/view/{id}/{slug}', [CatalogViewController::class, 'viewCatalogCategoryWithoutLang'])
            ->name('category.view');
        //product
        Route::get('/product/list', [CatalogViewController::class, 'viewCatalogProductList'])
            ->name('product.list');
        Route::get('/product/view/{id}/{slug}', [CatalogViewController::class, 'viewCatalogProductWithoutLang'])
            ->name('product.view');
    });
}
