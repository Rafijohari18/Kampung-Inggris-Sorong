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
                    <div class="main-title text-center">
                        <h1 class="title">{!! $data['read']->fieldLang('name') !!}</h1>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="content-wrap">
        <div class="container">
            <div class="row justify-content-center">
                @foreach($menu['struktur']->post as $struktur)
                <div class="col-lg-4">
                    <div class="single-team">
                        <figure class="team-img box-img">
                            <div class="thumb">
                                <img class="lazy" data-src="{!! $struktur->coverSrc($struktur) !!}">
                            </div>
                        </figure>
                        <div class="team-info text-center">
                            <div class="position">{!! $struktur->profile->field['name'] !!}</div>
                            <h4 class="name">
                                {!! $struktur->profile->field['position'] !!}
                            </h4>
                        </div>
                    </div>
                </div>
                @endforeach
                
            </div>
        </div>
    </section>


@endsection