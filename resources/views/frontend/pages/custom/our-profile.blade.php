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
                    <h1 class="title">{!! $data['read']->childPublish()->first()->fieldLang('title') !!}</h1>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="main-about">
    <section class="content-wrap">
        <div class="container">
            <div class="row caption-media no-gutter">
                <div class="col-lg-6 caption-img img-tile"> 
                    @foreach($data['media'] as $key => $media)
                        @if($media->is_youtube == 0)
                            @if($key == 0)
                                <div class="box-img img-abs img-secondary">
                                    <div class="thumb">
                                        <img class="lazy" data-src="{!! Storage::url(config('custom.images.path.filemanager').$media->file_path['filename']) !!}">
                                    </div>
                                </div>
                            @elseif($key == 1)
                            <div class="box-img img-primary">
                                <div class="thumb">
                                    <img class="lazy" data-src="{!! Storage::url(config('custom.images.path.filemanager').$media->file_path['filename']) !!}">
                                </div>
                            </div>
                            @elseif($key == 2)
                            <div class="box-img img-abs img-thirdy">
                                <div class="thumb">
                                    <img class="lazy" data-src="{!! Storage::url(config('custom.images.path.filemanager').$media->file_path['filename']) !!}">
                                </div>
                            </div>
                            @endif
                        @endif
                    @endforeach
                
                </div>
                <div class="col-lg-5 offset-lg-1 caption-txt">
                    <div class="main-title">
                        <div class="subtitle">{!! $data['read']->fieldLang('title') !!}</div>
                        <h1 class="title">{!! $data['read']->fieldLang('intro') !!}</h1>
                    </div>
                    <div class="caption-txt">
                        {!! $data['read']->fieldLang('content') !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="content-wrap bg-dark">
        <div class="container">
            <div class="main-title text-center">
                <div class="subtitle">{!! $data['read']->childPublish()->where('id', 5)->first()->fieldLang('title') !!}</div>
                {!! $data['read']->childPublish()->where('id', 5)->first()->fieldLang('intro') !!}
            </div>
            <div class="tab-menu">
                <div class="tab-menu-scroll">
                    <ul class="nav nav-tabs tab-style-1">
                        

                        @foreach($data['read']->where('parent', 5)->get() as $history)
                            <li class="nav-item">
                                <a class="nav-link btn btn-custom-outline {{ $loop->first ? ' active' : '' }}" data-toggle="tab" href="#story-{!! $history->fieldLang('title') !!}">{!! $history->fieldLang('title') !!}</a>
                            </li>
                        @endforeach    
                    </ul>
                </div>
            </div>
            <div class="tab-content">
                @foreach($data['read']->where('parent', 5)->get() as $history)
                    <div class="tab-pane fade show {{ $loop->first ? ' active' : '' }}" id="story-{!! $history->fieldLang('title') !!}">
                        <div class="row caption-media no-gutter justify-content-between flex-row-reverse">
                            <div class="col-lg-6 caption-img">
                                <div class="box-img">
                                    <div class="thumb">
                                        <img class="lazy" data-src="{{ $history->coverSrc($history)}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5 caption-txt">
                                <div class="main-title">
                                    <div class="subtitle">{!! strip_tags($data['read']->childPublish()->where('id', 5)->first()->fieldLang('intro')) !!}</div>
                                    
                                        {!! $history->fieldLang('intro') !!}
                                   
                                </div>
                                <div class="caption-txt txt-grey">
                                    {!! $history->fieldLang('content') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach  

            </div>
        </div>
    </section>
    <section class="content-wrap">
        <div class="container">
            <div class="main-title text-center">
                <div class="subtitle">About Us</div>
                <h1 class="title">
                 {!! $data['read']->childPublish()->where('id', 11)->first()->fieldLang('title') !!}
                </h1>
            </div>
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="post-entry">
                        {!! $data['read']->childPublish()->where('id', 11)->first()->fieldLang('content') !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection