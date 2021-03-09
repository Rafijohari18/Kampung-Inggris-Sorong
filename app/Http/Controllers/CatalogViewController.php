<?php

namespace App\Http\Controllers;

use App\Services\Catalog\CatalogCategoryService;
use App\Services\Catalog\CatalogProductService;
use App\Services\ConfigurationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CatalogViewController extends Controller
{
    private $serviceCategory, $serviceProduct, $config;

    public function __construct(
        CatalogCategoryService $serviceCategory,
        CatalogProductService $serviceProduct,
        ConfigurationService $config
    )
    {
        $this->serviceCategory = $serviceCategory;
        $this->serviceProduct = $serviceProduct;
        $this->config = $config;
    }

    public function viewCatalog(Request $request)
    {
        return redirect()->route('home');

        //data
        $data['banner'] = Storage::url('banner/'.$this->config->getValue('banner_default'));
        $limit = $this->config->getValue('content_limit');
        $data['categories'] = $this->serviceCategory->getCatalogCategory($request, 'paginate', $limit);
        $data['products'] = $this->serviceProduct->getCatalogProduct($request, 'paginate', $limit);

        return view('frontend.catalogue.index', compact('data'), [
            'title' => 'Catalogue',
            'breadcrumbs' => [
                'Catalogue' => '',
            ]
        ]);
    }

    /**
     * category
     */
    public function viewCatalogCategoryList(Request $request)
    {
        return redirect()->route('home');

        //data
        $data['banner'] = Storage::url('banner/'.$this->config->getValue('banner_default'));
        $limit = $this->config->getValue('content_limit');
        $data['categories'] = $this->serviceCategory->getCatalogCategory($request, 'paginate', $limit);

        return view('frontend.catalogue.categories.list', compact('data'), [
            'title' => 'Catalogue - Categories',
            'breadcrumbs' => [
                'Catalogue Categories' => '',
            ]
        ]);
    }

    public function viewCatalogCategoryWithLang(Request $request, $lang = null, $id = null, $slug = null)
    {
        if (empty($lang)) {
            return abort(404);
        }

        return $this->catalogCategory($request, $id, $slug, $lang);
    }

    public function viewCatalogCategoryWithoutLang(Request $request, $id = null, $slug = null)
    {
        return $this->catalogCategory($request, $id, $slug);
    }

    public function catalogCategory($request, $id, $slug, $lang = null)
    {
        $data['read'] = $this->serviceCategory->find($id);

        $this->serviceCategory->recordViewer($id);

        //check
        if (empty($id)) {
            return abort(404);
        }

        if (empty($slug)) {
            return abort(404);
        }

        if (empty($data['read']) || $data['read']->is_widget == 1) {
            return redirect()->route('home');
        }

        if ($slug != $data['read']->slug) {
            if ($lang == null) {
                return redirect()->route('catalog.category.view', ['id' => $data['read']->id, 'slug' => $data['read']->slug]);
            } else {
                return redirect()->route('catalog.category.view', ['lang' => $lang, 'id' => $data['read']->id, 'slug' => $data['read']->slug]);
            }
        }

        //data
        $limit = $this->config->getValue('content_limit');
        if (!empty($data['read']->list_limit)) {
            $limit = $data['read']->list_limit;
        }
        $data['field'] = $data['read']->field;
        $data['products'] = $this->serviceProduct->getCatalogProduct($request, 'paginate', $limit, $id);

        // meta data
        $data['meta_title'] = $data['read']->fieldLang('name');
        if (!empty($data['read']->meta_data['title'])) {
            $data['meta_title'] = Str::limit(strip_tags($data['read']->meta_data['title']), 69);
        }

        $data['meta_description'] = $this->config->getValue('meta_description');
        if (!empty($data['read']->meta_data['description'])) {
            $data['meta_description'] = $data['read']->meta_data['description'];
        } elseif (empty($data['read']->meta_data['description']) && !empty($data['read']->fieldLang('description'))) {
            $data['meta_description'] = Str::limit(strip_tags($data['read']->fieldLang('description')), 155);
        }

        $data['meta_keywords'] = $this->config->getValue('meta_keywords');
        if (!empty($data['read']->meta_data['keywords'])) {
            $data['meta_keywords'] = $data['read']->meta_data['keywords'];
        }

        //images
        $data['creator'] = $data['read']->createBy->name;
        $data['cover'] = $data['read']->coverSrc($data['read']);
        $data['banner'] = $data['read']->bannerSrc($data['read']);

        $blade = 'index';
        if (!empty($data['read']->custom_view_id)) {
            $blade = config('custom.resource.path.catalog_categories.custom').'.'.collect(explode("/", $data['read']->customView->file_path))->last();
        }

        //breadcrumbs
        $breadcrumbsLong = [
            'Catalog Category' => config('custom.language.multiple') == true ? route('catalog.category.list', ['locale' => app()->getLocale()]) : route('catalog.category.list'),
            $data['read']->fieldLang('name') => '',
        ];
        $breadcrumbsShort = [
            $data['read']->fieldLang('name') => '',
        ];

        return view('frontend.catalogue.categories.'.$blade, compact('data'), [
            'title' => 'Catalog Category - '.$data['read']->fieldLang('name'),
            'breadcrumbs' => $breadcrumbsShort
        ]);
    }

    /**
     * post
     */
    public function viewCatalogProductList(Request $request)
    {
        return redirect()->route('home');

        $data['banner'] = Storage::url('banner/'.$this->config->getValue('banner_default'));
        $limit = $this->config->getValue('content_limit');
        $data['products'] = $this->serviceProduct->getCatalogProduct($request, 'paginate', $limit);

        return view('frontend.catalogue.products.list', compact('data'), [
            'title' => 'Catalogue - Products',
            'breadcrumbs' => [
                'Catalogue Products' => '',
            ]
        ]);
    }

    public function viewCatalogProductWithLang(Request $request, $lang = null, $id = null, $slug = null)
    {
        if (empty($lang)) {
            return abort(404);
        }

        return $this->catalogProduct($request, $id, $slug, $lang);
    }

    public function viewCatalogProductWithoutLang(Request $request, $id = null, $slug = null)
    {
        return $this->catalogProduct($request, $id, $slug);
    }

    public function catalogProduct($request, $id, $slug, $lang = null)
    {
        $data['read'] = $this->serviceProduct->find($id);

        $this->serviceProduct->recordViewer($id);

        //check
        if (empty($id)) {
            return abort(404);
        }

        if (empty($slug)) {
            return abort(404);
        }

        if ($data['read']->publish == 0 || empty($data['read']) || $data['read']->is_widget == 1) {
            return redirect()->route('home');
        }

        if ($slug != $data['read']->slug) {
            if ($lang == null) {
                return redirect()->route('catalog.product.view', ['id' => $data['read']->id, 'slug' => $data['read']->slug]);
            } else {
                return redirect()->route('catalog.product.view', ['lang' => $lang, 'id' => $data['read']->id, 'slug' => $data['read']->slug]);
            }
        }

        if ($data['read']->public == 0 && auth()->guard()->check() == false) {
            return redirect()->route('home')->with('warning', 'You must login first');
        }

        //data
        $data['field'] = $data['read']->field;
        $data['images'] = $data['read']->images()->orderBy('position', 'ASC')->get();
        $data['latest_post'] = $this->serviceProduct->latestCatalogProduct($id, 3);
        $data['prev_post'] = $this->serviceProduct->catalogProductPrevNext($id, 1, 'prev');
        $data['next_post'] = $this->serviceProduct->catalogProductPrevNext($id, 1, 'next');

        // meta data
        $data['meta_title'] = $data['read']->fieldLang('title');
        if (!empty($data['read']->meta_data['title'])) {
            $data['meta_title'] = Str::limit(strip_tags($data['read']->meta_data['title']), 69);
        }

        $data['meta_description'] = $this->config->getValue('meta_description');
        if (!empty($data['read']->meta_data['description'])) {
            $data['meta_description'] = $data['read']->meta_data['description'];
        } elseif (empty($data['read']->meta_data['description']) && !empty($data['read']->fieldLang('intro'))) {
            $data['meta_description'] = Str::limit(strip_tags($data['read']->fieldLang('intro')), 155);
        } elseif (empty($data['read']->meta_data['description']) && empty($data['read']->fieldLang('intro')) && !empty($data['read']->fieldLang('content'))) {
            $data['meta_description'] = Str::limit(strip_tags($data['read']->fieldLang('content')), 155);
        }

        $data['meta_keywords'] = $this->config->getValue('meta_keywords');
        if (!empty($data['read']->meta_data['keywords'])) {
            $data['meta_keywords'] = $data['read']->meta_data['keywords'];
        }

        //images
        $data['creator'] = $data['read']->createBy->name;
        $data['cover'] = $data['read']->coverSrc($data['read']);
        $data['banner'] = $data['read']->bannerSrc($data['read']);

        //share
        $data['share_facebook'] = "https://www.facebook.com/share.php?u=".url()->full()."&title=".$data['read']->fieldLang('title')."";
        $data['share_twitter'] = "https://twitter.com/intent/tweet?text=".$data['read']->fieldLang('title')."&amp;url=".url()->full()."";
        $data['share_whatsapp'] = "whatsapp://send?text=".$data['read']->fieldLang('title')." ".url()->full()."";
        $data['share_gplus'] = "https://plus.google.com/share?url=".url()->full()."";
        $data['share_linkedin'] = "https://www.linkedin.com/shareArticle?mini=true&url=".url()->full()."&title=".$data['read']->fieldLang('title')."&source=".request()->root()."";
        $data['share_pinterest'] = "https://pinterest.com/pin/create/bookmarklet/?media=".$data['cover']."&url=".url()->full()."&is_video=false&description=".$data['read']->fieldLang('title')."";

        $blade = 'index';
        if (!empty($data['read']->custom_view_id)) {
            $blade = config('custom.resource.path.catalog_categories.custom').'.'.collect(explode("/", $data['read']->customView->file_path))->last();
        }

        //breadcrumbs
        $breadcrumbsLong = [
            'Catalog Product' => config('custom.language.multiple') == true ? route('catalog.product.list', ['locale' => app()->getLocale()]) : route('catalog.product.list'),
            $data['read']->fieldLang('title') => '',
        ];
        $breadcrumbsShort = [
            $data['read']->fieldLang('title') => '',
        ];

        return view('frontend.catalogue.products.'.$blade, compact('data'), [
            'title' => 'Catalog Product - '.$data['read']->fieldLang('title'),
            'breadcrumbs' => $breadcrumbsShort
        ]);
    }
}
