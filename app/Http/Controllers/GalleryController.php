<?php

namespace App\Http\Controllers;

use App\Services\ConfigurationService;
use App\Services\Gallery\AlbumService;
use App\Services\Gallery\PhotoService;
use App\Services\Gallery\PlaylistService;
use App\Services\Gallery\VideoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    private $serviceAlbum, $servicePhoto, $servicePlaylist, $serviceVideo, $config;

    public function __construct(
        AlbumService $serviceAlbum,
        PhotoService $servicePhoto,
        PlaylistService $servicePlaylist,
        VideoService $serviceVideo,
        ConfigurationService $config
    )
    {
        $this->serviceAlbum = $serviceAlbum;
        $this->servicePhoto = $servicePhoto;
        $this->servicePlaylist = $servicePlaylist;
        $this->serviceVideo = $serviceVideo;
        $this->config = $config;
    }

    public function viewGalleryList(Request $request)
    {
        $data['banner'] = Storage::url('banner/'.$this->config->getValue('banner_default'));

        $data['album'] = $this->serviceAlbum->getAlbum($request, null, null);
        $data['playlist'] = $this->servicePlaylist->getPlaylist($request, null, null);

        return view('frontend.gallery.index', compact('data'), [
            'title' => 'Gallery',
            'breadcrumbs' => [
                'Galeri' => ''
            ],
        ]);
    }

    /**
     * album
     */
    public function viewAlbumList(Request $request)
    {
        $data['banner'] = Storage::url('banner/'.$this->config->getValue('banner_default'));

        $limit = $this->config->getValue('content_limit');
        $data['section_photo'] = $this->serviceAlbum->find(1);

        $data['album']         = $this->serviceAlbum->getAlbum($request, 'paginate', 6);
        $data['photo']         = $this->servicePhoto->getPhoto($request, 'paginate', $limit);

        return view('frontend.gallery.albums.list', compact('data'), [
            'title' => 'Gallery - Album',
            'breadcrumbs' => [
                'Galeri' => route('gallery.list')
            ],
        ]);
    }

    public function viewAlbumWithLang(Request $request, $lang = null, $id = null)
    {
        if (empty($lang)) {
            return abort(404);
        }

        return $this->albumContent($request, $id, $lang);
    }

    public function viewAlbumWithoutLang(Request $request, $id = null)
    {
        return $this->albumContent($request, $id);
    }

    public function albumContent($request, $id, $lang = null)
    {
        $data['read'] = $this->serviceAlbum->find($id);

        $this->serviceAlbum->recordViewer($id);

        //check
        if (empty($id)) {
            return abort(404);
        }

        if ($data['read']->publish == 0 || empty($data['read']) || $data['read']->is_widget == 1) {
            return redirect()->route('home');
        }

        //data
        $limit = $this->config->getValue('content_limit');
        if (!empty($data['read']->list_limit)) {
            $limit = $data['read']->list_limit;
        }
        $data['photo'] = $this->servicePhoto->getPhoto($request, null, null, $id);

        // meta data
        $data['meta_title'] = $data['read']->fieldLang('name');
        $data['meta_description'] = $this->config->getValue('meta_description');
        if (!empty($data['read']->fieldLang('description'))) {
            $data['meta_description'] = $data['read']->fieldLang('description');
        }

        //images
        $data['creator'] = $data['read']->createBy->name;
        $data['cover'] = $data['read']->photoCover($id);
        $data['banner'] = $data['read']->bannerSrc($data['read']);

        $blade = 'index';
        if (!empty($data['read']->custom_view_id)) {
            $blade = config('custom.resource.path.albums.custom').'.'.collect(explode("/", $data['read']->customView->file_path))->last();
        }

        //breadcrumbs
        $breadcrumbsLong = [
            'Gallery Photo' => config('custom.language.multiple') == true ? route('album.list', ['locale' => app()->getLocale()]) : route('album.list'),
            $data['read']->fieldLang('name') => '',
        ];
        $breadcrumbsShort = [
            $data['read']->fieldLang('name') => '',
        ];

        return view('frontend.gallery.albums.'.$blade, compact('data'), [
            'title' => 'Album - '.$data['read']->fieldLang('name'),
            'breadcrumbs' => $breadcrumbsShort
        ]);
    }

    /**
     * playlist
     */
    public function viewPlaylistList(Request $request)
    {
        $data['banner'] = Storage::url('banner/'.$this->config->getValue('banner_default'));
        $limit = $this->config->getValue('content_limit');
        $data['playlist'] = $this->servicePlaylist->getPlaylist($request, 'paginate', 6);
        $data['video'] = $this->serviceVideo->getVideo($request, 'paginate', $limit);
        $data['section_video'] = $this->servicePlaylist->find(1);


        return view('frontend.gallery.playlists.list', compact('data'), [
            'title' => 'Gallery - Playlists',
            'breadcrumbs' => [
                'Galeri' => route('gallery.list')
            ],
        ]);
    }

    public function viewPlaylistWithLang(Request $request, $lang = null, $id = null)
    {
        if (empty($lang)) {
            return abort(404);
        }

        return $this->playlistContent($request, $id, $lang);
    }

    public function viewPlaylistWithoutLang(Request $request, $id = null)
    {
        return $this->playlistContent($request, $id);
    }

    public function playlistContent($request, $id, $lang = null)
    {
        $data['read'] = $this->servicePlaylist->find($id);

        $this->servicePlaylist->recordViewer($id);

        //check
        if (empty($id)) {
            return abort(404);
        }

        if ($data['read']->publish == 0 || empty($data['read']) || $data['read']->is_widget == 1) {
            return redirect()->route('home');
        }

        //data
        $limit = $this->config->getValue('content_limit');
        if (!empty($data['read']->list_limit)) {
            $limit = $data['read']->list_limit;
        }
        $data['video'] = $this->serviceVideo->getVideo($request, null, null, $id);

        // meta data
        $data['meta_title'] = $data['read']->fieldLang('name');
        $data['meta_description'] = $this->config->getValue('meta_description');
        if (!empty($data['read']->fieldLang('description'))) {
            $data['meta_description'] = $data['read']->fieldLang('description');
        }

        //images
        $data['creator'] = $data['read']->createBy->name;
        $data['cover'] = $data['read']->videoCover($id);
        $data['banner'] = $data['read']->bannerSrc($data['read']);

        $blade = 'index';
        if (!empty($data['read']->custom_view_id)) {
            $blade = config('custom.resource.path.playlists.custom').'.'.collect(explode("/", $data['read']->customView->file_path))->last();
        }

        //breadcrumbs
        $breadcrumbsLong = [
            'Gallery Video' => config('custom.language.multiple') == true ? route('playlist.list', ['locale' => app()->getLocale()]) : route('playlist.list'),
            $data['read']->fieldLang('name') => '',
        ];
        $breadcrumbsShort = [
            $data['read']->fieldLang('name') => '',
        ];

        return view('frontend.gallery.playlists.'.$blade, compact('data'), [
            'title' => 'Playlists - '.$data['read']->fieldLang('name'),
            'breadcrumbs' => $breadcrumbsShort
        ]);
    }
}
