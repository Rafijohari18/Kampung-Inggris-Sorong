<?php

namespace App\Http\Controllers\Admin\Gallery;

use App\Http\Controllers\Controller;
use App\Http\Requests\Gallery\AlbumPhotoRequest;
use App\Services\Gallery\AlbumService;
use App\Services\Gallery\PhotoService;
use App\Services\LanguageService;
use Illuminate\Http\Request;

class PhotoController extends Controller
{
    private $service, $serviceAlbum, $serviceLang;

    public function __construct(
        PhotoService $service,
        AlbumService $serviceAlbum,
        LanguageService $serviceLang
    )
    {
        $this->service = $service;
        $this->serviceAlbum = $serviceAlbum;
        $this->serviceLang = $serviceLang;
    }

    public function index(Request $request, $albumId)
    {
        $l = '';
        $q = '';
        if (isset($request->l) || isset($request->q)) {
            $l = '?l='.$request->l;
            $q = '&q='.$request->q;
        }

        $data['photo'] = $this->service->getPhotoList($request, $albumId);
        $data['no'] = $data['photo']->firstItem();
        $data['photo']->withPath(url()->current().$l.$q);
        $data['album'] = $this->serviceAlbum->find($albumId);

        return view('backend.gallery.albums.photo.index', compact('data'), [
            'title' => 'Album - Photo',
            'breadcrumbs' => [
                'Gallery' => '',
                'Album' => route('gallery.album.index'),
                'Photo' => '',
            ]
        ]);
    }

    public function create($albumId)
    {
        $data['album'] = $this->serviceAlbum->find($albumId);
        $data['languages'] = $this->serviceLang->getAllLang();

        return view('backend.gallery.albums.photo.form', compact('data'), [
            'title' => 'Photo - Create',
            'breadcrumbs' => [
                'Gallery' => '',
                'Album' => route('gallery.album.index'),
                'Photo' => route('gallery.album.photo', ['albumId' => $albumId]),
                'Create' => '',
            ],
        ]);
    }

    public function store(AlbumPhotoRequest $request, $albumId)
    {
        $this->service->store($request, $albumId);

        $redir = $this->redirectForm($request, $albumId);
        return $redir->with('success', 'photo successfully added');
    }

    public function edit($albumId, $id)
    {
        $data['album'] = $this->serviceAlbum->find($albumId);
        $data['languages'] = $this->serviceLang->getAllLang();
        $data['photo'] = $this->service->find($id);

        return view('backend.gallery.albums.photo.form-edit', compact('data'), [
            'title' => 'Photo - Edit',
            'breadcrumbs' => [
                'Gallery' => '',
                'Album' => route('gallery.album.index'),
                'Photo' => route('gallery.album.photo', ['albumId' => $albumId]),
                'Create' => '',
            ],
        ]);
    }

    public function update(Request $request, $albumId, $id)
    {
        $this->service->update($request, $id);

        $redir = $this->redirectForm($request, $albumId);
        return $redir->with('success', 'photo successfully updated');
    }

    public function position($albumId, $id, $position)
    {
        $this->service->position($id, $position);

        return back()->with('success', 'position photo changed');
    }

    public function sort($albumId)
    {
        $i = 0;

        foreach ($_POST['datas'] as $value) {
            $i++;
            $this->service->sort($value, $i);
        }
    }

    public function destroy($albumId, $id)
    {
        $this->service->delete($id);

        return response()->json([
            'success' => 1,
            'message' => ''
        ], 200);
    }

    public function redirectForm($request, $albumId)
    {
        $redir = redirect()->route('gallery.album.photo', ['albumId' => $albumId]);
        if ($request->action == 'back') {
            $redir = back();
        }

        return $redir;
    }
}
