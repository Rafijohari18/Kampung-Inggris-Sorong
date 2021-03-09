<?php

use App\Http\Controllers\Admin\InquiryController;
use App\Http\Controllers\InquiryViewController;
use Illuminate\Support\Facades\Route;

/**
 * backend
 */
Route::group(['middleware' => ['auth']], function () {

    Route::group(['prefix' => 'admin'], function () {

        Route::group(['prefix' => 'management/inquiry', 'as' => 'inquiry.'], function () {
            Route::get('/', [InquiryController::class, 'index'])
                ->name('index')
                ->middleware('permission:inquiries');
            Route::get('/create', [InquiryController::class, 'create'])
                ->name('create')
                ->middleware('permission:inquiry_create');
            Route::post('/', [InquiryController::class, 'store'])
                ->name('store')
                ->middleware('permission:inquiry_create');
            Route::get('/{id}/edit', [InquiryController::class, 'edit'])
                ->name('edit')
                ->middleware('permission:inquiry_edit');
            Route::put('/{id}/update', [InquiryController::class, 'update'])
                ->name('update')
                ->middleware('permission:inquiry_edit');
            Route::put('/{id}/publish', [InquiryController::class, 'publish'])
                ->name('publish')
                ->middleware('permission:inquiry_edit');
            Route::delete('/{id}', [InquiryController::class, 'destroy'])
                ->name('destroy')
                ->middleware('permission:inquiry_delete');

            //detail
            Route::get('/{inquiryId}/detail', [InquiryController::class, 'detail'])
                ->name('detail')
                ->middleware('permission:inquiry_detail');
            Route::get('/{inquiryId}/export', [InquiryController::class, 'export'])
                ->name('detail.export')
                ->middleware('permission:inquiry_detail');
            Route::post('/{inquiryId}/detail/{id}/read', [InquiryController::class, 'read'])
                ->name('read')
                ->middleware('permission:inquiry_detail');
            Route::delete('/{inquiryId}/detail/{id}', [InquiryController::class, 'destroyMessage'])
                ->name('destroy.message')
                ->middleware('permission:inquiry_detail');
        });

    });
});

/**
 * frontend
 */
if (config('custom.language.multiple') == true) {

    Route::group(['prefix' => '{locale?}', 'middleware' => 'languages'], function () {

        //inquiry
        Route::get('/inquiry/list', [InquiryViewController::class, 'viewInquiryList'])
            ->name('inquiry.list');
        Route::get('/inquiry/view/{slug}', [InquiryViewController::class, 'viewWithLang'])
            ->name('inquiry.view');
        Route::post('/inquiry/{id}/contact', [InquiryViewController::class, 'sendContact'])
            ->name('inquiry.contact.send');
    });

} else {

    //inquiry
    Route::get('/inquiry/list', [InquiryViewController::class, 'viewInquiryList'])
        ->name('inquiry.list');
    Route::get('/inquiry/view/{slug}', [InquiryViewController::class, 'viewWithoutLang'])
        ->name('inquiry.view');
    Route::post('/inquiry/{id}/contact', [InquiryViewController::class, 'sendContact'])
        ->name('inquiry.contact.send');
}

Route::post('/inquiry/{id}/contact', [InquiryViewController::class, 'sendContact'])
            ->name('inquiry.send');