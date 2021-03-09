@extends('layouts.frontend.layout')


@section('content')
<section class="post-header">
        <div class="container">
            <div class="row no-gutter">
                <div class="col-lg-8 offset-lg-2">
                    <div class="post-categories text-center">
                        <a class="badge badge-blue" href="information-news.html">{{ $data['read']->category->fieldLang('name') }}</a>
                    </div>
                    <h1 class="post-title text-center">{!! $data['read']->fieldLang('title') !!}</h1>
                    @if (!empty($data['read']->cover['file_path']))
                    <figure class="post-img box-img">
                        <div class="thumb">
                            <img class="lazy" data-src="{{ $data['cover'] }}">
                        </div>
                    </figure>
                    @endif
                    <div class="post-info">
                        <div class="post-meta text-center">
                            <span><i class="fal fa-clock fs-16 txt-g_blue"></i>{{ $data['read']->created_at->format('M d, Y') }} </span>
                            <span><i class="fal fa-user fs-16 txt-g_blue"></i>by <a href="#!">{{ $data['read']->createBy->name }}</a></span>
                        </div>
                        <div class="social-icon">
                            <div class="fs-14 txt-dark fw-med mr-15">Share</div>
                            <a class="btn btn-sm btn-icon btn-custom-outline" href="{{ $data['share_facebook'] }}"><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-sm btn-icon btn-custom-outline" href="{{ $data['share_twitter'] }}"><i class="fab fa-twitter"></i></a>
                            <a class="btn btn-sm btn-icon btn-custom-outline" href="#!"><i class="fal fa-envelope"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="content-wrap post-detail">
        <div class="container">
            <div class="row no-gutter">
                <div class="col-lg-8 offset-lg-2">
                    <div class="post-entry">
                     {!! $data['read']->fieldLang('content') !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    @if ($data['latest_post']->count() > 0)
        <section class="content-wrap related-post">
            <div class="container">
                <div class="main-title">
                    <h2 class="title">Other {!! $data['read']->section->fieldLang('name') !!}</h2>
                </div>
                <div class="row">
                    @foreach ($data['latest_post'] as $latest)
                        <di class="col-lg-4">
                            <div class="blog-post">
                                <figure class="post-img box-img">
                                    <a href="{{ $latest->section->routes($latest->section_id, $latest->section->slug) }}">
                                        <div class="thumb">
                                            <img class="lazy" data-src="{!! $latest->coverSrc($latest) !!}">
                                            <div class="overlay"><span>Read</span></div>
                                        </div>
                                    </a>
                                </figure>
                                <div class="post-content">
                                    <div class="post-categories">
                                        <a class="badge badge-blue" href="information-news.html">{{ $latest->category->fieldLang('name') }}</a>
                                    </div>
                                    <h5 class="post-title"><a class="hover-style" href="{{ $latest->section->routes($latest->section_id, $latest->section->slug) }}">{{ $latest->fieldLang('title') }}?</a></h5>
                                    <div class="post-meta">
                                        <span>{{ $latest->created_at->format('M d, Y') }}</span>
                                        <span>by <a href="#!">{{ $latest->createBy->name }}</a></span>
                                    </div>
                                </div>
                            </div>
                        </di>
                    @endforeach
                </div>
                <div class="caption-btn text-center">
                    <a class="link-icon" href="information-news.html">More News<span><i class="fal fa-long-arrow-right"></i></span></a>
                </div>
            </div>
        </section>
    @endif
@endsection

