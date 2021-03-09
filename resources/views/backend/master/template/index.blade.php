@extends('layouts.backend.layout')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/tmplts_backend/vendor/libs/sweetalert2/sweetalert2.css') }}">
<script src="{{ asset('assets/tmplts_backend/wysiwyg/tinymce.min.js') }}"></script>
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
                    <label class="form-label">Module</label>
                    <select class="module custom-select" name="m">
                        <option value=" " selected>Any</option>
                        @foreach (config('custom.label.template_module') as $key => $val)
                        <option value="{{ $key }}" {{ Request::get('m') == ''.$key.'' ? 'selected' : '' }} title="Filter by {{ str_replace('_', ' ', ucfirst($val)) }}">{{ str_replace('_', ' ', ucfirst($val)) }}</option>
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
        <h5 class="card-header-title mt-1 mb-0">Template List</h5>
        <div class="card-header-elements ml-auto">
            <button type="button" class="btn btn-success icon-btn-only-sm modal-create" data-toggle="modal" data-target="#modals-create" title="add template">
                <i class="las la-plus"></i> <span>Template</span>
            </button>
        </div>
    </div>
    <div class="table-responsive">
        <table id="user-list" class="table card-table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th style="width: 10px;">No</th>
                    <th>Name</th>
                    <th>Module</th>
                    <th>Type</th>
                    <th>File Path</th>
                    <th style="width: 215px;">Created</th>
                    <th style="width: 215px;">Updated</th>
                    <th style="width: 115px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @if ($data['template']->total() == 0)
                    <tr>
                        <td colspan="8" align="center">
                            <i>
                                <strong style="color:red;">
                                @if (Request::get('l') || Request::get('m') || Request::get('q'))
                                ! Template not found :( !
                                @else
                                ! Template not record !
                                @endif
                                </strong>
                            </i>
                        </td>
                    </tr>
                @endif
                @foreach ($data['template'] as $item)
                    <tr>
                        <td>{{ $data['no']++ }}</td>
                        <td><strong>{{ $item->name }}</strong></td>
                        <td><span class="badge badge-primary">{{ str_replace('_', ' ', strtoupper(config('custom.label.template_module.'.$item->module))) }}</span></td>
                        <td>{{ config('custom.label.template_type.'.$item->type) }}</td>
                        <td><code>{{ $item->file_path }}</code></td>
                        <td>{{ $item->created_at->format('d F Y (H:i A)') }}</td>
                        <td>{{ $item->updated_at->format('d F Y (H:i A)') }}</td>
                        <td>
                            {{-- <button type="button" class="btn btn-primary icon-btn btn-sm modal-edit" title="edit template"
                                data-toggle="modal"
                                data-target="#modals-edit"
                                data-id="{{ $item->id }}"
                                data-name="{{ $item->name }}"
                                data-content="{!! $item->content !!}">
                                <i class="las la-pen"></i>
                            </button> --}}
                            <button type="button" class="btn btn-danger icon-btn btn-sm sa-delete" title="delete template"
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
                Showing : <strong>{{ $data['template']->firstItem() }}</strong> - <strong>{{ $data['template']->lastItem() }}</strong> of
                <strong>{{ $data['template']->total() }}</strong>
            </div>
            <div class="col-lg-6 m--align-right">
                {{ $data['template']->onEachSide(1)->links() }}
            </div>
        </div>
    </div>
</div>

@include('backend.master.template.modal-create')
@include('backend.master.template.modal-edit')
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
        var content = $(this).data('content');
        var url = '/admin/managament/template/' + id;

        $(".modal-dialog #form-edit").attr('action', url);
        $('.modal-body #name').val(name);
        $('.modal-body #content').val(content);
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
                        url: '/admin/managament/template/' + id,
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
                        text: 'template successfully deleted'
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

@include('backend.includes.tinymce-fm')
@include('backend.components.toastr')
@endsection
