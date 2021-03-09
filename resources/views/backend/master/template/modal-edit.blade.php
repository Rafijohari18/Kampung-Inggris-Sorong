<div class="modal fade" id="modals-edit">
    <div class="modal-dialog">
      <form class="modal-content" action="" method="POST" id="form-edit">
        @csrf
        @method('PUT')
        <div class="modal-header">
          <h5 class="modal-title">
            Edit
            <span class="font-weight-light">Template</span>
            {{-- <br>
            <small class="text-muted">form is required & name is unique</small> --}}
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
        </div>
        <div class="modal-body">
          <div class="form-row">
            <div class="form-group col">
              <label class="form-label">Template Name</label>
              <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" placeholder="enter name...">
              @include('backend.components.field-error', ['field' => 'name'])
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col">
              <label class="form-label">Content</label>
              <textarea class="form-control tiny-mce" name="content" id="content"></textarea>
              @include('backend.components.field-error', ['field' => 'content'])
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
