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

@include('backend.components.alert-error')

<div class="card">
    <div class="card-header with-elements">
        <h5 class="card-header-title mt-1 mb-0">Role List</h5>
        <div class="card-header-elements ml-auto">
            <button type="button" class="btn btn-success icon-btn-only-sm modal-create" data-toggle="modal" data-target="#modals-create" title="add role">
                <i class="las la-plus"></i> <span>Role</span>
            </button>
        </div>
    </div>
    <div class="table-responsive">
        <table id="user-list" class="table card-table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th style="width: 10px;">No</th>
                    <th>Name</th>
                    <th>Writing</th>
                    <th>Guard</th>
                    <th style="width: 215px;">Created</th>
                    <th style="width: 215px;">Updated</th>
                    <th style="width: 140px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @if ($data['roles']->total() == 0)
                    <tr>
                        <td colspan="7" align="center">
                            <i>
                                <strong style="color:red;">
                                @if (Request::get('q'))
                                ! Role not found :( !
                                @else
                                ! Role not record !
                                @endif
                                </strong>
                            </i>
                        </td>
                    </tr>
                @endif
                @foreach ($data['roles'] as $item)
                    <tr>
                        <td>{{ $data['no']++ }}</td>
                        <td><strong>{{ str_replace('_', ' ', strtoupper($item->name)) }}</strong></td>
                        <td><code>{{ $item->name }}</code></td>
                        <td>{{ $item->guard_name }}</td>
                        <td>{{ $item->created_at->format('d F Y (H:i A)') }}</td>
                        <td>{{ $item->updated_at->format('d F Y (H:i A)') }}</td>
                        <td>
                            <a href="{{ route('role.permission', ['id' => $item->id]) }}" class="btn btn-warning icon-btn btn-sm" title="set permission">
                                <i class="las la-user-shield"></i>
                            </a>
                            <button type="button" class="btn btn-primary icon-btn btn-sm modal-edit" title="edit role"
                                data-toggle="modal"
                                data-target="#modals-edit"
                                data-id="{{ $item->id }}"
                                data-name="{{ $item->name }}">
                                <i class="las la-pen"></i>
                            </button>
                            <button type="button" class="btn btn-danger icon-btn btn-sm sa-delete" title="delete role"
                                data-id="{{ $item->id }}">
                                <i class="las la-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        <div class="row align-items-center">
            <div class="col-lg-6 m--valign-middle">
                Showing : <strong>{{ $data['roles']->firstItem() }}</strong> - <strong>{{ $data['roles']->lastItem() }}</strong> of
                <strong>{{ $data['roles']->total() }}</strong>
            </div>
            <div class="col-lg-6 m--align-right">
                {{ $data['roles']->onEachSide(1)->links() }}
            </div>
        </div>
    </div>
</div>

@include('backend.users.acl.role.modal-create')
@include('backend.users.acl.role.modal-edit')
@endsection

@section('scripts')
<script src="{{ asset('assets/tmplts_backend/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
@endsection

@section('jsbody')
<script>
    //modals edit
    $('.modal-edit').click(function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        var url = '/admin/management/role/' + id;

        $(".modal-dialog #form-edit").attr('action', url);
        $('.modal-body #name').val(name);
    });
    //delete
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
                        url: '/admin/management/role/' + id,
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
                        text: 'role successfully deleted'
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
