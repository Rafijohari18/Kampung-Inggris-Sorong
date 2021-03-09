<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Catalog\CatalogCategoryService;
use App\Services\Catalog\CatalogProductService;
use App\Services\Content\PostService;
use App\Services\Content\SectionService;
use App\Services\PageService;
use App\Services\Users\UserService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Analytics;
use App\Services\Gallery\AlbumService;
use App\Services\Gallery\PhotoService;
use App\Services\Gallery\PlaylistService;
use App\Services\Gallery\VideoService;
use App\Services\InquiryService;
use App\Services\NotificationService;
use Spatie\Analytics\Period;

class DashboardController extends Controller
{
    private $page, $section, $post, $catalogCategory, $catalogProduct,
        $album, $playlist, $photo, $video, $inquiry, $user, $notification;

    public function __construct(
        PageService $page,
        SectionService $section,
        PostService $post,
        CatalogCategoryService $catalogCategory,
        CatalogProductService $catalogProduct,
        AlbumService $album,
        PlaylistService $playlist,
        PhotoService $photo,
        VideoService $video,
        InquiryService $inquiry,
        UserService $user,
        NotificationService $notification
    )
    {
        $this->page = $page;
        $this->section = $section;
        $this->post = $post;
        $this->catalogCategory = $catalogCategory;
        $this->catalogProduct = $catalogProduct;
        $this->album = $album;
        $this->playlist = $playlist;
        $this->photo = $photo;
        $this->video = $video;
        $this->inquiry = $inquiry;
        $this->user = $user;
        $this->notification = $notification;
    }

    public function index(Request $request)
    {
        $data['counter'] = [
            'pages' => $this->page->countPage(),
            'sections' => $this->section->countSection(),
            'posts' => $this->post->countPost(),
            'users' => $this->user->countUser(),
            'catalog_categories' => $this->catalogCategory->countCatalogCategory(),
            'catalog_products' => $this->catalogProduct->countCatalogProduct(),
            'albums' => $this->album->countAlbum(),
            'photo' => $this->photo->countPhoto(),
            'playlists' => $this->playlist->countPlaylist(),
            'video' => $this->video->countVideo(),
        ];
        $data['latest'] = [
            'posts' => $this->post->getPost($request, null, 5),
            'inquiry' => $this->inquiry->getMessage($request, 5),
        ];
        if (!empty(env('ANALYTICS_VIEW_ID'))) {
            $start = Carbon::now()->startOfWeek();
            $end = Carbon::now()->endOfWeek();
            $periode = Period::create($start, $end);
            $data['total'] = Analytics::fetchTotalVisitorsAndPageViews($periode);
        }

        return view('backend.dashboard', compact('data'), [
            'title' => 'Dashboard'
        ]);
    }

    public function notification(Request $request)
    {
        $l = '';
        $r = '';
        $q = '';
        if (isset($request->l) || isset($request->r) || isset($request->q)) {
            $l = '?l='.$request->l;
            $r = '&r='.$request->r;
            $q = '&q='.$request->q;
        }

        $data['notifications'] = $this->notification->getNotificationList($request);
        $data['no'] = $data['notifications']->firstItem();
        $data['notifications']->withPath(url()->current().$l.$r.$q);

        $this->notification->read('notification');

        return view('backend.notification', compact('data'), [
            'title' => 'Notification',
            'breadcrumbs' => [
                'Notification' => ''
            ],
        ]);
    }

    public function notificationJson()
    {
        return response()->json([
            'total' => $this->notification->totalNotification(0),
            'latest' => $this->notification->latestNotification(),
        ]);
    }

    public function destroyNotification($id)
    {
        $this->notification->delete($id);

        return response()->json([
            'success' => 1,
            'message' => ''
        ], 200);
    }
}
