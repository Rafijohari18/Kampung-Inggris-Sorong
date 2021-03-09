@extends('layouts.backend.layout')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/tmplts_backend/vendor/libs/dropzone/dropzone.css') }}">
<link rel="stylesheet" href="{{ asset('assets/tmplts_backend/fancybox/fancybox.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/tmplts_backend/vendor/libs/sweetalert2/sweetalert2.css') }}">
@endsection

@section('content')
<!-- Filters -->
<div class="card">
    <div class="card-body">
        <div class="form-row align-items-center">
            <div class="col-md">
                <form action="" method="GET">
                    <div class="form-group">
                        <label class="form-label">Limit</label>
                        <select class="limit custom-select" name="l">
                            <option value="20" selected>Any</option>
                            @foreach (config('custom.filtering.limit') as $key => $val)
                            <option value="{{ $key }}" {{ Request::get('l') == ''.$key.'' ? 'selected' : '' }} title="Limit {{ $val }}">{{ $val }}</option>
                            @endforeach
                        </select>
                    </div>
            </div>
            <div class="col-md">
                    <div class="form-group">
                        <label class="form-label">Search</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="q" value="{{ Request::get('q') }}" placeholder="Keywords...">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-warning" title="klik untuk mencari"><i class="las la-filter"></i></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- / Filters -->

<div class="text-left">
    <a href="{{ route('gallery.playlist.index') }}" class="btn btn-secondary rounded-pill" title="back to list playlist"><i class="las la-arrow-left"></i>Back</a>
    &nbsp;
    <div class="btn-group dropdown ml-2">
        <button type="button" class="btn btn-primary dropdown-toggle hide-arrow icon-btn-only-sm" data-toggle="dropdown"><i class="las la-upload"></i><span>Upload</span></button>
        <div class="dropdown-menu dropdown-menu-right">
            <a href="{{ route('gallery.playlist.video.create', ['playlistId' => $data['playlist']->id]) }}" class="dropdown-item" title="add video">
                <i class="las la-file-upload"></i>
                <span>Form</span>
            </a>
            <a href="javascript:;" id="upload" class="dropdown-item" title="drag video">
                <i class="las la-hand-pointer"></i>
                <span>Drag / Drop</span>
            </a>
        </div>
    </div>
</div>
<br>

