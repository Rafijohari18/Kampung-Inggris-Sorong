<div class="modal fade" id="modals-change-photo">
    <div class="modal-dialog">
      <form class="modal-content" action="{{ route('profile.photo.change') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="modal-header">
          <h5 class="modal-title">
            Change
            <span class="font-weight-light">Photo</span>
            {{-- <br>
            <small class="text-muted">form is required & name is unique</small> --}}
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
        </div>
        <div class="modal-body">
            <div class="form-row">
              <div class="form-group col">
                <label class="form-label">Photo</label>
                <label class="custom-file-label" for="upload-2"></label>
                <input type="hidden" name="old_photo_file" value="{{ $data['user']->profile_photo_path['filename'] }}">
                <input class="form-control custom-file-input file @error('photo_file') is-invalid @enderror" type="file" id="upload-2" lang="en" name="photo_file" placeholder="enter photo...">
                @include('backend.components.field-error', ['field' => 'photo_file'])
                <small class="text-muted">File Type : <strong>{{ strtoupper(config('custom.mimes.photo.m')) }}</strong>, Pixel : <strong>{{ strtoupper(config('custom.mimes.photo.p')) }}</strong></small>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col mb-0">
                <label class="form-label">Title</label>
                <input type="text" class="form-control" name="photo_title" value="{{ old('photo_title', $data['user']->profile_photo_path['title']) }}" placeholder="title photo...">
              </div>
              <div class="form-group col mb-0">
                <label class="form-label">Alt</label>
                <input type="text" class="form-control" name="photo_alt" value="{{ old('photo_alt', $data['user']->profile_photo_path['alt']) }}" placeholder="alt photo...">
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
