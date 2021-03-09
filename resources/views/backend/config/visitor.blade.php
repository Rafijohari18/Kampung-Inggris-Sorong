@extends('layouts.backend.layout')

@section('content')
<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <div class="form-row align-items-center">
          <div class="col-md">
            <div class="form-group">
                <label class="form-label">Filtering</label>
                <select class="filter custom-select" name="f">
                    <option value=" " selected>Any</option>
                    @foreach (config('custom.filtering.visitor') as $key => $value)
                    <option value="{{ $key }}" {{ (Request::get('f') == $key) ? 'selected' : '' }} title="Filter by {{ $value }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>
          </div>
        </div>
    </div>
</div>
<!-- / Filters -->

<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <h6 class="card-header">Session / Last Weeks</h6>
            <div class="table-responsive">
                <table class="table card-table">
                    <thead>
                    <tr>
                        @foreach($data['n_visitor'] as $nv)
                        <th>{{ $nv['type']  }}</th>
                        @endforeach
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                        @foreach($data['n_visitor'] as $nv)
                        <td>{{ $nv['sessions'] }}</td>
                        @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <!-- Popular products -->
        <div class="card mb-4">
            <h6 class="card-header">Most Browser</h6>
            <div class="table-responsive">
            <table class="table card-table">
                <thead>
                    <tr>
                        @foreach($data['browser'] as $bro)
                        <th>{{ $bro['browser'] }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        @foreach($data['browser'] as $bro)
                        <td>{{ $bro['sessions'] }}</td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
            </div>
        </div>
        <!-- / Popular products -->
    </div>
    <div class="col-md-12">
        <!-- Popular products -->
        <div class="card mb-4">
            <h6 class="card-header">Visitor</h6>
            <div class="table-responsive">
                <table class="table card-table">
                    <thead>
                        <tr>
                            @foreach($data['total'] as $total)
                            <th>{{ Carbon\Carbon::parse($total['date'])->format('d F Y') }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            @foreach($data['total'] as $total)
                            <td>{{ $total['visitors'] }}</td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- / Popular products -->
    </div>
    <div class="col-md-6">
        <!-- Popular products -->
        <div class="card mb-4">
            <h6 class="card-header">TOP Visited Page</h6>
            <div class="table-responsive">
            <table class="table card-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Viewer</th>
                        <th>Visitor</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['vp']->take(10)->sortbydesc('visitors') as $v)
                    <tr>
                    <td>{{ Str::limit($v['pageTitle'], 70) }}</td>
                    <td>{{ $v['pageViews'] }} </td>
                    <td>{{ $v['visitors'] }} </td>
                    <td>{{ Carbon\Carbon::parse($v['date'])->format('d F Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>
        <!-- / Popular products -->
    </div>
    <div class="col-md-6">
        <!-- Popular products -->
        <div class="card mb-4">
            <h6 class="card-header">TOP Visited Page</h6>
            <div class="table-responsive">
            <table class="table card-table">
                <thead>
                    <tr>
                        <th>URL</th>
                        <th>HITS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['top']->take(5) as $tt)
                    <tr>
                    <td><a href="{{ url('/').$tt['url'] }}" title="View content">{{ url('/').$tt['url'] }}</a></td>
                    <td>{{ $tt['pageViews'] }} </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>
        <!-- / Popular products -->
    </div>
</div>
@endsection

@section('jsbody')
<script>
$('.filter').on('change', function () {
    var url = $(this).val();
    if (url) {
        window.location = '?f='+url;
    }

    return false;
});
</script>
@endsection
