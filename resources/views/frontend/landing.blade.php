@extends('layouts.frontend.application')

@section('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/tmplts_frontend/css/swiper.min.css') }}">
@endsection

@section('layout-content')
<section class="main-content">

</section>
@endsection

@section('scripts')
<script src="{{ asset('assets/tmplts_frontend/js/swiper.min.js') }}"></script>
@endsection

@section('jsbody')
<script>

    // storage
    var retrievedObject = localStorage.getItem('modal_notif');
    if (retrievedObject != 1) {
        localStorage.setItem('modal_notif', 1);
        $('#modal-notif').modal('show');
    } else {
        $('#modal-notif').modal('hide');
    }

    //banner slide
    var bannerSlide = new Swiper($(".banner-slide"),{
        speed: 600,
        effect: "fade",
        simulateTouch: false,
        touchRatio: 0,
        autoplay: {
            delay: 2000,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.swiper-pagination',
        },
    });

</script>
@endsection
