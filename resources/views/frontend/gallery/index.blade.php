@extends('layouts.frontend.layout')

@section('content')
<section class="page-header">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 offset-lg-3 col-md-8 offset-md-2">
                @include('frontend.components.breadcrumbs')
                <div class="main-title text-center">
                    <h1 class="title">@lang('common.menu_galeri')</h1>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="content-wrap section-content">
    <div class="container">
        <div class="row list-blog">
            @foreach ($data['album'] as $album)
            <div class="col-lg-4 col-md-4 col-sm-6">
                <div class="blog-post blog-5">
                    <figure class="post-image">
                        <div class="box-img">
                            <a href="{{ $album->routes($album->id) }}" title="{!! $album->fieldLang('name') !!}">
                                <div class="thumb">
                                    <img class="lazy" data-src="{{ $album->photoCover($album) }}" title="{!! $album->fieldLang('name') !!}" alt="{!! $album->fieldLang('name') !!}">
                                </div>
                            </a>
                        </div>
                    </figure>
                    <div class="post-content">
                        <ul class="post-categories">
                            <li><span class="badge badge-orange">{{ $album->photo->count() }} Foto</span></li>
                        </ul>
                        <h5 class="post-title f-bold" ellipsis><a class="hover-effect-1" href="{{ $album->routes($album->id) }}" title="{!! $album->fieldLang('name') !!}">{!! $album->fieldLang('name') !!}</a></h5>
                        <div class="post-meta txt-grey">
                            <span><i class="far fa-clock txt-dark"></i>{{ $album->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            @foreach ($data['playlist'] as $playlist)
            <div class="col-lg-4 col-md-4 col-sm-6">
                <div class="blog-post blog-5">
                    <figure class="post-image">
                        <div class="box-img">
                            <a href="{{ $playlist->routes($playlist->id) }}" title="{!! $playlist->fieldLang('name') !!}">
                                <div class="thumb">
                                    <img class="lazy" data-src="{{ $playlist->videoCover($playlist->id) }}" title="{!! $playlist->fieldLang('name') !!}" alt="{!! $playlist->fieldLang('name') !!}">
                                </div>
                            </a>
                        </div>
                    </figure>
                    <div class="post-content">
                        <ul class="post-categories">
                            <li><span class="badge badge-dark">{{ $playlist->video->count() }} Video</span></li>
                        </ul>
                        <h5 class="post-title f-bold" ellipsis><a class="hover-effect-1" href="{{ $playlist->routes($playlist->id) }}" title="{!! $playlist->fieldLang('name') !!}">{!! $playlist->fieldLang('name') !!}</a></h5>
                        <div class="post-meta txt-grey">
                            <span><i class="far fa-clock txt-dark"></i>{{ $playlist->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            @if ($data['album']->count() == 0 && $data['playlist']->count() == 0)
            <div class="text-center d-flex justify-content-center">
                <h5 style="color: red;">!<em> <strong style="color: red;">@lang('common.menu_galeri') Kosong</strong> </em>!</h5>
            </div>
            @endif
        </div>
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="caption-btn text-center d-flex justify-content-center">

                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script src="{{ asset('assets/tmplts_frontend/js/jquery.ellipsis.min.js') }}"></script>
<script src="{{ asset('assets/tmplts_frontend/js/lazyload.min.js') }}"></script>
@endsection

@section('jsbody')
<script>

    //ellipsis
    $("[ellipsis]").dotdotdot({
        ellipsis	: "...",
    });

    //lazy
    var lazyLoadInstance = new LazyLoad();
    $("img.lazy").each(function(){
        $(this).parent().css("background-color","rgba(154,158,160,.5)")
    });

</script>
@endsection
