<?php

namespace App\Http\Controllers;

use App\Services\ConfigurationService;
use App\Services\Content\CategoryService;
use App\Services\Content\PostService;
use App\Services\Content\SectionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ContentViewController extends Controller
{
    private $serviceSection, $serviceCategory, $servicePost, $config;

    public function __construct(
        SectionService $serviceSection,
        CategoryService $serviceCategory,
        PostService $servicePost,
        ConfigurationService $config
    )
    {
        $this->serviceSection = $serviceSection;
        $this->serviceCategory = $serviceCategory;
        $this->servicePost = $servicePost;
        $this->config = $config;
    }

    /**
     * section
     */
    public function viewSectionList(Request $request)
    {
        return redirect()->route('home');

        //data
        $data['banner'] = Storage::url('banner/'.$this->config->getValue('banner_default'));
        $limit = $this->config->getValue('content_limit');
        $data['sections'] = $this->serviceSection->getSection($request, 'paginate', $limit);

        return view('frontend.content.sections.list', compact('data'), [
            'title' => 'Content - Sections',
            'breadcrumbs' => [
                'Sections' => ''
            ],
        ]);
    }

    public function viewSectionWithLang(Request $request, $lang = null, $id = null, $slug = null)
    {
        if (empty($lang)) {
            return abort(404);
        }

        return $this->contentSection($request, $id, $slug, $lang);
    }

    public function viewSectionWithoutLang(Request $request, $id = null, $slug = null)
    {
        return $this->contentSection($request, $id, $slug);
    }

    public function contentSection($request, $id, $slug, $lang = null)
    {
       
        $data['read'] = $this->serviceSection->find($id);

        $this->serviceSection->recordViewer($id);

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
                return redirect()->route('section.view', ['id' => $data['read']->id, 'slug' => $data['read']->slug]);
            } else {
                return redirect()->route('section.view', ['lang' => $lang, 'id' => $data['read']->id, 'slug' => $data['read']->slug]);
            }
        }

        if ($data['read']->public == 0 && auth()->guard()->check() == false) {
            return redirect()->route('home')->with('warning', 'You must login first');
        }

        //data
        $categoryLimit = $this->config->getValue('content_limit');
        $postLimit = $this->config->getValue('content_limit');
        if (!empty($data['read']->limit_category)) {
            $categoryLimit = $data['read']->limit_category;
        }
        if (!empty($data['read']->limit_post)) {
            $postLimit = $data['read']->limit_post;
        }
        $data['field'] = $data['read']->field;
        $data['categories'] = $this->serviceCategory->getCategory($request, 'paginate', $categoryLimit, $id);
        $data['posts'] = $this->servicePost->getPost($request, 'paginate', $postLimit, 'section', $id);

        // meta data
        $data['meta_title'] = $data['read']->fieldLang('name');
        $data['meta_description'] = $this->config->getValue('meta_description');
        if (!empty($data['read']->fieldLang('description'))) {
            $data['meta_description'] = $data['read']->fieldLang('description');
        }

        //images
        $data['creator'] = $data['read']->createBy->name;
        $data['banner'] = $data['read']->bannerSrc($data['read']);

        $blade = 'index';
        if (!empty($data['read']->list_view_id)) {
            $blade = config('custom.resource.path.sections.list').'.'.collect(explode("/", $data['read']->listView->file_path))->last();
        }
        

        //breadcrumbs
        $breadcrumbsLong = [
            'Section' => config('custom.language.multiple') == true ? route('section.list', ['locale' => app()->getLocale()]) : route('section.list'),
            $data['read']->fieldLang('name') => '',
        ];
        $breadcrumbsShort = [
            $data['read']->fieldLang('name') => '',
        ];

        return view('frontend.content.sections.'.$blade, compact('data'), [
            'title' => 'Content - Section - '.$data['read']->fieldLang('name'),
            'breadcrumbs' => $breadcrumbsShort
        ]);
    }

    /**
     * category
     */

    public function viewCategoryList(Request $request)
    {
        return redirect()->route('home');

        //data
        $data['banner'] = Storage::url('banner/'.$this->config->getValue('banner_default'));
        $limit = $this->config->getValue('content_limit');
        $data['categories'] = $this->serviceCategory->getCategory($request, 'paginate', $limit);

        return view('frontend.content.categories.list', compact('data'), [
            'title' => 'Content - Categories',
            'breadcrumbs' => [
                'Categories' => ''
            ],
        ]);
    }

    public function viewCategoryWithLang(Request $request, $lang = null, $id = null, $slug = null)
    {
        if (empty($lang)) {
            return abort(404);
        }

        return $this->contentCategory($request, $id, $slug, $lang);
    }

    public function viewCategoryWithoutLang(Request $request, $id = null, $slug = null)
    {
        return $this->contentCategory($request, $id, $slug);
    }

    public function contentCategory($request, $id, $slug, $lang = null)
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
                return redirect()->route('category.view', ['id' => $data['read']->id, 'slug' => $data['read']->slug]);
            } else {
                return redirect()->route('category.view', ['lang' => $lang, 'id' => $data['read']->id, 'slug' => $data['read']->slug]);
            }
        }

        if ($data['read']->public == 0 && auth()->guard()->check() == false) {
            return redirect()->route('home')->with('warning', 'You must login first');
        }

        //data
        $limit = $this->config->getValue('content_limit');
        if (!empty($data['read']->list_limit)) {
            $limit = $data['read']->list_limit;
        }
        $data['field'] = $data['read']->field;
        $data['posts'] = $this->servicePost->getPost($request, 'paginate', $limit, 'category', $id);

        // meta data
        $data['meta_title'] = $data['read']->fieldLang('name');
        $data['meta_description'] = $this->config->getValue('meta_description');
        if (!empty($data['read']->fieldLang('description'))) {
            $data['meta_description'] = $data['read']->fieldLang('description');
        }

        //images
        $data['creator'] = $data['read']->createBy->name;
        $data['banner'] = $data['read']->bannerSrc($data['read']);

        $blade = 'index';
        if (!empty($data['read']->list_view_id)) {
            $blade = config('custom.resource.path.categories.list').'.'.collect(explode("/", $data['read']->listView->file_path))->last();
        }

        //breadcrumbs
        $breadcrumbsLong = [
            'Category' => config('custom.language.multiple') == true ? route('category.list', ['locale' => app()->getLocale()]) : route('category.list'),
            $data['read']->fieldLang('name') => '',
        ];
        $breadcrumbsShort = [
            $data['read']->fieldLang('name') => '',
        ];

        return view('frontend.content.categories.'.$blade, compact('data'), [
            'title' => 'Content - Category - '.$data['read']->fieldLang('name'),
            'breadcrumbs' => $breadcrumbsShort
        ]);
    }

    /**
     * post
     */
    public function viewPostList(Request $request)
    {
        return redirect()->route('home');

        //data
        $data['banner'] = Storage::url('banner/'.$this->config->getValue('banner_default'));
        $limit = $this->config->getValue('content_limit');
        $data['posts'] = $this->servicePost->getPost($request, 'paginate', $limit);

        return view('frontend.content.posts.list', compact('data'), [
            'title' => 'Content - Posts',
            'breadcrumbs' => [
                'Posts' => ''
            ],
        ]);
    }

    public function viewPostWithLang(Request $request, $lang = null, $id = null, $slug = null)
    {
        if (empty($lang)) {
            return abort(404);
        }

        return $this->contentPost($request, $id, $slug, $lang);
    }

    public function viewPostWithoutLang(Request $request, $id = null, $slug = null)
    {
        return $this->contentPost($request, $id, $slug);
    }

    public function contentPost($request, $id, $slug, $lang = null)
    {
        
        $data['read'] = $this->servicePost->find($id);

        $this->servicePost->recordViewer($id);

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
                return redirect()->route('post.view', ['id' => $data['read']->id, 'slug' => $data['read']->slug]);
            } else {
                return redirect()->route('post.view', ['lang' => $lang, 'id' => $data['read']->id, 'slug' => $data['read']->slug]);
            }
        }

        if ($data['read']->public == 0 && auth()->guard()->check() == false) {
            return redirect()->route('home')->with('warning', 'You must login first');
        }

        //data
        $data['files'] = $data['read']->files;
        $data['media'] = $data['read']->media()->orderBy('position', 'ASC')->get();
        
        $data['field'] = $data['read']->field;
        $data['latest_post']  = $this->servicePost->latestPost($id, 4, 'section');
  



        $data['prev_post'] = $this->servicePost->postPrevNext($id, 1, 'prev', 'section');
        $data['next_post'] = $this->servicePost->postPrevNext($id, 1, 'next', 'section');

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
        $data['banner'] = $data['read']->bannerSrc($data['read']);
        $data['cover'] = $data['read']->coverSrc($data['read']);

        //share
        $data['share_facebook'] = "https://www.facebook.com/share.php?u=".url()->full()."&title=".$data['read']->fieldLang('title')."";
        $data['share_twitter'] = "https://twitter.com/intent/tweet?text=".$data['read']->fieldLang('title')."&amp;url=".url()->full()."";
        $data['share_whatsapp'] = "whatsapp://send?text=".$data['read']->fieldLang('title')." ".url()->full()."";
        $data['share_gplus'] = "https://plus.google.com/share?url=".url()->full()."";
        $data['share_linkedin'] = "https://www.linkedin.com/shareArticle?mini=true&url=".url()->full()."&title=".$data['read']->fieldLang('title')."&source=".request()->root()."";
        $data['share_pinterest'] = "https://pinterest.com/pin/create/bookmarklet/?media=".$data['cover']."&url=".url()->full()."&is_video=false&description=".$data['read']->fieldLang('title')."";

        $blade = 'frontend.content.posts.index';


        if (!empty($data['read']->custom_view_id)) {
            $blade = 'frontend.content.posts.'.config('custom.resource.path.posts.custom').'.'.collect(explode("/", $data['read']->customView->file_path))->last();;
        } elseif (!empty($data['read']->section->detail_view_id)) {
            $blade = 'frontend.content.sections.'.config('custom.resource.path.sections.detail').'.'.collect(explode("/", $data['read']->section->detailView->file_path))->last();
        } elseif (!empty($data['read']->category->detail_view_id)) {
            $blade = 'frontend.content.categories.'.config('custom.resource.path.categories.detail').'.'.collect(explode("/", $data['read']->category->detailView->file_path))->last();
        }
       
        $breadcrumbsLong = [
            'Post' => config('custom.language.multiple') == true ? route('post.list', ['locale' => app()->getLocale()]) : route('post.list'),
            $data['read']->fieldLang('title') => '',
        ];
        $breadcrumbsShort = [
            $data['read']->fieldLang('title') => '',
        ];

        return view($blade, compact('data'), [
            'title' => 'Content - Post - '.$data['read']->fieldLang('title'),
            'breadcrumbs' => $breadcrumbsShort
        ]);
    }
}
