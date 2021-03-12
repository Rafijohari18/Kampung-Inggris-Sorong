@if (config('custom.maintenance.mode') == TRUE)
    @include('frontend.maintenance')
@else
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>

    <meta charset="utf-8">
    <meta name="theme-color" content="#000"/>
    <meta name="title" content="{!! isset($data['meta_title']) ? $data['meta_title'] : $config['meta_title'] !!}">
    <meta name="description" content="{!! isset($data['meta_description']) ? $data['meta_description'] : $config['meta_description'] !!}">
    <meta name="keywords" content="{!! isset($data['meta_keywords']) ? $data['meta_keywords'] : $config['meta_keywords'] !!}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>404 Not Found</title>

    <meta name="robots" content="index,follow" />
    <meta name="googlebot" content="index,follow" />
    <meta name="revisit-after" content="2 days" />
    <meta name="author" content="4 Vision Media">
    <meta name="expires" content="never" />

    <meta name="google-site-verification" content="{!! $config['google_verification'] !!}" />

    <meta property="og:locale" content="{{ app()->getlocale().'_'.strtoupper(app()->getlocale()) }}" />
    <meta property="og:site_name" content="{{ route('home') }}">
    <meta property="og:title" content="{!! isset($data['meta_title']) ? $data['meta_title'] : $config['meta_title'] !!}"/>
    <meta property="og:url" name="url" content="{{ url()->full() }}">
    <meta property="og:description" content="{!! isset($data['meta_description']) ? $data['meta_description'] : $config['meta_description'] !!}"/>
    <meta property="og:image" content="{!! isset($data['cover']) ? $data['cover'] : $config['open_graph'] !!}"/>
    <meta property="og:image:width" content="650" />
    <meta property="og:image:height" content="366" />
    <meta property="og:type" content="website" />

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{!! isset($data['meta_title']) ? $data['meta_title'] : $config['meta_title'] !!}">
    <meta name="twitter:site" content="{{ url()->full() }}">
    <meta name="twitter:creator" content="{!! isset($data['creator']) ? $data['creator'] : 'Administrator Web' !!}">
    <meta name="twitter:description" content="{!! isset($data['meta_description']) ? $data['meta_description'] : $config['meta_description'] !!}">
    <meta name="twitter:image" content="{!! isset($data['cover']) ? $data['cover'] : $config['open_graph'] !!}">

    <!-- Css -->
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/tmplts_frontend/images/favicon.ico') }}" sizes="32x32">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/tmplts_frontend/fonts/fontawesome/all.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/tmplts_frontend/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/tmplts_frontend/css/smooth-scrollbar.css')}}">
 
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/tmplts_frontend/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/tmplts_frontend/css/main.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/tmplts_frontend/css/responsive.css') }}">

    <!-- jshead -->
    <script src="{{ asset('assets/tmplts_frontend/js/jquery-3.4.1.min.js') }}"></script>
    <script src="{{ asset('assets/tmplts_frontend/js/popper.min.js')}}"></script>

    <script src="{{ asset('assets/tmplts_frontend/js/bootstrap.min.js') }}"></script>
    {!! $config['google_analytics'] !!}
    
</head>

