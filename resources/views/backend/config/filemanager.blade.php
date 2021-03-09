@extends('layouts.backend.layout')

@section('content')
<div class="card">
    <div class="card-header with-elements">
        <h5 class="card-header-title mt-1 mb-0">Filemanager</h5>
        <div class="card-header-elements ml-md-auto">
        </div>
    </div>
    <div class="card-body">
        <iframe src="/file-manager/fm-button" style="width: 100%; height: 780px; overflow: hidden; border: none;"></iframe>
    </div>
</div>
@endsection
