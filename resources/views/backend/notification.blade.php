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
                    <label class="form-label">Status</label>
                    <select class="status custom-select" name="r">
                        <option value=" " selected>Any</option>
                        <option value="1" {{ Request::get('r') == '1' ? 'selected' : '' }} title="Filter by Read">Read</option>
                        <option value="0" {{ Request::get('r') == '0' ? 'selected' : '' }} title="Filter by Un-Read">Un-Read</option>
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

<!-- Messages list -->
<div class="card">
    <h5 class="card-header media flex-wrap align-items-center py-4">
        <div class="media-body"><i class="las la-bell"></i> Notification @if($data['notifications']->where('read', 0)->count() > 0) <div class="badge badge-danger">{{ $data['notifications']->where('read', 0)->count() }}</div> @endif</div>
    </h5>
    <div class="table-responsive">
        <table id="user-list" class="table card-table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th style="width: 10px;">No</th>
                    <th>From</th>
                    <th>Title</th>
                    <th>Message</th>
                    <th style="width: 80px;" class="text-center">Status</th>
                    <th style="width: 230px;">Submit Time</th>
                    <th style="width: 115px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @if ($data['notifications']->total() == 0)
                <tr>
                    <td colspan="7" align="center">
                        <i>
                            <strong style="color:red;">
                            @if (Request::get('l') || Request::get('r')|| Request::get('q'))
                            ! Inquiries not found :( !
                            @else
                            ! Inquiries not record !
                            @endif
                            </strong>
                        </i>
                    </td>
                </tr>
                @endif
                @foreach ($data['notifications'] as $item)
                <tr>
                    <td>{{ $data['no'] }}</td>
                    <td>{{ !empty($item->user_from) ? $item->fromUser->name : 'Visitor' }}</td>
                    <td>{!! $item->title !!}</td>
                    <td>{!! $item->content !!}</td>
                    <td class="text-center">
                        <span class="badge badge-{{ $item->read == 1 ? 'success' : 'danger' }}">{{ $item->read == 1 ? 'Read' : 'Un-Read' }}</span>
                    </td>
                    <td>{{ $item->created_at->format('d F Y (H:i A)') }}</td>
                    <td>
                        <a href="{{ url($item->link) }}" class="btn icon-btn btn-sm btn-info" title="detail notification">
                            <i class="las la-eye"></i>
                        </a>
                        <a href="javascript:;" data-id="{{ $item->id }}" class="btn icon-btn btn-sm btn-danger js-delete" title="delete notification">
                            <i class="las la-trash"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        <div class="row align-items-center">
            <div class="col-lg-6 m--valign-middle">
                Showing : <strong>{{ $data['notifications']->firstItem() }}</strong> - <strong>{{ $data['notifications']->lastItem() }}</strong> of
                <strong>{{ $data['notifications']->total() }}</strong>
            </div>
            <div class="col-lg-6 m--align-right">
                {{ $data['notifications']->onEachSide(1)->links() }}
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
    $(document).ready(function () {
        //alert delete
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
                        url: '/admin/notification/' + id,
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
                        text: 'notification successfully deleted'
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
