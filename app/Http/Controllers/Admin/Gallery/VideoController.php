<?php

namespace App\Http\Controllers\Admin\Gallery;

use App\Http\Controllers\Controller;
use App\Http\Requests\Gallery\PlaylistVideoRequest;
use App\Services\Gallery\PlaylistService;
use App\Services\Gallery\VideoService;
use App\Services\LanguageService;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    private $service, $servicePlaylist, $serviceLang;

    public function __construct(
        VideoService $service,
        PlaylistService $servicePlaylist,
        LanguageService $serviceLang
    )
    {
        $this->service = $service;
        $this->servicePlaylist = $servicePlaylist;
        $this->serviceLang = $serviceLang;
    }

    public function index(Request $request, $playlistId)
    {
        $l = '';
        $q = '';
        if (isset($request->l) || isset($request->q)) {
            $l = '?l='.$request->l;
            $q = '&q='.$request->q;
        }

        $data['video'] = $this->service->getVideoList($request, $playlistId);
        $data['no'] = $data['video']->firstItem();
        $data['video']->withPath(url()->current().$l.$q);
        $data['playlist'] = $this->servicePlaylist->find($playlistId);

        return view('backend.gallery.playlists.video.index', compact('data'), [
            'title' => 'Playlist - Video',
            'breadcrumbs' => [
                'Gallery' => '',
                'Playlist' => route('gallery.playlist.index'),
                'Video' => '',
            ]
        ]);
    }

    public function create($playlistId)
    {
        $data['playlist'] = $this->servicePlaylist->find($playlistId);
        $data['languages'] = $this->serviceLang->getAllLang();

        return view('backend.gallery.playlists.video.form', compact('data'), [
            'title' => 'Video - Create',
            'breadcrumbs' => [
                'Gallery' => '',
                'Playlist' => route('gallery.playlist.index'),
                'Video' => route('gallery.playlist.video', ['playlistId' => $playlistId]),
                'Create' => '',
            ],
        ]);
    }

    public function store(PlaylistVideoRequest $request, $playlistId)
    {
        $this->service->store($request, $playlistId);

        $redir = $this->redirectForm($request, $playlistId);
        return $redir->with('success', 'video successfully added');
    }

    public function edit($playlistId, $id)
    {
        $data['playlist'] = $this->servicePlaylist->find($playlistId);
        $data['languages'] = $this->serviceLang->getAllLang();
        $data['video'] = $this->service->find($id);

        return view('backend.gallery.playlists.video.form-edit', compact('data'), [
            'title' => 'Video - Edit',
            'breadcrumbs' => [
                'Gallery' => '',
                'Playlist' => route('gallery.playlist.index'),
                'Video' => route('gallery.playlist.video', ['playlistId' => $playlistId]),
                'Create' => '',
            ],
        ]);
    }

    public function update(Request $request, $playlistId, $id)
    {
        $this->service->update($request, $id);

        $redir = $this->redirectForm($request, $playlistId);
        return $redir->with('success', 'video successfully updated');
    }

    public function position($playlistId, $id, $position)
    {
        $this->service->position($id, $position);

        return back()->with('success', 'position video changed');
    }

    public function sort($playlistId)
    {
        $i = 0;

        foreach ($_POST['datas'] as $value) {
            $i++;
            $this->service->sort($value, $i);
        }
    }

    public function destroy($playlistId, $id)
    {
        $this->service->delete($id);

        return response()->json([
            'success' => 1,
            'message' => ''
        ], 200);
    }

    public function redirectForm($request, $playlistId)
    {
        $redir = redirect()->route('gallery.playlist.video', ['playlistId' => $playlistId]);
        if ($request->action == 'back') {
            $redir = back();
        }

        return $redir;
    }
}
