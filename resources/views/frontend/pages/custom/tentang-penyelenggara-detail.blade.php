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
                                <nav class="tab-menu-h">
                                    <div class="tab-menu-scroll">
                                        <ul class="nav nav-tabs">
                                            @foreach ($data['read']->childPublish as $child)
                                            <li class="nav-item">
                                                <a class="nav-link {{ $loop->first ? 'active show' : '' }} child" href="#{{ $child->slug }}" data-toggle="tab" title="{!! $child->fieldLang('title') !!}" data-id="{{ $child->id }}">
                                                    {!! $child->fieldLang('title') !!}
                                                </a>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <button class="btn btn-dark btn-toggle-scroll btn-scroll-left btn-circle"><i class="far fa-arrow-left"></i></button>
                                    <button class="btn btn-dark btn-toggle-scroll btn-scroll-right btn-circle"><i class="far fa-arrow-right"></i></button>
                                </nav>
                                <script>
                                    var tabScroll = $(".tab-menu-scroll");
                                    var scrollLeftPrev = 0;
                                    $(".tab-menu-scroll").scroll(function () {
                                        var newScrollLeft = tabScroll.scrollLeft(),
                                            width=tabScroll.outerWidth(),
                                            scrollWidth=tabScroll.get(0).scrollWidth;
                                        if (scrollWidth-newScrollLeft==width) {
                                            $(".btn-scroll-right").hide();
                                            $(".btn-scroll-left").show();
                                        }
                                        if (newScrollLeft === 0) {
                                            $(".btn-scroll-right").show();
                                            $(".btn-scroll-left").hide();
                                        }
                                        console.log(tabScroll.width());
                                        scrollLeftPrev = newScrollLeft;
                                    });
                                    $(".btn-scroll-left").click(function () {
                                        var leftPos = tabScroll.scrollLeft();
                                        tabScroll.animate({
                                            scrollLeft: leftPos -80
                                        }, 200);
                                    });
                                    $(".btn-scroll-right").click(function () {
                                        var leftPos = tabScroll.scrollLeft();
                                        tabScroll.animate({
                                            scrollLeft: leftPos +80
                                        }, 200);
                                    });
                                    $(".nav-tabs a").click(function(){
                                        $(".nav-tabs a").removeClass("active");
                                        $(this).addClass("active");
                                        $(".tab-menu-scroll").scrollCenter("a.active",300);
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
                                @php
                                    $child = $data['read']->childPublish()->limit(1)->first();
                                @endphp
                                <input type="hidden" id="first" value="{{ $child->id }}">
                                <div class="post-entry" id="content">

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

    $('.child').on('click', function () {
        const id = $(this).data('id');
        let initElement = '';
        $.ajax({
            type : 'GET',
            url : '/json/page/child/'+id,
            success : function (data) {

                var coverCheck = data.cover_file;
                if (coverCheck == '') {
                    initElement += `
                        `+data.content+`
                    `;
                } else {
                    initElement += `
                        <img class="lazy" data-src="`+data.cover+`" title="`+data.cover_title+`" alt="`+data.cover_alt+`">
                        `+data.content+`
                    `;
                }

                $('#content').html(initElement);

                //lazy
                var lazyLoadInstance = new LazyLoad();
                $("img.lazy").each(function(){
                    $(this).parent(".thumb").css("background-color","rgba(154,158,160,.5)")
                });
            }
        });
    });

    $( document ).ready(function() {
        const id = $('#first').val();
        let initElement = '';
        $.ajax({
            type : 'GET',
            url : '/json/page/child/'+id,
            success : function (data) {

                var coverCheck = data.cover_file;
                if (coverCheck == '') {
                    initElement += `
                        `+data.content+`
                    `;
                } else {
                    initElement += `
                        <img class="lazy" data-src="`+data.cover+`" title="`+data.cover_title+`" alt="`+data.cover_alt+`">
                        `+data.content+`
                    `;
                }

                $('#content').html(initElement);

                //lazy
                var lazyLoadInstance = new LazyLoad();
                $("img.lazy").each(function(){
                    $(this).parent(".thumb").css("background-color","rgba(154,158,160,.5)")
                });
            }
        });
    });

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
