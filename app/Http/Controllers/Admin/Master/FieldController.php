<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\FieldRequest;
use App\Services\Banner\BannerCategoryService;
use App\Services\Catalog\CatalogCategoryService;
use App\Services\Catalog\CatalogProductService;
use App\Services\Content\CategoryService;
use App\Services\Content\PostService;
use App\Services\Content\SectionService;
use App\Services\InquiryService;
use App\Services\Link\LinkMediaService;
use App\Services\Link\LinkService;
use App\Services\Master\FieldService;
use App\Services\PageService;
use Illuminate\Http\Request;

class FieldController extends Controller
{
    private $service, $servicePage, $serviceSection, $serviceCategory, $servicePost,
     $serviceBannerCategory, $serviceCatalogCategory, $serviceCatalogProduct,
     $serviceLink, $serviceLinkMedia, $serviceInquiry;

    public function __construct(
        FieldService $service,
        PageService $servicePage,
        SectionService $serviceSection,
        CategoryService $serviceCategory,
        PostService $servicePost,
        BannerCategoryService $serviceBannerCategory,
        CatalogCategoryService $serviceCatalogCategory,
        CatalogProductService $serviceCatalogProduct,
        LinkService $serviceLink,
        LinkMediaService $serviceLinkMedia,
        InquiryService $serviceInquiry
    )
    {
        $this->service = $service;
        $this->servicePage = $servicePage;
        $this->serviceSection = $serviceSection;
        $this->serviceCategory = $serviceCategory;
        $this->servicePost = $servicePost;
        $this->serviceBannerCategory = $serviceBannerCategory;
        $this->serviceCatalogCategory = $serviceCatalogCategory;
        $this->serviceCatalogProduct = $serviceCatalogProduct;
        $this->serviceLink = $serviceLink;
        $this->serviceLinkMedia = $serviceLinkMedia;
        $this->serviceInquiry = $serviceInquiry;
    }

    public function form(Request $request, $id, $module)
    {
        $data['module'] = $this->checkModule($id, $module);

        if ($module == 'category' || $module == 'post') {
            $data['routeStore'] = route('field.store', [
                'id' => $request->segment(4),
                'module' => $request->segment(5),
                'sectionId' => $request->get('section'),
            ]);
            $data['routeBack'] = route(str_replace('_', '.', $request->segment(5)).'.index', ['sectionId' => $request->get('section')]);
        } elseif ($module == 'catalog_product') {
            $data['routeStore'] = route('field.store', [
                'id' => $request->segment(4),
                'module' => $request->segment(5),
                'catalog_category_id' => $request->get('catalog_category_id'),
            ]);
            $data['routeBack'] = route(str_replace('_', '.', $request->segment(5)).'.index', ['categoryId' => $request->get('catalog_category_id')]);
        } elseif ($module == 'link_media') {
            $data['routeStore'] = route('field.store', [
                'id' => $request->segment(4),
                'module' => $request->segment(5),
                'linkId' => $request->get('linkId'),
            ]);
            $data['routeBack'] = route(str_replace('_', '.', $request->segment(5)), ['linkId' => $request->get('linkId')]);
        } else {
            $data['routeStore'] = route('field.store', [
                'id' => $request->segment(4),
                'module' => $request->segment(5),
            ]);
            $data['routeBack'] = route(str_replace('_', '.', $request->segment(5)).'.index');
        }

        return view('backend.master.field.form', compact('data'), [
            'title' => 'Field Setting',
            'breadcrumbs' => [
                'Data Master' => '',
                'Field' => '',
            ]
        ]);
    }

    public function store(FieldRequest $request, $id, $module)
    {
        $model = $this->checkModule($id, $module);

        $this->service->store($request, $model);

        // $redir = $this->redirectForm($request, $module);
        return back()->with('success', 'setting field successfully');
    }

    public function update(FieldRequest $request, $id)
    {
        $this->service->update($request, $id);

        return back()->with('success', 'field successfully updated');
    }

    public function destroy($id)
    {
        $this->service->delete($id);

        return response()->json([
            'success' => 1,
            'message' => ''
        ], 200);
    }

    public function checkModule($id, $module)
    {
        if ($module == 'page') {
            $model = $this->servicePage->find($id);
        }

        if ($module == 'section') {
            $model = $this->serviceSection->find($id);
        }

        if ($module == 'category') {
            $model = $this->serviceCategory->find($id);
        }

        if ($module == 'post') {
            $model = $this->servicePost->find($id);
        }

        if ($module == 'banner_category') {
            $model = $this->serviceBannerCategory->find($id);
        }

        if ($module == 'catalog_category') {
            $model = $this->serviceCatalogCategory->find($id);
        }

        if ($module == 'catalog_product') {
            $model = $this->serviceCatalogProduct->find($id);
        }

        if ($module == 'link') {
            $model = $this->serviceLink->find($id);
        }

        if ($module == 'link_media') {
            $model = $this->serviceLinkMedia->find($id);
        }

        if ($module == 'inquiry') {
            $model = $this->serviceInquiry->find($id);
        }

        return $model;
    }
}
