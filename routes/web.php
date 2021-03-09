<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\Users\ACL\PermissionController;
use App\Http\Controllers\Admin\Users\ACL\RoleController;
use App\Http\Controllers\Admin\Users\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageViewController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/**
 * auth backend
 */
Route::get('/backend', function () {
    return redirect('/backend/authentication');
});
Route::get('/login', function () {
    return redirect('/backend/authentication');
});

Route::get('/backend/authentication', [LoginController::class, 'showLoginBackendForm'])
    ->name('backend.login')
    ->middleware('guest');
Route::get('/backend/re-authentication', [LoginController::class, 'showLoginBackendForm'])
    ->name('login')
    ->middleware('guest');
Route::post('/backend/authentication', [LoginController::class, 'loginBackend'])
    ->middleware('guest');

/**
 * backend
 */
Route::group(['middleware' => ['auth']], function () {

    Route::group(['prefix' => 'admin'], function () {

        //dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('backend.dashboard');

        //profile
        Route::get('/profile', [UserController::class, 'profile'])
            ->name('profile');
        Route::put('/profile', [UserController::class, 'updateProfile']);
        //photo
        Route::put('/profile/photo/change', [UserController::class, 'changePhoto'])
            ->name('profile.photo.change');
        Route::put('/profile/photo/remove', [UserController::class, 'removePhoto'])
            ->name('profile.photo.remove');
        //email
        Route::get('/profile/mail/send', [UserController::class, 'sendMailVerification'])
            ->name('profile.mail.send');
        Route::get('/profile/mail/verification/{email}', [UserController::class, 'verificationMail'])
            ->name('profile.mail.verification');

        /**users manager */
        //roles
        Route::group(['prefix' => 'management/role', 'as' => 'role.'], function () {
            Route::get('/', [RoleController::class, 'index'])
                ->name('index')
                ->middleware('role:super');
            Route::post('/store', [RoleController::class, 'store'])
                ->name('store')
                ->middleware('role:super');
            Route::put('/{id}', [RoleController::class, 'update'])
                ->name('update')
                ->middleware('role:super');
            Route::get('/{id}/permission', [RoleController::class, 'permission'])
                ->name('permission')
                ->middleware('role:super');
            Route::put('/{id}/permission', [RoleController::class, 'setPermission'])
                ->middleware('role:super');
            Route::delete('/{id}', [RoleController::class, 'destroy'])
                ->name('destroy')
                ->middleware('role:super');
        });

        //permission
        Route::group(['prefix' => 'management/permission', 'as' => 'permission.'], function () {
            Route::get('/', [PermissionController::class, 'index'])
                ->name('index')
                ->middleware('role:super');
            Route::post('/store', [PermissionController::class, 'store'])
                ->name('store')
                ->middleware('role:super');
            Route::put('/{id}', [PermissionController::class, 'update'])
                ->name('update')
                ->middleware('role:super');
            Route::delete('/{id}', [PermissionController::class, 'destroy'])
                ->name('destroy')
                ->middleware('role:super');
        });

        //users
        Route::group(['prefix' => 'management/user', 'as' => 'user.'], function () {
            Route::get('/', [UserController::class, 'index'])
                ->name('index')
                ->middleware('permission:users');
            Route::get('/log', [UserController::class, 'log'])
                ->name('log')
                ->middleware('permission:users');
            Route::get('/create', [UserController::class, 'create'])
                ->name('create')
                ->middleware('permission:user_create');
            Route::post('/', [UserController::class, 'store'])
                ->name('store')
                ->middleware('permission:user_create');
            Route::get('/{id}/edit', [UserController::class, 'edit'])
                ->name('edit')
                ->middleware('permission:user_edit');
            Route::put('/{id}', [UserController::class, 'update'])
                ->name('update')
                ->middleware('permission:user_edit');
            Route::put('/{id}/activate', [UserController::class, 'activate'])
                ->name('activate')
                ->middleware('permission:user_edit');
            Route::delete('/{id}/soft', [UserController::class, 'softdelete'])
                ->name('soft')
                ->middleware('permission:user_delete');
            Route::delete('/{id}/permanent', [UserController::class, 'permanentDelete'])
                ->name('permanent')
                ->middleware('permission:user_delete');
            Route::put('/{id}/restore', [UserController::class, 'restore'])
                ->name('restore')
                ->middleware('permission:user_edit');
            Route::delete('/delete/{id}/log', [UserController::class, 'logDelete'])
                ->name('log.destroy')
                ->middleware('permission:user_delete');
        });

        //notification
        Route::get('/notification', [DashboardController::class, 'notification'])
            ->name('notification');
        Route::get('/notification/json', [DashboardController::class, 'notificationJson'])
            ->name('notification.json');
        Route::delete('/notification/{id}', [DashboardController::class, 'destroyNotification'])
            ->name('notification.destroy');

        //logout
        Route::post('/logout', [LoginController::class, 'logoutBackend'])
            ->name('backend.logout');
    });

});

/**
 * frontend
 */
//sitemap
Route::get('/sitemap.xml', [SitemapController::class, 'sitemap'])
    ->name('sitemap');
#--rss
Route::get('/feed', [SitemapController::class, 'feed'])
    ->name('rss.feed');
Route::get('/feed/post', [SitemapController::class, 'post'])
    ->name('rss.post');

//json
Route::get('/json/page/child/{childId}', [PageViewController::class, 'jsonPageChild'])
    ->name('json.page.child');

if (config('custom.language.multiple') == true) {

    Route::group(['prefix' => '{locale?}', 'middleware' => 'languages'], function () {

        //home
        Route::get('/', [HomeController::class, 'home'])
            ->name('home');
    });

} else {

    //home
    Route::get('/', [HomeController::class, 'home'])
        ->name('home');

    //search
    Route::get('/search', [HomeController::class, 'search'])
        ->name('home.search');
}

Route::get('/search/all', [HomeController::class, 'search'])
    ->name('home.search');
