<?php

namespace App\Http\Controllers;

use App\Services\Banner\BannerCategoryService;
use App\Services\Catalog\CatalogCategoryService;
use App\Services\Catalog\CatalogProductService;
use App\Services\Content\CategoryService;
use App\Services\Content\PostService;
use App\Services\Content\SectionService;
use App\Services\Link\LinkService;
use App\Services\Link\LinkMediaService;
use App\Services\PageService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    private $page, $section, $category, $post, $banner, $catalogCategory, $catalogProduct, $link;
    public function __construct(
        PageService $page,
        SectionService $section,
        CategoryService $category,
        PostService $post,
        BannerCategoryService $banner,
        CatalogCategoryService $catalogCategory,
        CatalogProductService $catalogProduct,
        LinkService $link,
        LinkMediaService $linkmedia
    )
    {
        $this->page = $page;
        $this->section = $section;
        $this->category = $category;
        $this->post = $post;
        $this->banner = $banner;
        $this->catalogCategory = $catalogCategory;
        $this->catalogProduct = $catalogProduct;
        $this->link   = $link;
        $this->linkmedia   = $linkmedia;
    }


    public function home(Request $request)
    {
        $data['banner_home']  = $this->banner->find(1);
        $data['facilities']   = $this->post->getPost(null,null,5,'section',1);
        $data['most_program'] = $this->post->getPost(null,null,null,'section',2); 
        
        $data['section_news']  = $this->post->find(1);

        $data['latest_news']  = $this->post->getPost(null,null,3,'section',3);  
        
        $data['link']         = $this->link->find(1);
        $data['partner']      = $this->linkmedia->getLinkMedia(null,null,null,1);
        

        return view('frontend.index',compact('data'));
    }

    public function search(Request $request)
    {
       
        if (empty($request->get('keyword'))) {
            return redirect()->route('home');
        }

        $data['banner_home']  = $this->banner->find(1);
        
        $data['pages'] = $this->page->getPage($request);
        $data['sections'] = $this->section->getSection($request);
        $data['categories'] = $this->category->getCategory($request);
        $data['posts'] = $this->post->getPost($request);
        $data['catalog_categories'] = $this->catalogCategory->getCatalogCategory($request);
        $data['catalog_products'] = $this->catalogProduct->getCatalogProduct($request);

        $data['keyword']    =   $request->get('keyword');
       
        return view('frontend.search', compact('data'));
    }
}
