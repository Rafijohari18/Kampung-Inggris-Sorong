<section class="content-wrap home-news bg-white">
        <div class="container">
            <div class="main-title text-center">
                <div class="subtitle">Latest News</div>
                <h1 class="title">News From Resource</h1>
            </div>
            <div class="row">
                @foreach($data['latest_news'] as $news)
                <di class="col-lg-4">
                    <div class="blog-post">
                        <figure class="post-img box-img">
                            <a href="{{ $news->section->routes($news->section_id, $news->section->slug) }}">
                                <div class="thumb">
                                    <img class="lazy" data-src="{!! $news->coverSrc($news) !!}">
                                    <div class="overlay"><span>Read</span></div>
                                </div>
                            </a>
                        </figure>
                        <div class="post-content">
                            <div class="post-categories">
                                <a class="badge badge-blue" href="information-news.html">{{ $news->category->fieldLang('name') }}</a>
                            </div>
                            <h5 class="post-title"><a class="hover-style" href="{{ $news->section->routes($news->section_id, $news->section->slug) }}">{!! $news->fieldLang('title') !!}</a></h5>
                            <div class="post-meta">
                                <span>{{ $news->created_at->format('M d, Y') }}</span>
                                <span>by <a href="#!">{{ $news->createBy->name }}</a></span>
                            </div>
                        </div>
                    </div>
                </di>
                @endforeach
                
            </div>
            <div class="caption-btn text-center">
                <a class="link-icon" href="information-news.html">More News<span><i class="fal fa-long-arrow-right"></i></span></a>
            </div>
        </div>
        <div class="section-bg">
            <img src="{!! $data['section_news']->bannerSrc($data['section_news']) !!}">
        </div>
    </section>