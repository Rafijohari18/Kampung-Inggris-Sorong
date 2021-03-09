@extends('layouts.frontend.layout')

@section('content')
<section class="page-header">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 offset-lg-3 col-md-8 offset-md-2">
                @include('frontend.components.breadcrumbs')
                <div class="main-title text-center">
                    <h1 class="title">{!! !empty($data['read']->fieldLang('intro')) ? strip_tags($data['read']->fieldLang('intro')) : $data['read']->fieldLang('title') !!}</h1>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="content-wrap section-content">
    <div class="container">
        <div class="text-center">
            {!! $data['read']->fieldLang('content') !!}
        </div>
        <div class="row page-menu">
            @foreach ($data['read']->childPublish as $child)
            <div class="col-lg-3 col-md-4 col-sm-6">
                <a class="page-menu-link" href="{{ $child->routes($child->id, $child->slug) }}" title="{!! $child->fieldLang('title') !!}">
                    @php
                        if (!empty($child->custom_field['icon'])) {
                            $icon = $child->custom_field['icon'];
                        } else {
                            if (!empty($data['read']->custom_field['icon'])) {
                                $icon = $data['read']->custom_field['icon'];
                            } else {
                                $icon = 'far fa-bars';
                            }
                        }
                    @endphp
                    <i class="fs-24 txt-orange {{ $icon }}"></i>
                    <h5><span class="hover-effect-1 line-orange">{!! !empty($child->fieldLang('intro')) ? strip_tags($child->fieldLang('intro')) : $child->fieldLang('title') !!}</span></h5>
                </a>
            </div>
            @endforeach
            @if (Request::segment(4) == $menu['ppid']->slug)
            @foreach ($menu['ppid_link']->media()->orderBy('position', 'ASC')->get() as $ppidLink)
            <div class="col-lg-3 col-md-4 col-sm-6">
                <a class="page-menu-link" href="{{ $ppidLink->url }}" target="_blank" title="{!! $ppidLink->fieldLang('title') !!}">
                    <i class="fs-24 txt-orange {{ !empty($ppidLink->custom_field['icon']) ? $ppidLink->custom_field['icon'] : 'far fa-bars' }}"></i>
                    <h5><span class="hover-effect-1 line-orange">{!! $ppidLink->fieldLang('title') !!}</span></h5>
                </a>
            </div>
            @endforeach
            <div class="col-lg-3 col-md-4 col-sm-6">
                <a class="page-menu-link" href="{{ $menu['informasi']->routes($menu['informasi']->id, $menu['informasi']->slug) }}">
                    <i class="fs-24 txt-orange far fa-scroll"></i>
                    <h5><span class="hover-effect-1 line-orange">{!! $menu['informasi']->fieldLang('name') !!}</span></h5>
                </a>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <a class="page-menu-link" href="{{ $menu['kontak']->routes($menu['kontak']->slug) }}">
                    <i class="fs-24 txt-orange far fa-phone-rotary"></i>
                    <h5><span class="hover-effect-1 line-orange">{!! $menu['kontak']->fieldLang('name') !!}</span></h5>
                </a>
            </div>
            @endif
        </div>
    </div>
</section>
@endsection
