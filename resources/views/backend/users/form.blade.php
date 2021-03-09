@extends('layouts.backend.layout')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/tmplts_backend/vendor/css/pages/account.css') }}">
<link rel="stylesheet" href="{{ asset('assets/tmplts_backend/vendor/libs/bootstrap-select/bootstrap-select.css') }}">
<link rel="stylesheet" href="{{ asset('assets/tmplts_backend/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}">
<script src="{{ asset('assets/tmplts_backend/wysiwyg/tinymce.min.js') }}"></script>
<link rel="stylesheet" href="{{ asset('assets/tmplts_backend/fancybox/fancybox.min.css') }}">
@endsection

@section('content')
<div class="card">
    <div class="list-group list-group-flush account-settings-links flex-row">
        <a class="list-group-item list-group-item-action active" data-toggle="list" href="#account">Account</a>
        <a class="list-group-item list-group-item-action" data-toggle="list" href="#information">Information</a>
        <a class="list-group-item list-group-item-action" data-toggle="list" href="#socmed">Social Media</a>
    </div>
    <div class="card-body">
        <form action="{{ !isset($data['user']) ? route('user.store') : route('user.update', ['id' => $data['user']->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if (isset($data['user']))
                @method('PUT')
            @endif
            <div class="tab-content">
                <div class="tab-pane fade show active" id="account">
                    <div class="form-group row">
                        <div class="col-md-2 text-md-right">
                          <label class="col-form-label text-sm-right">Name</label>
                        </div>
                        <div class="col-md-10">
                          <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                            value="{{ (isset($data['user'])) ? old('name', $data['user']->name) : old('name') }}" placeholder="enter name..." autofocus>
                          @include('backend.components.field-error', ['field' => 'name'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2 text-md-right">
                          <label class="col-form-label text-sm-right">Email</label>
                        </div>
                        <div class="col-md-10">
                          <input type="text" class="form-control @error('email') is-invalid @enderror" name="email"
                            value="{{ (isset($data['user'])) ? old('email', $data['user']->email) : old('email') }}" placeholder="enter email...">
                          @include('backend.components.field-error', ['field' => 'email'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2 text-md-right">
                          <label class="col-form-label text-sm-right">Username</label>
                        </div>
                        <div class="col-md-10">
                          <input type="text" class="form-control @error('username') is-invalid @enderror" name="username"
                            value="{{ (isset($data['user'])) ? old('username', $data['user']->username) : old('username') }}" placeholder="enter username...">
                          @include('backend.components.field-error', ['field' => 'username'])
                        </div>
                    </div>
                    @if (!isset($data['user']) || isset($data['user']) && $data['user']->roles[0]->id <= 3)
                    <div class="form-group row">
                        <div class="col-md-2 text-md-right">
                          <label class="col-form-label text-sm-right">Roles</label>
                        </div>
                        <div class="col-md-10">
                          <select id="select-role" class="selectpicker show-tick @error('roles') is-invalid @enderror" name="roles" data-style="btn-default">
                              <option value="" disabled selected>Select</option>
                              @foreach ($data['roles'] as $item)
                                    <option value="{{ $item->name }}" {{ isset($data['user']) ? (old('roles', $data['user']->roles[0]->name) == $item->name ? 'selected' : '') : (old('roles') == $item->name ? 'selected' : '') }}>
                                    {{ strtoupper($item->name) }}
                                    </option>
                              @endforeach
                          </select>
                          @error('roles')
                          <label class="error jquery-validation-error small form-text invalid-feedback" style="display: inline-block; color:red;">{!! $message !!}</label>
                          @enderror
                        </div>
                    </div>
                    @else
                    <input type="hidden" name="roles" value="{{ $data['user']->roles[0]->name }}">
                    @endif
                    <div class="form-group row">
                        <div class="col-md-2 text-md-right">
                          <label class="col-form-label text-sm-right">Status</label>
                        </div>
                        <div class="col-md-10">
                          <select class="show-tick form-control @error('active') is-invalid @enderror" name="active">
                              @foreach (config('custom.label.active') as $key => $val)
                              <option value="{{ $key }}" {{ isset($data['user']) ? (old('active', $data['user']->active) == ''.$key.'' ? 'selected' : '') : (old('active') == ''.$key.'' ? 'selected' : '') }}>{{ $val['title'] }}</option>
                              @endforeach
                          </select>
                          @error('active')
                          <label class="error jquery-validation-error small form-text invalid-feedback" style="display: inline-block; color:red;">{!! $message !!}</label>
                          @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2 text-md-right">
                          <label class="col-form-label text-sm-right">Password</label>
                        </div>
                        <div class="col-md-10">
                          <div class="input-group">
                          <input type="password" id="password-field" class="form-control gen-field @error('password') is-invalid @enderror" name="password"
                            value="{{ old('password') }}" placeholder="enter password...">
                          <div class="input-group-append">
                            <span toggle="#password-field" class="input-group-text toggle-password fas fa-eye"></span>
                            <span class="btn btn-warning ml-2" id="generate"> Generate Password</span>
                          </div>
                          @include('backend.components.field-error', ['field' => 'password'])
                          </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2 text-md-right">
                          <label class="col-form-label">Repeat Password</label>
                        </div>
                        <div class="col-md-10">
                          <div class="input-group">
                          <input type="password" id="password-confirm-field" class="form-control gen-field @error('password_confirmation') is-invalid @enderror" name="password_confirmation"
                            value="{{ old('password_confirmation') }}" placeholder="enter repeat password...">
                          <div class="input-group-append">
                            <span toggle="#password-confirm-field" class="input-group-text toggle-password-confirm fas fa-eye"></span>
                          </div>
                          @include('backend.components.field-error', ['field' => 'password_confirmation'])
                          </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="information">
                    <div class="form-group row">
                        <div class="col-md-2 text-md-right">
                          <label class="col-form-label text-sm-right">Date of birthday</label>
                        </div>
                        <div class="col-md-10">
                            <div class="input-group">
                                <input type="text" class="form-control @error('date_of_birthday') is-invalid @enderror datepicker" name="date_of_birthday" readonly
                                  value="{{ (isset($data['user'])) ? old('date_of_birthday', $data['user']->info->general['date_of_birthday']) : old('date_of_birthday') }}"
                                  placeholder="enter date of birthday...">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="las la-calendar"></i></span>
                                </div>
                                @include('backend.components.field-error', ['field' => 'date_of_birthday'])
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2 text-md-right">
                          <label class="col-form-label text-sm-right">Place of birthday</label>
                        </div>
                        <div class="col-md-10">
                            <div class="input-group">
                                <input type="text" class="form-control @error('place_of_birthday') is-invalid @enderror" name="place_of_birthday"
                                  value="{{ (isset($data['user'])) ? old('place_of_birthday', $data['user']->info->general['place_of_birthday']) : old('place_of_birthday') }}"
                                  placeholder="enter place of birthday...">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="las la-map-marker"></i></span>
                                </div>
                                @include('backend.components.field-error', ['field' => 'place_of_birthday'])
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2 text-md-right">
                          <label class="col-form-label text-sm-right">Gender</label>
                        </div>
                        <div class="col-md-10">
                          <select class="show-tick form-control @error('gender') is-invalid @enderror" name="gender">
                            <option value=" " selected disabled>Select</option>
                              @foreach (config('custom.label.gender') as $key => $val)
                              <option value="{{ $key }}" {{ isset($data['user']) ? (old('gender', $data['user']->info->general['gender']) == ''.$key.'' ? 'selected' : '') : (old('gender') == ''.$key.'' ? 'selected' : '') }}>{{ $val['title'] }}</option>
                              @endforeach
                          </select>
                          @error('active')
                          <label class="error jquery-validation-error small form-text invalid-feedback" style="display: inline-block; color:red;">{!! $message !!}</label>
                          @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2 text-md-right">
                          <label class="col-form-label text-sm-right">Address</label>
                        </div>
                        <div class="col-md-10">
                            <div class="input-group">
                                <textarea class="form-control @error('address') is-invalid @enderror" name="address" placeholder="enter address...">{{ (isset($data['user'])) ? old('address', $data['user']->info->general['address']) : old('address') }}</textarea>
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="las la-map"></i></span>
                                </div>
                                @include('backend.components.field-error', ['field' => 'address'])
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2 text-md-right">
                          <label class="col-form-label text-sm-right">Phone</label>
                        </div>
                        <div class="col-md-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><strong>+62</strong></span>
                                </div>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone"
                                  value="{{ (isset($data['user'])) ? old('phone', $data['user']->info->general['phone']) : old('phone') }}" placeholder="enter phone...">
                                @include('backend.components.field-error', ['field' => 'phone'])
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2 text-md-right">
                          <label class="col-form-label text-sm-right">Description</label>
                        </div>
                        <div class="col-md-10">
                            <textarea class="form-control tiny-mce @error('description') is-invalid @enderror" name="description" placeholder="enter description...">{!! (isset($data['user'])) ? old('description', $data['user']->info->general['description']) : old('description') !!}</textarea>
                            @include('backend.components.field-error', ['field' => 'description'])
                        </div>
                    </div>
                    <div class="form-group media row" style="min-height:1px">
                        <div class="col-md-2 text-md-right">
                          <label class="col-form-label text-sm-right">Photo</label>
                        </div>
                        @if (isset($data['user']))
                            <input type="hidden" name="old_photo_file" value="{{ $data['user']->profile_photo_path['filename'] }}">
                            <div class="col-md-1">
                                <a href="{{ $data['user']->getPhoto($data['user']->profile_photo_path['filename']) }}" data-fancybox="gallery">
                                    <div class="ui-bg-cover" style="width: 100px;height: 100px;background-image: url('{{ $data['user']->getPhoto($data['user']->profile_photo_path['filename']) }}');"></div>
                                </a>
                            </div>
                        @endif
                        <div class="col-md-{{ isset($data['user']) ? '9' : '10' }}">
                            <label class="custom-file-label" for="upload-2"></label>
                            <input class="form-control custom-file-input file @error('photo_file') is-invalid @enderror" type="file" id="upload-2" lang="en" name="photo_file" placeholder="enter photo...">
                            @include('backend.components.field-error', ['field' => 'photo_file'])
                            <small class="text-muted">File Type : <strong>{{ strtoupper(config('custom.mimes.photo.m')) }}</strong>, Pixel : <strong>{{ strtoupper(config('custom.mimes.photo.p')) }}</strong></small>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="photo_title" value="{{ isset($data['user']) ? old('photo_title', $data['user']->profile_photo_path['title']) : old('photo_title') }}" placeholder="title photo...">
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="photo_alt" value="{{ isset($data['user']) ? old('photo_alt', $data['user']->profile_photo_path['alt']) : old('photo_alt') }}" placeholder="alt photo...">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="socmed">
                    <div class="form-group row">
                        <div class="col-md-2 text-md-right">
                          <label class="col-form-label text-sm-right">Facebook URL</label>
                        </div>
                        <div class="col-md-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="lab la-facebook"></i></span>
                                </div>
                                <input type="text" class="form-control @error('facebook') is-invalid @enderror" name="facebook"
                                  value="{{ (isset($data['user'])) ? old('facebook', $data['user']->info->socmed['facebook']) : old('facebook') }}" placeholder="enter facebook url...">
                                @include('backend.components.field-error', ['field' => 'facebook'])
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2 text-md-right">
                          <label class="col-form-label text-sm-right">Instagram URL</label>
                        </div>
                        <div class="col-md-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="lab la-instagram"></i></span>
                                </div>
                                <input type="text" class="form-control @error('instagram') is-invalid @enderror" name="instagram"
                                  value="{{ (isset($data['user'])) ? old('instagram', $data['user']->info->socmed['instagram']) : old('instagram') }}" placeholder="enter instagram url...">
                                @include('backend.components.field-error', ['field' => 'instagram'])
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2 text-md-right">
                          <label class="col-form-label text-sm-right">Twitter URL</label>
                        </div>
                        <div class="col-md-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="lab la-twitter"></i></span>
                                </div>
                                <input type="text" class="form-control @error('twitter') is-invalid @enderror" name="twitter"
                                  value="{{ (isset($data['user'])) ? old('twitter', $data['user']->info->socmed['twitter']) : old('twitter') }}" placeholder="enter instagram url...">
                                @include('backend.components.field-error', ['field' => 'twitter'])
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2 text-md-right">
                          <label class="col-form-label text-sm-right">Pinterest URL</label>
                        </div>
                        <div class="col-md-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="lab la-pinterest"></i></span>
                                </div>
                                <input type="text" class="form-control @error('pinterest') is-invalid @enderror" name="pinterest"
                                  value="{{ (isset($data['user'])) ? old('pinterest', $data['user']->info->socmed['pinterest']) : old('pinterest') }}" placeholder="enter pinterest url...">
                                @include('backend.components.field-error', ['field' => 'instagram'])
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2 text-md-right">
                          <label class="col-form-label text-sm-right">Linked In URL</label>
                        </div>
                        <div class="col-md-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="lab la-linkedin"></i></span>
                                </div>
                                <input type="text" class="form-control @error('linkedin') is-invalid @enderror" name="linkedin"
                                  value="{{ (isset($data['user'])) ? old('linkedin', $data['user']->info->socmed['instagram']) : old('linkedin') }}" placeholder="enter linkedin url...">
                                @include('backend.components.field-error', ['field' => 'linkedin'])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="d-flex justify-content-center">
                <a href="{{ route('user.index') }}" class="btn btn-secondary" title="Cancel">Cancel</a>&nbsp;&nbsp;
                <button type="submit" class="btn btn-primary" name="action" value="back" title="{{ isset($data['user']) ? 'Save changes' : 'Save' }}">
                    {{ isset($data['user']) ? 'Save changes' : 'Save' }}
                </button>&nbsp;&nbsp;
                <button type="submit" class="btn btn-danger" name="action" value="exit" title="{{ isset($data['user']) ? 'Save changes & Exit' : 'Save & Exit' }}">
                    {{ isset($data['user']) ? 'Save changes & Exit' : 'Save & Exit' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/tmplts_backend/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
<script src="{{ asset('assets/tmplts_backend/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('assets/tmplts_backend/fancybox/fancybox.min.js') }}"></script>
@endsection

@section('jsbody')
<script src="{{ asset('assets/tmplts_backend/js/pages_account-settings.js') }}"></script>
<script>
    $( ".datepicker" ).datepicker({
        format: 'yyyy-mm-dd',
        todayHighlight: true,
    });
    //show & hide password
    $(".toggle-password, .toggle-password-confirm").click(function() {
        $(this).toggleClass("fa-eye fa-eye-slash");
        var input = $($(this).attr("toggle"));
        if (input.attr("type") == "password") {
        input.attr("type", "text");
        } else {
        input.attr("type", "password");
        }
    });
    //generate password
    function makeid(length) {
        var result           = '';
        var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        var charactersLength = characters.length;
        for ( var i = 0; i < length; i++ ) {
            result += characters.charAt(Math.floor(Math.random() * charactersLength));
        }

        return result;
    }
    $("#generate").click(function(){
        $(".gen-field").val(makeid(8));
    });
</script>

@include('backend.includes.tinymce')
@include('backend.components.toastr')
@endsection
