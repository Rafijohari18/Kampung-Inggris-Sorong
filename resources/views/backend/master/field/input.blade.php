<div class="form-group row">
    <label class="col-form-label col-sm-2 text-sm-right">{{ $field->label }}</label>
    <div class="col-sm-10">
        @if ($field->type == 0)
        <input type="{{ config('custom.label.type_form.'.$field->type) }}" class="form-control"
            name="{{ 'field_'.$field->name }}" placeholder="enter {{ $field->label }}..."
            value="{{ !empty($data[''.$module.'']->custom_field[$field->name]) ?
             old('field_'.$field->name, $data[''.$module.'']->custom_field[$field->name]) : 
             old('field_'.$field->name) }}">
        @else
        <textarea class="form-control" name="{{ 'field_'.$field->name }}" placeholder="enter {{ $field->label }}...">{{ !empty($data[''.$module.'']->custom_field) ? old('field_'.$field->name, $data[''.$module.'']->custom_field[$field->name]) : old('field_'.$field->name) }}</textarea>
        @endif
    </div>
</div>
