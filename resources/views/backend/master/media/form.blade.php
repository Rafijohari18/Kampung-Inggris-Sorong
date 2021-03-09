@extends('layouts.backend.layout')

@section('content')
<div class="card mb-4">
    <h6 class="card-header">
      Form Media
    </h6>
    <div class="card-body">
      <form action="{{ !isset($data['media']) ? $data['routeStore'] : $data['routeUpdate'] }}"
        method="POST">
        @csrf
        @if (isset($data['media']))
            @method('PUT')
        @endif
        <div class="form-group row">
            <label class="col-form-label col-sm-2 text-sm-right">File</label>
            <div class="col-sm-10">
                <div class="input-group">
                    <input type="text" class="form-control @error('filename') is-invalid @enderror" id="image1" aria-label="Image" aria-describedby="button-image" name="filename"
                            value="{{ !isset($data['media']) ? old('filename') : old('filename', $data['media']->file_path['filename']) }}" placeholder="browse file..." readonly>
                    <div class="input-group-append" title="browse file">
                        <button class="btn btn-primary file-name" id="button-image" type="button"><i class="las la-file"></i></button>
                    </div>
                    @include('backend.components.field-error', ['field' => 'filename'])
                </div>
                <span class="text-muted"><em>* click icon file to browse file</em></span>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-sm-2 text-sm-right">Thumbnail</label>
            <div class="col-sm-10">
                <div class="input-group">
                    <input type="text" class="form-control" id="image2" aria-label="Image2" aria-describedby="button-image2" name="thumbnail"
                            value="{{ !isset($data['media']) ? old('thumbnail') : old('thumbnail', $data['media']->file_path['thumbnail']) }}" placeholder="browse thumbnail..." readonly>
                    <div class="input-group-append" title="browse thumbnail">
                        <button class="btn btn-primary file-name" id="button-image2" type="button"><i class="las la-image"></i></button>
                    </div>
                </div>
                <span class="text-muted"><em>* click icon image to browse thumbnail</em></span>
            </div>
        </div>
        <hr>
        <div class="form-group row">
            <label class="col-form-label col-sm-2 text-sm-right">Is Youtube</label>
            <div class="col-sm-10">
                <label class="custom-control custom-checkbox m-0">
                    <input type="checkbox" class="custom-control-input" name="is_youtube" value="1" {{ !isset($data['media']) ? (old('is_youtube') ? 'checked' : '') : (old('is_youtube', $data['media']->is_youtube) == 1 ? 'checked' : '') }}>
                    <span class="custom-control-label ml-4">YES (if media is not file)</span>
                </label>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-sm-2 text-sm-right">Youtube ID</label>
            <div class="col-sm-10">
                <input type="text" class="form-control mb-1 @error('youtube_id') is-invalid @enderror" name="youtube_id" value="{{ !isset($data['media']) ? old('youtube_id') : old('youtube_id', $data['media']->youtube_id) }}" placeholder="Enter youtube id...">
                @include('backend.components.field-error', ['field' => 'youtube_id'])
            </div>
        </div>
        <hr>
        <div class="form-group row">
            <label class="col-form-label col-sm-2 text-sm-right">Title</label>
            <div class="col-sm-10">
                <input type="text" class="form-control mb-1 @error('title') is-invalid @enderror" name="title" value="{{ !isset($data['media']) ? old('title') : old('title', $data['media']->caption['title']) }}" placeholder="Enter title...">
                @include('backend.components.field-error', ['field' => 'title'])
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-sm-2 text-sm-right">Description</label>
            <div class="col-sm-10">
                <textarea class="form-control tiny-mce" name="description">{!! !isset($data['media']) ? old('description') : old('description', $data['media']->caption['description']) !!}</textarea>
            </div>
        </div>
        <div class="d-flex justify-content-center">
            <a href="{{ $data['routeBack'] }}" class="btn btn-secondary" title="Cancel">Cancel</a>&nbsp;&nbsp;
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

@section('jsbody')
@include('backend.includes.button-fm')
@include('backend.components.toastr')
@endsection
