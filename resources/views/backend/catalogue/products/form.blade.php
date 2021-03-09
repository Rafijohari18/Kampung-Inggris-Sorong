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
        <a class="list-group-item list-group-item-action" data-toggle="list" href="#product">Product</a>
        <a class="list-group-item list-group-item-action" data-toggle="list" href="#setting">Setting</a>
    </div>
    <div class="card-body">
        <form action="{{ route('catalog.product.store', ['categoryId' => $data['catalog_category']->id]) }}" method="POST">
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
                <div class="tab-pane fade" id="product">
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-form-label col-sm-2 text-sm-right">Quantity</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control mb-1 @error('quantity') is-invalid @enderror" name="quantity"
                                    value="{{ old('quantity') }}" placeholder="Enter quantity...">
                                @include('backend.components.field-error', ['field' => 'quantity'])
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-sm-2 text-sm-right">Price</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control mb-1 @error('price') is-invalid @enderror" id="price" name="price" onkeyup="document.getElementById('format').innerHTML = formatCurrency(this.value);" autocomplete="off"
                                    value="{{ old('price') }}" placeholder="Enter price...">
                                @include('backend.components.field-error', ['field' => 'price'])
                                <em><span  id="format" class=""></span> <span id="price-generate" class="font"></span></em>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-sm-2 text-sm-right">Product Discount</label>
                            <div class="col-sm-10">
                                <label class="switcher">
                                    <input type="checkbox" name="is_discount" id="is-discount" value="{{ old('is_discount') ? 'checked' : '' }}" class="switcher-input">
                                    <span class="switcher-indicator">
                                        <span class="switcher-yes"></span>
                                        <span class="switcher-no"></span>
                                    </span>
                                    <span class="switcher-label"></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group row" id="discount">
                            <label class="col-form-label col-sm-2 text-sm-right">Discount</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <input type="number" class="form-control @error('discount') is-invalid @enderror" name="discount" max="100"
                                        value="{{ old('discount') }}" placeholder="Enter discount...">
                                    <div class="input-group-append">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                                @include('backend.components.field-error', ['field' => 'discount'])
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="setting">
                    <div class="card-body">

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
                                            value="{{ old('cover_file') }}" placeholder="browse cover..." readonly>
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
            </div>
            <hr>
            <div class="d-flex justify-content-center">
                <a href="{{ route('catalog.product.index', ['categoryId' => $data['catalog_category']->id]) }}" class="btn btn-secondary" title="Cancel">Cancel</a>&nbsp;&nbsp;
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

    //price
    function formatCurrency(price)
    {
        price = price.toString().replace(/\$|\,/g,'');
        if(isNaN(price))
        price = "0";
        sign = (price == (price = Math.abs(price)));
        price = Math.floor(price*100+0.50000000001);
        cents = price%100;
        price = Math.floor(price/100).toString();
        if(cents<10)
        cents = "0" + cents;
        for (var i = 0; i < Math.floor((price.length-(1+i))/3); i++)
        price = price.substring(0,price.length-(4*i+3))+'.'+
        price.substring(price.length-(4*i+3));
        return (((sign)?'':'-') + ' Rp. ' + price + ',' + cents);
    };

    //max length discount
    $(document).ready(function() {

        $('input[type=number][max]:not([max=""])').on('input', function(ev) {
        var $this = $(this);
        var maxlength = $this.attr('max').length;
        var value = $this.val();
        if (value && value.length >= maxlength) {
            $this.val(value.substr(0, maxlength));
        }
        });

    });

    //is discount
    $('#discount').hide();
    $('#is-discount').click(function() {
        if ($('#is-discount').prop('checked') == true) {
            $('#discount').toggle('slow');
        } else {
            $('#discount').toggle('slow');
        }
    });

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

@include('backend.includes.button-fm')
@include('backend.includes.tinymce-fm')
@include('backend.components.toastr')
@endsection
