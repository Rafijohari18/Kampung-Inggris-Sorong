<header class="main-header">
    <div class="container">
        <div class="top-header">
            <a class="logo-header" href="{{ route('home') }}">
                <div class="logo-icon"></div>
                <div class="logo-type">Kampung Inggris<br>Sorong</div>
            </a>
            <div class="lang-header dropdown">
                <i class="circle-46 fs-20 mr-5 fal fa-globe txt-g_blue"></i>
                <a href="#!" data-toggle="dropdown">
                    <div class="fs-12 txt-grey">Language</div>
                    @foreach($language as $lang)
                        @if($lang->iso_codes == app()->getLocale())
                            <div class="fs-14 txt-white fw-med">{{ $lang->country }}</div>
                        @endif
                    @endforeach
                </a>
                <div class="dropdown-menu">
                    <ul>
                       @foreach($language as $lang)   
                        <li><a href="{{ $lang->urlSwitcher(Request::segment(1),$lang->iso_codes) }}">{{ $lang->country }}</a></li>
                       @endforeach 
                    </ul>
                </div>
            </div>
            <!--<div class="contact-header txt-white">
                <div class="contact-info">
                    <i class="circle-46 fs-20 fal fa-phone-rotary"></i>
                    <div class="text">
                        <div class="fs-13 info-label">Call Us</div>
                        <div class="fs-14">+123 456 7890</div>
                    </div>
                </div>
                <div class="contact-info">
                    <i class="circle-46 fs-20 fal fa-envelope"></i>
                    <div class="text">
                        <div class="fs-13 info-label">Email Us</div>
                        <div class="fs-14">info@kpinggrissorong.com</div>
                    </div>
                </div>
            </div>-->
            @if (!empty($menu['kontak']))
            <a class="btn btn-custom-outline btn-white btn-contact" href="{{ $menu['kontak']->routes($menu['kontak']->slug) }}">Contact Us</a>
            @endif
        </div>
        <nav class="nav-header">
            <div class="burger-menu">
                <div class="burger-icon">
                    <span></span>
                    <span></span>
                </div>
            </div>

            <a class="logo-header" href="{{ route('home') }}">
                <div class="logo-icon"></div>
            </a>
            @php
                $packageActive = ((Request::segment(1) == 'content' && Request::segment(5) == $menu['package']->slug) ? 'active' : '');
                $programActive = ((Request::segment(1) == 'content' && Request::segment(5) == $menu['program']->slug) ? 'active' : '');
            @endphp
            
            <div class="menu-header">
                <ul class="menu">
                    <li class="dropdown"><a href="#!">About Us</a>
                        <div class="dropdown-hover">
                            <ul>
                                <li><a href="{{ $menu['about']->routes($menu['about']->id, $menu['about']->slug) }}">Our Profile</a></li>
                                <li><a href="{{ $menu['struktur']->routes($menu['struktur']->id, $menu['struktur']->slug) }}l">Structure Organization</a></li>
                            </ul>
                        </div>
                    </li>
                    @if (!empty($menu['program']))
                    <li class="dropdown"><a href="#!" class="{{ $programActive }}">{{ $menu['program']->fieldLang('name') }}</a>
                        @if ($menu['program']->category->count() > 0)
                        <div class="dropdown-hover">
                            <ul>
                                @foreach ($menu['program']->category as $program)
                                    <li><a href="{{ $program->routes($program->id, $program->slug) }}">{!! $program->fieldLang('name') !!}</a></li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </li>
                    @endif

                    @if (!empty($menu['package']))
                    <li><a class="{{ $packageActive }}" href="{{ $menu['package']->routes($menu['package']->id, $menu['package']->slug) }}" title="{!! $menu['package']->fieldLang('name') !!}">{!! $menu['package']->fieldLang('name') !!}</a>
                    @endif

                    @if (!empty($menu['information']))
                    <li class="dropdown"><a href="#!">{{ $menu['information']->fieldLang('name') }}</a>
                        @if ($menu['information']->category->count() > 0)
                            <div class="dropdown-hover">
                                <ul>
                                    @foreach($menu['information']->category as $information)
                                        <li><a href="{{ $information->routes($information->id, $information->slug) }}">{!! $information->fieldLang('name') !!}</a></li>
                                    @endforeach  
                                </ul>
                            </div>
                        @endif

                    </li>
                    @endif


                    <li class="dropdown"><a href="#!" class="{{ Request::is('gallery*') ? 'active' : '' }}">Gallery</a>
                        <div class="dropdown-hover">
                            <ul>
                                <li><a href="{{ route('album.list') }}">Gallery Photo</a></li>
                                <li><a href="{{ route('playlist.list') }}">Gallery Video</a></li>
                            </ul>
                        </div>
                    </li>
                </ul>
                <a class="btn btn-contact btn-custom-outline btn-white" href="">Contact Us</a>
            </div>
            <div class="search-header">
                <button class="btn btn-white btn-search btn-icon" type="button"><span></span></button>
                <div class="search-form">
                    <form class="form-header" action="{{ route('home.search') }}" method="GET">
                        <input class="form-control" placeholder="Search..." type="search" name="keyword">
                        <button class="btn btn-submit btn-icon" type="submit"><i class="fal fa-arrow-right"></i></button>
                    </form>
                </div>
            </div>
        </nav>
    </div>
</header>
