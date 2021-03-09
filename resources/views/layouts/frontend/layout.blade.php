@extends('layouts.frontend.application')

@section('layout-content')

    @include('layouts.frontend.includes.header')
    <div class="main-content" >
        @yield('content')
    </div>
    @include('layouts.frontend.includes.footer')

@endsection
