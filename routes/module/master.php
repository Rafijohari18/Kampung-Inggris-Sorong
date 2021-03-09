<?php

use App\Http\Controllers\Admin\Master\CommentController;
use App\Http\Controllers\Admin\Master\FieldController;
use App\Http\Controllers\Admin\Master\MediaController;
use App\Http\Controllers\Admin\Master\TagController;
use App\Http\Controllers\Admin\Master\TemplateViewController;
use Illuminate\Support\Facades\Route;

/**
 * backend
 */
Route::group(['middleware' => ['auth']], function () {

    Route::group(['prefix' => 'admin'], function () {

        //tags
        Route::group(['prefix' => 'management/tag', 'as' => 'tag.'], function () {
            Route::get('/', [TagController::class, 'index'])
                ->name('index')
                ->middleware('permission:tags');
            Route::get('/create', [TagController::class, 'create'])
                ->name('create')
                ->middleware('permission:tag_create');
            Route::post('/store', [TagController::class, 'store'])
                ->name('store')
                ->middleware('permission:tag_create');
            Route::get('/{id}/edit', [TagController::class, 'edit'])
                ->name('edit')
                ->middleware('permission:tag_edit');
            Route::put('/{id}', [TagController::class, 'update'])
                ->name('update')
                ->middleware('permission:tag_edit');
            Route::put('/{id}/flags', [TagController::class, 'flags'])
                ->name('flags')
                ->middleware('permission:tag_edit');
            Route::put('/{id}/standar', [TagController::class, 'standar'])
                ->name('standar')
                ->middleware('permission:tag_edit');
            Route::delete('/{id}', [TagController::class, 'destroy'])
                ->name('destroy')
                ->middleware('permission:tag_delete');
        });

        //comments
        Route::group(['prefix' => 'management/comment', 'as' => 'comment.'], function () {
            Route::get('/', [CommentController::class, 'index'])
                ->name('index')
                ->middleware('permission:comments');
            Route::put('/{id}/flags', [CommentController::class, 'flags'])
                ->name('flags')
                ->middleware('permission:comment_edit');
            Route::put('/reply/{id}/flags', [CommentController::class, 'flagsReply'])
                ->name('reply.flags')
                ->middleware('permission:comment_edit');
            Route::delete('/{id}', [CommentController::class, 'destroy'])
                ->name('destroy')
                ->middleware('permission:comment_delete');
            Route::delete('/{id}/reply', [CommentController::class, 'destroyReply'])
                ->name('reply.destroy')
                ->middleware('permission:comment_delete');
        });

        //field
        Route::group(['prefix' => 'management/field', 'as' => 'field.'], function () {
            Route::get('/{id}/{module}', [FieldController::class, 'form'])
                ->name('form')
                ->middleware('permission:fields');
            Route::post('/{id}/{module}', [FieldController::class, 'store'])
                ->name('store')
                ->middleware('permission:fields');
            Route::put('/{id}', [FieldController::class, 'update'])
                ->name('update')
                ->middleware('permission:fields');
            Route::delete('/{id}', [FieldController::class, 'destroy'])
                ->name('destroy')
                ->middleware('permission:fields');
        });

        //media
        Route::group(['prefix' => 'management/media', 'as' => 'media.'], function () {
            Route::get('/{id}/{module}', [MediaController::class, 'index'])
                ->name('index')
                ->middleware('permission:medias');
            Route::get('/{id}/{module}/create', [MediaController::class, 'create'])
                ->name('create')
                ->middleware('permission:medias');
            Route::post('/{id}/{module}', [MediaController::class, 'store'])
                ->name('store')
                ->middleware('permission:medias');
            Route::get('/{id}/{module}/{mediaId}/edit', [MediaController::class, 'edit'])
                ->name('edit')
                ->middleware('permission:medias');
            Route::put('/{id}/{module}/{mediaId}', [MediaController::class, 'update'])
                ->name('update')
                ->middleware('permission:medias');
            Route::post('/{id}/{module}/sort', [MediaController::class, 'sort'])
                ->name('sort')
                ->middleware('permission:medias');
            Route::delete('/{id}/delete', [MediaController::class, 'destroy'])
                ->name('destroy')
                ->middleware('permission:medias');
        });

        //template
        Route::group(['middleware' => ['role:super']], function () {
            Route::group(['prefix' => 'managament/template', 'as' => 'template.'], function () {
                Route::get('/', [TemplateViewController::class, 'index'])
                    ->name('index');
                Route::post('/store', [TemplateViewController::class, 'store'])
                    ->name('store');
                Route::put('/{id}', [TemplateViewController::class, 'update'])
                    ->name('update');
                Route::delete('/{id}', [TemplateViewController::class, 'destroy'])
                    ->name('destroy');
            });
        });

    });

});
