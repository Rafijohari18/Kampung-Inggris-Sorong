@extends('layouts.frontend.layout')
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

<section class="content-wrap">
    <div class="container">
        <div class="row">
            @foreach ($data['posts'] as $post)
                <div class="col-lg-4">
                    <div class="single-product">
                        <figure class="product-img box-img">
                            <div class="thumb">
                                <img class="lazy" data-src="{!! $post->coverSrc($post) !!}">
                            </div>
                        </figure>
                        <div class="product-info">
                            <div class="d-flex align-items-center">
                                <a class="fs-12" href="program-english.html">{{ $post->category->fieldLang('name') }}</a>
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
                            <h5 class="product-title"><a class="hover-style" href="{{ $post->routes($post->id, $post->slug) }}">{{ $post->fieldLang('title') }}</a></h5>
                            <div class="product-price d-flex align-items-center">
                                <a class="btn btn-custom-outline btn-blue" href="{{ $post->routes($post->id, $post->slug) }}">Details</a>
                                <div class="price-label text-right ml-auto">
                                    <div class="fs-12 txt-grey">Start from</div>
                                    <div class="price txt-dark fw-sbold">
                                    {!! !empty($post->custom_field['start_from']) ?
                                $post->custom_field['start_from'] : 'Harga Belum Tersedia' !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="row">
            <div class="col-md-8 offset-md-2">
                {{ $data['posts']->onEachSide(1)->links('vendor.pagination.custom') }}   
            </div>
        </div>
    </div>
</section>

@endsection



