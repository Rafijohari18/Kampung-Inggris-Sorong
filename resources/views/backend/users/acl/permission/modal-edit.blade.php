<div class="modal fade" id="modals-edit">
    <div class="modal-dialog">
      <form class="modal-content" action="" method="POST" id="form-edit">
        @csrf
        @method('PUT')
        <div class="modal-header">
          <h5 class="modal-title">
            Edit
            <span class="font-weight-light">Permission</span>
            {{-- <br>
            <small class="text-muted">form is required & name is unique</small> --}}
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
        </div>
        <div class="modal-body">
          <div class="form-row">
            <div class="form-group col">
              <label class="form-label">Permission Name</label>
              <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" placeholder="Sugested lower case">
              @include('backend.components.field-error', ['field' => 'name'])
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal" title="close modal">Close</button>
          <button type="submit" class="btn btn-primary" title="save">Save changes</button>
        </div>
      </form>
    </div>
</div>