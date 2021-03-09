<?php

namespace App\Http\Controllers\Admin\Gallery;

use App\Http\Controllers\Controller;
use App\Http\Requests\Gallery\AlbumRequest;
use App\Services\Gallery\AlbumService;
use App\Services\LanguageService;
use App\Services\Master\TemplateViewService;
use Illuminate\Http\Request;

class AlbumController extends Controller
{
    private $service, $serviceLang, $serviceTemplate;

    public function __construct(
        AlbumService $service,
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

        $data['albums'] = $this->service->getAlbumList($request);
        $data['no'] = $data['albums']->firstItem();
        $data['albums']->withPath(url()->current().$l.$s.$q);

        return view('backend.gallery.albums.index', compact('data'), [
            'title' => 'Gallery - Album',
            'breadcrumbs' => [
                'Gallery' => '',
                'Album' => '',
            ]
        ]);
    }

    public function create()
    {
        $data['languages'] = $this->serviceLang->getAllLang();
        $data['template'] = $this->serviceTemplate->getTemplate(6);

        return view('backend.gallery.albums.form', compact('data'), [
            'title' => 'Album - Create',
            'breadcrumbs' => [
                'Gallery' => '',
                'Album' => route('gallery.album.index'),
                'Create' => '',
            ],
        ]);
    }

    public function store(AlbumRequest $request)
    {
        $this->service->store($request);

        $redir = $this->redirectForm($request);
        return $redir->with('success', 'album successfully added');
    }

    public function edit($id)
    {
        $data['album'] = $this->service->find($id);
        $data['languages'] = $this->serviceLang->getAllLang();
        $data['template'] = $this->serviceTemplate->getTemplate(6);

        return view('backend.gallery.albums.form-edit', compact('data'), [
            'title' => 'Album - Edit',
            'breadcrumbs' => [
                'Gallery' => '',
                'Album' => route('gallery.album.index'),
                'Edit' => ''
            ],
        ]);
    }

    public function update(AlbumRequest $request, $id)
    {
        $this->service->update($request, $id);

        $redir = $this->redirectForm($request);
        return $redir->with('success', 'album successfully updated');
    }

    public function publish($id)
    {
        $this->service->publish($id);

        return back()->with('success', 'status album changed');
    }

    public function position($id, $position)
    {
        $this->service->position($id, $position);

        return back()->with('success', 'position album changed');
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
                'message' => 'cannot delete album that still have photo'
            ], 200);
        }
    }

    public function redirectForm($request)
    {
        $redir = redirect()->route('gallery.album.index');
        if ($request->action == 'back') {
            $redir = back();
        }

        return $redir;
    }
}
