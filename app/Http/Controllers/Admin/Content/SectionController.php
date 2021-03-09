<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use App\Http\Requests\Content\SectionRequest;
use App\Services\Content\SectionService;
use App\Services\LanguageService;
use App\Services\Master\TemplateViewService;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    private $service, $serviceLang, $serviceTemplate;

    public function __construct(
        SectionService $service,
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
        $e = '';
        $p = '';
        $q = '';
        if (isset($request->l) || isset($request->e) || isset($request->p) || isset($request->q)) {
            $l = '?l='.$request->l;
            $e = '&e='.$request->e;
            $p = '&p='.$request->p;
            $q = '&q='.$request->q;
        }

        $data['section'] = $this->service->getSectionList($request);
        $data['no'] = $data['section']->firstItem();
        $data['section']->withPath(url()->current().$l.$e.$p.$q);

        return view('backend.content.sections.index', compact('data'), [
            'title' => 'Content - Sections',
            'breadcrumbs' => [
                'Content' => '',
                'Sections' => '',
            ]
        ]);
    }

    public function create(Request $request)
    {
        $data['languages'] = $this->serviceLang->getAllLang();
        $data['template_list'] = $this->serviceTemplate->getTemplate(1, 1);
        $data['template_detail'] = $this->serviceTemplate->getTemplate(1, 2);

        return view('backend.content.sections.form', compact('data'), [
            'title' => 'Section - Create',
            'breadcrumbs' => [
                'Content' => '',
                'Section' => route('section.index'),
                'Create' => '',
            ],
        ]);
    }

    public function store(SectionRequest $request)
    {
        $this->service->store($request);

        $redir = $this->redirectForm($request);
        return $redir->with('success', 'section successfully added');
    }

    public function edit($id)
    {
        $data['section'] = $this->service->find($id);
        $data['languages'] = $this->serviceLang->getAllLang();
        $data['template_list'] = $this->serviceTemplate->getTemplate(1, 1);
        $data['template_detail'] = $this->serviceTemplate->getTemplate(1, 2);

        return view('backend.content.sections.form-edit', compact('data'), [
            'title' => 'Section - Edit',
            'breadcrumbs' => [
                'Content' => '',
                'Section' => route('section.index'),
                'Edit' => ''
            ],
        ]);
    }

    public function update(SectionRequest $request, $id)
    {
        $this->service->update($request, $id);

        $redir = $this->redirectForm($request);
        return $redir->with('success', 'section successfully updated');
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
                'message' => 'Cannot delete section, Because this section still has category / post',
            ], 200);
        }
    }

    public function redirectForm($request)
    {
        $redir = redirect()->route('section.index');
        if ($request->action == 'back') {
            $redir = back();
        }

        return $redir;
    }
}
