@extends('layouts.backend.layout')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/tmplts_backend/vendor/libs/sweetalert2/sweetalert2.css') }}">
@endsection

@section('content')
<div class="media align-items-center py-3 mb-3">
    <img src="{{ $data['user']->getPhoto($data['user']->profile_photo_path['filename']) }}" alt="" class="d-block ui-w-100 rounded-circle">
    <div class="media-body ml-4">
      <h4 class="font-weight-bold mb-0">{{ $data['user']->name }} <span class="text-muted font-weight-normal"><span class="badge badge-primary">{{ strtoupper($data['user']->roles[0]->name) }}</span></span></h4>
      <div class="text-muted mb-2">Last Activity : <strong>{{ !empty($data['user']->session->last_activity) ? $data['user']->session->last_activity->format('l, j F Y (H:i A)') : '' }}</strong></div>
      <div class="text-muted mb-2">IP Address : <strong>{{ $data['user']->session->ip_address }}</strong></div>
    </div>
</div>

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
        <h5 class="card-header-title mt-1 mb-0">Logs List</h5>
        <div class="card-header-elements ml-auto">
        </div>
    </div>
    <div class="table-responsive">
        <table id="user-list" class="table card-table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th style="width: 10px;">No</th>
                    <th>Module</th>
                    <th>Type</th>
                    <th>User</th>
                    <th>IP Address</th>
                    <th style="width: 215px;">Times</th>
                    <th style="width: 80px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @if ($data['logs']->total() == 0)
                    <tr>
                        <td colspan="7" align="center">
                            <i>
                                <strong style="color:red;">
                                @if (Request::get('q'))
                                ! Logs not found :( !
                                @else
                                ! Logs not record !
                                @endif
                                </strong>
                            </i>
                        </td>
                    </tr>
                @endif
                @foreach ($data['logs'] as $item)
                    <tr>
                        <td>{{ $data['no']++ }}</td>
                        <td><strong>{{ str_replace('_', ' ', strtoupper(trans($item->logable_name))) }} ({{ $item->logable_id }})</strong></td>
                        <td>
                            @if ($item->event == 'created')
                                <span class="badge badge-success">{{ strtoupper($item->event) }}</span>
                            @elseif ($item->event == 'updated')
                                <span class="badge badge-primary">{{ strtoupper($item->event) }}</span>
                            @else
                                <span class="badge badge-danger">{{ strtoupper($item->event) }}</span>
                            @endif
                        </td>
                        <td>{{ $item->creator->name }}</td>
                        <td>{{ $item->ip_address }}</td>
                        <td>{{ $item->created_at->format('d F Y (H:i A)') }}</td>
                        <td>
                            <button type="button" class="btn btn-danger icon-btn btn-sm js-delete" title="delete log"
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
                Showing : <strong>{{ $data['logs']->firstItem() }}</strong> - <strong>{{ $data['logs']->lastItem() }}</strong> of
                <strong>{{ $data['logs']->total() }}</strong>
            </div>
            <div class="col-lg-6 m--align-right">
                {{ $data['logs']->onEachSide(1)->links() }}
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
    //delete
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
                        url: '/admin/management/user/delete/' + id + '/log',
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
                        text: 'log successfully deleted'
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