<div class="row drag card-default">
    @foreach ($data['video'] as $item)
    <div class="col-sm-6 col-xl-4" id="{{ $item->id }}" style="cursor: move;" title="change position">
        <div class="card card-list">
            <div class="w-100">
                <div class="card-img-top d-block ui-rect-60 ui-bg-cover" style="background-image: url({{ $item->videoType($item)['background'] }});">
                    <div class="d-flex justify-content-between align-items-end ui-rect-content p-3">
                        <div class="flex-shrink-1">

                        </div>
                        <div class="text-big">
                            <div class="badge badge-dark font-weight-bold">{{ $item->videoType($item)['name'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <h5 class="mb-3">
                    <a href="#" class="text-body">
                        {{ !empty($item->fieldLang('title')) ? Str::limit($item->fieldLang('title'), 60) : '-' }}
                    </a>
                </h5>
                <p class="text-muted mb-3">
                    {!! !empty($item->fieldLang('description')) ? strip_tags(Str::limit($item->fieldLang('description'), 70)) : '-' !!}
                </p>
                <div class="media">
                    <div class="media-body">
                        @if (!empty($item->file))
                        <button class="btn icon-btn btn-sm btn-info" data-toggle="modal" data-target="#preview-video-{{ $item->id }}" title="preview video">
                            <i class="las la-play text-white"></i>
                        </button>
                        @else
                        <a href="{{ $item->videoType($item)['video'] }}" class="btn icon-btn btn-sm btn-info" title="preview video" data-fancybox="gallery">
                            <i class="las la-play text-white"></i>
                        </a>
                        @endif
                        <a href="{{ route('gallery.playlist.video.edit', ['playlistId' => $item->playlist_id, 'id' => $item->id]) }}" class="btn icon-btn btn-sm btn-primary" title="edit video">
                            <i class="las la-pen"></i>
                        </a>
                        <a href="javascript:void(0);" data-playlistid="{{ $item->playlist_id }}" data-id="{{ $item->id }}" class="btn icon-btn btn-sm btn-danger js-delete" title="delete video">
                          <i class="las la-trash-alt text-white"></i>
                        </a>
                        &nbsp;&nbsp;
                        @if ($item->where('playlist_id', $item->playlist_id)->min('position') != $item->position)
                        <a href="javascript:void(0);" onclick="$(this).find('form').submit();" class="btn icon-btn btn-sm btn-dark" data-toggle="tooltip" data-original-title="Click to up position">
                            <i class="las la-arrow-up"></i>
                            <form action="{{ route('gallery.playlist.video.position', ['playlistId' => $item->playlist_id, 'id' => $item->id, 'position' => ($item->position - 1)]) }}" method="POST">
                                @csrf
                                @method('PUT')
                            </form>
                        </a>
                        @else
                        <button type="button" class="btn icon-btn btn-sm btn-default" title="you are not allowed to take this action" disabled><i class="las la-arrow-up"></i></button>
                        @endif
                        @if ($item->where('playlist_id', $item->playlist_id)->max('position') != $item->position)
                        <a href="javascript:void(0);" onclick="$(this).find('form').submit();" class="btn icon-btn btn-sm btn-dark" data-toggle="tooltip" data-original-title="Click to down position">
                            <i class="las la-arrow-down"></i>
                            <form action="{{ route('gallery.playlist.video.position', ['playlistId' => $item->playlist_id, 'id' => $item->id, 'position' => ($item->position + 1)]) }}" method="POST">
                                @csrf
                                @method('PUT')
                            </form>
                        </a>
                        @else
                        <button type="button" class="btn icon-btn btn-sm btn-default" title="you are not allowed to take this action" disabled><i class="las la-arrow-down"></i></button>
                        @endif
                    </div>
                    <div class="text-muted small">
                        <i class="las la-user text-primary"></i>
                        <span>{{ $item->createBy->name }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div id="dropzone">
    <div class="dropzone needsclick" id="dropzone-upload">
        <div class="dz-message needsclick">
            Drop files here
            <span class="note needsclick">(Type File : <strong>{{ strtoupper(config('custom.mimes.gallery_video.m')) }}</strong>, Max Upload File <strong>10</strong></span>
        </div>
        <div class="fallback">
          <input name="file" type="file" multiple>
        </div>
    </div>
</div>

@if ($data['video']->total() == 0)
<div class="card card-default">
    <div class="card-body text-center">
        <strong style="color: red;">
            @if (Request::get('l') || Request::get('q'))
            ! Video not found :( !
            @else
            ! Video not record !
            @endif
        </strong>
    </div>
</div>
@endif

@if ($data['video']->total() > 0)
<div class="card">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-lg-6 m--valign-middle">
                Showing : <strong>{{ $data['video']->firstItem() }}</strong> - <strong>{{ $data['video']->lastItem() }}</strong> of
                <strong>{{ $data['video']->total() }}</strong>
            </div>
            <div class="col-lg-6 m--align-right">
                {{ $data['video']->onEachSide(1)->links() }}
            </div>
        </div>
    </div>
</div>
@endif

@include('backend.gallery.playlists.video.modal-preview')
@endsection

@section('scripts')
<script src="{{ asset('assets/tmplts_backend/vendor/libs/dropzone/dropzone.js') }}"></script>
<script src="{{ asset('assets/tmplts_backend/fancybox/fancybox.min.js') }}"></script>
<script src="{{ asset('assets/tmplts_backend/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
@endsection

@section('jsbody')
<script src="{{ asset('assets/tmplts_backend/jquery-ui.js') }}"></script>
<script src="{{ asset('assets/tmplts_backend/js/pages_gallery.js') }}"></script>
<script>
    //sort
    $(function () {
        $(".drag").sortable({
            connectWith: '.drag',
            update : function (event, ui) {
                var data  = $(this).sortable('toArray');
                var id = '{{ $data['playlist']->id }}';
                $.ajax({
                    data: {'datas' : data},
                    url: '/admin/management/gallery/playlist/'+ id +'/video/sort',
                    type: 'POST',
                    dataType:'json',
                });
                if (data) {
                    location.reload();
                }
            }
        });
        $( "#drag" ).disableSelection();
    });
    $(document).ready(function () {
        //form upload
        $('.card-default').show();
        $("#dropzone").hide();
        $('#upload').click(function(){
            $('.card-default').toggle('slow');
            $("#dropzone").toggle('slow');
        });
        //dropzone
        $('#dropzone-upload').dropzone({
            url: '/admin/management/gallery/playlist/{{ $data['playlist']->id }}/video',
            method:'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            parallelUploads: 2,
            maxFilesize: 0,
            maxFiles: 10,
            filesizeBase: 1000,
            // acceptedFiles:"image/*",
            paramName:"file",
            dictInvalidFileType:"Type file not allowed",
            addRemoveLinks: true,

            init : function () {
                this.on('complete', function () {
                    if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
                        toastr.options = {
                            "closeButton": true,
                            "debug": false,
                            "newestOnTop": false,
                            "progressBar": false,
                            "positionClass": "toast-top-center",
                            "preventDuplicates": false,
                            "onclick": null,
                            "showDuration": "300",
                            "hideDuration": "1000",
                            "timeOut": "5000",
                            "extendedTimeOut": "1000",
                            "showEasing": "swing",
                            "hideEasing": "linear",
                            "showMethod": "fadeIn",
                            "hideMethod": "fadeOut"
                        };
                        toastr.success('Upload image successfully', 'Success');

                        setTimeout(() => window.location.reload(), 1500);
                    }
                });
            }
        });
        //delete
        $('.js-delete').on('click', function () {
            var playlist_id = $(this).attr('data-playlistid');
            var id = $(this).attr('data-id');
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                type: "warning",
                confirmButtonText: "Yes, delete!",
                customClass: {
                    confirmButton: "btn btn-danger btn-lg",
                    cancelButton: "btn btn-info btn-lg"
                },
                showLoaderOnConfirm: true,
                showCancelButton: true,
                allowOutsideClick: () => !Swal.isLoading(),
                cancelButtonText: "No, thanks",
                preConfirm: () => {
                    return $.ajax({
                        url: '/admin/management/gallery/playlist/'+ playlist_id +'/video/'+ id,
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        dataType: 'json'
                    }).then(response => {
                        if (!response.success) {
                            return new Error(response.message);
                        }
                        return response;
                    }).catch(error => {
                        swal({
                            type: 'error',
                            text: 'Error while deleting data. Error Message: ' + error
                        })
                    });
                }
            }).then(response => {
                if (response.value.success) {
                    Swal.fire({
                        type: 'success',
                        text: 'video successfully deleted'
                    }).then(() => {
                        window.location.reload();
                    })
                } else {
                    Swal.fire({
                        type: 'error',
                        text: response.value.message
                    }).then(() => {
                        window.location.reload();
                    })
                }
            });
        })
    });
</script>

@include('backend.components.toastr')
@endsection
