@extends('layouts.frontend.layout')
@section('jshead')

<script type="text/javascript">
    function callbackThen(response){
        // read HTTP status
        console.log(response.status);
    
        // read Promise object
        response.json().then(function(data){
            console.log(data);
        });
    }
    function callbackCatch(error){
        console.error('Error:', error)
    }
</script>
 
{!! htmlScriptTagJsApi([
    'callback_then' => 'callbackThen',
    'callback_catch' => 'callbackCatch'
]) !!}

@endsection

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
            <div class="row main-contact">
                <div class="col-lg-4">
                    <div class="main-title">
                        <div class="subtitle">{!! $data['read']->fieldLang('name') !!}</div>
                        <h2 class="title">
                            {!! $data['read']->fieldLang('body') !!}
                        </h2>
                    </div>
                    <div class="contact-list">
                        <p>
                            <span class="fs-12 txt-grey">Address</span><br>
                            <b>
                             {{ $config['address'] }}
                            </b>
                        </p>
                        <p>
                            <span class="fs-12 txt-grey">Telephone</span><br>
                            Telp. <b><a href="#!">{{ $config['phone'] }}</a></b><br>
                            Fax. <b><a href="#!">{{ $config['fax'] }}</a></b>
                        </p>
                        <p>
                            <span class="fs-12 txt-grey">Email</span><br>
                            <b><a href="#!">{{ $config['email'] }}</a></b>
                        </p>
                        <p>
                            <div class="fs-12 txt-grey">Follow Us</div>
                            <div class="social-icon mt-10">
                                <a class="btn btn-icon btn-sm btn-custom-outline" href="{{ $config['facebook'] }}"><i class="fab fa-facebook-square"></i></a>
                                <a class="btn btn-icon btn-sm btn-custom-outline" href="{{ $config['twitter'] }}"><i class="fab fa-twitter"></i></a>
                                <a class="btn btn-icon btn-sm btn-custom-outline" href="{{ $config['instagram'] }}"><i class="fab fa-instagram"></i></a>
                                <a class="btn btn-icon btn-sm btn-custom-outline" href="{{ $config['youtube'] }}"><i class="fab fa-youtube"></i></a>
                                <a class="btn btn-icon btn-sm btn-custom-outline" href="{{ $config['whatsapp'] }}"><i class="fab fa-whatsapp"></i></a>
                            </div>
                        </p>
                    </div>
                </div>
                <div class="col-lg-6 offset-lg-2">
                    <div class="map">
                        <div id="map-canvas"></div>
                    </div>
                </div>
            </div>
            <div class="row no-gutter main-contact-form">
                <div class="col-lg-6 offset-lg-3">
                    <div class="main-title text-center">
                        <div class="subtitle">Contact Us</div>
                        <h2 class="title">Inquery Form</h2>
                    </div>

                    @if ($success = Session::get('success'))
                    <div class="alert alert-primary alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button> 
                        <strong>{{ $success }}</strong>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('inquiry.send',['id'=> $data['read']->id ]) }}">
                    @csrf
                        <div class="form-group">
                            <label class="form-label">Full Name</label>
                            <input class="form-control" name="name" placeholder="Your name">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email Address</label>
                            <input class="form-control" name="email" type="email" placeholder="Your email">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Subject</label>
                            <select class="form-control" name="subject" placeholder="Your name">
                                <option selected disabled>Choose subject</option>
                                <option>Subject 1</option>
                                <option>Subject 2</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Message</label>
                            <input class="form-control" name="message" placeholder="Your message">
                        </div>

                        {!! htmlFormSnippet() !!}

                        <div class="caption-btn text-right mt-30">
                            <button class="link-icon" type="submit">Sent Message<span><i class="fal fa-long-arrow-right"></i></span></button>
                        </div>
                    </form>
                </div>
                <div class="section-bg">
                    <img src="img/bg-news.jpg">
                </div>
            </div>
            
        </div>
        
    </section>

@endsection
@section('scripts')
<script src="{{ asset('assets/tmplts_frontend/js/maplace.js')}}"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBfeBwT8ZlyiYyOMHt-jpeVQWVtwEoS_UI"></script>
@endsection

@section('jsbody')
<script>
//maplace
$(document).ready(function () {
    var mapStyle = [{"featureType":"landscape.man_made","elementType":"geometry","stylers":[{"color":"#f7f1df"}]},{"featureType":"landscape.natural","elementType":"geometry","stylers":[{"color":"#d0e3b4"}]},{"featureType":"landscape.natural.terrain","elementType":"geometry","stylers":[{"visibility":"off"}]},{"featureType":"poi","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"poi.business","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"poi.medical","elementType":"geometry","stylers":[{"color":"#fbd3da"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#bde6ab"}]},{"featureType":"road","elementType":"geometry.stroke","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"labels","stylers":[{"visibility":"on"}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#ffe15f"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#efd151"}]},{"featureType":"road.arterial","elementType":"geometry.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"road.local","elementType":"geometry.fill","stylers":[{"color":"black"}]},{"featureType":"transit.station.airport","elementType":"geometry.fill","stylers":[{"color":"#cfb2db"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#a2daf2"}]}]
    var LocA = [{
        lat: {{ $data['read']->latitude }},
        lon: {{ $data['read']->longitude }},
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


