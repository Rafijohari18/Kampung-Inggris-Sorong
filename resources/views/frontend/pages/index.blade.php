@extends('layouts.frontend.layout')

@section('content')
<section class="content-wrap">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-8 section-left">
                <div class="page-content sticky-top">
                    @include('frontend.components.breadcrumbs')
                    <div class="blog-post blog-6">
                        <div class="post-content">
                            <h1 class="post-title">{!! !empty($data['read']->fieldLang('intro')) ? strip_tags($data['read']->fieldLang('intro')) : $data['read']->fieldLang('title') !!}</h1>
                            <div class="single-post">
                                <div class="post-entry">
                                    @if (!empty($data['read']->cover['file_path']))
                                    <img class="lazy" data-src="{{ $data['cover'] }}" title="{{ $data['read']->cover['title'] }}" alt="{{ $data['read']->cover['alt'] }}">
                                    @endif
                                    {!! $data['read']->fieldLang('content') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('frontend.includes.widget')
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
        $(this).parent(".thumb").css("background-color","rgba(154,158,160,.5)")
    });

</script>
@endsection
