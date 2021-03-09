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

    <title>{{ $config['meta_title'] }} {{ isset($title) ? ' | '.$title : '' }} @yield('title')</title>

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
    @yield('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/tmplts_frontend/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/tmplts_frontend/css/main.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/tmplts_frontend/css/responsive.css') }}">

    <!-- jshead -->
    <script src="{{ asset('assets/tmplts_frontend/js/jquery-3.4.1.min.js') }}"></script>
    <script src="{{ asset('assets/tmplts_frontend/js/popper.min.js')}}"></script>

    <script src="{{ asset('assets/tmplts_frontend/js/bootstrap.min.js') }}"></script>
    @yield('jshead')

    {!! $config['google_analytics'] !!}
    
</head>

<body class="@yield('body-class')">
    @if(Request::segment('5') == '5' || Request::segment('5') == '10')
    <div id="main" class="single-page">
    @else
    <div id="main">
    @endif   
        @yield('layout-content')
        <div class="site-background"></div>
    </div>

    <!-- Javascript -->
    @yield('scripts')
    
    <script src="{{ asset('assets/tmplts_frontend/js/smooth-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/tmplts_frontend/js/lazyload.min.js') }}"></script>
    
    <script src="{{ asset('assets/tmplts_frontend/js/main.js') }}"></script>


    <!-- jsbody -->
    @yield('jsbody')
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
