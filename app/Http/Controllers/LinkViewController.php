<?php

namespace App\Http\Controllers;

use App\Services\ConfigurationService;
use App\Services\Link\LinkMediaService;
use App\Services\Link\LinkService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LinkViewController extends Controller
{
    private $serviceLink, $serviceLinkMedia, $config;

    public function __construct(
        LinkService $serviceLink,
        LinkMediaService $serviceLinkMedia,
        ConfigurationService $config
    )
    {
        $this->serviceLink = $serviceLink;
        $this->serviceLinkMedia = $serviceLinkMedia;
        $this->config = $config;
    }

    public function viewLinkList(Request $request)
    {
        return redirect()->route('home');

        //data
        $data['banner'] = Storage::url('banner/'.$this->config->getValue('banner_default'));
        $limit = $this->config->getValue('content_limit');
        $data['link'] = $this->serviceLink->getLink($request, 'paginate', $limit);
        $data['link_media'] = $this->serviceLinkMedia->getLinkMedia($request, 'paginate', $limit);

        return view('frontend.links.list', compact('data'), [
            'title' => 'Links',
            'breadcrumbs' => [
                'Links' => '',
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
        $data['read'] = $this->serviceLink->find($id);

        $this->serviceLink->recordViewer($id);

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
                return redirect()->route('link.view', ['id' => $data['read']->id, 'slug' => $data['read']->slug]);
            } else {
                return redirect()->route('link.view', ['lang' => $lang, 'id' => $data['read']->id, 'slug' => $data['read']->slug]);
            }
        }

        //data
        $limit = $this->config->getValue('content_limit');
        if (!empty($data['read']->list_limit)) {
            $limit = $data['read']->list_limit;
        }
        $data['media'] = $this->serviceLinkMedia->getLinkMedia($request, null, null, $id);
        $data['field'] = $data['read']->field;

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
            $blade = config('custom.resource.path.links.custom').'.'.collect(explode("/", $data['read']->customView->file_path))->last();
        }

        //breadcrumbs
        $breadcrumbsLong = [
            'Link' => config('custom.language.multiple') == true ? route('link.list', ['locale' => app()->getLocale()]) : route('link.list'),
            $data['read']->fieldLang('name') => '',
        ];
        $breadcrumbsShort = [
            $data['read']->fieldLang('name') => '',
        ];

        return view('frontend.links.'.$blade, compact('data'), [
            'title' => 'Link - '.$data['read']->fieldLang('name'),
            'breadcrumbs' => $breadcrumbsShort
        ]);
    }
}
