<?php

namespace App\Http\Controllers;

use App\Services\Catalog\CatalogCategoryService;
use App\Services\Catalog\CatalogProductService;
use App\Services\ConfigurationService;
use App\Services\Content\CategoryService;
use App\Services\Content\PostService;
use App\Services\Content\SectionService;
use App\Services\Gallery\AlbumService;
use App\Services\Gallery\PlaylistService;
use App\Services\InquiryService;
use App\Services\Link\LinkService;
use App\Services\PageService;
use Illuminate\Http\Request;

class SitemapController extends Controller
{
    private $page, $section, $category, $post, $catalogCategory, $catalogProduct,
        $album, $playlist, $link, $inquiry, $config;

    public function __construct(
        PageService $page,
        SectionService $section,
        CategoryService $category,
        PostService $post,
        CatalogCategoryService $catalogCategory,
        CatalogProductService $catalogProduct,
        AlbumService $album,
        PlaylistService $playlist,
        LinkService $link,
        InquiryService $inquiry,
        ConfigurationService $config
    )
    {
        $this->page = $page;
        $this->section = $section;
        $this->category = $category;
        $this->post = $post;
        $this->catalogCategory = $catalogCategory;
        $this->catalogProduct = $catalogProduct;
        $this->album = $album;
        $this->playlist = $playlist;
        $this->link = $link;
        $this->inquiry = $inquiry;
        $this->config = $config;
    }

    public function sitemap(Request $request)
    {
        $data['pages'] = $this->page->getPage($request);
        $data['sections'] = $this->section->getSection($request);
        $data['categories'] = $this->category->getCategory($request);
        $data['posts'] = $this->post->getPost($request);
        $data['catalog_categories'] = $this->catalogCategory->getCatalogCategory($request);
        $data['catalog_products'] = $this->catalogProduct->getCatalogProduct($request);
        $data['albums'] = $this->album->getAlbum($request);
        $data['playlists'] = $this->playlist->getPlaylist($request);
        $data['links'] = $this->link->getLink($request);
        $data['inquiries'] = $this->inquiry->getInquiry($request);

        return response()->view('frontend.sitemap.sitemap', compact('data'))
            ->header('Content-Type', 'text/xml');
    }

    public function feed(Request $request)
    {
        $data['title'] = $this->config->getValue('meta_title');
        $data['description'] = $this->config->getValue('meta_description');
        $data['posts'] = $this->post->getPost($request);

        return view('frontend.sitemap.rss-feed', compact('data'));
    }

    public function post(Request $request)
    {
        $data['title'] = $this->config->getValue('meta_title');
        $data['description'] = $this->config->getValue('meta_description');
        $data['posts'] = $this->post->getPost($request);

        return view('frontend.sitemap.rss-post', compact('data'));
    }
}
