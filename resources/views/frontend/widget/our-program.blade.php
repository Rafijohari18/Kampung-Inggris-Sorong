<section class="content-wrap home-programs bg-white">
        <div class="container">
            <div class="row row-title">
                <div class="col-lg-6">
                    <div class="main-title">
                        <div class="subtitle">Program</div>
                        <h1 class="title">Our Programs</h1>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="tab-menu">
                        <div class="tab-menu-scroll">
                            <ul class="nav nav-tabs">

                                @foreach($menu['program']->category as $program)
                                    <li class="nav-item"><a class="nav-link btn btn-custom {{ $loop->first ? ' active' : '' }}" data-toggle="tab" href="#{!! $program->fieldLang('name') !!}">{!! $program->fieldLang('name') !!} </a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-content">
                @foreach($menu['program']->category as $program)
                <div class="tab-pane fade show {{ $loop->first ? ' active' : '' }}" id="{!! $program->fieldLang('name') !!}">
                    <div class="row">

                        @foreach($program->post as $post)
                        
                        <div class="col-lg-6">
                            <div class="single-product long-product">
                                <figure class="product-img box-img">
                                    <div class="thumb">
                                        <img class="lazy" data-src="{!! $post->coverSrc($post) !!}">
                                    </div>
                                </figure>
                                <div class="product-info">
                                    <div class="d-flex align-items-center">
                                        <a class="fs-12" href="{{ $program->section->routes($post->section_id, $post->section->slug).'?category_id='.$post->category_id }}">{{ $post->category->fieldLang('name') }}</a>
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
                                    <h5 class="product-title"><a class="hover-style" href="{{ $program->routes($program->id, $program->slug) }}">{!! $post->fieldLang('title') !!}</a></h5>
                                    <div class="product-price d-flex align-items-center">
                                        <a class="btn btn-custom-outline btn-blue" href="{{ $program->routes($program->id, $program->slug) }}">Details</a>
                                        <div class="price-label text-right ml-auto">
                                            <div class="fs-12 txt-grey">Start from</div>
                                            <div class="price txt-dark fw-sbold">
                                            {{ !empty($post->custom_field['start_from']) ?
                                            $post->custom_field['start_from'] : 'Harga Belum Tersedia' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        
                    </div>
                </div>
                @endforeach
                
            </div>
            <div class="caption-btn text-center">
                <a class="link-icon" href="program-english.html">More programs<span><i class="fal fa-long-arrow-right"></i></span></a>
            </div>
        </div>
    </section>