<div class="modal fade" id="modals-create">
    <div class="modal-dialog">
      <form class="modal-content" action="{{ route('role.store') }}" method="POST" id="form-create">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">
            Create
            <span class="font-weight-light">Role</span>
            {{-- <br>
            <small class="text-muted">form is required & name is unique</small> --}}
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
        </div>
        <div class="modal-body">
          <div class="form-row">
            <div class="form-group col">
              <label class="form-label">Role Name</label>
              <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" placeholder="Sugested lower case">
              @include('backend.components.field-error', ['field' => 'name'])
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal" title="close modal">Close</button>
          <button type="submit" class="btn btn-primary" title="save">Save</button>
        </div>
      </form>
    </div>
</div>