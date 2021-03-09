@extends('layouts.backend.layout')

@section('styles')
<script src="{{ asset('assets/tmplts_backend/wysiwyg/tinymce.min.js') }}"></script>
<link rel="stylesheet" href="{{ asset('vendor/file-manager/css/file-manager.css') }}">
@endsection

@section('content')
<div class="card mb-4">
    <h6 class="card-header">
      Language Form
    </h6>
    <div class="card-body">
      <form action="{{ !isset($data['language']) ? route('language.store') : route('language.update', ['id' => $data['language']->id]) }}" method="POST">
        @csrf
        @if (isset($data['language']))
            @method('PUT')
        @endif
        <div class="form-group row">
            <label class="col-form-label col-sm-2 text-sm-right">ISO CODE</label>
            <div class="col-sm-10">
              <input type="text" class="form-control @error('iso_codes') is-invalid @enderror" name="iso_codes"
                    value="{{ (isset($data['language'])) ? old('iso_codes', $data['language']->iso_codes) : old('iso_codes') }}"
                    placeholder="Sugested lower case" {{ (isset($data['language'])) ? (($data['language']->iso_codes == 'id' || $data['language']->iso_codes == 'en') ? 'readonly' : '') : '' }}>
              @include('backend.components.field-error', ['field' => 'iso_codes'])
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-sm-2 text-sm-right">Country</label>
            <div class="col-sm-10">
              <input type="text" class="form-control @error('country') is-invalid @enderror" name="country"
                    value="{{ (isset($data['language'])) ? old('country', $data['language']->country) : old('country') }}" placeholder="enter country...">
              @include('backend.components.field-error', ['field' => 'country'])
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-2 text-md-right">
              <label class="col-form-label text-sm-right">Description</label>
            </div>
            <div class="col-md-10">
                <textarea class="form-control tiny-mce @error('description') is-invalid @enderror" name="description" placeholder="enter description...">{!! (isset($data['language'])) ? old('description', $data['language']->description) : old('description') !!}</textarea>
                @include('backend.components.field-error', ['field' => 'description'])
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-sm-2 text-sm-right">Time Zone</label>
            <div class="col-sm-10">
              <input type="text" class="form-control @error('time_zone') is-invalid @enderror" name="time_zone"
                    value="{{ (isset($data['language'])) ? old('time_zone', $data['language']->time_zone) : old('time_zone') }}" placeholder="enter time zone...">
              @include('backend.components.field-error', ['field' => 'time_zone'])
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-sm-2 text-sm-right">GMT</label>
            <div class="col-sm-10">
              <input type="text" class="form-control @error('gmt') is-invalid @enderror" name="gmt"
                    value="{{ (isset($data['language'])) ? old('gmt', $data['language']->gmt) : old('gmt') }}" placeholder="enter gmt...">
              @include('backend.components.field-error', ['field' => 'gmt'])
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-sm-2 text-sm-right">Faker Locale</label>
            <div class="col-sm-10">
              <input type="text" class="form-control @error('faker_locale') is-invalid @enderror" name="faker_locale"
                    value="{{ (isset($data['language'])) ? old('faker_locale', $data['language']->faker_locale) : old('faker_locale') }}" placeholder="enter faker locale...">
              @include('backend.components.field-error', ['field' => 'faker_locale'])
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-sm-2 text-sm-right">Icon / Image</label>
            <div class="col-sm-10">
              <div class="input-group">
                <input type="text" class="form-control @error('icon') is-invalid @enderror" id="image1" aria-label="Image" aria-describedby="button-image" name="icon"
                        value="{{ (isset($data['language'])) ? old('icon', $data['language']->icon) : old('icon') }}" placeholder="enter icon...">
                <div class="input-group-append" title="browse file">
                    <button class="btn btn-primary file-name" id="button-image" type="button"><i class="las la-image"></i></button>
                </div>
                @include('backend.components.field-error', ['field' => 'icon'])
              </div>
            </div>
        </div>
        <fieldset class="form-group">
            <div class="row">
            <label class="col-form-label col-sm-2 text-sm-right pt-sm-0">Status</label>
            <div class="col-sm-10">
                <label class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="status" value="1"
                        {{ isset($data['language']) ? (old('status', $data['language']->status == 1) ? 'checked' : '') : (old('status') ? 'checked' : 'checked') }}>
                    <span class="form-check-label">
                        ACTIVE
                    </span>
                </label>
                <label class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="status" value="0"
                        {{ isset($data['language']) ? (old('status', $data['language']->status == 0) ? 'checked' : '') : (old('status') ? 'checked' : '') }}>
                    <span class="form-check-label">
                        INACTIVE
                    </span>
                </label>
            </div>
            </div>
        </fieldset>
        <hr>
        <div class="d-flex justify-content-center">
            <a href="{{ route('language.index') }}" class="btn btn-secondary" title="Cancel">Cancel</a>&nbsp;&nbsp;
            <button type="submit" class="btn btn-primary" name="action" value="back" title="{{ isset($data['language']) ? 'Save changes' : 'Save' }}">
                {{ isset($data['language']) ? 'Save changes' : 'Save' }}
            </button>&nbsp;&nbsp;
            <button type="submit" class="btn btn-danger" name="action" value="exit" title="{{ isset($data['language']) ? 'Save changes & Exit' : 'Save & Exit' }}">
                {{ isset($data['language']) ? 'Save changes & Exit' : 'Save & Exit' }}
            </button>
        </div>
      </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('vendor/file-manager/js/file-manager.js') }}"></script>
@endsection

@section('jsbody')
@include('backend.includes.button-fm')
@include('backend.includes.tinymce-fm')
@include('backend.components.toastr')
@endsection
