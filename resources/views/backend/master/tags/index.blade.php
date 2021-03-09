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
                    <label class="form-label">Flags</label>
                    <select class="status custom-select" name="f">
                        <option value=" " selected>Any</option>
                        @foreach (config('custom.label.flags') as $key => $val)
                        <option value="{{ $key }}" {{ Request::get('f') == ''.$key.'' ? 'selected' : '' }} title="Filter by {{ $val['title'] }}">{{ $val['title'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md">
                <div class="form-group">
                    <label class="form-label">Standar</label>
                    <select class="status custom-select" name="s">
                        <option value=" " selected>Any</option>
                        @foreach (config('custom.label.true_false') as $key => $val)
                        <option value="{{ $key }}" {{ Request::get('s') == ''.$key.'' ? 'selected' : '' }} title="Filter by {{ $val['title'] }}">{{ $val['title'] }}</option>
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
        <h5 class="card-header-title mt-1 mb-0">Tags List</h5>
        <div class="card-header-elements ml-auto">
            @can ('tag_create')
            <a href="{{ route('tag.create') }}" class="btn btn-success icon-btn-only-sm" title="add tags">
                <i class="las la-plus"></i> <span>Tags</span>
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
                    <th>Creator</th>
                    <th style="width: 150px;">Flags</th>
                    <th style="width: 150px;">Standar</th>
                    <th style="width: 215px;">Created</th>
                    <th style="width: 215px;">Updated</th>
                    <th style="width: 115px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @if ($data['tags']->total() == 0)
                    <tr>
                        <td colspan="8" align="center">
                            <i>
                                <strong style="color:red;">
                                @if (Request::get('l') || Request::get('f') || Request::get('s') || Request::get('q'))
                                ! Tags not found :( !
                                @else
                                ! Tags not record !
                                @endif
                                </strong>
                            </i>
                        </td>
                    </tr>
                @endif
                @foreach ($data['tags'] as $item)
                    <tr>
                        <td>{{ $data['no']++ }} </td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->creator->name }}</td>
                        <td>
                            @can ('tag_edit')
                            <a href="javascript:void(0);" onclick="$(this).find('form').submit();" class="badge badge-{{ config('custom.label.flags.'.$item->flags)['color'] }}"
                                title="Click to change tags flags">
                                {{ config('custom.label.flags.'.$item->flags)['title'] }}
                                <form action="{{ route('tag.flags', ['id' => $item->id]) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                </form>
                            </a>
                            @else
                            <span class="badge badge-{{ config('custom.label.flags.'.$item->flags)['color'] }}">{{ config('custom.label.flags.'.$item->flags)['title'] }}</span>
                            @endcan
                        </td>
                        <td>
                            @can ('tag_edit')
                            <a href="javascript:void(0);" onclick="$(this).find('form').submit();" class="badge badge-{{ config('custom.label.true_false.'.$item->standar)['color'] }}"
                                title="Click to change tags standar">
                                {{ config('custom.label.true_false.'.$item->standar)['title'] }}
                                <form action="{{ route('tag.standar', ['id' => $item->id]) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                </form>
                            </a>
                            @else
                            <span class="badge badge-{{ config('custom.label.true_false.'.$item->standar)['color'] }}">{{ config('custom.label.true_false.'.$item->standar)['title'] }}</span>
                            @endcan
                        </td>
                        <td>{{ $item->created_at->format('d F Y (H:i A)') }}</td>
                        <td>{{ $item->updated_at->format('d F Y (H:i A)') }}</td>
                        <td>
                            @can ('tag_edit')
                            <a href="{{ route('tag.edit', ['id' => $item->id]) }}" class="btn btn-primary icon-btn btn-sm" title="edit tags">
                                <i class="las la-pen"></i>
                            </a>
                            @else
                            <button type="button" class="btn btn-primary icon-btn btn-sm" title="you are not allowed to take this action" disabled>
                                <i class="las la-pen"></i>
                            </button>
                            @endcan
                            @can ('tag_delete')
                            <button type="button" class="btn btn-danger icon-btn btn-sm js-delete" title="delete tags"
                                data-id="{{ $item->id }}">
                                <i class="las la-trash-alt"></i>
                            </button>
                            @else
                            <button type="button" class="btn btn-danger icon-btn btn-sm" title="you are not allowed to take this action" disabled>
                                <i class="las la-trash-alt"></i>
                            </button>
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
                Showing : <strong>{{ $data['tags']->firstItem() }}</strong> - <strong>{{ $data['tags']->lastItem() }}</strong> of
                <strong>{{ $data['tags']->total() }}</strong>
            </div>
            <div class="col-lg-6 m--align-right">
                {{ $data['tags']->onEachSide(1)->links() }}
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
                        url: '/admin/management/tag/' + id,
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
                        text: 'tags successfully deleted'
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
