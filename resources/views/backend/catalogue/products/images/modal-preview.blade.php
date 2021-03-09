@foreach ($data['product_images'] as $item)
<div class="modal fade" id="preview-image-{{ $item->id }}">
    <div class="modal-dialog modal-lg" style="max-width:60%;">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">
            Preview
            <span class="font-weight-light">Video</span>
            <br>
            <small class="text-muted"></small>
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="las la-times"></i></button>
        </div>
        <div class="modal-body">
            <video style="width:100%; height:100%;" controls>
                <source src="{{ $item->fileType($item)['background'] }}" type="video/mp4">
                Your browser does not support HTML video.
            </video>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal" title="close">Close</button>
        </div>
      </div>
    </div>
</div>
@endforeach
