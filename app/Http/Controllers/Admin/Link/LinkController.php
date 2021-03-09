<?php

namespace App\Http\Controllers\Admin\Link;

use App\Http\Controllers\Controller;
use App\Http\Requests\Link\LinkRequest;
use App\Services\LanguageService;
use App\Services\Link\LinkService;
use App\Services\Master\TemplateViewService;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    private $service, $serviceLang, $serviceTemplate;

    public function __construct(
        LinkService $service,
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

        $data['links'] = $this->service->getLinkList($request);
        $data['no'] = $data['links']->firstItem();
        $data['links']->withPath(url()->current().$l.$s.$q);

        return view('backend.links.index', compact('data'), [
            'title' => 'Links',
            'breadcrumbs' => [
                'Links' => '',
            ]
        ]);
    }

    public function create()
    {
        $data['languages'] = $this->serviceLang->getAllLang();
        $data['template'] = $this->serviceTemplate->getTemplate(8);

        return view('backend.links.form', compact('data'), [
            'title' => 'Link - Create',
            'breadcrumbs' => [
                'Link' => route('link.index'),
                'Create' => '',
            ],
        ]);
    }

    public function store(LinkRequest $request)
    {
        $this->service->store($request);

        $redir = $this->redirectForm($request);
        return $redir->with('success', 'link successfully added');
    }

    public function edit($id)
    {
        $data['link'] = $this->service->find($id);
        $data['languages'] = $this->serviceLang->getAllLang();
        $data['template'] = $this->serviceTemplate->getTemplate(8);

        return view('backend.links.form-edit', compact('data'), [
            'title' => 'Link - Edit',
            'breadcrumbs' => [
                'Link' => route('link.index'),
                'Edit' => ''
            ],
        ]);
    }

    public function update(LinkRequest $request, $id)
    {
        $this->service->update($request, $id);

        $redir = $this->redirectForm($request);
        return $redir->with('success', 'link successfully updated');
    }

    public function publish($id)
    {
        $this->service->publish($id);

        return back()->with('success', 'status link changed');
    }

    public function position($id, $position)
    {
        $this->service->position($id, $position);

        return back()->with('success', 'position link changed');
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
                'message' => 'cannot delete link that still have media'
            ], 200);
        }
    }

    public function redirectForm($request)
    {
        $redir = redirect()->route('link.index');
        if ($request->action == 'back') {
            $redir = back();
        }

        return $redir;
    }
}
