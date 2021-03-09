@extends('layouts.backend.layout')

@section('styles')
<script src="{{ asset('assets/tmplts_backend/admin.js') }}"></script>
<script src="{{ asset('assets/tmplts_backend/wysiwyg/tinymce.min.js') }}"></script>
<link rel="stylesheet" href="{{ asset('assets/tmplts_backend/fancybox/fancybox.min.css') }}">
@endsection

@section('content')

<div class="card mb-4">
    <h6 class="card-header">
      Form Video
    </h6>
    <div class="card-body">
        <form action="{{ route('gallery.playlist.video.store', ['playlistId' => $data['playlist']->id]) }}" method="POST" enctype="multipart/form-data">
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
                                <div class="form-group row" id="from-youtube">
                                    <label class="col-form-label col-sm-2 text-sm-right">From Youtube</label>
                                    <div class="col-sm-10">
                                        <label class="custom-control custom-checkbox m-0">
                                            <input type="checkbox" class="custom-control-input" name="from_youtube" value="1" {{ old('from_youtube') ? '' : '' }}>
                                            <span class="custom-control-label ml-4">YES</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group row" id="video">
                                    <div class="col-md-2 text-md-right">
                                        <label class="col-form-label text-sm-right">Video</label>
                                    </div>
                                    <div class="col-md-10">
                                        <label class="custom-file-label" for="file-0"></label>
                                        <input class="form-control custom-file-input file @error('file') is-invalid @enderror" type="file" id="file-0" lang="en" name="file" placeholder="enter file...">
                                        @include('backend.components.field-error', ['field' => 'file'])
                                        <span class="text-muted">Type of file : <strong>{{ config('custom.mimes.gallery_video.m') }}</strong></span>
                                    </div>
                                </div>
                                <div class="form-group row" id="thumbnail">
                                    <div class="col-md-2 text-md-right">
                                        <label class="col-form-label text-sm-right">Thumbnail</label>
                                    </div>
                                    <div class="col-md-10">
                                        <label class="custom-file-label" for="file-1"></label>
                                        <input class="form-control custom-file-input file @error('thumbnail') is-invalid @enderror" type="file" id="file-1" lang="en" name="thumbnail" placeholder="enter thumbnail...">
                                        @include('backend.components.field-error', ['field' => 'thumbnail'])
                                        <span class="text-muted">Type of file : <strong>{{ config('custom.mimes.gallery_video.mt') }}</strong></span>
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
                            </div>
                          </div>
                          @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="d-flex justify-content-center">
                <a href="{{ route('gallery.playlist.video', ['playlistId' => $data['playlist']->id]) }}" class="btn btn-secondary" title="Cancel">Cancel</a>&nbsp;&nbsp;
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
    $('#video').show();
    $('#thumbnail').show();
    $('#youtube-id').hide();
    $('input[type=checkbox][name=from_youtube]').change(function() {
        if (this.value == 1) {
            $('#video').toggle('slow');
            $('#thumbnail').toggle('slow');
            $('#youtube-id').toggle('slow');
        } else {
            $('#video').toggle('slow');
            $('#thumbnail').toggle('slow');
            $('#youtube-id').toggle('slow');
        }
    });
</script>

@include('backend.includes.tinymce-fm')
@include('backend.components.toastr')
@endsection
