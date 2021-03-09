@extends('layouts.backend.layout')

@section('styles')
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
    <a href="{{ route('banner.category.index') }}" class="btn btn-secondary rounded-pill" title="back to list category"><i class="las la-arrow-left"></i>Back</a>
    &nbsp;
    <a href="{{ route('banner.create', ['categoryId' => $data['banner_category']->id]) }}" class="btn btn-success rounded-pill" title="add banner"><i class="las la-plus"></i>Banner</a>
</div>
<br>

<div class="row drag">
    @foreach ($data['banners'] as $item)
    <div class="col-sm-6 col-xl-4" id="{{ $item->id }}" style="cursor: move;" title="change position">
        <div class="card card-list">
            <div class="w-100">
                <div class="card-img-top d-block ui-rect-60 ui-bg-cover" style="background-image: url({{ $item->bannerType($item)['background'] }});">
                    <div class="d-flex justify-content-between align-items-end ui-rect-content p-3">
                        <div class="flex-shrink-1">
                            <a href="javascript:void(0);" onclick="$(this).find('form').submit();" title="click to {{ $item->publish == 1 ? 'publish' : 'un-publish' }} banner">
                                <span class="badge badge-{{ $item->publish == 1 ? 'success' : 'warning' }}">{{ $item->publish == 1 ? 'PUBLISH' : 'DRAFT' }}</span>
                                <form action="{{ route('banner.publish', ['categoryId' => $item->banner_category_id, 'id' => $item->id]) }}" method="POST">
                                  @csrf
                                  @method('PUT')
                                </form>
                            </a>
                        </div>
                        <div class="text-big">
                            <div class="badge badge-dark font-weight-bold">{{ $item->bannerType($item)['name'] }}</div>
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
                        @if ($item->is_video == 1 && !empty($item->file))
                        <button class="btn icon-btn btn-sm btn-info modals-preview" data-toggle="modal" data-target="#preview-banner-{{ $item->id }}" title="preview banner">
                            <i class="las la-play text-white"></i>
                        </button>
                        @else
                        <a href="{{ !empty($item->youtube_id) ? $item->bannerType($item)['video'] : $item->bannerType($item)['background'] }}" class="btn icon-btn btn-sm btn-info" title="preview banner" data-fancybox="gallery">
                            <i class="las la-play text-white"></i>
                        </a>
                        @endif
                        @can ('banner_edit')
                        <a href="{{ route('banner.edit', ['categoryId' => $item->banner_category_id, 'id' => $item->id]) }}" class="btn icon-btn btn-sm btn-primary" title="edit banner">
                            <i class="las la-pen text-white"></i>
                        </a>
                        @else
                        <button class="btn icon-btn btn-sm btn-secondary" title="you are not allowed to take this action" disabled><i class="las la-pen"></i></button>
                        @endcan
                        @can ('banner_delete')
                        <a href="javascript:void(0);" data-categoryid="{{ $item->banner_category_id }}"  data-id="{{ $item->id }}" class="btn icon-btn btn-sm btn-danger js-delete" title="delete banner">
                          <i class="las la-trash-alt text-white"></i>
                        </a>
                        @else
                        <button class="btn icon-btn btn-sm btn-secondary" title="you are not allowed to take this action" disabled><i class="las la-trash"></i></button>
                        @endcan
                        &nbsp;&nbsp;
                        @if (auth()->user()->can('banner_edit') && $item->where('banner_category_id', $item->banner_category_id)->min('position') != $item->position)
                        <a href="javascript:void(0);" onclick="$(this).find('form').submit();" class="btn icon-btn btn-sm btn-dark" data-toggle="tooltip" data-original-title="Click to up position">
                            <i class="las la-arrow-up"></i>
                            <form action="{{ route('banner.position', ['categoryId' => $item->banner_category_id, 'id' => $item->id, 'position' => ($item->position - 1)]) }}" method="POST">
                                @csrf
                                @method('PUT')
                            </form>
                        </a>
                        @else
                        <button type="button" class="btn icon-btn btn-sm btn-default" title="you are not allowed to take this action" disabled><i class="las la-arrow-up"></i></button>
                        @endif
                        @if (auth()->user()->can('banner_edit') && $item->where('banner_category_id', $item->banner_category_id)->max('position') != $item->position)
                        <a href="javascript:void(0);" onclick="$(this).find('form').submit();" class="btn icon-btn btn-sm btn-dark" data-toggle="tooltip" data-original-title="Click to down position">
                            <i class="las la-arrow-down"></i>
                            <form action="{{ route('banner.position', ['categoryId' => $item->banner_category_id, 'id' => $item->id, 'position' => ($item->position + 1)]) }}" method="POST">
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

@if ($data['banners']->total() == 0)
<div class="card">
    <div class="card-body text-center">
        <strong style="color: red;">
            @if (Request::get('l') || Request::get('q'))
            ! Banner not found :( !
            @else
            ! Banner not record !
            @endif
        </strong>
    </div>
</div>
@endif

@if ($data['banners']->total() > 0)
<div class="card">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-lg-6 m--valign-middle">
                Showing : <strong>{{ $data['banners']->firstItem() }}</strong> - <strong>{{ $data['banners']->lastItem() }}</strong> of
                <strong>{{ $data['banners']->total() }}</strong>
            </div>
            <div class="col-lg-6 m--align-right">
                {{ $data['banners']->onEachSide(1)->links() }}
            </div>
        </div>
    </div>
</div>
@endif

@include('backend.banners.modal-preview')
@endsection

@section('scripts')
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
                var id = '{{ $data['banner_category']->id }}';
                $.ajax({
                    data: {'datas' : data},
                    url: '/admin/management/banner/category/'+ id +'/sort',
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
    //delete
    $(document).ready(function () {
        $('.js-delete').on('click', function () {
            var category_id = $(this).attr('data-categoryid');
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
                        url: '/admin/management/banner/category/'+ category_id +'/'+ id,
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
                        text: 'banner successfully deleted'
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
