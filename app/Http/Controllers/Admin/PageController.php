<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PageRequest;
use App\Services\LanguageService;
use App\Services\Master\TemplateViewService;
use App\Services\PageService;
use Illuminate\Http\Request;

class PageController extends Controller
{
    private $service, $serviceLang, $serviceTemplate;

    public function __construct(
        PageService $service,
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
        $p = '';
        $q = '';
        if (isset($request->l) || isset($request->s) || isset($request->p) || isset($request->q)) {
            $l = '?l='.$request->l;
            $s = '&s='.$request->s;
            $p = '&p='.$request->p;
            $q = '&q='.$request->q;
        }

        $data['pages'] = $this->service->getPageList($request);
        $data['no'] = $data['pages']->firstItem();
        $data['pages']->withPath(url()->current().$l.$s.$p.$q);
        $data['total_page'] = $this->service->countPage();

        return view('backend.pages.index', compact('data'), [
            'title' => 'Pages',
            'breadcrumbs' => [
                'Pages' => '',
            ]
        ]);
    }

    public function create(Request $request)
    {
        $data['languages'] = $this->serviceLang->getAllLang();
        $data['template'] = $this->serviceTemplate->getTemplate(0);
        if (isset($request->parent)) {
            $data['parent'] = $this->service->find($request->parent);
        }

        return view('backend.pages.form', compact('data'), [
            'title' => 'Page - Create',
            'breadcrumbs' => [
                'Page' => route('page.index'),
                'Create' => '',
            ],
        ]);
    }

    public function store(PageRequest $request)
    {
        $this->service->store($request);

        $redir = $this->redirectForm($request);
        return $redir->with('success', 'page successfully added');
    }

    public function edit($id)
    {
        $data['page'] = $this->service->find($id);
        $data['languages'] = $this->serviceLang->getAllLang();
        $data['template'] = $this->serviceTemplate->getTemplate(0);

        return view('backend.pages.form-edit', compact('data'), [
            'title' => 'Page - Edit',
            'breadcrumbs' => [
                'Page' => route('page.index'),
                'Edit' => ''
            ],
        ]);
    }

    public function update(PageRequest $request, $id)
    {
        $this->service->update($request, $id);

        $redir = $this->redirectForm($request);
        return $redir->with('success', 'page successfully updated');
    }

    public function publish($id)
    {
        $this->service->publish($id);

        return back()->with('success', 'status page changed');
    }

    public function position(Request $request, $id, $position)
    {
        $this->service->position($id, $position, $request->get('parent'));

        return back()->with('success', 'position page changed');
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
                'message' => 'cannot delete pages that still have child'
            ], 200);
        }
    }

    public function redirectForm($request)
    {
        $redir = redirect()->route('page.index');
        if ($request->action == 'back') {
            $redir = back();
        }

        return $redir;
    }
}
