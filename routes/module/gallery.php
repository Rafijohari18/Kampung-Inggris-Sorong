<?php

use App\Http\Controllers\Admin\Gallery\AlbumController;
use App\Http\Controllers\Admin\Gallery\PhotoController;
use App\Http\Controllers\Admin\Gallery\PlaylistController;
use App\Http\Controllers\Admin\Gallery\VideoController;
use App\Http\Controllers\GalleryController;
use Illuminate\Support\Facades\Route;

/**
 * backend
 */
Route::group(['middleware' => ['auth']], function () {

    Route::group(['prefix' => 'admin'], function () {

        Route::group(['prefix' => 'management/gallery', 'as' => 'gallery.'], function () {
            //album
            Route::group(['prefix' => '/album', 'as' => 'album.'], function () {
                Route::get('/', [AlbumController::class, 'index'])
                    ->name('index')
                    ->middleware('permission:albums');
                Route::get('/create', [AlbumController::class, 'create'])
                    ->name('create')
                    ->middleware('permission:album_create');
                Route::post('/', [AlbumController::class, 'store'])
                    ->name('store')
                    ->middleware('permission:album_create');
                Route::get('/{id}/edit', [AlbumController::class, 'edit'])
                    ->name('edit')
                    ->middleware('permission:album_edit');
                Route::put('/{id}', [AlbumController::class, 'update'])
                    ->name('update')
                    ->middleware('permission:album_edit');
                Route::put('/{id}/publish', [AlbumController::class, 'publish'])
                    ->name('publish')
                    ->middleware('permission:album_edit');
                Route::put('/{id}/position/{position}', [AlbumController::class, 'position'])
                    ->name('position')
                    ->middleware('permission:album_edit');
                Route::delete('/{id}', [AlbumController::class, 'destroy'])
                    ->name('destroy')
                    ->middleware('permission:album_delete');

                //photo
                Route::get('/{albumId}/photo', [PhotoController::class, 'index'])
                    ->name('photo')
                    ->middleware('permission:album_photo');
                Route::get('/{albumId}/photo/create', [PhotoController::class, 'create'])
                    ->name('photo.create')
                    ->middleware('permission:album_photo');
                Route::post('/{albumId}/photo', [PhotoController::class, 'store'])
                    ->name('photo.store')
                    ->middleware('permission:album_photo');
                Route::get('/{albumId}/photo/{id}/edit', [PhotoController::class, 'edit'])
                    ->name('photo.edit')
                    ->middleware('permission:album_photo');
                Route::put('/{albumId}/photo/{id}', [PhotoController::class, 'update'])
                    ->name('photo.update')
                    ->middleware('permission:album_photo');
                Route::post('/{albumId}/photo/sort', [PhotoController::class, 'sort'])
                    ->name('photo.sort')
                    ->middleware('permission:album_photo');
                Route::put('/{albumId}/photo/{id}/position/{position}', [PhotoController::class, 'position'])
                    ->name('photo.position')
                    ->middleware('permission:album_photo');
                Route::delete('/{albumId}/photo/{id}', [PhotoController::class, 'destroy'])
                    ->name('photo.destroy')
                    ->middleware('permission:album_photo');
            });

            //playlist
            Route::group(['prefix' => '/playlist', 'as' => 'playlist.'], function () {
                Route::get('/', [PlaylistController::class, 'index'])
                    ->name('index')
                    ->middleware('permission:playlists');
                Route::get('/create', [PlaylistController::class, 'create'])
                    ->name('create')
                    ->middleware('permission:playlist_create');
                Route::post('/', [PlaylistController::class, 'store'])
                    ->name('store')
                    ->middleware('permission:playlist_create');
                Route::get('/{id}/edit', [PlaylistController::class, 'edit'])
                    ->name('edit')
                    ->middleware('permission:playlist_edit');
                Route::put('/{id}', [PlaylistController::class, 'update'])
                    ->name('update')
                    ->middleware('permission:playlist_edit');
                Route::put('/{id}/publish', [PlaylistController::class, 'publish'])
                    ->name('publish')
                    ->middleware('permission:playlist_edit');
                Route::put('/{id}/position/{position}', [PlaylistController::class, 'position'])
                    ->name('position')
                    ->middleware('permission:playlist_edit');
                Route::delete('/{id}', [PlaylistController::class, 'destroy'])
                    ->name('destroy')
                    ->middleware('permission:playlist_delete');

                //video
                Route::get('/{playlistId}/video', [VideoController::class, 'index'])
                    ->name('video')
                    ->middleware('permission:playlist_video');
                Route::get('/{playlistId}/video/create', [VideoController::class, 'create'])
                    ->name('video.create')
                    ->middleware('permission:playlist_video');
                Route::post('/{playlistId}/video', [VideoController::class, 'store'])
                    ->name('video.store')
                    ->middleware('permission:playlist_video');
                Route::get('/{playlistId}/video/{id}/edit', [VideoController::class, 'edit'])
                    ->name('video.edit')
                    ->middleware('permission:playlist_video');
                Route::put('/{playlistId}/video/{id}', [VideoController::class, 'update'])
                    ->name('video.update')
                    ->middleware('permission:playlist_video');
                Route::post('/{playlistId}/video/sort', [VideoController::class, 'sort'])
                    ->name('video.sort')
                    ->middleware('permission:playlist_video');
                Route::put('/{playlistId}/video/{id}/position/{position}', [VideoController::class, 'position'])
                    ->name('video.position')
                    ->middleware('permission:playlist_video');
                Route::delete('/{playlistId}/video/{id}', [VideoController::class, 'destroy'])
                    ->name('video.destroy')
                    ->middleware('permission:playlist_video');
            });
        });

    });

});

/**
 * frontend
 */
if (config('custom.language.multiple') == true) {

    Route::group(['prefix' => '{locale?}', 'middleware' => 'languages'], function () {

        Route::group(['prefix' => 'gallery'], function () {
            Route::get('/list', [GalleryController::class, 'viewGalleryList'])
                ->name('gallery.list');
            //album
            Route::get('/album/list', [GalleryController::class, 'viewAlbumList'])
                ->name('album.list');
            Route::get('/album/{id}/view', [GalleryController::class, 'viewAlbumWithLang'])
                ->name('album.view');

            //playlist
            Route::get('/playlist/list', [GalleryController::class, 'viewPlaylistList'])
                ->name('playlist.list');
            Route::get('/playlist/{id}/view', [GalleryController::class, 'viewPlaylistWithLang'])
                ->name('playlist.view');
        });

    });

} else {

    Route::group(['prefix' => 'gallery'], function () {
        Route::get('/list', [GalleryController::class, 'viewGalleryList'])
            ->name('gallery.list');
        //album
        Route::get('/album/list', [GalleryController::class, 'viewAlbumList'])
            ->name('album.list');
        Route::get('/album/{id}/view', [GalleryController::class, 'viewAlbumWithoutLang'])
            ->name('album.view');

        //playlist
        Route::get('/playlist/list', [GalleryController::class, 'viewPlaylistList'])
            ->name('playlist.list');
        Route::get('/playlist/{id}/view', [GalleryController::class, 'viewPlaylistWithoutLang'])
            ->name('playlist.view');
    });
}
