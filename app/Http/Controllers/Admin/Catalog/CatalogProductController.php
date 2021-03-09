<?php

namespace App\Http\Controllers\Admin\Catalog;

use App\Http\Controllers\Controller;
use App\Http\Requests\Catalog\CatalogProductRequest;
use App\Services\Catalog\CatalogCategoryService;
use App\Services\Catalog\CatalogProductService;
use App\Services\LanguageService;
use App\Services\Master\TagService;
use App\Services\Master\TemplateViewService;
use Illuminate\Http\Request;

class CatalogProductController extends Controller
{
    private $service, $serviceCategory, $serviceLang, $serviceTags, $serviceTemplate;

    public function __construct(
        CatalogProductService $service,
        CatalogCategoryService $serviceCategory,
        LanguageService $serviceLang,
        TagService $serviceTags,
        TemplateViewService $serviceTemplate
    )
    {
        $this->service = $service;
        $this->serviceCategory = $serviceCategory;
        $this->serviceLang = $serviceLang;
        $this->serviceTags = $serviceTags;
        $this->serviceTemplate = $serviceTemplate;
    }

    public function index(Request $request, $categoryId)
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

        $data['catalog_products'] = $this->service->getCatalogProductList($request, $categoryId);
        $data['no'] = $data['catalog_products']->firstItem();
        $data['catalog_products']->withPath(url()->current().$l.$p.$q);
        $data['catalog_category'] = $this->serviceCategory->find($categoryId);

        return view('backend.catalogue.products.index', compact('data'), [
            'title' => 'Catalogue - Product',
            'breadcrumbs' => [
                'Catalogue' => '',
                'Category' => route('catalog.category.index'),
                'Product' => '',
            ]
        ]);
    }

    public function create($categoryId)
    {
        $data['languages'] = $this->serviceLang->getAllLang();
        $data['catalog_category'] = $this->serviceCategory->find($categoryId);
        $data['tags'] = $this->serviceTags->getTags();
        $data['template'] = $this->serviceTemplate->getTemplate(5);

        return view('backend.catalogue.products.form', compact('data'), [
            'title' => 'Product - Create',
            'breadcrumbs' => [
                'Catalogue' => '',
                'Category' => route('catalog.category.index'),
                'Product' => route('catalog.product.index', ['categoryId' => $categoryId]),
                'Create' => '',
            ],
        ]);
    }

    public function store(CatalogProductRequest $request, $categoryId)
    {
        $this->service->store($request, $categoryId);

        $redir = $this->redirectForm($request, $categoryId);
        return $redir->with('success', 'catalog product successfully added');
    }

    public function edit($categoryId, $id)
    {
        $data['languages'] = $this->serviceLang->getAllLang();
        $data['catalog_category'] = $this->serviceCategory->find($categoryId);
        $data['tags'] = $this->serviceTags->getTags();
        $data['catalog_product'] = $this->service->find($id);
        $data['template'] = $this->serviceTemplate->getTemplate(5);

        $collectTags = collect($data['catalog_product']->tags);
        $data['tags_id'] = $collectTags->map(function($item, $key) {
            return $item->tag_id;
        })->all();

        return view('backend.catalogue.products.form-edit', compact('data'), [
            'title' => 'Product - Edit',
            'breadcrumbs' => [
                'Catalogue' => '',
                'Category' => route('catalog.category.index'),
                'Product' => route('catalog.product.index', ['categoryId' => $categoryId]),
                'Edit' => ''
            ],
        ]);
    }

    public function update(CatalogProductRequest $request, $categoryId, $id)
    {
        $this->service->update($request, $id);

        $redir = $this->redirectForm($request, $categoryId);
        return $redir->with('success', 'catalog product successfully updated');
    }

    public function publish($categoryId, $id)
    {
        $this->service->publish($id);

        return back()->with('success', 'status product changed');
    }

    public function selection($categoryId, $id)
    {
        $product = $this->service->find($id);
        $check = $this->service->selection($id);

        if ($check == true) {
            return back()->with('success', 'Product selected');
        } else {
            return back()->with('warning', 'Cannot select product because product select limited '.$product->category->product_selection);
        }
    }

    public function position($categoryId, $id, $position)
    {
        $this->service->position($id, $position);

        return back()->with('success', 'position product changed');
    }

    public function destroy($categoryId, $id)
    {
        $this->service->delete($id);

        return response()->json([
            'success' => 1,
            'message' => ''
        ], 200);
    }

    public function redirectForm($request, $categoryId)
    {
        $redir = redirect()->route('catalog.product.index', ['categoryId' => $categoryId]);
        if ($request->action == 'back') {
            $redir = back();
        }

        return $redir;
    }
}
