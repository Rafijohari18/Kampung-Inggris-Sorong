<section class="content-wrap home-popular">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="main-title">
                        <div class="subtitle">Most Program</div>
                        <h1 class="title">Popular Programs</h1>
                    </div>
                </div>
            </div>
            <div class="popuplar-programs o-visible swiper-container">
                <divv class="swiper-wrapper">
                    
                    @foreach($data['most_program']->where('selection',1) as $program)
                    <div class="swiper-slide">
                        <div class="single-product">
                            <figure class="product-img box-img">
                                <div class="thumb">
                                    <img class="lazy" data-src="{!! $program->coverSrc($program) !!}">
                                </div>
                            </figure>
                            <div class="product-info">
                                <div class="d-flex align-items-center">
                                    <a class="fs-12" href="{{ $program->section->routes($program->section_id, $program->section->slug).'?category_id='.$program->category_id }}">{{ $program->category->fieldLang('name') }}</a>
                                    <div class="rating-icon ml-auto">
                                        <div class="rating-content">
                                            <div class="rating rated"></div>
                                            <div class="rating rated"></div>
                                            <div class="rating rated"></div>
                                            <div class="rating rated"></div>
                                            <div class="rating"></div>
                                        </div>
                                        <div class="rating-score">5.0</div>
                                    </div>
                                </div>
                                <h5 class="product-title"><a class="hover-style" href="{{ $program->routes($program->id, $program->slug) }}">{{ $program->fieldLang('title') }}</a></h5>
                                <div class="product-price d-flex align-items-center">
                                    <a class="btn btn-custom-outline btn-blue" href="{{ $program->routes($program->id, $program->slug) }}">Details</a>
                                    <div class="price-label text-right ml-auto">
                                        <div class="fs-12 txt-grey">Start from</div>
                                        <div class="price txt-dark fw-sbold">
                                        {{ !empty($program->custom_field['start_from']) ?
                                            $program->custom_field['start_from'] : 'Harga Belum Tersedia' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @endforeach
                    
                   
                </divv>
            </div>
            <div class="caption-btn d-flex">
                <div class="swiper-button-wrapper">
                    <div class="btn btn-icon btn-custom-outline swiper-button-prev"><i class="fal fa-long-arrow-left"></i></div>
                    <div class="btn btn-icon btn-custom-outline swiper-button-next"><i class="fal fa-long-arrow-right"></i></div>
                </div>
                <a class="link-icon ml-auto" href="{{ $menu['package']->routes($menu['package']->id, $menu['package']->slug) }}">More programs<span><i class="fal fa-long-arrow-right"></i></span></a>
            </div>
        </div>
    </section>