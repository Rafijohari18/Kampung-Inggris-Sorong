@extends('layouts.backend.layout')

@section('content')
<div class="card mb-4">
    <h6 class="card-header">
        Give Permission For <i class="lab la-slack"></i><strong>{{ strtoupper($data['role']->name) }}</strong>
    </h6>
    <form action="{{ route('role.permission', ['id' => $data['role']->id]) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="table-responsive mb-4">
                <table class="table mb-0 table-bordered">
                    <thead>
                        <tr>
                          <th>Permission</th>
                          <th>Allow</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($data['permission'] as $item)
                            <tr>
                                <td><strong>{!! strtoupper(str_replace('_', ' ', $item->name)) !!}</strong></td>
                                <td>
                                    <label class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input check-parent" data-id="{{ $item->id }}" name="permission[]" value="{{ $item->id }}"
                                            {{ in_array($item->id, $data['permission_id']) ? 'checked' : '' }}>
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="custom-control-label">Allowed</span>
                                    </label>
                                </td>
                            </tr>
                            @foreach ($item->where('parent', $item->id)->get() as $child)
                            @php
                                $parentName = substr_replace($item->name, '', -1);
                                $childName = str_replace($parentName.'_', '', $child->name)
                            @endphp
                                <tr>
                                    <td><i class="lab la-slack"></i> --- <i>{!! strtoupper(str_replace('_', ' ', $childName)) !!}</i></td>
                                    <td>
                                        <label class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input check-child-{{ $child->parent }}" name="permission[]" value="{{ $child->id }}"
                                                {{ in_array($child->id, $data['permission_id']) ? 'checked' : '' }}>
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="custom-control-label">Allowed</span>
                                        </label>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            <div class="d-flex justify-content-center">
                <a href="{{ route('role.index') }}" class="btn btn-secondary" title="Cancel">Cancel</a>&nbsp;&nbsp;
                <button type="submit" class="btn btn-primary" name="action" value="back" title="Save changes">
                    Save changes
                </button>&nbsp;&nbsp;
                <button type="submit" class="btn btn-danger" name="action" value="exit" title="Save changes & Exit">
                    Save changes & Exit
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@section('jsbody')
<script>
    $(".check-parent").click(function () {
        var parent = $(this).attr('data-id');
       $('.check-child-' + parent).not(this).prop('checked', this.checked);
   });
</script>

@include('backend.components.toastr')
@endsection
