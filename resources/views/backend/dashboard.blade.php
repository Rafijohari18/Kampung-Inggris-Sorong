@extends('layouts.backend.layout')

@section('content')
<h4 class="font-weight-bold py-3 mb-3">
    Dashboard
    <div class="text-muted text-tiny mt-1 time-frame">
        <small class="font-weight-normal">Today is {{ now()->format('l, j F Y') }} (<em id="time-part"></em>)</small>
    </div>
</h4>

<div class="row">
    <div class="col-md-12">
      <div class="alert alert-primary alert-dismissible fade show text-muted">
        Welcome, <strong><em>{{ auth()->user()->name }}</em></strong> in Backend Panel !
      </div>
    </div>
</div>

{{-- counter --}}
<div class="row">

    <div class="col-sm-6 col-xl-3">
      <div class="card mb-4">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <div class="las la-bars display-4 text-primary"></div>
            <div class="ml-3">
              <div class="text-muted small">Pages</div>
              <div class="text-large">{{ $data['counter']['pages'] }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card mb-4">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div class="las la-pen-square display-4 text-primary"></div>
              <div class="ml-3">
                <div class="text-muted small">Sections</div>
                <div class="text-large">{{ $data['counter']['sections'] }}</div>
              </div>
            </div>
          </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card mb-4">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div class="las la-newspaper display-4 text-primary"></div>
              <div class="ml-3">
                <div class="text-muted small">Posts</div>
                <div class="text-large">{{ $data['counter']['posts'] }}</div>
              </div>
            </div>
          </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card mb-4">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div class="las la-users display-4 text-primary"></div>
              <div class="ml-3">
                <div class="text-muted small">Users</div>
                <div class="text-large">{{ $data['counter']['users'] }}</div>
              </div>
            </div>
          </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card mb-4">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div class="las la-store-alt display-4 text-success"></div>
              <div class="ml-3">
                <div class="text-muted small">Catalog Category</div>
                <div class="text-large">{{ $data['counter']['catalog_categories'] }}</div>
              </div>
            </div>
          </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card mb-4">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div class="las la-shopping-cart display-4 text-success"></div>
              <div class="ml-3">
                <div class="text-muted small">Catalog Product</div>
                <div class="text-large">{{ $data['counter']['catalog_products'] }}</div>
              </div>
            </div>
          </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card mb-4">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div class="las la-images display-4 text-warning"></div>
              <div class="ml-3">
                <div class="text-muted small">Album Photo</div>
                <div class="text-large">{{ $data['counter']['albums'] }} - {{ $data['counter']['photo'] }}</div>
              </div>
            </div>
          </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card mb-4">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div class="las la-film display-4 text-warning"></div>
              <div class="ml-3">
                <div class="text-muted small">Playlist Video</div>
                <div class="text-large">{{ $data['counter']['playlists'] }} - {{ $data['counter']['video'] }}</div>
              </div>
            </div>
          </div>
        </div>
    </div>

</div>

@if (!empty(env('ANALYTICS_VIEW_ID')))
{{-- visitor --}}
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <h6 class="card-header"><i class="las la-calendar"></i> Latest Visitor (<strong>this week</strong>)</h6>
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
                <a href="{{ route('visitor') }}" class="card-footer d-block text-center text-body small font-weight-semibold">SHOW MORE</a>
            </div>
        </div>
    </div>
</div>
@endif

{{-- viewer --}}
{{-- <div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <h6 class="card-header"><i class="las la-eye"></i> Most Viewer</h6>
            <div class="table-responsive">
                <table class="table card-table">
                    <thead>
                        <tr>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div> --}}

{{-- post & inquiry --}}
<div class="row">

    <div class="col-md-6">

      <!-- Popular products -->
      <div class="card mb-4 card-list">
        <h6 class="card-header"><i class="las la-newspaper"></i> Latest Posts</h6>
        <div class="table-responsive">
          <table class="table card-table">
            <thead>
              <tr>
                <th colspan="2">Title</th>
                <th>Category</th>
                <th>Views</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @if ($data['latest']['posts']->count() == 0)
              <tr>
                  <td colspan="5" align="center"><i><strong style="color:red;">! No Record From Posts !</strong></i></td>
              </tr>
              @endif
              @foreach ($data['latest']['posts'] as $post)
              <tr>
                <td class="align-middle" style="width: 75px">
                  <img class="ui-w-40" src="{{ $post->coverSrc($post) }}" alt="">
                </td>
                <td class="align-middle">
                  <a href="javascript:void(0)" class="text-body">{!! Str::limit($post->fieldLang('title'), 40) !!}</a>
                </td>
                <td class="align-middle">{!! $post->category->fieldLang('name') !!}</td>
                <td class="align-middle"><span class="badge badge-info">{{ $post->viewer }}</span></td>
                <td class="align-middle">
                  <a href="{{ $post->routes($post->id, $post->slug) }}" class="btn icon-btn btn-sm btn-primary" title="view post">
                      <i class="las la-external-link-alt"></i>
                  </a>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
          <a href="{{ route('section.index') }}" class="card-footer d-block text-center text-body small font-weight-semibold">SHOW MORE</a>
        </div>
      </div>
      <!-- / Popular products -->

    </div>
    <div class="col-md-6">

      <!-- Latest comments -->
      <div class="card mb-4 card-list">
        <h6 class="card-header">
          <div class="title-text"><i class="las la-envelope"></i> Latest Message</div>
        </h6>
        <div class="card-body">

         @if ($data['latest']['inquiry']->count() == 0)
          <div class="media pb-1 mb-3">
            <div class="media-body flex-truncate ml-3 text-center">
                <i><strong style="color:red;">! No Record From Inquiry !</strong></i>
            </div>
          </div>
          @endif
          @foreach ($data['latest']['inquiry'] as $inquiry)
          <div class="media pb-1 mb-3">
            <img src="{{ asset(config('custom.images.file.photo')) }}" class="d-block ui-w-40 rounded-circle" alt="">
            <div class="media-body flex-truncate ml-3">
              <a href="javascript:void(0)">{!! $inquiry->name !!}</a>
              <span class="text-muted">send message on</span>
              <a href="{{ route('inquiry.detail', ['inquiryId' => $inquiry->inquiry_id]) }}">{{ $inquiry->inquiry->fieldLang('name') }}</a>
              <p class="text-truncate my-1"></p>
              <div class="clearfix">
                <span class="float-left text-muted small">{{ $inquiry->submit_time->diffForHumans() }}</span>
              </div>
            </div>
          </div>
          @endforeach

        </div>
        <a href="javascript:void(0)" class="card-footer d-block text-center text-body small font-weight-semibold">SHOW MORE</a>
      </div>
      <!-- / Latest comments -->

    </div>

</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/tmplts_backend/vendor/libs/moment/moment.js') }}"></script>
@endsection

@section('jsbody')
<script>
$(document).ready(function() {
    var interval = setInterval(function() {
        var momentNow = moment();
        $('#time-part').html(momentNow.format('hh:mm:ss A'));
    }, 100);
});
</script>

@include('backend.components.toastr')
@endsection
