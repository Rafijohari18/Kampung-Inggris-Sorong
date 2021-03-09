@extends('layouts.backend.layout')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/tmplts_backend/vendor/css/pages/account.css') }}">
<script src="{{ asset('assets/tmplts_backend/admin.js') }}"></script>
<script src="{{ asset('assets/tmplts_backend/wysiwyg/tinymce.min.js') }}"></script>
<link rel="stylesheet" href="{{ asset('assets/tmplts_backend/vendor/libs/bootstrap-select/bootstrap-select.css') }}">
<link rel="stylesheet" href="{{ asset('assets/tmplts_backend/vendor/libs/select2/select2.css') }}">
<link rel="stylesheet" href="{{ asset('assets/tmplts_backend/vendor/libs/bootstrap-material-datetimepicker/bootstrap-material-datetimepicker.css') }}">
<link rel="stylesheet" href="{{ asset('assets/tmplts_backend/fancybox/fancybox.min.css') }}">
@endsection

@section('content')
@if($errors->has('files'))
    <div class="alert alert-danger alert-dismissible fade show">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <i class="las la-thumbtack" style="font-size: 1.2em;"></i> {{ $errors->first('files') }}
    </div>
@endif
<div class="card">
    <div class="list-group list-group-flush account-settings-links flex-row">
        <a class="list-group-item list-group-item-action active" data-toggle="list" href="#content">Content</a>
        <a class="list-group-item list-group-item-action" data-toggle="list" href="#setting">Setting</a>
        @if ($data['section']->extra == 1)
        <a class="list-group-item list-group-item-action" data-toggle="list" href="#files">Files</a>
        @endif
        @if ($data['section']->extra == 2)
        <a class="list-group-item list-group-item-action" data-toggle="list" href="#profile">Profile</a>
        @endif
    </div>
    <div class="card-body">
        <form action="{{ route('post.store', ['sectionId' => $data['section']->id]) }}" method="POST" enctype="multipart/form-data">
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
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-2 text-sm-right">Title</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control mb-1 gen_slug @error('title_'.$lang->iso_codes) is-invalid @enderror"
                                            lang="{{ $lang->iso_codes }}" name="title_{{ $lang->iso_codes }}" value="{{ old('title_'.$lang->iso_codes) }}" placeholder="Enter title...">
                                        @include('backend.components.field-error', ['field' => 'title_'.$lang->iso_codes])
                                    </div>
                                </div>
                                @if ($lang->iso_codes == app()->getLocale())
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-2 text-sm-right">Slug</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control mb-1 slug_spot @error('slug') is-invalid @enderror" lang="{{ $lang->iso_codes }}" name="slug"
                                                value="{{ old('slug') }}" placeholder="Enter slug...">
                                        @include('backend.components.field-error', ['field' => 'slug'])
                                    </div>
                                </div>
                                @endif
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-2 text-sm-right">Intro</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control tiny-mce" name="intro_{{ $lang->iso_codes }}">{!! old('intro_'.$lang->iso_codes) !!}</textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-2 text-sm-right">Content</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control tiny-mce" name="content_{{ $lang->iso_codes }}">{!! old('content_'.$lang->iso_codes) !!}</textarea>
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
                            <label class="col-form-label col-sm-2 text-sm-right">Category</label>
                            <div class="col-sm-10">
                                <select class="selectpicker custom-select" name="category_id" data-style="btn-default">
                                    {{-- <option value=" " selected disabled>Select</option> --}}
                                    @foreach ($data['categories'] as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{!! $category->fieldLang('name') !!}</option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                <label class="error jquery-validation-error small form-text invalid-feedback" style="display: inline-block;color:red;">{!! $message !!}</label>
                                @enderror
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
                        <div class="form-group row">
                            <label class="col-form-label col-sm-2 text-sm-right">Publish Time</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <input type="text" class="datetime-picker form-control @error('created_at') is-invalid @enderror" name="created_at"
                                        value="{{ old('created_at', now()) }}" placeholder="enter publish time...">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="las la-calendar"></i></span>
                                    </div>
                                    @include('backend.components.field-error', ['field' => 'created_at'])
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-sm-2 text-sm-right">Public</label>
                            <div class="col-sm-10">
                                <label class="custom-control custom-checkbox m-0">
                                    <input type="checkbox" class="custom-control-input" name="public" value="1" {{ old('public') ? 'checked' : 'checked' }}>
                                    <span class="custom-control-label ml-4">YES</span>
                                </label>
                            </div>
                        </div>
                        @role('super')
                        <div class="form-group row">
                            <label class="col-form-label col-sm-2 text-sm-right">Widget</label>
                            <div class="col-sm-10">
                                <label class="custom-control custom-checkbox m-0">
                                    <input type="checkbox" class="custom-control-input" name="is_widget" value="1" {{ old('is_widget') ? 'checked' : '' }}>
                                    <span class="custom-control-label ml-4">YES</span>
                                </label>
                            </div>
                        </div>
                        @endrole
                        <div class="form-group row">
                            <label class="col-form-label col-sm-2 text-sm-right">Cover</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <input type="text" class="form-control @error('cover_file') is-invalid @enderror" id="image1" aria-label="Image" aria-describedby="button-image" name="cover_file"
                                            value="{{ old('cover_file') }}" placeholder="browse cover...">
                                    <div class="input-group-append" title="browse file">
                                        <span class="input-group-text">
                                            <input type="checkbox" id="remove-cover" value="1">&nbsp; Remove
                                        </span>
                                        <button class="btn btn-primary file-name" id="button-image" type="button"><i class="las la-image"></i></button>
                                    </div>
                                    @include('backend.components.field-error', ['field' => 'cover_file'])
                                </div>
                                <span class="text-muted"><em>* click icon image to browse cover</em></span>
                                <br>
                                <div class="row">
                                    <div class="col-sm-6">
                                    <input type="text" class="form-control" placeholder="title..." name="cover_title" value="{{ old('cover_title') }}">
                                    </div>
                                    <div class="col-sm-6">
                                    <input type="text" class="form-control" placeholder="alt..." name="cover_alt" value="{{ old('cover_alt') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-sm-2 text-sm-right">Banner</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="image2" aria-label="Image2" aria-describedby="button-image2" name="banner_file"
                                            value="{{ old('banner_file') }}" placeholder="browse banner..." readonly>
                                    <div class="input-group-append" title="browse file">
                                        <span class="input-group-text">
                                            <input type="checkbox" id="remove-banner" value="1">&nbsp; Remove
                                        </span>
                                        <button class="btn btn-primary file-name" id="button-image2" type="button"><i class="las la-image"></i></button>
                                    </div>
                                </div>
                                <span class="text-muted"><em>* click icon image to browse banner</em></span>
                                <br>
                                <div class="row">
                                    <div class="col-sm-6">
                                    <input type="text" class="form-control" placeholder="title..." name="banner_title" value="{{ old('banner_title') }}">
                                    </div>
                                    <div class="col-sm-6">
                                    <input type="text" class="form-control" placeholder="alt..." name="banner_alt" value="{{ old('banner_alt') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-sm-2 text-sm-right">Custom View</label>
                            <div class="col-sm-10">
                                <select class="custom-select" name="custom_view_id" data-style="btn-default">
                                    <option value=" " selected>default</option>
                                    @foreach ($data['template'] as $custom)
                                        <option value="{{ $custom->id }}" {{ (old('custom_view_id') == $custom->id) ? 'selected' : '' }}>
                                            {{ $custom->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-sm-2 text-sm-right">Tags</label>
                            <div class="col-sm-10">
                                <div class="select2-primary">
                                    <select class="select2 form-control" name="tags[]" multiple style="width: 100%">
                                        @foreach ($data['tags'] as $keyT => $tag)
                                        <option value="{{ $tag->id }}" {{ old('tags.'.$keyT) == $tag->id ? 'selected' : '' }}>
                                            {{ $tag->name }}
                                            @if ($tag->standar == 1)
                                            (standar)
                                            @endif
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row">
                            <label class="col-form-label col-sm-2 text-sm-right">Meta Title</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control mb-1" name="meta_title" value="{{ old('meta_title') }}" placeholder="Enter meta title...">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-sm-2 text-sm-right">Meta Description</label>
                            <div class="col-sm-10">
                                <textarea class="form-control mb-1" name="meta_description" placeholder="Separated by comma (,)">{{ old('meta_decsription') }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-sm-2 text-sm-right">Meta Keywords</label>
                            <div class="col-sm-10">
                                <textarea class="form-control mb-1" name="meta_keywords" placeholder="Separated by comma (,)">{{ old('meta_keywords') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                @if ($data['section']->extra == 1)
                <div class="tab-pane fade" id="files">
                    <div class="card-body">
                        <div id="list">
                            <div class="form-group row">
                                <div class="col-md-2 text-md-right">
                                    <label class="col-form-label text-sm-right">Files</label>
                                </div>
                                <div class="col-md-10">
                                    <label class="custom-file-label" for="file-0"></label>
                                    <input class="form-control custom-file-input file @error('files.0') is-invalid @enderror" type="file" id="file-0" lang="en" name="files[]" placeholder="enter file...">
                                    @include('backend.components.field-error', ['field' => 'files.0'])
                                    <span class="text-muted">Type of file : <strong>{{ config('custom.mimes.extra_file.m') }}</strong></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row" id="add-file">
                            <div class="col-md-2 text-md-right d-none d-md-block">
                                <label class="col-form-label">&nbsp;</label>
                            </div>
                            <div class="col-md-10 d-flex justify-content-start">
                                <button type="button" id="add" class="btn btn-success"><i class="las la-plus"></i>Add Files</button>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                @if ($data['section']->extra == 2)
                <div class="tab-pane fade" id="profile">
                    <div class="card-body">
                        @foreach (config('custom.columns.columns_profile_for_post') as $keyP => $valP)
                        <div class="form-group row">
                            <label class="col-form-label col-sm-2 text-sm-right">{{ $valP['label'] }}</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control mb-1 @error('profile_'.$valP['name']) is-invalid @enderror" name="profile_{{ $valP['name'] }}" value="{{ old('profile_'.$valP['name']) }}" placeholder="Enter {{ $valP['label'] }}...">
                                @include('backend.components.field-error', ['field' => 'profile_'.$valP['name']])
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            <hr>
            <div class="d-flex justify-content-center">
                <a href="{{ route('post.index', ['sectionId' => $data['section']->id]) }}" class="btn btn-secondary" title="Cancel">Cancel</a>&nbsp;&nbsp;
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
<script src="{{ asset('assets/tmplts_backend/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
<script src="{{ asset('assets/tmplts_backend/vendor/libs/select2/select2.js') }}"></script>
<script src="{{ asset('assets/tmplts_backend/vendor/libs/moment/moment.js') }}"></script>
<script src="{{ asset('assets/tmplts_backend/vendor/libs/bootstrap-material-datetimepicker/bootstrap-material-datetimepicker.js') }}"></script>
<script src="{{ asset('assets/tmplts_backend/fancybox/fancybox.min.js') }}"></script>
@endsection

@section('jsbody')
<script src="{{ asset('assets/tmplts_backend/js/pages_account-settings.js') }}"></script>
<script>
     //datetime
     $('.datetime-picker').bootstrapMaterialDatePicker({
        date: true,
        shortTime: false,
        format: 'YYYY-MM-DD HH:mm'
    });
    //select
    $('.select2').select2();

    $('#remove-cover').click(function() {
        if ($('#remove-cover').prop('checked') == true) {
            $('#image1').val('');
        }
    });
    $('#remove-banner').click(function() {
        if ($('#remove-banner').prop('checked') == true) {
            $('#image2').val('');
        }
    });
</script>

@if ($data['section']->extra == 1)
<script>
    $(function()  {
        var no=1;
        $("#add").click(function() {
            $("#list").append(`
                <div class="form-group row" id="delete-`+no+`">
                    <div class="col-md-2 text-md-right">
                        <label class="col-form-label text-sm-right"></label>
                    </div>
                    <div class="col-md-9">
                        <label class="custom-file-label" for="file-`+no+`"></label>
                        <input class="form-control custom-file-input file @error('files.`+no+`') is-invalid @enderror" type="file" id="file-`+no+`" lang="en" name="files[]" placeholder="enter file...">
                        @include('backend.components.field-error', ['field' => 'files.`+no+`'])
                    </div>
                    <div class="col-md-1">
                        <div class="col-md-1 text-center">
                            <button type="button" id="remove" data-id="`+no+`" class="btn icon-btn btn-danger btn-sm"><span class="las la-times"></span></button>
                        </div>
                    </div>
                </div>
            `);

            var noOfColumns = $('.num-list').length;
            var maxNum = 20;
            if (noOfColumns < maxNum) {
                $("#add-file").show();
            } else {
                $("#add-file").hide();
            }

            // FILE BROWSE
            function callfileBrowser() {
                $(".custom-file-input").on("change", function() {
                    const fileName = Array.from(this.files).map((value, index) => {
                        if (this.files.length == index + 1) {
                            return value.name
                        } else {
                            return value.name + ', '
                        }
                    });
                    $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
                });
            }
            callfileBrowser();

            no++;
        });
    });

    $(document).on('click', '#remove', function() {
        var id = $(this).attr("data-id");
        $("#delete-"+id).remove();
    });
</script>
@endif

@include('backend.includes.button-fm')
@include('backend.includes.tinymce-fm')
@include('backend.components.toastr')
@endsection
