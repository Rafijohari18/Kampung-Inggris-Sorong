@extends('layouts.frontend.layout')
@section('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/tmplts_frontend/css/lightgallery.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/tmplts_frontend/css/swiper.min.css')}}">

@endsection

@section('content')

    <section class="content-wrap product-header">
        <div class="container">
            <div class="row no-gutter">
                <div class="col-lg-5 img-wrapper">
                    <div class="product-img product-img-slide gallery swiper-container">
                        <div class="swiper-wrapper">
                            
                            @if($data['media']->count() > 0)
                            
                                @foreach($data['media'] as $media)
                                    @if($media->is_youtube == 1)
                                    <div class="swiper-slide gallery-item gallery-video" data-poster="https://img.youtube.com/vi/{{ $media->youtube_id }}/mqdefault.jpg" data-src="https://www.youtube.com/watch?v={{ $media->youtube_id}}">
                                        <div class="thumb" data-swiper-parallax="50%">
                                            <img class="lazy" data-src="https://img.youtube.com/vi/{{ $media->youtube_id }}/mqdefault.jpg">
                                        </div>
                                        <div class="overlay">
                                            <button class="btn btn-lg btn-custom btn-blue btn-icon" type="button"><i class="fas fa-play"></i></button>
                                        </div>
                                    </div>

                                    @else
                                    
                                    <div class="swiper-slide gallery-item" data-src="{!! Storage::url(config('custom.images.path.filemanager').$media->file_path['filename']) !!}">
                                        <div class="thumb" data-swiper-parallax="50%">
                                            <img class="lazy" data-src="{!! Storage::url(config('custom.images.path.filemanager').$media->file_path['filename']) !!}">
                                        </div>
                                        <div class="overlay">
                                            <button class="btn btn-lg btn-white btn-icon" type="button"><i class="fal fa-search"></i></button>
                                        </div>
                                    </div>
                                    @endif

                                @endforeach
                            

                            @endif
                            
                        </div>
                        <div class="swiper-button-wrapper">
                            <div class="swiper-button-prev btn btn-sm btn-icon btn-white"><i class="fal fa-long-arrow-left"></i></div>
                            <div class="swiper-button-next btn btn-sm btn-icon btn-white"><i class="fal fa-long-arrow-right"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7 txt-wrapper">
                    <div class="product-info">
                        <div class="d-flex align-items-center">
                            <a href="program-english.html" class="fs-12">{{ $data['read']->category->fieldLang('name') }}</a>
                            <div class="rating-icon ml-auto">
                                <div class="rating-content">
                                    <div class="rating rated"></div>
                                    <div class="rating rated"></div>
                                    <div class="rating rated"></div>
                                    <div class="rating rated"></div>
                                    <div class="rating"></div>
                                </div>
                                <div class="rating-score">5.0</div>
                            </div>
                        </div>
                        <h1 class="product-title">{{ $data['read']->fieldLang('title') }}</h1>
                        <div class="product-price d-flex align-items-center">
                            @if($data['read']->files->count() > 0)
                                @foreach($data['read']->files as $files)
                                    <a class="btn btn-custom-outline btn-blue" href="{{ $files->filePath($files) }}">
                                        <i class="icon-left fal fa-arrow-to-bottom"></i>Brochure
                                    </a>
                                @endforeach
                            @endif
                            
                            <div class="price-label text-right ml-auto">
                                <div class="fs-12 txt-grey">Start from</div>
                                <div class="price txt-dark fw-sbold">
                                {!! !empty($data['read']->custom_field['start_from']) ?
                                $data['read']->custom_field['start_from'] : 'Harga Belum Tersedia' !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="content-wrap product-detail">
        <div class="container">
            <div class="tab-menu">
                <div class="tab-menu-scroll">
                    <ul class="nav nav-tabs">
                        <li class="nav-item"><a class="nav-link btn btn-custom active" data-toggle="tab" href="#program-detail">Program Detail</a></li>
                        <li class="nav-item"><a class="nav-link btn btn-custom" data-toggle="tab" href="#program-desc">Description</a></li>
                        <li class="nav-item"><a class="nav-link btn btn-custom" data-toggle="tab" href="#program-rec">Latest Post</a></li>
                    </ul>
                </div>
            </div>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="program-detail">
                    <div class="post-entry">
                        {!! $data['read']->fieldLang('intro') !!}
                    </div>
                </div>
                <div class="tab-pane fade" id="program-desc">
                    <div class="post-entry">
                        {!! $data['read']->fieldLang('content') !!}
                    </div>
                </div>
                <div class="tab-pane fade" id="program-rec">
                    <div class="row">
                        @if($data['latest_post']->count() > 0)
                            @foreach($data['latest_post'] as $latest)
                            <div class="col-lg-4">
                                <div class="single-product">
                                    <figure class="product-img box-img">
                                        <div class="thumb">
                                            <img class="lazy" data-src="{!! $latest->coverSrc($latest) !!}">
                                        </div>
                                    </figure>
                                    <div class="product-info">
                                        <div class="d-flex align-items-center">
                                            <a class="fs-12" href="program-english.html">{{ $latest->category->fieldLang('name') }}</a>
                                            <div class="rating-icon ml-auto">
                                                <div class="rating-content">
                                                    <div class="rating rated"></div>
                                                    <div class="rating rated"></div>
                                                    <div class="rating rated"></div>
                                                    <div class="rating rated"></div>
                                                    <div class="rating"></div>
                                                </div>
                                                <div class="rating-score">4.0</div>
                                            </div>
                                        </div>
                                        <h5 class="product-title"><a class="hover-style" href="product-detail.html">{{ $latest->fieldLang('title') }}</a></h5>
                                        <div class="product-price d-flex align-items-center">
                                            <a class="btn btn-custom-outline btn-blue" href="product-detail.html">Details</a>
                                            <div class="price-label text-right ml-auto">
                                                <div class="fs-12 txt-grey">Start from</div>
                                                <div class="price txt-dark fw-sbold">
                                                {!! !empty($latest->custom_field['start_from']) ?
                                $latest->custom_field['start_from'] : 'Harga Belum Tersedia' !!}
                                               </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="caption-btn text-center">
                        <a class="link-icon" href="{{ $menu['package']->routes($menu['package']->id, $menu['package']->slug) }}">More Programs<span><i class="fal fa-long-arrow-right"></i></span></a>
                    </div>
                </div>
            </div>
        </div>
    </section>





@endsection


@section('scripts')
<script src="{{ asset('assets/tmplts_frontend/js/swiper.min.js') }}"></script>
<script src="{{ asset('assets/tmplts_frontend/js/lightgallery-all.min.js') }}"></script>
@endsection

@section('jsbody')
<script>
//product image slide
$(".product-img-slide").each(function(){
    var prdImg = new Swiper($(this),{
        speed: 500,
        parallax: true,
        autoplay: {
            delay: 4000,
            disableOnInteraction: false,
        },
        pagination: {
            el: $(this).parents().eq(1).find(".swiper-pagination"),
        },
        navigation: {
            nextEl: $(this).parents().eq(1).find(".swiper-button-next"),
            prevEl: $(this).parents().eq(1).find(".swiper-button-prev"),
        },
    });
});

//gallery
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

</script>
@endsection
