@extends('layouts.backend.layout')

@section('content')
<div class="container px-0">
    <h2 class="text-center font-weight-bolder pt-5">
      <i class="lnr lnr-construction d-block"></i> Website Maintenance Mode
    </h2>
    <div class="text-center text-muted text-big mx-auto mt-3" style="max-width: 500px;">
      If you activate maintenance mode, the pages on your website cannot be accessed, are you sure?
    </div>

    <br>
    <div class="d-flex justify-content-center">

        <form action="" method="GET">
            @foreach ($data['maintenance'] as $key => $item)
            @if (($item) == false)
            <input type="hidden" name="{{ $key }}" value="1">
            <button class="btn btn-xl btn-outline-success" title="click to maintenance your website"><i class="las la-power-off"></i> CLICK TO MODE ON</button>
            @else
            <input type="hidden" name="{{ $key }}" value="0">
            <button class="btn btn-xl btn-outline-danger" title="click to activate your website"><i class="las la-power-off"></i> CLICK TO MODE OFF</button>
            @endif
            @endforeach
        </form>
        &nbsp;
    </div>
</div>
@endsection

@section('jsbody')
@include('backend.components.toastr')
@endsection
