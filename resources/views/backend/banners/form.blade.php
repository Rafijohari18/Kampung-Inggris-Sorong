@extends('layouts.backend.layout')

@section('styles')
<script src="{{ asset('assets/tmplts_backend/admin.js') }}"></script>
<script src="{{ asset('assets/tmplts_backend/wysiwyg/tinymce.min.js') }}"></script>
<link rel="stylesheet" href="{{ asset('assets/tmplts_backend/fancybox/fancybox.min.css') }}">
@endsection

@section('content')

<div class="card mb-4">
    <h6 class="card-header">
      Form Banner
    </h6>
    <div class="card-body">
        <form action="{{ route('banner.store', ['categoryId' => $data['banner_category']->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="tab-content">
                <div class="tab-pane fade show active" id="content">
                    <div class="nav-tabs-left mb-4">
                        <ul class="nav nav-tabs">
                          @foreach ($data['languages'] as $lang)
                          <li class="nav-item">
                            <a class="nav-link {{ ($loop->first) ? 'active' : '' }}" data-toggle="tab" href="#{{ $lang->iso_codes }}">{{ $lang->country }}</a>
                          </li>
                          @endforeach
                        </ul>
                        <div class="tab-content">
                          @foreach ($data['languages'] as $lang)
                          <div class="tab-pane fade {{ ($loop->first) ? 'active show' : '' }}" id="{{ $lang->iso_codes }}">
                            <div class="card-body pb-2">
                                @if ($lang->iso_codes == app()->getLocale())
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-2 text-sm-right">Is Video</label>
                                    <div class="col-sm-10">
                                        <label class="custom-control custom-checkbox m-0">
                                            <input type="checkbox" class="custom-control-input" name="is_video" value="1" {{ old('is_video') ? '' : '' }}>
                                            <span class="custom-control-label ml-4">YES</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group row" id="from-youtube">
                                    <label class="col-form-label col-sm-2 text-sm-right">From Youtube</label>
                                    <div class="col-sm-10">
                                        <label class="custom-control custom-checkbox m-0">
                                            <input type="checkbox" class="custom-control-input" name="from_youtube" value="1" {{ old('from_youtube') ? '' : '' }}>
                                            <span class="custom-control-label ml-4">YES</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group row" id="file">
                                    <div class="col-md-2 text-md-right">
                                        <label class="col-form-label text-sm-right">File</label>
                                    </div>
                                    <div class="col-md-10">
                                        <label class="custom-file-label" for="file-0"></label>
                                        <input class="form-control custom-file-input file @error('file') is-invalid @enderror" type="file" id="file-0" lang="en" name="file" placeholder="enter file...">
                                        @include('backend.components.field-error', ['field' => 'file'])
                                        <span class="text-muted">Type of file : <strong>{{ config('custom.mimes.banner.m').','.config('custom.mimes.banner.mv') }}</strong>, Pixel : <strong>{{ config('custom.mimes.banner.p') }}</strong></span>
                                    </div>
                                </div>
                                <div class="form-group row" id="youtube-id">
                                    <label class="col-form-label col-sm-2 text-sm-right">Youtube ID</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control mb-1 @error('youtube_id') is-invalid @enderror" name="youtube_id" value="{{ old('youtube_id') }}" placeholder="Enter youtube id...">
                                        @include('backend.components.field-error', ['field' => 'youtube_id'])
                                    </div>
                                </div>
                                @endif
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-2 text-sm-right">Title</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control mb-1 gen_slug @error('title_'.$lang->iso_codes) is-invalid @enderror"
                                            lang="{{ $lang->iso_codes }}" name="title_{{ $lang->iso_codes }}" value="{{ old('title_'.$lang->iso_codes) }}" placeholder="Enter title...">
                                        @include('backend.components.field-error', ['field' => 'title_'.$lang->iso_codes])
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-2 text-sm-right">Description</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control tiny-mce" name="description_{{ $lang->iso_codes }}">{!! old('description_'.$lang->iso_codes) !!}</textarea>
                                    </div>
                                </div>
                                @if ($lang->iso_codes == app()->getLocale())
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-2 text-sm-right">ALT</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control mb-1 @error('file_alt') is-invalid @enderror" name="file_alt" value="{{ old('file_alt') }}" placeholder="Enter file alt...">
                                        @include('backend.components.field-error', ['field' => 'file_alt'])
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-2 text-sm-right">URL</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control mb-1 @error('url') is-invalid @enderror" name="url" value="{{ old('url') }}" placeholder="Enter url...">
                                        @include('backend.components.field-error', ['field' => 'url'])
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-2 text-sm-right">Status</label>
                                    <div class="col-sm-10">
                                        <select class="custom-select" name="publish" data-style="btn-default">
                                            @foreach (config('custom.label.publish') as $key => $publish)
                                                <option value="{{ $key }}" {{ (old('publish') == ''.$key.'') ? 'selected' : '' }}>
                                                    {{ $publish['title'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @endif
                            </div>
                          </div>
                          @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="d-flex justify-content-center">
                <a href="{{ route('banner.index', ['categoryId' => $data['banner_category']->id]) }}" class="btn btn-secondary" title="Cancel">Cancel</a>&nbsp;&nbsp;
                <button type="submit" class="btn btn-primary" name="action" value="back" title="Save">
                    Save
                </button>&nbsp;&nbsp;
                <button type="submit" class="btn btn-danger" name="action" value="exit" title="Save & Exit">
                    Save & Exit
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/tmplts_backend/fancybox/fancybox.min.js') }}"></script>
@endsection

@section('jsbody')
<script>
    $('#from-youtube').hide();
    $('#file').show();
    $('#youtube-id').hide();
    $('input[type=checkbox][name=is_video]').change(function() {
        if (this.value == 1) {
            $('#from-youtube').toggle('slow');
        } else {
            $('#from-youtube').hide();
        }
    });
    $('input[type=checkbox][name=from_youtube]').change(function() {
        if (this.value == 1) {
            $('#file').toggle('slow');
            $('#youtube-id').toggle('slow');
        } else {
            $('#file').toggle('slow');
            $('#youtube-id').toggle('slow');
        }
    });
</script>

@include('backend.includes.button-fm')
@include('backend.includes.tinymce-fm')
@include('backend.components.toastr')
@endsection
