<?php

namespace App\Http\Controllers;

use App\Services\ConfigurationService;
use App\Services\PageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PageViewController extends Controller
{
    private $service, $config;

    public function __construct(
        PageService $service,
        ConfigurationService $config
    )
    {
        $this->service = $service;
        $this->config = $config;
    }

    public function viewPageList(Request $request)
    {
        return redirect()->route('home');

        //data
        $data['banner'] = Storage::url('banner/'.$this->config->getValue('banner_default'));
        $limit = $this->config->getValue('content_limit');
        $data['pages'] = $this->service->getPage($request, 'paginate', $limit);

        return view('frontend.pages.list', compact('data'), [
            'title' => 'Pages',
            'breadcrumbs' => [
                'Pages' => '',
            ],
        ]);
    }

    public function viewWithLang(Request $request, $lang = null, $id = null, $slug = null)
    {
        if (empty($lang)) {
            return abort(404);
        }

        return $this->content($request, $id, $slug, $lang);
    }

    public function viewWithoutLang(Request $request, $id = null, $slug = null)
    {
        return $this->content($request, $id, $slug);
    }

    public function content($request, $id, $slug, $lang = null)
    {
        $data['read'] = $this->service->find($id);

        $this->service->recordViewer($id);

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
                return redirect()->route('page.view', ['id' => $data['read']->id, 'slug' => $data['read']->slug]);
            } else {
                return redirect()->route('page.view', ['lang' => $lang, 'id' => $data['read']->id, 'slug' => $data['read']->slug]);
            }
        }

        if ($data['read']->public == 0 && auth()->guard()->check() == false) {
            return redirect()->route('home')->with('warning', 'You must login first');
        }

        //data
        $data['childs'] = $data['read']->childs()->orderBy('position', 'ASC')->get();
        
        $data['media'] = $data['read']->media()->orderBy('position', 'ASC')->get();
        $data['field'] = $data['read']->field;

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

        $blade = 'index';
        if (!empty($data['read']->custom_view_id)) {
            $blade = config('custom.resource.path.pages.custom').'.'.collect(explode("/", $data['read']->customView->file_path))->last();
        }

        //breadcrumbs
        $breadcrumbsLong = [];
        $breadcrumbsLong['Pages'] = config('custom.language.multiple') == true ? route('page.list', ['locale' => app()->getLocale()]) : route('page.list');
        foreach ($data['read']->where('id', $data['read']->parent)->orderBy('position', 'ASC')->get() as $breadA) {
            foreach ($breadA->where('id', $breadA->parent)->orderBy('position', 'ASC')->get() as $breadB) {
                $breadcrumbsLong[$breadB->fieldLang('title')] = $breadA->routes($breadB->id, $breadB->slug);
            }
            $breadcrumbsLong[$breadA->fieldLang('title')] = $breadA->routes($breadA->id, $breadA->slug);
        }
        $breadcrumbsLong[$data['read']->fieldLang('title')] = '';

        $breadcrumbsShort = [
            $data['read']->fieldLang('title') => '',
        ];

        return view('frontend.pages.'.$blade, compact('data'), [
            'title' => 'Page - '.$data['read']->fieldLang('title'),
            'breadcrumbs' => $breadcrumbsShort
        ]);
    }

    public function jsonPageChild($childId)
    {
        $find = $this->service->find($childId);

        return response()->json([
            'cover' => $find->coverSrc($find),
            'cover_file' => $find->cover['file_path'],
            'cover_title' => $find->cover['title'] ?? '',
            'cover_alt' => $find->cover['alt'] ?? '',
            'content' => $find->fieldLang('content') ?? '',
        ]);
    }
}
