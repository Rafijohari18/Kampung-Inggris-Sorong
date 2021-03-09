<?php

namespace App\Http\Controllers\Admin\Banner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Banner\BannerCategoryRequest;
use App\Services\Banner\BannerCategoryService;
use App\Services\LanguageService;
use Illuminate\Http\Request;

class BannerCategoryController extends Controller
{
    private $service, $serviceLang;

    public function __construct(
        BannerCategoryService $service,
        LanguageService $serviceLang
    )
    {
        $this->service = $service;
        $this->serviceLang = $serviceLang;
    }

    public function index(Request $request)
    {
        $l = '';
        $q = '';
        if (isset($request->l) || isset($request->q)) {
            $l = '?l='.$request->l;
            $q = '&q='.$request->q;
        }

        $data['banner_categories'] = $this->service->getBannerCategoryList($request);
        $data['no'] = $data['banner_categories']->firstItem();
        $data['banner_categories']->withPath(url()->current().$l.$q);

        return view('backend.banners.categories.index', compact('data'), [
            'title' => 'Banner - Categories',
            'breadcrumbs' => [
                'Banner' => '',
                'Category' => '',
            ]
        ]);
    }

    public function create()
    {
        $data['languages'] = $this->serviceLang->getAllLang();

        return view('backend.banners.categories.form', compact('data'), [
            'title' => 'Banner - Category - Create',
            'breadcrumbs' => [
                'Banner' => '',
                'Category' => route('banner.category.index'),
                'Create' => '',
            ],
        ]);
    }

    public function store(BannerCategoryRequest $request)
    {
        $this->service->store($request);

        $redir = $this->redirectForm($request);
        return $redir->with('success', 'banner category successfully added');
    }

    public function edit($id)
    {
        $data['banner_category'] = $this->service->find($id);
        $data['languages'] = $this->serviceLang->getAllLang();

        return view('backend.banners.categories.form-edit', compact('data'), [
            'title' => 'Banner - Category - Edit',
            'breadcrumbs' => [
                'Banner' => '',
                'Category' => route('banner.category.index'),
                'Edit' => ''
            ],
        ]);
    }

    public function update(BannerCategoryRequest $request, $id)
    {
        $this->service->update($request, $id);

        $redir = $this->redirectForm($request);
        return $redir->with('success', 'banner category successfully updated');
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
                'message' => 'Cannot delete banner category, Because this banner category still has banner',
            ], 200);
        }
    }

    public function redirectForm($request)
    {
        $redir = redirect()->route('banner.category.index');
        if ($request->action == 'back') {
            $redir = back();
        }

        return $redir;
    }
}