<body>
   
    <div id="main" class="single-page">

        <header class="main-header">
            <div class="container">
                <div class="top-header">
                    <a class="logo-header" href="{{ route('home') }}">
                        <div class="logo-icon"></div>
                        <div class="logo-type">Kampung Inggris<br>Sorong</div>
                    </a>
                    <div class="lang-header dropdown">
                        <i class="circle-46 fs-20 mr-5 fal fa-globe txt-g_blue"></i>
                        <a href="#!" data-toggle="dropdown">
                            <div class="fs-12 txt-grey">Language</div>
                            @foreach($language as $lang)
                                @if($lang->iso_codes == app()->getLocale())
                                    <div class="fs-14 txt-white fw-med">{{ $lang->country }}</div>
                                @endif
                            @endforeach
                        </a>
                        <div class="dropdown-menu">
                            <ul>
                            @foreach($language as $lang)   
                                <li><a href="{{ $lang->urlSwitcher(Request::segment(1),$lang->iso_codes) }}">{{ $lang->country }}</a></li>
                            @endforeach 
                            </ul>
                        </div>
                    </div>
                    <!--<div class="contact-header txt-white">
                        <div class="contact-info">
                            <i class="circle-46 fs-20 fal fa-phone-rotary"></i>
                            <div class="text">
                                <div class="fs-13 info-label">Call Us</div>
                                <div class="fs-14">+123 456 7890</div>
                            </div>
                        </div>
                        <div class="contact-info">
                            <i class="circle-46 fs-20 fal fa-envelope"></i>
                            <div class="text">
                                <div class="fs-13 info-label">Email Us</div>
                                <div class="fs-14">info@kpinggrissorong.com</div>
                            </div>
                        </div>
                    </div>-->
                    @if (!empty($menu['kontak']))
                    <a class="btn btn-custom-outline btn-white btn-contact" href="{{ $menu['kontak']->routes($menu['kontak']->slug) }}">Contact Us</a>
                    @endif
                </div>
                <nav class="nav-header">
                    <div class="burger-menu">
                        <div class="burger-icon">
                            <span></span>
                            <span></span>
                        </div>
                    </div>

                    <a class="logo-header" href="{{ route('home') }}">
                        <div class="logo-icon"></div>
                    </a>
                    @php
                        $packageActive = ((Request::segment(1) == 'content' && Request::segment(5) == $menu['package']->slug) ? 'active' : '');
                        $programActive = ((Request::segment(1) == 'content' && Request::segment(5) == $menu['program']->slug) ? 'active' : '');
                    @endphp
                    
                    <div class="menu-header">
                        <ul class="menu">
                            <li class="dropdown"><a href="#!">About Us</a>
                                <div class="dropdown-hover">
                                    <ul>
                                        <li><a href="{{ $menu['about']->routes($menu['about']->id, $menu['about']->slug) }}">Our Profile</a></li>
                                        <li><a href="{{ $menu['struktur']->routes($menu['struktur']->id, $menu['struktur']->slug) }}l">Structure Organization</a></li>
                                    </ul>
                                </div>
                            </li>
                            @if (!empty($menu['program']))
                            <li class="dropdown"><a href="#!" class="{{ $programActive }}">{{ $menu['program']->fieldLang('name') }}</a>
                                @if ($menu['program']->category->count() > 0)
                                <div class="dropdown-hover">
                                    <ul>
                                        @foreach ($menu['program']->category as $program)
                                            <li><a href="{{ $program->routes($program->id, $program->slug) }}">{!! $program->fieldLang('name') !!}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                            </li>
                            @endif

                            @if (!empty($menu['package']))
                            <li><a class="{{ $packageActive }}" href="{{ $menu['package']->routes($menu['package']->id, $menu['package']->slug) }}" title="{!! $menu['package']->fieldLang('name') !!}">{!! $menu['package']->fieldLang('name') !!}</a>
                            @endif

                            @if (!empty($menu['information']))
                            <li class="dropdown"><a href="#!">{{ $menu['information']->fieldLang('name') }}</a>
                                @if ($menu['information']->category->count() > 0)
                                    <div class="dropdown-hover">
                                        <ul>
                                            @foreach($menu['information']->category as $information)
                                                <li><a href="{{ $information->routes($information->id, $information->slug) }}">{!! $information->fieldLang('name') !!}</a></li>
                                            @endforeach  
                                        </ul>
                                    </div>
                                @endif

                            </li>
                            @endif


                            <li class="dropdown"><a href="#!" class="{{ Request::is('gallery*') ? 'active' : '' }}">Gallery</a>
                                <div class="dropdown-hover">
                                    <ul>
                                        <li><a href="{{ route('album.list') }}">Gallery Photo</a></li>
                                        <li><a href="{{ route('playlist.list') }}">Gallery Video</a></li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                        <a class="btn btn-contact btn-custom-outline btn-white" href="">Contact Us</a>
                    </div>
                    <div class="search-header">
                        <button class="btn btn-white btn-search btn-icon" type="button"><span></span></button>
                        <div class="search-form">
                            <form class="form-header" action="{{ route('home.search') }}" method="GET">
                                <input class="form-control" placeholder="Search..." type="search" name="keyword">
                                <button class="btn btn-submit btn-icon" type="submit"><i class="fal fa-arrow-right"></i></button>
                            </form>
                        </div>
                    </div>
                </nav>
            </div>
        </header>

        <div class="main-content" >
            <section class="page-header">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6 offset-lg-3 col-md-8 offset-md-2">
                            <ul class="breadcrumb">
                                <li><a class="link" href="{{ route('home') }}" title="@lang('common.menu_beranda')" style="color:black;">@lang('common.menu_beranda')</a></li>
                                <li style="color:black;">404 Not Found</li>
                            </ul>
                            <div class="main-title text-center">
                                <h1 class="title" style="color:black;">@lang('common.error_404_title')</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content-wrap section-content">
                <div class="container">
                    <div class="single-post">
                        <div class="post-entry text-center">
                            @lang('common.error_404_description')
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <footer class="main-footer">
            @yield('footer_add')
            <section class="content-wrap top-footer">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-3 col-md-3 logo-wrap">
                            <a class="logo-footer" href="{{ route('home') }}">
                                <div class="logo-icon"></div>
                                <div class="logo-type">{{ $config['website_name'] }}</div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-3 footer-wrap">
                            <div class="main-title">
                                <h5 class="title txt-white">{{ $menu['program']->fieldLang('name') }}</h5>
                            </div>
                            <ul class="footer-menu">
                            @if (!empty($menu['program']))
                                @if ($menu['program']->category->count() > 0)
                                    @foreach ($menu['program']->category as $program)
                                    <li><a href="{{ $program->routes($program->id, $program->slug) }}">{!! $program->fieldLang('name') !!}</a></li>
                                    @endforeach
                                @endif
                            @endif
                            </ul>
                        </div>
                        <div class="col-lg-3 col-md-3 footer-wrap">
                            <div class="main-title">
                                <h5 class="title txt-white">Package</h5>
                            </div>
                            <ul class="footer-menu">
                                @if (!empty($menu['package']))
                                    <li><a href="{{ $menu['package']->routes($menu['package']->id, $menu['package']->slug) }}" title="{!! $menu['package']->fieldLang('name') !!}">{!! $menu['package']->fieldLang('name') !!}</a>
                                @endif
                                
                            </ul>
                        </div>
                        <div class="col-lg-3 col-md-3 footer-wrap">
                            <div class="main-title">
                                <h5 class="title txt-white">Information</h5>
                            </div>
                            @if (!empty($menu['information']))
                            
                            <ul class="footer-menu">
                                @if ($menu['information']->category->count() > 0)
                                    @foreach($menu['information']->category as $information)
                                        <li><a href="{{ $information->routes($information->id, $information->slug) }}">{!! $information->fieldLang('name') !!}</a></li>
                                    @endforeach
                                @endif
                            </ul>
                            @endif
                        </div>
                    </div>
                </div>
            </section>
            <section class="bottom-footer">
                <div class="container">
                    <div class="copyright fs-12">© 2021 Kampung Inggris Sorong</div>
                    <div class="social-icon">
                        <a class="btn btn-icon btn-sm btn-custom-outline btn-white" href="{{ $config['facebook'] }}"><i class="fab fa-facebook-f"></i></a>
                        <a class="btn btn-icon btn-sm btn-custom-outline btn-white" href="{{ $config['twitter'] }}"><i class="fab fa-twitter"></i></a>
                        <a class="btn btn-icon btn-sm btn-custom-outline btn-white" href="{{ $config['instagram'] }}"><i class="fab fa-instagram"></i></a>
                        <a class="btn btn-icon btn-sm btn-custom-outline btn-white" href="{{ $config['youtube'] }}"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </section>
        </footer>

        <div class="site-background"></div>
    </div>

    
    <script src="{{ asset('assets/tmplts_frontend/js/smooth-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/tmplts_frontend/js/lazyload.min.js') }}"></script>
    
    <script src="{{ asset('assets/tmplts_frontend/js/main.js') }}"></script>

    <script>
    
    var lazyLoadInstance = new LazyLoad();
    $("img.lazy").each(function(){
        $(this).parent().css("background-color","rgba(154,158,160,.5)")
    });

    $(".btn-search").on("click", function(){
        $("body").toggleClass("is-search");
        if($("body").hasClass("is-search")){
            setTimeout(function(){
                $("body").removeClass("is-menu");
                $(".search-header input").focus();
            },300);
        } else {
            setTimeout(function(){
                $(".search-header input").val("");
            },300);
        }
    });

    const scrollbar = Scrollbar.init(document.querySelector('#main'),{
    damping: 0.12,
	speed: 0.75,
    thumbMinSize: 16,
    syncCallbacks: true
    });
    
    scrollbar.addListener(function(status){
        var offset = status.offset;
        var page = document.querySelector("body");
        var header = document.querySelector(".main-header");
        var tHeader = $(".top-header").outerHeight();    
        
        if(scrollbar.offset.y > 800){
            $(".main-header").addClass("is-sticky").removeClass("is-hide").css("transform","translateY(-"+tHeader+"px)");
            header.style.top = offset.y + 'px';
        } else if(scrollbar.offset.y >= 300) {
            $(".main-header").addClass("is-hide").css("transform","translateY(-100%)");
            $("body").removeClass("is-menu");
            header.style.top = offset.y + 'px';
        } else {
            $(".main-header").removeClass("is-sticky is-hide").css("transform","translateY(0)");
            header.style.top = 0 + 'px';
        }

        if(scrollbar.isVisible(page)){
            $(".parallax-header").css("transform","translate3d(0, "+ offset.y/4 +"px, 0)");
        }

    });


    $(".burger-menu").click(function(){
        $("body").toggleClass("is-menu");
    });

    $(".nav-tabs a").click(function(){
        $(".nav-tabs a").removeClass("is-scroll");
        $(this).addClass("is-scroll");
        $(".tab-menu-scroll").scrollCenter("a.is-scroll",300);
        $(this).dblclick();
    });
    $.fn.scrollCenter = function(elem, speed) {
        var active = $(this).find(elem);
        var activeWidth = active.width() / 2 + 20;
        var pos = active.position().left + activeWidth;
        var elpos = $(this).scrollLeft();
        var elW = $(this).width();
        pos = pos + elpos - elW / 2;
        $(this).animate({scrollLeft: pos }, speed == undefined ? 500 : speed);
        return this;
    };
    $.fn.scrollCenterORI = function(elem, speed) {
        $(this).animate({scrollLeft: $(this).scrollLeft() - $(this).offset().left + $(elem).offset().left}, speed == undefined ? 500 : speed);
        return this;
    };
    </script>

</body>
</html>
@endif
