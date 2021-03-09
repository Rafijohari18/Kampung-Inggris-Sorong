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
        <div class="text-center">
            {!! $data['read']->fieldLang('description') !!}
        </div>
        <div class="row page-menu">
            @foreach ($data['media'] as $media)
            <div class="col-lg-3 col-md-4 col-sm-6">
                <a class="page-menu-link" href="{{ $media->url }}" target="_blank" title="{!! $media->fieldLang('title') !!}">
                    <i class="fs-24 txt-orange {{ !empty($data['read']->custom_field['icon']) ? $data['read']->custom_field['icon'] : 'far fa-bars' }}"></i>
                    <h5><span class="hover-effect-1 line-orange">{!! $media->fieldLang('title') !!}</span></h5>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
