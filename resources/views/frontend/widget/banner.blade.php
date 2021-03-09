<section class="banner-area">
        <div class="banner-img swiper-container">
            <div class="swiper-wrapper">
                @foreach($data['banner_home']->bannerPublish(1)->get() as $banner)
                <div class="swiper-slide">
                    <div class="thumb" data-swiper-parallax="50%">
                        <img class="lazy parallax-header" data-src="{{ $banner->bannerType($banner)['background'] }}">
                    </div>
                </div>
                @endforeach
               
            </div>
        </div>
        <div class="banner-caption">
            <div class="container">
                <div class="main-title text-center">
                    <div class="subtitle">Welcome to website</div>
                    {!! $data['banner_home']->fieldLang('description') !!}
                   
                </div>
            </div>
        </div>
        <div class="swiper-pagination"></div>
        <div class="swiper-button-wrapper">
            <div class="swiper-button-prev btn btn-sm btn-icon btn-custom-outline btn-white"><i class="fal fa-long-arrow-left"></i></div>
            <div class="swiper-button-next btn btn-sm btn-icon btn-custom-outline btn-white"><i class="fal fa-long-arrow-right"></i></div>
        </div>
        <div class="scroll-icon">
            <div class="icon"></div>
            <div class="icon-label">Scroll</div>
        </div>
    </section>