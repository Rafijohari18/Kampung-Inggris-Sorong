<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use App\Http\Requests\Content\CategoryRequest;
use App\Services\Content\CategoryService;
use App\Services\Content\SectionService;
use App\Services\LanguageService;
use App\Services\Master\TemplateViewService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    private $service, $serviceLang, $serviceSection, $serviceTemplate;

    public function __construct(
        CategoryService $service,
        LanguageService $serviceLang,
        SectionService $serviceSection,
        TemplateViewService $serviceTemplate
    )
    {
        $this->service = $service;
        $this->serviceLang = $serviceLang;
        $this->serviceSection = $serviceSection;
        $this->serviceTemplate = $serviceTemplate;
    }

    public function index(Request $request, $sectionId)
    {
        $l = '';
        $p = '';
        $q = '';
        if (isset($request->l) || isset($request->p) || isset($request->q)) {
            $l = '?l='.$request->l;
            $p = '&p='.$request->p;
            $q = '&q='.$request->q;
        }

        $data['categories'] = $this->service->getCategoryList($request, $sectionId);
        $data['no'] = $data['categories']->firstItem();
        $data['categories']->withPath(url()->current().$l.$p.$q);
        $data['section'] = $this->serviceSection->find($sectionId);

        return view('backend.content.categories.index', compact('data'), [
            'title' => 'Content - Categories',
            'breadcrumbs' => [
                'Content' => '',
                'Section' => route('section.index'),
                'Categories' => '',
            ]
        ]);
    }

    public function create($sectionId)
    {
        $data['languages'] = $this->serviceLang->getAllLang();
        $data['section'] = $this->serviceSection->find($sectionId);
        $data['template_list'] = $this->serviceTemplate->getTemplate(2, 1);
        $data['template_detail'] = $this->serviceTemplate->getTemplate(2, 2);

        return view('backend.content.categories.form', compact('data'), [
            'title' => 'Category - Create',
            'breadcrumbs' => [
                'Content' => '',
                'Section' => route('section.index'),
                'Category' => route('category.index', ['sectionId' => $sectionId]),
                'Create' => '',
            ],
        ]);
    }

    public function store(CategoryRequest $request, $sectionId)
    {
        $this->service->store($request, $sectionId);

        $redir = $this->redirectForm($request, $sectionId);
        return $redir->with('success', 'category successfully added');
    }

    public function edit($sectionId, $id)
    {
        $data['category'] = $this->service->find($id);
        $data['languages'] = $this->serviceLang->getAllLang();
        $data['section'] = $this->serviceSection->find($sectionId);
        $data['template_list'] = $this->serviceTemplate->getTemplate(2, 1);
        $data['template_detail'] = $this->serviceTemplate->getTemplate(2, 2);

        return view('backend.content.categories.form-edit', compact('data'), [
            'title' => 'Category - Edit',
            'breadcrumbs' => [
                'Content' => '',
                'Section' => route('section.index'),
                'Category' => route('category.index', ['sectionId' => $sectionId]),
                'Edit' => ''
            ],
        ]);
    }

    public function update(CategoryRequest $request, $sectionId, $id)
    {
        $this->service->update($request, $id);

        $redir = $this->redirectForm($request, $sectionId);
        return $redir->with('success', 'section successfully updated');
    }

    public function destroy($sectionId, $id)
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
                'message' => 'Cannot delete category, Because this category still has post',
            ], 200);
        }
    }

    public function redirectForm($request, $sectionId)
    {
        $redir = redirect()->route('category.index', ['sectionId' => $sectionId]);
        if ($request->action == 'back') {
            $redir = back();
        }

        return $redir;
    }
}
