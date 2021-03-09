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
                <di class="col-lg-4">
                    <div class="blog-post">
                        <figure class="post-img box-img">
                            <a href="{{ $post->routes($post->id, $post->slug) }}">
                                <div class="thumb">
                                    <img class="lazy" data-src="{{ $post->coverSrc($post) }}">
                                    <div class="overlay"><span>Read</span></div>
                                </div>
                            </a>
                        </figure>
                        <div class="post-content">
                            <div class="post-categories">
                                <a class="badge badge-blue" href="information-news.html">{{ $post->category->fieldLang('name') }}</a>
                            </div>
                            <h5 class="post-title"><a class="hover-style" href="{{ $post->routes($post->id, $post->slug) }}">{!! $post->fieldLang('title') !!}</a></h5>
                            <div class="post-meta">
                                <span>{{ $post->created_at->format('M d, Y') }}</span>
                                <span>by <a href="#!">{{ $post->createBy->name }}</a></span>
                            </div>
                        </div>
                    </div>
                </di>
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
