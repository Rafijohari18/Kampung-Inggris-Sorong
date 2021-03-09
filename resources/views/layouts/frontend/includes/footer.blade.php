
<footer class="main-footer">
    @yield('footer_add')
    <section class="content-wrap top-footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-3 logo-wrap">
                    <a class="logo-footer" href="{{ route('home') }}">
                        <div class="logo-icon"></div>
                        <div class="logo-type">{{ $config['website_name'] }}</div>
                    </a>
                </div>
                <div class="col-lg-3 col-md-3 footer-wrap">
                    <div class="main-title">
                        <h5 class="title txt-white">{{ $menu['program']->fieldLang('name') }}</h5>
                    </div>
                    <ul class="footer-menu">
                    @if (!empty($menu['program']))
                        @if ($menu['program']->category->count() > 0)
                            @foreach ($menu['program']->category as $program)
                            <li><a href="{{ $program->routes($program->id, $program->slug) }}">{!! $program->fieldLang('name') !!}</a></li>
                            @endforeach
                        @endif
                    @endif
                    </ul>
                </div>
                <div class="col-lg-3 col-md-3 footer-wrap">
                    <div class="main-title">
                        <h5 class="title txt-white">Package</h5>
                    </div>
                    <ul class="footer-menu">
                        @if (!empty($menu['package']))
                            <li><a href="{{ $menu['package']->routes($menu['package']->id, $menu['package']->slug) }}" title="{!! $menu['package']->fieldLang('name') !!}">{!! $menu['package']->fieldLang('name') !!}</a>
                        @endif
                        
                    </ul>
                </div>
                <div class="col-lg-3 col-md-3 footer-wrap">
                    <div class="main-title">
                        <h5 class="title txt-white">Information</h5>
                    </div>
                    @if (!empty($menu['information']))
                    
                    <ul class="footer-menu">
                        @if ($menu['information']->category->count() > 0)
                            @foreach($menu['information']->category as $information)
                                <li><a href="{{ $information->routes($information->id, $information->slug) }}">{!! $information->fieldLang('name') !!}</a></li>
                            @endforeach
                        @endif
                    </ul>
                    @endif
                </div>
            </div>
        </div>
    </section>
    <section class="bottom-footer">
        <div class="container">
            <div class="copyright fs-12">© 2021 Kampung Inggris Sorong</div>
            <div class="social-icon">
                <a class="btn btn-icon btn-sm btn-custom-outline btn-white" href="{{ $config['facebook'] }}"><i class="fab fa-facebook-f"></i></a>
                <a class="btn btn-icon btn-sm btn-custom-outline btn-white" href="{{ $config['twitter'] }}"><i class="fab fa-twitter"></i></a>
                <a class="btn btn-icon btn-sm btn-custom-outline btn-white" href="{{ $config['instagram'] }}"><i class="fab fa-instagram"></i></a>
                <a class="btn btn-icon btn-sm btn-custom-outline btn-white" href="{{ $config['youtube'] }}"><i class="fab fa-youtube"></i></a>
            </div>
        </div>
    </section>
</footer>
