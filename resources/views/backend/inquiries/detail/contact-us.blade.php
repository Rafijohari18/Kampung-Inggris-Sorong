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
                    <select class="status custom-select" name="s">
                        <option value=" " selected>Any</option>
                        <option value="1" {{ Request::get('s') == '1' ? 'selected' : '' }} title="Filter by Read">Read</option>
                        <option value="0" {{ Request::get('s') == '0' ? 'selected' : '' }} title="Filter by Un-Read">Un-Read</option>
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
        <h5 class="card-header-title mt-1 mb-0">Inquiry Message List</h5>
        <div class="card-header-elements ml-auto">
            @can ('inquiry_detail')
                @if ($data['message']->total() > 0)
                <a href="{{ route('inquiry.detail.export', ['inquiryId' => $data['inquiry']->id]) }}" class="btn btn-success icon-btn-only-sm" title="export message">
                    <i class="las la-file-excel"></i> <span>Export</span>
                </a>
                @endif
            @endcan
        </div>
    </div>
    <div class="table-responsive">
        <table id="user-list" class="table card-table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th style="width: 10px;">No</th>
                    <th>IP Address</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th style="width: 80px;" class="text-center">Status</th>
                    <th style="width: 80px;" class="text-center">Exported</th>
                    <th style="width: 230px;">Submit Time</th>
                    <th style="width: 115px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @if ($data['message']->total() == 0)
                    <tr>
                        <td colspan="8" align="center">
                            <i>
                                <strong style="color:red;">
                                @if (Request::get('l') || Request::get('s')|| Request::get('q'))
                                ! Inquiries Message not found :( !
                                @else
                                ! Inquiries Message not record !
                                @endif
                                </strong>
                            </i>
                        </td>
                    </tr>
                @endif
                @foreach ($data['message'] as $item)
                <tr>
                    <td>{{ $data['no'] }}</td>
                    <td>{{ $item->ip_address }}</td>
                    <td>{{ $item->name }}</td>
                    <td><a href="mailto:{{ $item->email }}">{{ $item->email }}</a></td>
                    <td class="text-center">
                        <span class="badge badge-{{ $item->status == 1 ? 'success' : 'danger' }}">{{ $item->status == 1 ? 'Read' : 'Un-Read' }}</span>
                    </td>
                    <td class="text-center">
                        <span class="badge badge-{{ $item->exported == 1 ? 'secondary' : 'warning' }}">{{ $item->exported == 1 ? 'YES' : 'NO' }}</span>
                    </td>
                    <td>{{ $item->submit_time->format('d F Y (H:i A)') }}</td>
                    <td>
                        <button type="button" class="btn btn-info icon-btn btn-sm read-message"
                            data-inquiryid="{{ $item->inquiry_id }}"
                            data-id="{{ $item->id }}"
                            data-status="{{ $item->status }}"
                            data-toggle="modal"
                            data-target="#modals-detail-{{ $item->id }}" title="detail message">
                            <i class="las la-eye"></i>
                        </button>
                        <a href="javascript:;" data-inquiryid="{{ $item->inquiry_id }}" data-id="{{ $item->id }}" class="btn icon-btn btn-sm btn-danger js-delete" title="delete message">
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
                Showing : <strong>{{ $data['message']->firstItem() }}</strong> - <strong>{{ $data['message']->lastItem() }}</strong> of
                <strong>{{ $data['message']->total() }}</strong>
            </div>
            <div class="col-lg-6 m--align-right">
                {{ $data['message']->onEachSide(1)->links() }}
            </div>
        </div>
    </div>
</div>

@foreach ($data['message'] as $item)
<div class="modal fade" id="modals-detail-{{ $item->id }}">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">
            Detail
            <span class="font-weight-light">Message</span>
            {{-- <br>
            <small class="text-muted">form is required & name is unique</small> --}}
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
        </div>
        <div class="modal-body">
            <div class="form-row">
                <div class="form-group col">
                  <label class="form-label">Message From :</label> <br>
                  <em>{{ $item->name }}</em>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col">
                  <label class="form-label">Subject :</label> <br>
                  <em>{{ $item->custom_field['subject'] }}</em>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col">
                  <label class="form-label">Message :</label> <br>
                  <em>{{ $item->custom_field['message'] }}</em>
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal" title="close modal">Close</button>
        </div>
      </div>
    </div>
</div>
@endforeach
@endsection

@section('scripts')
<script src="{{ asset('assets/tmplts_backend/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
@endsection

@section('jsbody')
<script>
    $(document).ready(function () {
        //read
        $('.read-message').on('click', function () {
            var inquiry_id = $(this).attr('data-inquiryid');
            var id = $(this).attr('data-id');
            var status = $(this).attr('data-status');
            if (status == 0) {
                $.ajax({
                    url: '/admin/management/inquiry/' + inquiry_id + '/detail/' + id + '/read',
                    type: 'POST',
                });
            }
        });

        //alert delete
        $('.js-delete').on('click', function () {
            var inquiry_id = $(this).attr('data-inquiryid');
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
                        url: '/admin/management/inquiry/' + inquiry_id + '/detail/' + id,
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
                        text: 'message successfully deleted'
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
