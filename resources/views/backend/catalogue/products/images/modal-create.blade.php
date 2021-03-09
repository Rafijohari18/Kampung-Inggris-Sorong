<!-- Modal template -->
<div class="modal fade" id="modals-add-image">
    <div class="modal-dialog">
        <form class="modal-content" action="{{ route('catalog.product.image.store', ['productId' => $data['catalog_product']->id]) }}" method="POST" enctype="multipart/form-data" id="form-edit-file">
        @csrf
        <div class="modal-header">
            <h5 class="modal-title">
            Upload
            <span class="font-weight-light">Image / Video</span>
            <br>
            </h5>
            <button type="button" class="close close-modal" data-dismiss="modal" aria-label="Close">×</button>
        </div>
        <div class="modal-body">
            <div class="form-row">
                <div class="form-group col">
                    <label class="form-label">File</label>
                        <label class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" name="is_video" value="1" id="show-thumb">
                            <span class="custom-control-label ml-4">Video</span>
                        </label>
                        <label class="custom-file mt-3">
                        <label class="custom-file-label mt-1" for="file-1"></label>
                        <input type="file" class="form-control custom-file-input file @error('file') is-invalid @enderror" type="file" id="file-1" lang="en" name="file" value="browse...">
                        @include('backend.components.field-error', ['field' => 'file'])
                    </label>
                    <small>Type File : <strong>{{ strtoupper(config('custom.mimes.product_image.m').','.config('custom.mimes.product_image.mv')) }}</strong></small>
                </div>
            </div>
            <div class="form-row" id="thumbnail">
                <div class="form-group col">
                    <label class="form-label">Thumbnail</label>
                        <label class="custom-file mt-3">
                        <label class="custom-file-label mt-1" for="file-2"></label>
                        <input type="file" class="form-control custom-file-input file @error('thumbnail') is-invalid @enderror" type="file" id="file-2" lang="en" name="thumbnail" value="browse...">
                        @include('backend.components.field-error', ['field' => 'thumbnail'])
                    </label>
                    <small>Tipe File : <strong>{{ strtoupper(config('custom.mimes.product_image.m')) }}</strong></small>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col mb-0">
                    <label class="form-label">Title</label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') }}" placeholder="enter title">
                    @include('backend.components.field-error', ['field' => 'title'])
                </div>
                <div class="form-group col mb-0">
                    <label class="form-label">Alt</label>
                    <input type="text" class="form-control @error('alt') is-invalid @enderror" name="alt" value="{{ old('alt') }}" placeholder="enter alt">
                    @include('backend.components.field-error', ['field' => 'alt'])
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col">
                    <label class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" name="description" placeholder="enter description">{{ old('description') }}</textarea>
                    @include('backend.components.field-error', ['field' => 'description'])
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default close-modal" data-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
        </form>
    </div>
</div>

<script>
$("#thumbnail").hide();
$('#show-thumb').click(function() {
    if ($(this).val() == 1) {
        $('#thumbnail').toggle('slow');
    } else {
        $('#thumbnail').hide();
    }
});
</script>
