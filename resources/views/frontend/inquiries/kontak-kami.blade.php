@extends('layouts.frontend.layout')

@section('content')
<section class="page-header">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 offset-lg-3 col-md-8 offset-md-2">
                @include('frontend.components.breadcrumbs')
                <div class="main-title text-center">
                    <h1 class="title">{!! $data['read']->fieldLang('name') !!}</h1>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="content-wrap section-content">
    <div class="container">
        <div class="row main-contact">
            <div class="col-lg-6 col-md-6">
                <div id="map-canvas"></div>
            </div>
            <div class="col-lg-4 offset-lg-1 col-md-4 offset-md-1">
                <div class="main-title">
                    {!! $data['read']->fieldLang('body') !!}
                </div>
                <div class="contact-list">
                    <p>
                        <span class="fs-12 txt-grey">Alamat.</span><br>
                        <b>{!! $config['address'] !!}</b>
                    </p>
                    <p>
                        <span class="fs-12 txt-grey">No Telp.</span><br>
                        Telp. <b><a href="tel:{{ $config['phone'] }}">{{ $config['phone'] }}</a></b><br>
                        Fax. <b><a href="tel:{{ $config['fax'] }}">{{ $config['fax'] }}</a></b>
                    </p>
                    <p>
                        <span class="fs-12 txt-grey">Email</span><br>
                        <b><a href="mailto:{{ $config['email'] }}">{{ $config['email'] }}</a></b>
                    </p>
                    <p>
                        <div class="fs-12 txt-grey">@lang('common.text_sosmed')</div>
                        <div class="social-share mt-10">
                            <a href="{!! $config['facebook'] !!}" target="_blank" title="Facebook {!! $config['meta_title'] !!}"><i class="fab fa-facebook-square"></i></a>
                            <a href="{!! $config['twitter'] !!}" target="_blank" title="Twitter {!! $config['meta_title'] !!}"><i class="fab fa-twitter"></i></a>
                            <a href="{!! $config['instagram'] !!}" target="_blank" title="Instagram {!! $config['meta_title'] !!}"><i class="fab fa-instagram"></i></a>
                            <a href="{!! $config['youtube'] !!}" target="_blank" title="Youtube {!! $config['meta_title'] !!}"><i class="fab fa-youtube"></i></a>
                            <a href="{!! $config['whatsapp'] !!}" target="_blank" title="WhatsApp {!! $config['meta_title'] !!}"><i class="fab fa-whatsapp"></i></a>
                        </div>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script src="{{ asset('assets/tmplts_frontend/js/maplace.js') }}"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBfeBwT8ZlyiYyOMHt-jpeVQWVtwEoS_UI"></script>
@endsection

@section('jsbody')
<script>

    //maplace
    $(document).ready(function () {
        var mapStyle = [{"featureType":"landscape.man_made","elementType":"geometry","stylers":[{"color":"#f7f1df"}]},{"featureType":"landscape.natural","elementType":"geometry","stylers":[{"color":"#d0e3b4"}]},{"featureType":"landscape.natural.terrain","elementType":"geometry","stylers":[{"visibility":"off"}]},{"featureType":"poi","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"poi.business","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"poi.medical","elementType":"geometry","stylers":[{"color":"#fbd3da"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#bde6ab"}]},{"featureType":"road","elementType":"geometry.stroke","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"labels","stylers":[{"visibility":"on"}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#ffe15f"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#efd151"}]},{"featureType":"road.arterial","elementType":"geometry.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"road.local","elementType":"geometry.fill","stylers":[{"color":"black"}]},{"featureType":"transit.station.airport","elementType":"geometry.fill","stylers":[{"color":"#cfb2db"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#a2daf2"}]}]
        var LocA = [{
            lat: '{{ $data['read']->latitude }}',
            lon: '{{ $data['read']->longitude }}',
            //icon: 'http://www.mqorganik.com/assets/frontend/images/marker-icon.svg',
            show_infowindow: false,
        }]
        new Maplace({
            locations: LocA,
            map_div: "#map-canvas",
            controls_on_map: false,
            map_options: {
                scrollwheel: false,
                styles: mapStyle,
                //draggable: false,
                zoom: 14
            }
        }).Load();
    });

</script>
@endsection
