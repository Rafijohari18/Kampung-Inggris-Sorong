<?php

use App\Http\Controllers\Admin\Content\CategoryController;
use App\Http\Controllers\Admin\Content\PostController;
use App\Http\Controllers\Admin\Content\SectionController;
use App\Http\Controllers\ContentViewController;
use Illuminate\Support\Facades\Route;

/**
 * backend
 */
Route::group(['middleware' => ['auth']], function () {

    Route::group(['prefix' => 'admin'], function () {

        //section
        Route::group(['prefix' => 'management/section', 'as' => 'section.'], function () {
            Route::get('/', [SectionController::class, 'index'])
                ->name('index')
                ->middleware('permission:content_sections');
            Route::get('/create', [SectionController::class, 'create'])
                ->name('create')
                ->middleware('permission:content_section_create');
            Route::post('/store', [SectionController::class, 'store'])
                ->name('store')
                ->middleware('permission:content_section_create');
            Route::get('/{id}/edit', [SectionController::class, 'edit'])
                ->name('edit')
                ->middleware('permission:content_section_edit');
            Route::put('/{id}', [SectionController::class, 'update'])
                ->name('update')
                ->middleware('permission:content_section_edit');
            Route::delete('/{id}', [SectionController::class, 'destroy'])
                ->name('destroy')
                ->middleware('permission:content_section_delete');
        });

        //category
        Route::group(['prefix' => 'management/section/{sectionId}/category', 'as' => 'category.'], function () {
            Route::get('/', [CategoryController::class, 'index'])
                ->name('index')
                ->middleware('permission:content_categories');
            Route::get('/create', [CategoryController::class, 'create'])
                ->name('create')
                ->middleware('permission:content_category_create');
            Route::post('/store', [CategoryController::class, 'store'])
                ->name('store')
                ->middleware('permission:content_category_create');
            Route::get('/{id}/edit', [CategoryController::class, 'edit'])
                ->name('edit')
                ->middleware('permission:content_category_edit');
            Route::put('/{id}', [CategoryController::class, 'update'])
                ->name('update')
                ->middleware('permission:content_category_edit');
            Route::delete('/{id}', [CategoryController::class, 'destroy'])
                ->name('destroy')
                ->middleware('permission:content_category_delete');
        });

        //post
        Route::group(['prefix' => 'management/section/{sectionId}/post', 'as' => 'post.'], function () {
            Route::get('/', [PostController::class, 'index'])
                ->name('index')
                ->middleware('permission:content_posts');
            Route::get('create', [PostController::class, 'create'])
                ->name('create')
                ->middleware('permission:content_post_create');
            Route::post('/', [PostController::class, 'store'])
                ->name('store')
                ->middleware('permission:content_post_create');
            Route::get('/{id}/edit', [PostController::class, 'edit'])
                ->name('edit')
                ->middleware('permission:content_post_edit');
            Route::put('/{id}', [PostController::class, 'update'])
                ->name('update')
                ->middleware('permission:content_post_edit');
            Route::put('/{id}/publish', [PostController::class, 'publish'])
                ->name('publish')
                ->middleware('permission:content_post_edit');
            Route::put('/{id}/selection', [PostController::class, 'selection'])
                ->name('selection')
                ->middleware('permission:content_post_edit');
            Route::put('/{id}/position/{position}', [PostController::class, 'position'])
                ->name('position')
                ->middleware('permission:content_post_edit');
            Route::delete('/{id}', [PostController::class, 'destroy'])
                ->name('destroy')
                ->middleware('permission:content_post_delete');
            Route::delete('/{id}/file', [PostController::class, 'destroyFile'])
                ->name('file.destroy')
                ->middleware('permission:content_post_delete');
        });

    });

});

/**
 * frontend
 */
if (config('custom.language.multiple') == true) {
    
    Route::group(['prefix' => '{locale?}', 'middleware' => 'languages'], function () {

        Route::group(['prefix' => 'content'], function () {
            //section
            Route::get('/section/list', [ContentViewController::class, 'viewSectionList'])
                ->name('section.list');
            Route::get('/section/view/{id}/{slug}', [ContentViewController::class, 'viewSectionWithLang'])
                ->name('section.view');
            //category
            Route::get('/category/list', [ContentViewController::class, 'viewCategoryList'])
                ->name('category.list');
            Route::get('/category/view/{id}/{slug}', [ContentViewController::class, 'viewCategoryWithLang'])
                ->name('category.view');
            //post
            Route::get('/post/list', [ContentViewController::class, 'viewPostList'])
                ->name('post.list');
            Route::get('/post/view/{id}/{slug}', [ContentViewController::class, 'viewPostWithLang'])
                ->name('post.view');
        });

    });

} else {

    Route::group(['prefix' => 'content'], function () {
        //section
        Route::get('/section/list', [ContentViewController::class, 'viewSectionList'])
            ->name('section.list');
        Route::get('/section/view/{id}/{slug}', [ContentViewController::class, 'viewSectionWithoutLang'])
            ->name('section.view');
        //category
        Route::get('/category/list', [ContentViewController::class, 'viewCategoryList'])
            ->name('category.list');
        Route::get('/category/view/{id}/{slug}', [ContentViewController::class, 'viewCategoryWithoutLang'])
            ->name('category.view');
        //post
        Route::get('/post/list', [ContentViewController::class, 'viewPostList'])
            ->name('post.list');
        Route::get('/post/view/{id}/{slug}', [ContentViewController::class, 'viewPostWithoutLang'])
            ->name('post.view');
    });
}
