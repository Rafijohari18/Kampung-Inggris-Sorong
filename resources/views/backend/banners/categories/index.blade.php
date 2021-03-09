@extends('layouts.backend.layout')

@section('styles')
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
<div class="card">
    <div class="card-header with-elements">
        <h5 class="card-header-title mt-1 mb-0">Banners Categories List</h5>
        <div class="card-header-elements ml-auto">
            @can ('banner_category_create')
            <a href="{{ route('banner.category.create') }}" class="btn btn-success icon-btn-only-sm" title="add banner category">
                <i class="las la-plus"></i> <span>Banner Category</span>
            </a>
            @endcan
        </div>
    </div>
    <div class="table-responsive">
        <table id="user-list" class="table card-table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th style="width: 10px;">No</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th style="width: 100px;">Banner</th>
                    <th style="width: 80px;">Limit</th>
                    <th style="width: 230px;">Created</th>
                    <th style="width: 230px;">Updated</th>
                    <th style="width: 175px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @if ($data['banner_categories']->total() == 0)
                    <tr>
                        <td colspan="8" align="center">
                            <i>
                                <strong style="color:red;">
                                @if (Request::get('l') || Request::get('q'))
                                ! Banner Category not found :( !
                                @else
                                ! Banner Category not record !
                                @endif
                                </strong>
                            </i>
                        </td>
                    </tr>
                @endif
                @foreach ($data['banner_categories'] as $item)
                <tr>
                    <td>{{ $data['no']++ }}</td>
                    <td><strong>{!! Str::limit($item->fieldLang('name'), 80) !!}</strong></td>
                    <td>{!! !empty($item->fieldLang('description'))? strip_tags(Str::limit($item->fieldLang('description')), 100) : '-' !!}</td>
                    <td>Total : <span class="badge badge-success">{{ $item->banner->count() }}</span></td>
                    <td class="text-center"><span class="badge badge-primary">{{ !empty($item->list_limit) ? $item->list_limit : 'Default Config' }}</span></td>
                    <td>
                        <span class="text-muted">by {{ $item->createBy->name }}</span><br>
                        {{ $item->created_at->format('d F Y (H:i A)') }}
                    </td>
                    <td>
                        <span class="text-muted">by {{  $item->updateBy->name }}</span><br>
                        {{ $item->updated_at->format('d F Y (H:i A)') }}
                    </td>
                    <td>
                        @can('fields')
                        <a href="{{ route('field.form', ['id' => $item->id, 'module' => 'banner_category']) }}" class="btn icon-btn btn-sm btn-secondary" title="setting addon field">
                            <i class="las la-cog"></i>
                        </a>
                        @else
                        <button class="btn icon-btn btn-sm btn-secondary" title="you are not allowed to take this action" disabled><i class="las la-cog"></i></button>
                        @endcan
                        @can ('banners')
                        <a href="{{ route('banner.index', ['categoryId' => $item->id]) }}" class="btn btn-sm icon-btn btn-info" title="list banner">
                            <i class="las la-image"></i>
                        </a>
                        @else
                        <button class="btn btn-sm icon-btn btn-secondary" title="you are not allowed to take this action" disabled><i class="las la-list"></i></button>
                        @endcan
                        @can('banner_category_edit')
                        <a href="{{ route('banner.category.edit', ['id' => $item->id]) }}" class="btn icon-btn btn-sm btn-primary" title="edit banner">
                            <i class="las la-pen"></i>
                        </a>
                        @else
                        <button class="btn icon-btn btn-sm btn-secondary" title="you are not allowed to take this action" disabled><i class="las la-pen"></i></button>
                        @endcan
                        @can('banner_category_delete')
                        <a href="javascript:;" data-id="{{ $item->id }}" class="btn icon-btn btn-sm btn-danger js-delete" title="delete banner">
                            <i class="las la-trash"></i>
                        </a>
                        @else
                        <button class="btn icon-btn btn-sm btn-secondary" title="you are not allowed to take this action" disabled><i class="las la-trash"></i></button>
                        @endcan
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        <div class="row align-items-center">
            <div class="col-lg-6 m--valign-middle">
                Showing : <strong>{{ $data['banner_categories']->firstItem() }}</strong> - <strong>{{ $data['banner_categories']->lastItem() }}</strong> of
                <strong>{{ $data['banner_categories']->total() }}</strong>
            </div>
            <div class="col-lg-6 m--align-right">
                {{ $data['banner_categories']->onEachSide(1)->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/tmplts_backend/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
@endsection

@section('jsbody')
<script>
    //alert delete
    $(document).ready(function () {
        $('.js-delete').on('click', function () {
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
                        url: '/admin/management/banner/' + id,
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
                        text: 'banner category successfully deleted'
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
