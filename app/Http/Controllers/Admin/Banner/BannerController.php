<?php

namespace App\Http\Controllers\Admin\Banner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Banner\BannerRequest;
use App\Services\Banner\BannerCategoryService;
use App\Services\Banner\BannerService;
use App\Services\LanguageService;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    private $service, $serviceBannerCategory, $serviceLang;

    public function __construct(
        BannerService $service,
        BannerCategoryService $serviceBannerCategory,
        LanguageService $serviceLang
    )
    {
        $this->service = $service;
        $this->serviceBannerCategory = $serviceBannerCategory;
        $this->serviceLang = $serviceLang;
    }

    public function index(Request $request, $categoryId)
    {
        $l = '';
        $q = '';
        if (isset($request->l) || isset($request->q)) {
            $l = '?l='.$request->l;
            $q = '&q='.$request->q;
        }

        $data['banners'] = $this->service->getBannerList($request, $categoryId);
        $data['no'] = $data['banners']->firstItem();
        $data['banners']->withPath(url()->current().$l.$q);
        $data['banner_category'] = $this->serviceBannerCategory->find($categoryId);

        return view('backend.banners.index', compact('data'), [
            'title' => 'Category - Banner',
            'breadcrumbs' => [
                'Category' => route('banner.category.index'),
                'Banner' => '',
            ]
        ]);
    }

    public function create($categoryId)
    {
        $data['banner_category'] = $this->serviceBannerCategory->find($categoryId);
        $data['languages'] = $this->serviceLang->getAllLang();

        return view('backend.banners.form', compact('data'), [
            'title' => 'Banner - Create',
            'breadcrumbs' => [
                'Category' => route('banner.category.index'),
                'Banner' => route('banner.index', ['categoryId' => $categoryId]),
                'Create' => '',
            ],
        ]);
    }

    public function store(BannerRequest $request, $categoryId)
    {
        $this->service->store($request, $categoryId);

        $redir = $this->redirectForm($request, $categoryId);
        return $redir->with('success', 'banner successfully added');
    }

    public function edit($categoryId, $id)
    {
        $data['languages'] = $this->serviceLang->getAllLang();
        $data['banner_category'] = $this->serviceBannerCategory->find($categoryId);
        $data['banner'] = $this->service->find($id);

        return view('backend.banners.form-edit', compact('data'), [
            'title' => 'Banner - Edit',
            'breadcrumbs' => [
                'Category' => route('banner.category.index'),
                'Banner' => route('banner.index', ['categoryId' => $categoryId]),
                'Edit' => ''
            ],
        ]);
    }

    public function update(Request $request, $categoryId, $id)
    {
        $this->service->update($request, $id);

        $redir = $this->redirectForm($request, $categoryId);
        return $redir->with('success', 'banner successfully updated');
    }

    public function publish($categoryId, $id)
    {
        $this->service->publish($id);

        return back()->with('success', 'status banner changed');
    }

    public function position($categoryId, $id, $position)
    {
        $this->service->position($id, $position);

        return back()->with('success', 'position banner changed');
    }

    public function sort($categoryId)
    {
        $i = 0;

        foreach ($_POST['datas'] as $value) {
            $i++;
            $this->service->sort($value, $i);
        }
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
        $redir = redirect()->route('banner.index', ['categoryId' => $categoryId]);
        if ($request->action == 'back') {
            $redir = back();
        }

        return $redir;
    }
}
