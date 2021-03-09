@extends('layouts.backend.layout')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/tmplts_backend/vendor/css/pages/file-manager.css') }}">
<link rel="stylesheet" href="{{ asset('assets/tmplts_backend/vendor/libs/dropzone/dropzone.css') }}">
<link rel="stylesheet" href="{{ asset('assets/tmplts_backend/fancybox/fancybox.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/tmplts_backend/vendor/libs/sweetalert2/sweetalert2.css') }}">
@endsection

@section('content')
<div class="text-left">
    <a href="{{ $data['routeBack'] }}" class="btn btn-secondary rounded-pill" title="back to list"><i class="las la-arrow-left"></i>Back</a>
</div>
<br>

<div class="card">
    <div class="card-header with-elements">
        <div class="card-header-elements">
            <h5 class="card-header-title mt-1 mb-0">Media (<strong><i class="lab la-slack"></i> {{ strtoupper(Request::segment(5)) }}</strong>)</h5>
        </div>
        <div class="card-header-elements ml-auto">
            <div class="file-manager-actions">
                <a href="{{ route('media.create', ['id' => $data['module']->id, 'module' => Request::segment(5), 'sectionId' => Request::get('section')]) }}" class="btn btn-success icon-btn-only-sm" title="add media">
                    <i class="las la-plus"></i> <span>Media</span>
                </a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="file-manager-container file-manager-col-view drag">
                @foreach ($data['module']->media as $key => $file)
                <div class="file-item only" id="{{ $file->id }}" style="cursor: move;" title="move position">
                    @if ($file->is_youtube == 1)
                        <a href="https://www.youtube.com/embed/{{ $file->youtube_id }}?rel=0;showinfo=0" class="file-item-name" data-fancybox="gallery">
                        <div class="file-item-img" style="background-image: url(https://img.youtube.com/vi/{{ $file->youtube_id }}/mqdefault.jpg)"></div>
                            <div class="desc-of-name">
                                Youtube Video
                            </div>
                        </a>
                    @else
                        <a href="{{ Storage::url(config('custom.images.path.filemanager').$file->file_path['filename']) }}" class="file-item-name" data-fancybox="gallery">
                            @if ($file->icon($file) == 'image')
                            <div class="file-item-img" style="background-image: url({{ Storage::url(config('custom.images.path.filemanager').$file->file_path['filename']) }})"></div>
                            @else
                            <div class="file-item-icon las la-file-{{ $file->icon($file) }} text-secondary"></div>
                            @endif
                            <div class="desc-of-name">
                                {{ collect(explode("/", $file->file_path['filename']))->last() }}
                            </div>
                        </a>
                    @endif
                    <div class="file-item-actions btn-group dropdown">
                        <button type="button" class="btn btn-sm btn-default icon-btn dropdown-toggle btn-toggle-radius hide-arrow" data-toggle="dropdown"><i class="ion ion-ios-more"></i></button>
                        <div class="dropdown-menu dropdown-menu-right">
                          <a href="{{ route('media.edit', ['id' => Request::segment(4), 'module' => Request::segment(5), 'mediaId' => $file->id, 'sectionId' => Request::get('section')]) }}" class="dropdown-item" title="edit media">
                            <i class="las la-pen"></i> Edit
                          </a>
                          <a class="dropdown-item sa-delete" href="javascript:void(0)" data-id="{{ $file->id }}" title="delete media">
                            <i class="las la-trash-alt"></i> Hapus
                          </a>
                        </div>
                    </div>
                </div>
                @endforeach
            @if ($data['module']->media->count() == 0)
            <div class="file-item">
                <div class="file-item-icon las la-ban text-danger"></div>
                <div class="file-item-name">
                    ! Media not record !
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/tmplts_backend/vendor/libs/dropzone/dropzone.js') }}"></script>
<script src="{{ asset('assets/tmplts_backend/fancybox/fancybox.min.js') }}"></script>
<script src="{{ asset('assets/tmplts_backend/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
@endsection

@section('jsbody')
<script src="{{ asset('assets/tmplts_backend/jquery-ui.js') }}"></script>
<script>
     //sort
     $(function () {
        $(".drag").sortable({
            connectWith: '.drag',
            update : function (event, ui) {
                var data  = $(this).sortable('toArray');
                var id = "{{ Request::segment(4) }}";
                var model = "{{ Request::segment(5) }}";
                $.ajax({
                    data: {'datas' : data},
                    url: '/admin/media/'+ id +'/'+ model +'/sort',
                    type: 'POST',
                    dataType:'json',
                });
                // if (data) {
                //     location.reload();
                // }
            }
        });
        $( "#drag" ).disableSelection();
    });
    //alert delete
    $(document).ready(function () {
        $('.sa-delete').on('click', function () {
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
                        url: '/admin/management/media/' + id + '/delete',
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
                        text: 'media successfully deleted'
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
        });
    });
</script>

@include('backend.components.toastr')
@endsection
