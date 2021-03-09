@extends('layouts.backend.layout')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/tmplts_backend/vendor/libs/bootstrap-tagsinput/bootstrap-tagsinput.css') }}">
<link rel="stylesheet" href="{{ asset('assets/tmplts_backend/fancybox/fancybox.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/tmplts_backend/vendor/css/pages/account.css') }}">
@endsection

@section('content')
<div class="card overflow-hidden">
    <div class="row no-gutters row-bordered row-border-light">
      <div class="col-md-3 pt-0">
        <div class="list-group list-group-flush account-settings-links">
          <a class="list-group-item list-group-item-action {{ (Request::get('tab') == 'upload') ? 'active' : '' }}" href="{{ route('configuration.web', ['tab' => 'upload']) }}" title="Images">Upload</a>
          <a class="list-group-item list-group-item-action {{ empty(Request::get('tab')) ? 'active' : '' }}" href="{{ route('configuration.web') }}" title="General">General</a>
          <a class="list-group-item list-group-item-action {{ (Request::get('tab') == 'meta-data') ? 'active' : '' }}" href="{{ route('configuration.web', ['tab' => 'meta-data']) }}"
                title="Meta data">Meta Data</a>
          <a class="list-group-item list-group-item-action {{ (Request::get('tab') == 'social-media') ? 'active' : '' }}" href="{{ route('configuration.web', ['tab' => 'social-media']) }}"
                title="Social media">Social Media</a>
        </div>
      </div>
      <div class="col-md-9">
        <div class="tab-content">
            <div class="tab-pane fade {{ (Request::get('tab') == 'upload') ? 'show active' : '' }}">
                @foreach ($data['upload'] as $upload)
                <div class="card-body media align-items-center">
                    @if ($upload->name != 'google_analytics_api')
                        @php

                        @endphp
                        @if (!empty($upload->value))
                        <a href="{{ $upload->path($upload->name, $upload->value) }}" data-fancybox="gallery" title="Click to view image">
                            <img src="{{ $upload->path($upload->name, $upload->value) }}" alt="" class="d-block ui-w-80">
                        </a>
                        @else
                        {{-- <img src="{{ asset(config('cms.images.file.default')) }}" alt="" class="d-block ui-w-80"> --}}
                        <i class="las la-image display-4 text-primary"></i>
                        @endif
                    @else
                    <i class="lab la-gripfire display-4 text-primary"></i>
                    @endif
                    <div class="media-body ml-4" title="Click to change image / file">
                        <form id="upload-{{ $upload->name }}" action="{{ route('configuration.web.upload', ['name' => $upload->name]) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <label class="btn btn-outline-primary">
                                {{ $upload->label }}
                                <input type="hidden" name="old_{{ $upload->name }}" value="{{ $upload->value }}">
                                <input type="file" id="{{ $upload->name }}" name="{{ $upload->name }}" class="account-settings-fileinput">
                            </label>
                        </form>

                        <div class="text-light small mt-1">
                            @if ($upload->name != 'google_analytics_api')
                            Allowed <strong>{{ strtoupper(config('custom.mimes.'.$upload->name.'.m')) }}</strong>.
                            <strong>{{ strtoupper(config('custom.mimes.'.$upload->name.'.p')) }}</strong> Pixel .
                            Max size of <strong>{{ ini_get('post_max_size') }}</strong>
                            @else
                            Allowed <strong>JSON</strong> Only.
                            @endif
                        </div>
                        @error($upload->name)
                        <div class="small mt-1" style="color:#d9534f;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                @endforeach
            </div>
            <div class="tab-pane fade {{ empty(Request::get('tab')) ? 'show active' : '' }}">
                <form action="{{ route('configuration.web.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                    @foreach ($data['general'] as $general)
                    <div class="form-group">
                        <label class="form-label">{{ $general->label }}</label>
                        <textarea class="form-control mb-1" name="name[{{ $general->name }}]" placeholder="Enter value...">{!! old($general->name, $general->value) !!}</textarea>
                    </div>
                    @endforeach
                    <hr>
                    <div class="text-left mt-3">
                        <button type="submit" class="btn btn-primary" title="Save changes">Save changes</button>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade {{ (Request::get('tab') == 'meta-data') ? 'show active' : '' }}">
                <form action="{{ route('configuration.web.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                    @foreach ($data['meta_data'] as $meta)
                    <div class="form-group">
                        <label class="form-label">{{ $meta->label }}</label>
                        <textarea class="form-control mb-1" name="name[{{ $meta->name }}]" placeholder="Enter value...">{!! old($meta->name, $meta->value) !!}</textarea>
                    </div>
                    @endforeach
                    <hr>
                    <div class="text-left mt-3">
                        <button type="submit" class="btn btn-primary" title="Save changes">Save changes</button>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade {{ (Request::get('tab') == 'social-media') ? 'show active' : '' }}">
                <form action="{{ route('configuration.web.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                    @foreach ($data['social_media'] as $socmed)
                    <div class="form-group">
                        <label class="form-label">{{ $socmed->label }}</label>
                        <textarea class="form-control mb-1" name="name[{{ $socmed->name }}]" placeholder="Enter value...">{!! old($socmed->name, $socmed->value) !!}</textarea>
                    </div>
                    @endforeach
                    <hr>
                    <div class="text-left mt-3">
                        <button type="submit" class="btn btn-primary" title="Save changes">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/tmplts_backend/vendor/libs/bootstrap-tagsinput/bootstrap-tagsinput.js') }}"></script>
<script src="{{ asset('assets/tmplts_backend/fancybox/fancybox.min.js') }}"></script>
@endsection

@section('jsbody')
<script src="{{ asset('assets/tmplts_backend/js/pages_account-settings.js') }}"></script>
<script>
    $('#logo').change(function() {
        $('#upload-logo').submit();
    });
    $('#logo_2').change(function() {
        $('#upload-logo_2').submit();
    });
    $('#logo_small').change(function() {
        $('#upload-logo_small').submit();
    });
    $('#logo_small_2').change(function() {
        $('#upload-logo_small_2').submit();
    });
    $('#logo_mail').change(function() {
        $('#upload-logo_mail').submit();
    });
    $('#open_graph').change(function() {
        $('#upload-open_graph').submit();
    });
    $('#banner_default').change(function() {
        $('#upload-banner_default').submit();
    });
    $('#google_analytics_api').change(function() {
        $('#upload-google_analytics_api').submit();
    });
</script>
@include('backend.components.toastr')
@endsection
