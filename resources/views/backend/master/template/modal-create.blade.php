<div class="modal fade" id="modals-create">
    <div class="modal-dialog">
      <form class="modal-content" action="{{ route('template.store') }}" method="POST" id="form-create">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">
            Create
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
              <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" placeholder="enter name...">
              @include('backend.components.field-error', ['field' => 'name'])
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col">
              <label class="form-label">Module</label>
              <select class="custom-select" name="module" data-style="btn-default">
                <option value="" selected disabled>Select</option>
                @foreach (config('custom.label.template_module') as $key => $module)
                    <option value="{{ $key }}" {{ (old('module') == ''.$key.'') ? 'selected' : '' }}>
                        {{ str_replace('_', ' ', ucfirst($module)) }}
                    </option>
                @endforeach
                @include('backend.components.field-error', ['field' => 'module'])
              </select>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col">
              <label class="form-label">Type Template</label>
              <select class="custom-select" name="type" data-style="btn-default">
                <option value="" selected disabled>Select</option>
                @foreach (config('custom.label.template_type') as $key => $type)
                    <option value="{{ $key }}" {{ (old('type') == ''.$key.'') ? 'selected' : '' }}>
                        {{ str_replace('_', ' ', ucfirst($type)) }}
                    </option>
                @endforeach
                @include('backend.components.field-error', ['field' => 'type'])
              </select>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col">
              <label class="form-label">Filename</label>
              <input type="text" class="form-control @error('filename') is-invalid @enderror" name="filename" value="{{ old('filename') }}" placeholder="example : detail-custom">
              @include('backend.components.field-error', ['field' => 'filename'])
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col">
              <label class="form-label">Content</label>
              <textarea class="form-control tiny-mce" name="content">{!! old('content') !!}</textarea>
              @include('backend.components.field-error', ['field' => 'content'])
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
