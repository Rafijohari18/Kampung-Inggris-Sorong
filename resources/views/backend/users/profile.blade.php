@extends('layouts.backend.layout')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/tmplts_backend/vendor/css/pages/account.css') }}">
<link rel="stylesheet" href="{{ asset('assets/tmplts_backend/vendor/libs/bootstrap-select/bootstrap-select.css') }}">
<link rel="stylesheet" href="{{ asset('assets/tmplts_backend/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}">
<script src="{{ asset('assets/tmplts_backend/wysiwyg/tinymce.min.js') }}"></script>
<link rel="stylesheet" href="{{ asset('assets/tmplts_backend/fancybox/fancybox.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/tmplts_backend/vendor/libs/sweetalert2/sweetalert2.css') }}">
@endsection

@section('content')
<div class="row">
    <div class="col-xl-4">

      <!-- Side info -->
      <div class="card mb-4">
        <div class="card-body">
          <div class="media">
            <a href="{{ $data['user']->getPhoto($data['user']->profile_photo_path['filename']) }}" data-fancybox="gallery">
                <img src="{{ $data['user']->getPhoto($data['user']->profile_photo_path['filename']) }}" alt class="ui-w-60 rounded-circle">
            </a>
            <div class="media-body pt-2 ml-3">
              <h5 class="mb-2">{{ $data['user']->name }}</h5>
              <div class="text-muted small">{{ strtoupper($data['user']->roles[0]->name) }}</div>

              <div class="mt-3">
                <a href="javascript:void(0)" class="btn btn-primary btn-sm rounded-pill"  data-toggle="modal" data-target="#modals-change-photo">
                    <i class="las la-camera"></i> Change
                </a>
                @if (!empty($data['user']->profile_photo_path['filename']))
                &nbsp;
                <a href="javascript:void(0)" class="btn btn-default btn-sm rounded-pill confirm-remove">
                    <i class="las la-times"></i> Remove
                    <form action="{{ route('profile.photo.remove') }}" method="POST">
                        @csrf
                        @method('PUT')
                    </form>
                </a>
                @endif
              </div>
            </div>
          </div>
        </div>
        <hr class="border-light m-0">
        <div class="card-body">
          <div class="mb-2">
            <span class="text-muted">Birthday:</span>&nbsp;
            @if (!empty($data['user']->info->general['place_of_birthday']) && !empty($data['user']->info->general['date_of_birthday']))
                {{ $data['user']->info->general['place_of_birthday'].', '.$data['user']->info->general['date_of_birthday'] }}
            @else
                -
            @endif
          </div>
          <div class="mb-2">
            <span class="text-muted">Gender:</span>&nbsp;
            @if (!empty($data['user']->info->general['gender']))
                <a href="javascript:void(0)" class="text-body">{{ config('cms.label.gender.'.$data['user']->info->general['gender'])['title'] }}</a>
            @else
                -
            @endif
          </div>
          <div class="mb-2">
            <span class="text-muted">Address:</span>&nbsp;
            @if (!empty($data['user']->info->general['address']))
                <a href="javascript:void(0)" class="text-body">{{ $data['user']->info->general['address'] }}</a>
            @else
                -
            @endif
          </div>
          <div class="mb-2">
            <span class="text-muted">Phone:</span>&nbsp;
            @if (!empty($data['user']->info->general['phone']))
                <a href="javascript:void(0)" class="text-body">{{ '+62'.$data['user']->info->general['phone'] }}</a>
            @else
                -
            @endif
          </div>
          <div class="text-muted">
            {!! $data['user']->info->general['description'] !!}
          </div>
        </div>
      </div>
      <!-- / Side info -->

      <!-- Links -->
      <div class="card mb-4">
        <div class="card-header">My Logs</div>
        <div class="card-body">


            <div class="media align-items-center pb-1 mb-3">
                <i class="las la-calendar-day d-block ui-w-40" style="font-size: 1.6em;"></i>
                <div class="media-body flex-truncate ml-3">
                {{ $data['user']->session->last_activity->format('l, j F Y (H:i A)') }}
                <div class="text-muted small text-truncate">Last Activity</div>
                </div>
            </div>
            <div class="media align-items-center pb-1 mb-3">
                <i class="las la-map-pin d-block ui-w-40" style="font-size: 1.6em;"></i>
                <div class="media-body flex-truncate ml-3">
                {{ $data['user']->session->ip_address }}
                <div class="text-muted small text-truncate">IP Address</div>
                </div>
            </div>
            <div class="media align-items-center pb-1 mb-3">
                <i class="las la-globe d-block ui-w-40" style="font-size: 1.6em;"></i>
                <div class="media-body flex-truncate ml-3">
                {{ $data['user']->session->user_agent }}
                <div class="text-muted small text-truncate">User Agent</div>
                </div>
            </div>
            <div class="media align-items-center pb-1 mb-3">
                <a href="{{ route('user.log') }}" class="btn btn-info btn-rounded-pill btn-sm">
                    <i class="las la-user-clock"></i> View all my logs
                </a>
            </div>

        </div>
      </div>
      <!-- / Links -->


    </div>
    <div class="col">

      <!-- Posts -->
      <div class="card mb-4">
          <form action="{{ route('profile') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="nav-tabs-top mb-4">
                    <ul class="nav nav-tabs">
                      <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#account">Account</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#information">Information</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#socmed">Social Media</a>
                      </li>
                    </ul>
                    <div class="tab-content">
                      <div class="tab-pane fade active show" id="account">
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-md-1 text-md-left">
                                  <label class="col-form-label text-sm-right">Name</label>
                                </div>
                                <div class="col-md-11">
                                  <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                                    value="{{ (isset($data['user'])) ? old('name', $data['user']->name) : old('name') }}" placeholder="enter name..." autofocus>
                                  @include('backend.components.field-error', ['field' => 'name'])
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-1 text-md-left">
                                  <label class="col-form-label text-sm-right">Email</label>
                                </div>
                                <div class="col-md-11">
                                  <input type="hidden" name="old_email" value="{{ $data['user']->email }}">
                                  <input type="text" class="form-control @error('email') is-invalid @enderror" name="email"
                                    value="{{ (isset($data['user'])) ? old('email', $data['user']->email) : old('email') }}" placeholder="enter email...">
                                  @include('backend.components.field-error', ['field' => 'email'])
                                  @if ($data['user']->email_verified == 0)
                                  <div class="alert alert-warning alert-dismissible fade show mt-2">
                                      <button type="button" class="close" data-dismiss="alert">×</button>
                                      <i class="las la-exclamation" style="font-size: 1.2em;"></i> Your email is not verified, <a href="{{ route('profile.mail.send') }}"><em>verified now</em></a>.
                                  </div>
                                  @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-1 text-md-left">
                                  <label class="col-form-label text-sm-right">Username</label>
                                </div>
                                <div class="col-md-11">
                                  <input type="text" class="form-control @error('username') is-invalid @enderror" name="username"
                                    value="{{ (isset($data['user'])) ? old('username', $data['user']->username) : old('username') }}" placeholder="enter username...">
                                  @include('backend.components.field-error', ['field' => 'username'])
                                </div>
                            </div>
                            <hr>
                            <h6 class="text-muted"><em>if you change password :</em></h6>
                            <div class="form-group row">
                                <div class="col-md-2 text-md-right">
                                  <label class="col-form-label text-sm-right">Current Password</label>
                                </div>
                                <div class="col-md-10">
                                  <div class="input-group">
                                    <input type="password" id="current-password-field" class="form-control @error('current_password') is-invalid @enderror" name="current_password" placeholder="enter current password...">
                                    <div class="input-group-append">
                                        <span toggle="#current-password-field" class="input-group-text toggle-current-password fas fa-eye"></span>
                                    </div>
                                    @include('backend.components.field-error', ['field' => 'current_password'])
                                  </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-2 text-md-right">
                                  <label class="col-form-label text-sm-right">New Password</label>
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
                                  <label class="col-form-label">Repeat New Password</label>
                                </div>
                                <div class="col-md-10">
                                  <div class="input-group">
                                  <input type="password" id="password-confirm-field" class="form-control gen-field @error('password_confirmation') is-invalid @enderror" name="password_confirmation" value="{{ old('password_confirmation') }}" placeholder="enter repeat password...">
                                  <div class="input-group-append">
                                    <span toggle="#password-confirm-field" class="input-group-text toggle-password-confirm fas fa-eye"></span>
                                  </div>
                                  @include('backend.components.field-error', ['field' => 'password_confirmation'])
                                  </div>
                                </div>
                            </div>
                        </div>
                      </div>
                      <div class="tab-pane fade" id="information">
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-md-2 text-md-left">
                                  <label class="col-form-label text-sm-right">Date of birthday</label>
                                </div>
                                <div class="col-md-10">
                                    <div class="input-group">
                                        <input type="text" class="form-control @error('date_of_birthday') is-invalid @enderror datepicker" name="date_of_birthday" readonly
                                          value="{{ old('date_of_birthday', $data['user']->info->general['date_of_birthday'])}}" placeholder="enter date of birthday...">
                                        <div class="input-group-append">
                                            <span class="input-group-text"><i class="las la-calendar"></i></span>
                                        </div>
                                        @include('backend.components.field-error', ['field' => 'date_of_birthday'])
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-2 text-md-left">
                                  <label class="col-form-label text-sm-right">Place of birthday</label>
                                </div>
                                <div class="col-md-10">
                                    <div class="input-group">
                                        <input type="text" class="form-control @error('place_of_birthday') is-invalid @enderror" name="place_of_birthday"
                                          value="{{ old('place_of_birthday', $data['user']->info->general['place_of_birthday']) }}" placeholder="enter place of birthday...">
                                        <div class="input-group-append">
                                            <span class="input-group-text"><i class="las la-map-marker"></i></span>
                                        </div>
                                        @include('backend.components.field-error', ['field' => 'place_of_birthday'])
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-2 text-md-left">
                                  <label class="col-form-label text-sm-right">Gender</label>
                                </div>
                                <div class="col-md-10">
                                  <select class="show-tick form-control @error('gender') is-invalid @enderror" name="gender">
                                    <option value=" " selected disabled>Select</option>
                                      @foreach (config('custom.label.gender') as $key => $val)
                                      <option value="{{ $key }}" {{ old('gender', $data['user']->info->general['gender']) == ''.$key.'' ? 'selected' : '' }}>{{ $val['title'] }}</option>
                                      @endforeach
                                  </select>
                                  @error('active')
                                  <label class="error jquery-validation-error small form-text invalid-feedback" style="display: inline-block; color:red;">{!! $message !!}</label>
                                  @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-2 text-md-left">
                                  <label class="col-form-label text-sm-right">Address</label>
                                </div>
                                <div class="col-md-10">
                                    <div class="input-group">
                                        <textarea class="form-control @error('address') is-invalid @enderror" name="address" placeholder="enter address...">{{ old('address', $data['user']->info->general['address']) }}</textarea>
                                        <div class="input-group-append">
                                            <span class="input-group-text"><i class="las la-map"></i></span>
                                        </div>
                                        @include('backend.components.field-error', ['field' => 'address'])
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-2 text-md-left">
                                  <label class="col-form-label text-sm-right">Phone</label>
                                </div>
                                <div class="col-md-10">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><strong>+62</strong></span>
                                        </div>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone"
                                          value="{{ old('phone', $data['user']->info->general['phone']) }}" placeholder="enter phone...">
                                        @include('backend.components.field-error', ['field' => 'phone'])
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-2 text-md-left">
                                  <label class="col-form-label text-sm-right">Description</label>
                                </div>
                                <div class="col-md-10">
                                    <textarea class="form-control tiny-mce @error('description') is-invalid @enderror" name="description" placeholder="enter description...">{!! old('description', $data['user']->info->general['description']) !!}</textarea>
                                    @include('backend.components.field-error', ['field' => 'description'])
                                </div>
                            </div>
                        </div>
                      </div>
                      <div class="tab-pane fade" id="socmed">
                          <div class="card-body">
                            @foreach ($data['user']->info->socmed as $key => $val)
                            <div class="form-group row">
                                <div class="col-md-2 text-md-right">
                                  <label class="col-form-label text-sm-right">{{ $key }} URL</label>
                                </div>
                                <div class="col-md-10">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="lab la-{{ $key }}"></i></span>
                                        </div>
                                        <input type="text" class="form-control @error($key) is-invalid @enderror" name="{{ $key }}"
                                          value="{{ old($key, $data['user']->info->socmed[$key]) }}" placeholder="enter $key url...">
                                        @include('backend.components.field-error', ['field' => $key])
                                    </div>
                                </div>
                            </div>
                            @endforeach
                          </div>
                      </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary" title="Save changes">
                    Save changes
                </button>
            </div>
          </form>
      </div>
      <!-- / Posts -->

    </div>
</div>

@include('backend.users.modal-change-photo')
@endsection

@section('scripts')
<script src="{{ asset('assets/tmplts_backend/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
<script src="{{ asset('assets/tmplts_backend/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('assets/tmplts_backend/fancybox/fancybox.min.js') }}"></script>
<script src="{{ asset('assets/tmplts_backend/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
@endsection

@section('jsbody')
<script src="{{ asset('assets/tmplts_backend/js/pages_account-settings.js') }}"></script>
<script>
    $('.confirm-remove').click(function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        Swal.fire({
            title: 'Are you sure remove photo ?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, remove it!'
        }).then((result) => {
            if (result.value) {
                $(this).find('form').submit();
            }
        })
    });

    $( ".datepicker" ).datepicker({
        format: 'yyyy-mm-dd',
        todayHighlight: true,
    });
    //show & hide password
    $(".toggle-current-password, .toggle-password, .toggle-password-confirm").click(function() {
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
