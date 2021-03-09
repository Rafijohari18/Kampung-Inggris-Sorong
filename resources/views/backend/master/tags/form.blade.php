@extends('layouts.backend.layout')

@section('styles')
<script src="{{ asset('assets/tmplts_backend/wysiwyg/tinymce.min.js') }}"></script>
@endsection

@section('content')
<div class="card mb-4">
    <h6 class="card-header">
      Tags Form
    </h6>
    <div class="card-body">
      <form action="{{ !isset($data['tag']) ? route('tag.store') : route('tag.update', ['id' => $data['tag']->id]) }}" method="POST">
        @csrf
        @if (isset($data['tag']))
            @method('PUT')
        @endif
        <div class="form-group row">
            <label class="col-form-label col-sm-2 text-sm-right">Name</label>
            <div class="col-sm-10">
              <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                    value="{{ (isset($data['tag'])) ? old('name', $data['tag']->name) : old('name') }}" placeholder="enter name...">
              @include('backend.components.field-error', ['field' => 'name'])
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-2 text-md-right">
              <label class="col-form-label text-sm-right">Description</label>
            </div>
            <div class="col-md-10">
                <textarea class="form-control tiny-mce @error('description') is-invalid @enderror" name="description" placeholder="enter description...">{!! (isset($data['tag'])) ? old('description', $data['tag']->description) : old('description') !!}</textarea>
                @include('backend.components.field-error', ['field' => 'description'])
            </div>
        </div>
        <hr>
        <div class="d-flex justify-content-center">
            <a href="{{ route('tag.index') }}" class="btn btn-secondary" title="Cancel">Cancel</a>&nbsp;&nbsp;
            <button type="submit" class="btn btn-primary" name="action" value="back" title="{{ isset($data['tag']) ? 'Save changes' : 'Save' }}">
                {{ isset($data['tag']) ? 'Save changes' : 'Save' }}
            </button>&nbsp;&nbsp;
            <button type="submit" class="btn btn-danger" name="action" value="exit" title="{{ isset($data['tag']) ? 'Save changes & Exit' : 'Save & Exit' }}">
                {{ isset($data['tag']) ? 'Save changes & Exit' : 'Save & Exit' }}
            </button>
        </div>
      </form>
    </div>
</div>
@endsection

@section('jsbody')
@include('backend.includes.tinymce')
@include('backend.components.toastr')
@endsection
