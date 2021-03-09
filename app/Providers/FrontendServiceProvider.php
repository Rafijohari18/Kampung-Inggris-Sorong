<?php

namespace App\Providers;

use App\Models\Configuration;
use App\Models\Content\Category;
use App\Models\Content\Post\Post;
use App\Models\Content\Section;
use App\Models\Gallery\PlaylistVideo;
use App\Models\Inquiry\Inquiry;
use App\Models\Link\Link;
use App\Models\Page;
use App\Models\Language;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class FrontendServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::share([
            'config' => [
                //group 1
                'logo' => Configuration::getImage('logo', 'logo'),
                'logo_2' => Configuration::getImage('logo_2', 'logo'),
                'logo_small' => Configuration::getImage('logo_small', 'logo'),
                'logo_small_2' => Configuration::getImage('logo_small_2', 'logo'),
                'logo_mail' => Configuration::getImage('logo_mail', 'logo'),
                'open_graph' => Configuration::getImage('open_graph', 'og'),
                'banner_default' => Configuration::getImage('banner_default', 'banner'),
                //group 2
                'website_name' => Configuration::value('website_name') ?? 'Content Management System',
                'address' => Configuration::value('address'),
                'email' => Configuration::value('email'),
                'email_2' => Configuration::value('email_2'),
                'fax' => Configuration::value('fax'),
                'phone' => Configuration::value('phone'),
                'phone_2' => Configuration::value('phone_2'),
                //group 3
                'meta_title' => Configuration::value('meta_title') ?? 'Content Management System',
                'meta_description' => Configuration::value('meta_description'),
                'meta_keywords' => Configuration::value('meta_keywords'),
                'google_analytics' => Configuration::value('google_analytics'),
                'google_verification' => Configuration::value('google_verification'),
                'domain_verification' => Configuration::value('domain_verification'),
                //group 4
                'twitter' => Configuration::value('twitter'),
                'youtube' => Configuration::value('youtube'),
                'facebook' => Configuration::value('facebook'),
                'linkedin' => Configuration::value('linkedin'),
                'whatsapp' => Configuration::value('whatsapp'),
                'app_store' => Configuration::value('app_store'),
                'instagram' => Configuration::value('instagram'),
                'pinterest' => Configuration::value('pinterest'),
                'youtube_id' => Configuration::value('youtube_id'),
                'google_plus' => Configuration::value('google_plus'),
                'google_play_store' => Configuration::value('google_play_store'),
            ],
            //menu
            'menu' => [
                //page
                'about' => Page::where('id', 1)->publish()->first(),
                'selayang' => Page::where('id', 2)->publish()->first(),
                'ppid' => Page::where('id', 3)->publish()->first(),
                //content
                'pengumuman' => Section::find(1),
                'program'   => Section::find(2),
                'information' => Section::find(3),
                'package' => Section::find(4),
                'struktur' => Section::find(5),
                //link
                'rekrutmen' => Link::where('id', 1)->publish()->first(),
                'regulasi' => Link::where('id', 2)->publish()->first(),
                'ppid_link' => Link::where('id', 3)->publish()->first(),
                'informasi' => Link::where('id', 4)->publish()->first(),
                'situs' => Link::where('id', 5)->publish()->first(),
                //inquiry
                'kontak' => Inquiry::where('id', 1)->publish()->first(),
                //category
                'kategori' => Category::whereIn('id', [1,2,3])->get(),
            ],
            'widget' => [
                // 'berita_video' => Post::where('category_id', 3)->publish()
                //     ->orderBy('created_at', 'DESC')->limit(1)->get(),
                'video' => PlaylistVideo::orderBy('created_at', 'DESC')->limit(1)->get(),
                'berita_terbaru' => Post::whereIn('category_id', [2, 3])->publish()
                    ->orderBy('created_at', 'DESC')->limit(3)->get(),
                'berita_trending' => Post::whereIn('category_id', [2, 3])
                    ->where('viewer', '>', 0)->publish()
                    ->orderBy('viewer', 'DESC')->limit(3)->get(),
            ],
            'language' => Language::active()->get(),
        ]);
    }
}
