@isset($breadcrumbs)
<ul class="breadcrumb">
    <li><a href="{{ route('home') }}"><i class="fal fa-home"></i></a></li>
    
    @foreach ($breadcrumbs as $key => $val)
    <li>
        @if (!empty($val))
        <a class="link" href="{{ $val }}" title="{!! $key !!}">{!! $key !!}</a>
        @else
        {!! $key !!}
        @endif
    </li>
    @endforeach

</ul>
@endisset
