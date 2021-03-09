@extends('layouts.frontend.layout')

@section('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/tmplts_frontend/css/lightgallery.css') }}">
@endsection

@section('content')
<section class="page-header">
    <div class="thumb">
        <img class="parallax-header" src="{!! $data['read']->bannerSrc($data['read']) !!}">
        <div class="overlay"></div>
    </div>
    <div class="container">
        <div class="row no-gutter">
            <div class="col-lg-8 offset-lg-2">
                @include('frontend.components.breadcrumbs')
                <div class="main-title">
                    <h1 class="title">{!! $data['read']->fieldLang('name') !!}</h1>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="content-wrap section-content">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-3">
                <div class="sidebar sticky-top album-info">
                    <div class="album-info-content">
                        <div class="info">
                            <div class="fs-12 txt-grey info-label">Title</div>
                            <h5 class="album-title fs-18 m-0">{!! $data['read']->fieldLang('name') !!}</h5>
                        </div>
                        <div class="info">
                            <div class="fs-12 txt-grey info-label">Deskripsi</div>
                            <div class="excerpt">{!! !empty($data['read']->fieldLang('description')) ? strip_tags($data['read']->fieldLang('description')) : '-' !!}</div>
                        </div>
                        <div class="info">
                            <div class="fs-12 txt-grey info-label">Diposting</div>
                            <div class="txt-dark">{{ $data['read']->created_at->format('M d, Y') }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-md-9">
                <div class="row gallery">
                    @foreach ($data['video'] as $video)
                    <div class="col-lg-4 col-md-4 col-sm-6">
                        <div class="gallery-item gallery-video" data-poster="https://img.youtube.com/vi/{{ $video->youtube_id }}/hqdefault.jpg" data-src="https://www.youtube.com/watch?v={{ $video->youtube_id }}">
                            <div class="box-img">
                                <div class="thumb">
                                    <img class="lazy" data-src="https://img.youtube.com/vi/{{ $video->youtube_id }}/hqdefault.jpg">
                                </div>
                                <div class="overlay">
                                    <button class="btn btn-circle" type="button"><i class="fas fa-play"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @if ($data['video']->count() == 0)
                    <div class="text-center d-flex justify-content-center">
                        <h5 style="color: red;">!<em> <strong style="color: red;">@lang('common.menu_galeri_video') Kosong</strong> </em>!</h5>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script src="{{ asset('assets/tmplts_frontend/js/lightgallery-all.min.js') }}"></script>
<script src="{{ asset('assets/tmplts_frontend/js/lazyload.min.js') }}"></script>
@endsection

@section('jsbody')
<script>

    //gallery video
    $(".gallery").lightGallery({
            selector: ".gallery-item",
            zoom: false,
            download: false,
            share: false,
            fullScreen: false,
            autoplay: false,
            counter: false,
            thumbnail: false,
        });

    //lazy
    var lazyLoadInstance = new LazyLoad();
    $("img.lazy").each(function(){
        $(this).parent().css("background-color","rgba(154,158,160,.5)")
    });

</script>
@endsection
