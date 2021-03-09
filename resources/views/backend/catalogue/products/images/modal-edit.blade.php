<!-- Modal template -->
<div class="modal fade" id="modals-edit-image">
    <div class="modal-dialog">
        <form class="modal-content" action="" method="POST" enctype="multipart/form-data" id="form-edit-image">
        @csrf
        @method('PUT')
        <div class="modal-header">
            <h5 class="modal-title">
            Edit
            <span class="font-weight-light">Image</span>
            <br>
            </h5>
            <button type="button" class="close close-modal" data-dismiss="modal" aria-label="Close">×</button>
        </div>
        <div class="modal-body">
            <div class="form-row" id="form-thumbnail">
                <div class="form-group col">
                    <label class="form-label">Thumbnail</label>
                    <div class="row">
                        <div class="col-md-12">
                            <label class="custom-file mt-3">
                            <label class="custom-file-label mt-1" for="file-3"></label>
                            <input type="hidden" name="old_thumbnail" id="thumbnail">
                            <input type="file" class="form-control custom-file-input file @error('thumbnail') is-invalid @enderror" id="file-3" lang="en" name="thumbnail" value="browse...">
                            @include('backend.components.field-error', ['field' => 'thumbnail'])
                            <small>Tipe File : <strong>{{ strtoupper(config('custom.mimes.product_image.m')) }}</strong></small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col mb-0">
                    <label class="form-label">Title</label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') }}" id="title" placeholder="enter title">
                    @include('backend.components.field-error', ['field' => 'title'])
                </div>
                <div class="form-group col mb-0">
                    <label class="form-label">Alt</label>
                    <input type="text" class="form-control @error('alt') is-invalid @enderror" name="alt" value="{{ old('alt') }}" id="alt" placeholder="enter alt">
                    @include('backend.components.field-error', ['field' => 'alt'])
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col">
                    <label class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" name="description" id="description" placeholder="enter description">{{ old('description') }}</textarea>
                    @include('backend.components.field-error', ['field' => 'description'])
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default close-modal" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
        </form>
    </div>
</div>

{{-- <script>
    $('#old-thumb').hide();
    $('#show-thumb').click(function() {
        $('#old-thumb').toggle('slow');
    });
</script> --}}
