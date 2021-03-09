@extends('layouts.backend.layout')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/tmplts_backend/vendor/css/pages/account.css') }}">
<script src="{{ asset('assets/tmplts_backend/admin.js') }}"></script>
<script src="{{ asset('assets/tmplts_backend/wysiwyg/tinymce.min.js') }}"></script>
<link rel="stylesheet" href="{{ asset('assets/tmplts_backend/fancybox/fancybox.min.css') }}">
@endsection

@section('content')

<div class="card">
    <div class="list-group list-group-flush account-settings-links flex-row">
        <a class="list-group-item list-group-item-action active" data-toggle="list" href="#content">Content</a>
        <a class="list-group-item list-group-item-action" data-toggle="list" href="#setting">Setting</a>
        @if ($data['category']->field->count() > 0)
        <a class="list-group-item list-group-item-action" data-toggle="list" href="#field">Addon Field</a>
        @endif
    </div>
    <div class="card-body">
        <form action="{{ route('category.update', ['sectionId' => $data['category']->section_id, 'id' => $data['category']->id]) }}" method="POST">
            @csrf
            @method('PUT')
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
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-2 text-sm-right">Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control mb-1 gen_slug @error('name_'.$lang->iso_codes) is-invalid @enderror"
                                            lang="{{ $lang->iso_codes }}" name="name_{{ $lang->iso_codes }}" value="{{ old('name_'.$lang->iso_codes, $data['category']->fieldLang('name', $lang->iso_codes)) }}" placeholder="Enter name...">
                                        @include('backend.components.field-error', ['field' => 'name_'.$lang->iso_codes])
                                    </div>
                                </div>
                                @if ($lang->iso_codes == app()->getLocale())
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-2 text-sm-right">Slug</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control mb-1 slug_spot @error('slug') is-invalid @enderror" lang="{{ $lang->iso_codes }}" name="slug"
                                                value="{{ old('slug', $data['category']->slug) }}" placeholder="Enter slug...">
                                        @include('backend.components.field-error', ['field' => 'slug'])
                                    </div>
                                </div>
                                @endif
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-2 text-sm-right">Description</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control tiny-mce" name="description_{{ $lang->iso_codes }}">{!! old('description_'.$lang->iso_codes, $data['category']->fieldLang('description', $lang->iso_codes)) !!}</textarea>
                                    </div>
                                </div>
                            </div>
                          </div>
                          @endforeach
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="setting">
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-form-label col-sm-2 text-sm-right">Public</label>
                            <div class="col-sm-10">
                                <label class="custom-control custom-checkbox m-0">
                                    <input type="checkbox" class="custom-control-input" name="public" value="1" {{ old('public') == $data['category']->public ? 'checked' : 'checked' }}>
                                    <span class="custom-control-label ml-4">YES</span>
                                </label>
                            </div>
                        </div>
                        @role('super')
                        <div class="form-group row">
                            <label class="col-form-label col-sm-2 text-sm-right">Widget</label>
                            <div class="col-sm-10">
                                <label class="custom-control custom-checkbox m-0">
                                    <input type="checkbox" class="custom-control-input" name="is_widget" value="1" {{ old('is_widget', $data['category']->is_widget) ? 'checked' : '' }}>
                                    <span class="custom-control-label ml-4">YES</span>
                                </label>
                            </div>
                        </div>
                        @endrole
                        <div class="form-group row">
                            <label class="col-form-label col-sm-2 text-sm-right">Banner</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="image1" aria-label="Image" aria-describedby="button-image" name="banner_file"
                                            value="{{ old('banner_file', $data['category']->banner['file_path']) }}" placeholder="browse banner..." readonly>
                                    <div class="input-group-append" title="browse file">
                                        <span class="input-group-text">
                                            <input type="checkbox" id="remove-banner" value="1">&nbsp; Remove
                                        </span>
                                        <button class="btn btn-primary file-name" id="button-image" type="button"><i class="las la-image"></i></button>
                                    </div>
                                </div>
                                <span class="text-muted"><em>* click icon image to browse banner</em></span>
                                <br>
                                <div class="row">
                                    <div class="col-sm-6">
                                    <input type="text" class="form-control" placeholder="title..." name="banner_title" value="{{ old('banner_title', $data['category']->banner['title']) }}">
                                    </div>
                                    <div class="col-sm-6">
                                    <input type="text" class="form-control" placeholder="alt..." name="banner_alt" value="{{ old('banner_alt', $data['category']->banner['alt']) }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-sm-2 text-sm-right">List View</label>
                            <div class="col-sm-10">
                                <select class="custom-select" name="list_view_id" data-style="btn-default">
                                    <option value=" " selected>default</option>
                                    @foreach ($data['template_list'] as $custom_list)
                                        <option value="{{ $custom_list->id }}" {{ (old('list_view_id', $data['category']->list_view_id) == $custom_list->id) ? 'selected' : '' }}>
                                            {{ $custom_list->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-sm-2 text-sm-right">Detail View</label>
                            <div class="col-sm-10">
                                <select class="custom-select" name="detail_view_id" data-style="btn-default">
                                    <option value=" " selected>default</option>
                                    @foreach ($data['template_detail'] as $custom_detail)
                                        <option value="{{ $custom_detail->id }}" {{ (old('detail_view_id', $data['category']->detail_view_id) == $custom_detail->id) ? 'selected' : '' }}>
                                            {{ $custom_detail->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-sm-2 text-sm-right">Content Limit <i>(posts)</i></label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" name="list_limit" value="{{ old('list_limit', $data['category']->list_limit) }}" placeholder="for posts">
                            </div>
                        </div>
                    </div>
                </div>
                @if ($data['category']->field->count() > 0)
                <div class="tab-pane fade" id="field">
                    <div class="card-body">
                        @foreach ($data['category']->field as $field)
                        @include('backend.master.field.input', ['module' => 'category'])
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            <hr>
            <div class="d-flex justify-content-center">
                <a href="{{ route('category.index', ['sectionId' => $data['category']->section_id]) }}" class="btn btn-secondary" title="Cancel">Cancel</a>&nbsp;&nbsp;
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
<script src="{{ asset('assets/tmplts_backend/js/pages_account-settings.js') }}"></script>
<script>
    $('#remove-banner').click(function() {
        if ($('#remove-banner').prop('checked') == true) {
            $('#image1').val('');
        }
    });
</script>

@include('backend.includes.button-fm')
@include('backend.includes.tinymce-fm')
@include('backend.components.toastr')
@endsection
