<section class="content-wrap home-facilities">
        <div class="caption-media">
            <div class="row no-gutter caption-media-img">
                <div class="col-lg-6 caption-img">
                    <div class="caption-slide caption-img-slide swiper-container">
                        <div class="swiper-wrapper">
                            @foreach($data['facilities'] as $facilities )
                                <div class="swiper-slide">
                                    <div class="thumb" data-swiper-parallax="50%">
                                        <img class="lazy" data-src="{{ $facilities->coverSrc($facilities) }}">
                                    </div>
                                </div>
                            @endforeach
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row no-gutter caption-media-txt">
                    <div class="col-lg-5 offset-lg-7">
                        <div class="caption-slide caption-txt swiper-container">
                            <div class="swiper-wrapper">
                                @foreach($data['facilities'] as $facilities )
                                <div class="swiper-slide">
                                    <div class="main-title">
                                        <div class="subtitle">Facilities</div>
                                        <h1 class="title">{{ $facilities->fieldLang('title') }}</h1>
                                    </div>
                                    <div class="caption-txt">
                                        @if (!empty($facilities->fieldLang('intro')) || !empty($facilities->fieldLang('content')))
                                            <p>
                                                {!! !empty($facilities->fieldLang('intro')) ? strip_tags(Str::limit($facilities->fieldLang('intro'), 300)) : strip_tags(Str::limit($facilities->fieldLang('content'), 300)) !!}
                                            </p>
                                        @endif   
                                    </div>
                                    <div class="caption-btn">
                                        <a class="btn btn-custom btn-blue" href="{{ $facilities->routes($facilities->id, $facilities->slug) }}">Read More</a>
                                    </div>
                                </div>
                              @endforeach  
                               
                            </div>
                        </div>
                        <div class="swiper-pagination-wrapper">
                            <div class="swiper-pagination caption-pagination"></div>
                            <div class="swiper-button-wrapper">
                                <div class="btn btn-icon btn-custom-outline swiper-button-prev"><i class="fal fa-long-arrow-left"></i></div>
                                <div class="btn btn-icon btn-custom-outline swiper-button-next"><i class="fal fa-long-arrow-right"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>