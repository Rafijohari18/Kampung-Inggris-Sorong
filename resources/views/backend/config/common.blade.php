@extends('layouts.backend.layout')

@section('content')
<div class="card">
    <div class="card-body">
        @if (config('custom.language.multiple') == true)
        <div id="sidenav-2" class="sidenav sidenav-horizontal bg-primary mb-2" style="position: relative;z-index: 100">
            <ul class="sidenav-inner">
              @foreach ($data['languages'] as $item)
              <li class="sidenav-item {{ $item->iso_codes == Request::segment(4) ? 'active' : '' }}">
                <a href="{{ route('configuration.common', ['lang' => $item->iso_codes]) }}" class="sidenav-link">
                  <i class="sidenav-icon las la-language"></i>
                  <div>{{ strtoupper($item->country) }}</div>
                </a>
              </li>
              @endforeach
            </ul>
        </div>
        @endif
        <hr>
        <form action="" method="get">
            @csrf
            @foreach ($data['files'] as $key => $value)
            <label class="col-form-label">{{ str_replace('_', ' ', strtoupper($key)) }}</label>
            <div class="form-group row">
                <div class="col-sm-12">
                    <textarea class="form-control mb-1" name="lang[{{ $key }}]" placeholder="enter text...">{{ $value }}</textarea>
                </div>
            </div>
            @endforeach
            <hr>
            <div class="form-group row">
              <div class="col-sm-10 ml-sm-auto">
                <button type="submit" class="btn btn-primary "title="Save changes">Save changes</button>
              </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('jsbody')
<script src="{{ asset('assets/tmplts_backend/js/pages_account-settings.js') }}"></script>

@include('backend.components.toastr')
@endsection
