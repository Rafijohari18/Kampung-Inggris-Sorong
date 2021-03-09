@extends('layouts.frontend.layout')

@section('content')
<section class="page-header">
    <div class="thumb">
        <img class="parallax-header" src="{!! $data['section_photo']->bannerSrc($data['section_photo']) !!}">
        <div class="overlay"></div>
    </div>
    <div class="container">
        <div class="row no-gutter">
            <div class="col-lg-8 offset-lg-2">
                @include('frontend.components.breadcrumbs')
                <div class="main-title">
                    <h1 class="title">Gallery Photo</h1>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="content-wrap">
    <div class="container">
        <div class="row">
        @foreach ($data['album'] as $album)
            <di class="col-lg-4">
                <div class="blog-post">
                    <figure class="post-img box-img">
                        <a href="{{ $album->routes($album->id) }}">
                            <div class="thumb">
                                <img class="lazy" data-src="{{ $album->photoCover($album->id) }}">
                                <div class="overlay"><span>Photo</span></div>
                            </div>
                        </a>
                    </figure>
                    <div class="post-content">
                        <div class="post-categories">
                            <span class="badge badge-blue">{{ $album->photo->count() }} photos</span>
                        </div>
                        <h5 class="post-title"><a class="hover-style" href="{{ $album->routes($album->id) }}">Album Title Photo</a></h5>
                        <div class="post-meta">
                            <span>{{ $album->created_at->format('M d, Y') }}</span>
                            <span>by <a href="#!">{{ $album->createBy->name }}</a></span>
                        </div>
                    </div>
                </div>
            </di>
        @endforeach
        @if ($data['album']->total() == 0)
        <div class="text-center d-flex justify-content-center">
            <h5 style="color: red;">!<em> <strong style="color: red;">@lang('common.menu_galeri_foto') Kosong</strong> </em>!</h5>
        </div>
        @endif
            
        </div>
        <div class="row">
            <div class="col-md-8 offset-md-2">
                {{ $data['album']->onEachSide(1)->links('vendor.pagination.custom') }}  
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
