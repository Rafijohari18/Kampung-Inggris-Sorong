@extends('layouts.frontend.layout')

@section('title', '| 404 Not Found')

@section('content')
<section class="page-header">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 offset-lg-3 col-md-8 offset-md-2">
                <ul class="breadcrumb">
                    <li><a class="link" href="{{ route('home') }}" title="@lang('common.menu_beranda')">@lang('common.menu_beranda')</a></li>
                    <li>404 Not Found</li>
                </ul>
                <div class="main-title text-center">
                    <h1 class="title">@lang('common.error_404_title')</h1>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="content-wrap section-content">
    <div class="container">
        <div class="single-post">
            <div class="post-entry text-center">
                @lang('common.error_404_description')
            </div>
        </div>
    </div>
</section>
@endsection
