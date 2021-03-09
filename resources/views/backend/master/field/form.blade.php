@extends('layouts.backend.layout')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/tmplts_backend/vendor/libs/sweetalert2/sweetalert2.css') }}">
@endsection

@section('content')
<div class="card mb-4">
    <h6 class="card-header">
      Setting Field For (<strong><i class="lab la-slack"></i> {{ strtoupper(Request::segment(5)) }}</strong>)
    </h6>
    <div class="card-body">
      <form action="{{ $data['routeStore'] }}" method="POST">
        @csrf
        @foreach ($data['module']->field as $item)
        <div class="form-row">
            <div class="form-group col-md-3">
                <label class="form-label">Label</label>
                <input type="text" class="form-control" value="{{ $item->label }}" readonly>
            </div>
            <div class="form-group col-md-3">
                <label class="form-label">Name</label>
                <input type="text" class="form-control" value="{{ $item->name }}" readonly>
            </div>
            <div class="form-group col-md-1">
                <label class="form-label">Type</label>
                <input type="text" class="form-control" value="{{ config('custom.label.type_form.'.$item->type) }}" readonly>
            </div>
            <div class="form-group col-md-4">
                <label class="form-label">Properties</label>
                <textarea type="text" class="form-control" style="margin-top: 0px; margin-bottom: 0px; height: 43px;" readonly>{{ $item->properties }}</textarea>
                @include('backend.components.field-error', ['field' => 'properties.0'])
            </div>
            <div class="form-group col-md-1">
                <button type="button" class="btn btn-primary icon-btn btn-sm mt-4 modal-edit"
                    data-toggle="modal"
                    data-target="#modals-edit"
                    data-id="{{ $item->id }}"
                    data-label="{{ $item->label }}"
                    data-name="{{ $item->name }}"
                    data-type="{{ $item->type }}"
                    data-properties="{{ $item->properties }}"
                    title="edit">
                    <i class="las la-pen"></i>
                </button>
                <button type="button" class="btn btn-danger icon-btn btn-sm mt-4 sa-delete" data-id="{{ $item->id }}" title="delete"><i class="las la-times"></i></button>
            </div>
        </div>
        @endforeach
        <div id="list">
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label class="form-label">Label</label>
                    <input type="text" class="form-control @error('label.0') is-invalid @enderror" name="label[]" value="{{ old('label.0') }}" placeholder="enter label...">
                    @include('backend.components.field-error', ['field' => 'label.0'])
                </div>
                <div class="form-group col-md-3">
                    <label class="form-label">Name</label>
                    <input type="text" class="form-control @error('name.0') is-invalid @enderror" name="name[]" value="{{ old('name.0') }}" placeholder="enter name...">
                    @include('backend.components.field-error', ['field' => 'name.0'])
                </div>
                <div class="form-group col-md-1">
                    <label class="form-label">Type</label>
                    <select class="custom-select form-control @error('type.0') is-invalid @enderror" name="type">
                        @foreach (config('custom.label.type_form') as $key => $value)
                            <option value="{{ $key }}" {{ old('type') ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label class="form-label">Properties</label>
                    <textarea type="text" class="form-control @error('properties.0') is-invalid @enderror" name="properties[]" placeholder="enter properties..." style="margin-top: 0px; margin-bottom: 0px; height: 43px;">{{ old('properties.0') }}</textarea>
                    @include('backend.components.field-error', ['field' => 'properties.0'])
                </div>
                <div class="form-group col-md-1">
                    <button type="button" class="btn btn-success icon-btn btn-sm mt-4" id="add"><i class="las la-plus"></i></button>
                </div>
            </div>
        </div>
        <hr>
        <div class="d-flex justify-content-center">
            <a href="{{ $data['routeBack'] }}" class="btn btn-secondary" title="Cancel">Cancel</a>&nbsp;&nbsp;
            <button type="submit" class="btn btn-primary" name="action" value="back" title="Save">
                Save
            </button>
            {{-- &nbsp;&nbsp;
            <button type="submit" class="btn btn-danger" name="action" value="exit" title="Save & Exit">
                Save & Exit
            </button> --}}
        </div>
      </form>
    </div>
</div>

@include('backend.master.field.modal-edit')
@endsection

@section('scripts')
<script src="{{ asset('assets/tmplts_backend/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
@endsection

@section('jsbody')
<script>
    //append
    $(function()  {
        var no=1;
        $("#add").click(function() {
            $("#list").append(`
                <div class="form-row" id="delete-`+no+`">
                    <div class="form-group col-md-3">
                        <label class="form-label">Label</label>
                        <input type="text" class="form-control @error('label.`+no+`') is-invalid @enderror" name="label[]" value="{{ old('label.`+no+`') }}" placeholder="enter label...">
                        @include('backend.components.field-error', ['field' => 'label.`+no+`'])
                    </div>
                    <div class="form-group col-md-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control @error('name.`+no+`') is-invalid @enderror" name="name[]" value="{{ old('name.`+no+`') }}" placeholder="enter name...">
                        @include('backend.components.field-error', ['field' => 'name.`+no+`'])
                    </div>
                    <div class="form-group col-md-1">
                        <label class="form-label">Type</label>
                        <select class="custom-select form-control @error('type.`+no+`') is-invalid @enderror" name="type">
                            @foreach (config('custom.label.type_form') as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label class="form-label">Properties</label>
                        <textarea type="text" class="form-control @error('properties.`+no+`') is-invalid @enderror" name="properties[]" placeholder="enter properties..." style="margin-top: 0px; margin-bottom: 0px; height: 43px;">{{ old('properties.`+no+`') }}</textarea>
                        @include('backend.components.field-error', ['field' => 'properties.`+no+`'])
                    </div>
                    <div class="form-group col-md-1">
                        <button type="button" class="btn btn-danger icon-btn btn-sm mt-4" id="remove" data-id="`+no+`"><i class="las la-times"></i></button>
                    </div>
                </div>
            `);

            no++;
        })
    });

    $(document).on('click', '#remove', function() {
        var id = $(this).attr("data-id");
        $("#delete-"+id).remove();
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
                        url: '/admin/management/field/' + id,
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
                        text: 'field successfully deleted'
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

    //modals edit
    $('.modal-edit').click(function() {
        var id = $(this).data('id');
        var label = $(this).data('label');
        var name = $(this).data('name');
        var type = $(this).data('type');
        var properties = $(this).data('properties');
        var url = '/admin/management/field/' + id;

        $(".modal-dialog #form-edit").attr('action', url);
        $('.modal-body #label').val(label);
        $('.modal-body #name').val(name);
        $('.modal-body #type, #selected').val(type);
        $('.modal-body #properties').val(properties);
    });
</script>

@include('backend.components.toastr')
@endsection
