<?php

namespace App\Http\Controllers\Admin\Gallery;

use App\Http\Controllers\Controller;
use App\Http\Requests\Gallery\PlaylistRequest;
use App\Services\Gallery\PlaylistService;
use App\Services\LanguageService;
use App\Services\Master\TemplateViewService;
use Illuminate\Http\Request;

class PlaylistController extends Controller
{
    private $service, $serviceLang, $serviceTemplate;

    public function __construct(
        PlaylistService $service,
        LanguageService $serviceLang,
        TemplateViewService $serviceTemplate
    )
    {
        $this->service = $service;
        $this->serviceLang = $serviceLang;
        $this->serviceTemplate = $serviceTemplate;
    }

    public function index(Request $request)
    {
        $l = '';
        $s = '';
        $q = '';
        if (isset($request->l) || isset($request->s) || isset($request->q)) {
            $l = '?l='.$request->l;
            $s = '&s='.$request->s;
            $q = '&q='.$request->q;
        }

        $data['playlists'] = $this->service->getPlaylistList($request);
        $data['no'] = $data['playlists']->firstItem();
        $data['playlists']->withPath(url()->current().$l.$s.$q);

        return view('backend.gallery.playlists.index', compact('data'), [
            'title' => 'Gallery - Playlist',
            'breadcrumbs' => [
                'Gallery' => '',
                'Playlist' => '',
            ]
        ]);
    }

    public function create()
    {
        $data['languages'] = $this->serviceLang->getAllLang();
        $data['template'] = $this->serviceTemplate->getTemplate(7);

        return view('backend.gallery.playlists.form', compact('data'), [
            'title' => 'Playlist - Create',
            'breadcrumbs' => [
                'Gallery' => '',
                'Playlist' => route('gallery.playlist.index'),
                'Create' => '',
            ],
        ]);
    }

    public function store(PlaylistRequest $request)
    {
        $this->service->store($request);

        $redir = $this->redirectForm($request);
        return $redir->with('success', 'playlist successfully added');
    }

    public function edit($id)
    {
        $data['playlist'] = $this->service->find($id);
        $data['languages'] = $this->serviceLang->getAllLang();
        $data['template'] = $this->serviceTemplate->getTemplate(7);

        return view('backend.gallery.playlists.form-edit', compact('data'), [
            'title' => 'Playlist - Edit',
            'breadcrumbs' => [
                'Gallery' => '',
                'Playlist' => route('gallery.playlist.index'),
                'Edit' => ''
            ],
        ]);
    }

    public function update(PlaylistRequest $request, $id)
    {
        $this->service->update($request, $id);

        $redir = $this->redirectForm($request);
        return $redir->with('success', 'playlist successfully updated');
    }

    public function publish($id)
    {
        $this->service->publish($id);

        return back()->with('success', 'status playlist changed');
    }

    public function position($id, $position)
    {
        $this->service->position($id, $position);

        return back()->with('success', 'position playlist changed');
    }

    public function destroy($id)
    {
        $delete = $this->service->delete($id);

        if ($delete == true) {

            return response()->json([
                'success' => 1,
                'message' => ''
            ], 200);

        } else {

            return response()->json([
                'success' => 0,
                'message' => 'cannot delete playlist that still have video'
            ], 200);
        }
    }

    public function redirectForm($request)
    {
        $redir = redirect()->route('gallery.playlist.index');
        if ($request->action == 'back') {
            $redir = back();
        }

        return $redir;
    }
}
