@extends('layouts.backend.application')

@section('layout-content')
<!-- Layout wrapper -->
<div class="layout-wrapper layout-2">
    <div class="layout-inner">

        <!-- Layout sidenav -->
        @include('layouts.backend.includes.layout-sidenav')

        <!-- Layout container -->
        <div class="layout-container">
            <!-- Layout navbar -->
            @include('layouts.backend.includes.layout-navbar')

            <!-- Layout content -->
            <div class="layout-content">

                <!-- Content -->
                <div class="container-fluid flex-grow-1 container-p-y">
                    @include('backend.components.breadcrumbs')
                    @yield('content')
                </div>
                <!-- / Content -->

                <!-- Layout footer -->
                @include('layouts.backend.includes.layout-footer')
            </div>
            <!-- Layout content -->

        </div>
        <!-- / Layout container -->

    </div>

    <!-- Overlay -->
    <div class="layout-overlay layout-sidenav-toggle"></div>
</div>
<!-- / Layout wrapper -->
@endsection
