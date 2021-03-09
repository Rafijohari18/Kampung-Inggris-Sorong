<?php

namespace App\Http\Controllers\Admin\Link;

use App\Http\Controllers\Controller;
use App\Http\Requests\Link\LinkMediaRequest;
use App\Services\LanguageService;
use App\Services\Link\LinkMediaService;
use App\Services\Link\LinkService;
use Illuminate\Http\Request;

class LinkMediaController extends Controller
{
    private $service, $serviceLink, $serviceLang;

    public function __construct(
        LinkMediaService $service,
        LinkService $serviceLink,
        LanguageService $serviceLang
    )
    {
        $this->service = $service;
        $this->serviceLink = $serviceLink;
        $this->serviceLang = $serviceLang;
    }

    public function index(Request $request, $linkId)
    {
        $l = '';
        $q = '';
        if (isset($request->l) || isset($request->q)) {
            $l = '?l='.$request->l;
            $q = '&q='.$request->q;
        }

        $data['media'] = $this->service->getlinkMediaList($request, $linkId);
        $data['no'] = $data['media']->firstItem();
        $data['media']->withPath(url()->current().$l.$q);
        $data['link'] = $this->serviceLink->find($linkId);

        return view('backend.links.media.index', compact('data'), [
            'title' => 'Links - Media',
            'breadcrumbs' => [
                'Links' => route('link.index'),
                'Media' => ''
            ]
        ]);
    }

    public function create($linkId)
    {
        $data['languages'] = $this->serviceLang->getAllLang();
        $data['link'] = $this->serviceLink->find($linkId);

        return view('backend.links.media.form', compact('data'), [
            'title' => 'Media - Create',
            'breadcrumbs' => [
                'Link' => route('link.index'),
                'Media' => route('link.media', ['linkId' => $linkId]),
                'Create' => '',
            ],
        ]);
    }

    public function store(LinkMediaRequest $request, $linkId)
    {
        $this->service->store($request, $linkId);

        $redir = $this->redirectForm($request, $linkId);
        return $redir->with('success', 'link media successfully added');
    }

    public function edit($linkId, $id)
    {
        $data['languages'] = $this->serviceLang->getAllLang();
        $data['link'] = $this->serviceLink->find($linkId);
        $data['media'] = $this->service->find($id);

        return view('backend.links.media.form-edit', compact('data'), [
            'title' => 'Media - Edit',
            'breadcrumbs' => [
                'Link' => route('link.index'),
                'Media' => route('link.media', ['linkId' => $linkId]),
                'Edit' => '',
            ],
        ]);
    }

    public function update(LinkMediaRequest $request, $linkId, $id)
    {
        $this->service->update($request, $id);

        $redir = $this->redirectForm($request, $linkId);
        return $redir->with('success', 'link media successfully updated');
    }

    public function position($linkId, $id, $position)
    {
        $this->service->position($id, $position);

        return back()->with('success', 'position link media changed');
    }

    public function destroy($linkId, $id)
    {
        $this->service->delete($id);

        return response()->json([
            'success' => 1,
            'message' => ''
        ], 200);
    }

    public function redirectForm($request, $linkId)
    {
        $redir = redirect()->route('link.media', ['linkId' => $linkId]);
        if ($request->action == 'back') {
            $redir = back();
        }

        return $redir;
    }
}
