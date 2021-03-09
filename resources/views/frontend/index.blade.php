@extends('layouts.frontend.layout')

@section('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/tmplts_frontend/css/swiper.min.css') }}">
@endsection

@section('content')

@include('frontend.widget.banner')
@include('frontend.widget.facilities')
@include('frontend.widget.most-program')
@include('frontend.widget.our-program')
@include('frontend.widget.latest-news')


@endsection
@section('footer_add')
<section class="content-wrap bg-grey_l3 partner-footer">
        <div class="container">
            <div class="partner-logo swiper-container">
                <div class="swiper-wrapper">
                    @foreach($data['partner'] as $partner)
                    <div class="swiper-slide">
                        <a href="#!">
                            <img src="{!! $partner->coverSrc($partner) !!}">
                        </a>
                    </div>
                    @endforeach
                    
                </div>
                <div class="swiper-button-prev btn btn-icon btn-custom-outline"><i class="fal fa-long-arrow-left"></i></div>
                <div class="swiper-button-next btn btn-icon btn-custom-outline"><i class="fal fa-long-arrow-right"></i></div>
            </div>
        </div>
    </section>
    <section class="content-wrap contact-footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="main-title">
                        <i class="fal fa-phone-rotary"></i>
                        <h1 class="title"> @lang('common.footer_title_kontak')</h1>
                    </div>
                    <h5>
                        @lang('common.footer_description_kontak')
                    </h5>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <div class="contact-box">
                        <i class="fal fa-phone-office"></i>
                        <div class="flex-grow-1">
                            <h4>Telephone</h4>
                            Telp. <a href="#!">{{ $config['phone'] }}</a><br>
                            Fax. <a href="#!">{{ $config['fax'] }}</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="contact-box">
                        <i class="fal fa-house"></i>
                        <div class="flex-grow-1">
                            <h4>Address</h4>
                            <address>
                             {{ $config['address'] }}

                            </address>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="contact-box">
                        <i class="fal fa-envelope-open"></i>
                        <div class="flex-grow-1">
                            <h4>Email</h4>
                            <a href="#!">{{ $config['email'] }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection


@section('scripts')

<script src="{{ asset('assets/tmplts_frontend/js/swiper.min.js') }}"></script>
@endsection

@section('jsbody')
<script>

    //banner images
$(".banner-img").each(function(){
    var menu = ['Slide 1', 'Slide 2', 'Slide 3']
    var bannerImg = new Swiper($(this),{
        speed: 800,
        parallax: true,
        autoplay: {
            delay: 4000,
            disableOnInteraction: false,
        },
        pagination: {
            el: $(this).parent().find(".swiper-pagination"),
            clickable: true,
        },
        navigation: {
            nextEl: $(this).parents().eq(0).find(".swiper-button-next"),
            prevEl: $(this).parents().eq(0).find(".swiper-button-prev"),
        },
    });
});

$(".caption-media .caption-slide").each(function(){
    var facSlide = new Swiper($(this), {
        speed: 600,
        touchRatio: 0,
        simulateTouch: false,
        parallax: true,
        pagination: {
            el: $(this).parents().eq(2).find(".swiper-pagination"),
            type: "fraction",
            renderFraction: function (currentClass, totalClass) { 
                return '<span class="'+currentClass+'"></span><span class="divider"></span><span class="'+totalClass+'"></span>'
            },
        }
    });
    $(this).parents().eq(2).find(".swiper-button-prev").on('click', function(e){
        e.preventDefault();
        facSlide.slidePrev();
    });
    $(this).parents().eq(2).find(".swiper-button-next").on('click', function(e){
        e.preventDefault();
        facSlide.slideNext();
    });
});

//popuplar programs
$(".popuplar-programs").each(function(){
    var popularPrg = new Swiper($(this),{
        speed: 500,
        slidesPerView: "auto",
        spaceBetween: 15,
        navigation: {
            nextEl: $(this).parents().eq(1).find(".swiper-button-next"),
            prevEl: $(this).parents().eq(1).find(".swiper-button-prev"),
        },
        breakpoints:{
            991:{
                slidesPerView: 3,
                spaceBetween: 30
            }
        }
    });
});

//partner logo
$(".partner-logo").each(function(){
    var popularPrg = new Swiper($(this),{
        speed: 500,
        slidesPerView: "auto",
        spaceBetween: 30,
        navigation: {
            nextEl: $(this).parents().eq(1).find(".swiper-button-next"),
            prevEl: $(this).parents().eq(1).find(".swiper-button-prev"),
        },
        breakpoints:{
            991:{
                slidesPerView: 4,
                centeredSlide: true
            }
        }
    });
});



</script>
@endsection
