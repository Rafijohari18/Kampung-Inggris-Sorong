<?php

namespace App\Http\Controllers\Admin\Catalog;

use App\Http\Controllers\Controller;
use App\Http\Requests\Catalog\CatalogCategoryRequest;
use App\Services\Catalog\CatalogCategoryService;
use App\Services\LanguageService;
use App\Services\Master\TemplateViewService;
use Illuminate\Http\Request;

class CatalogCategoryController extends Controller
{
    private $service, $serviceLang, $serviceTemplate;

    public function __construct(
        CatalogCategoryService $service,
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
        $q = '';
        if (isset($request->l) || isset($request->q)) {
            $l = '?l='.$request->l;
            $q = '&q='.$request->q;
        }

        $data['catalog_categories'] = $this->service->getCatalogCategoryList($request);
        $data['no'] = $data['catalog_categories']->firstItem();
        $data['catalog_categories']->withPath(url()->current().$l.$q);

        return view('backend.catalogue.categories.index', compact('data'), [
            'title' => 'Catalogue - Categories',
            'breadcrumbs' => [
                'Catalogue' => '',
                'Categories' => '',
            ]
        ]);
    }

    public function create(Request $request)
    {
        $data['languages'] = $this->serviceLang->getAllLang();
        $data['template'] = $this->serviceTemplate->getTemplate(4);

        return view('backend.catalogue.Categories.form', compact('data'), [
            'title' => 'Category - Create',
            'breadcrumbs' => [
                'Catalogue' => '',
                'Category' => route('catalog.category.index'),
                'Create' => '',
            ],
        ]);
    }

    public function store(CatalogCategoryRequest $request)
    {
        $this->service->store($request);

        $redir = $this->redirectForm($request);
        return $redir->with('success', 'catalog category successfully added');
    }

    public function edit($id)
    {
        $data['catalog_category'] = $this->service->find($id);
        $data['languages'] = $this->serviceLang->getAllLang();
        $data['template'] = $this->serviceTemplate->getTemplate(4);

        return view('backend.catalogue.categories.form-edit', compact('data'), [
            'title' => 'Category - Edit',
            'breadcrumbs' => [
                'Catalogue' => '',
                'Category' => route('catalog.category.index'),
                'Edit' => ''
            ],
        ]);
    }

    public function update(CatalogCategoryRequest $request, $id)
    {
        $this->service->update($request, $id);

        $redir = $this->redirectForm($request);
        return $redir->with('success', 'catalog category successfully updated');
    }

    public function position($id, $position)
    {
        $this->service->position($id, $position);

        return back()->with('success', 'position catalog category changed');
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
                'message' => 'Cannot delete catalog category, Because this category still product',
            ], 200);
        }
    }

    public function redirectForm($request)
    {
        $redir = redirect()->route('catalog.category.index');
        if ($request->action == 'back') {
            $redir = back();
        }

        return $redir;
    }
}
