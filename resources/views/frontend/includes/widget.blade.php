<div class="col-lg-4 col-md-4 section-right">
    <div class="sidebar sticky-top">
        <div class="widget">
            <div class="tab-container">
                <ul class="nav nav-tabs">
                    <li class="nav-item"><a class="nav-link active" href="#berita-video" data-toggle="tab">Video</a></li>
                    <li class="nav-item"><a class="nav-link" href="#berita-terbaru" data-toggle="tab">Terbaru</a></li>
                    <li class="nav-item"><a class="nav-link" href="#berita-trending" data-toggle="tab">Trending</a></li>
                </ul>
                <div class="tab-content">
                    <div id="berita-video" class="tab-pane active">
                        <div class="list-blog">
                            @foreach ($widget['video'] as $video)
                            <div class="blog-post blog-5">
                                <figure class="post-image post-video">
                                    <div class="box-img">
                                        <a class="btn btn-circle btn-play btn-lg" data-poster="https://img.youtube.com/vi/{{ $video->youtube_id }}/maxresdefault.jpg" href="https://youtu.be/{{ $video->youtube_id }}"><i class="fas fa-play"></i></a>
                                        <div class="thumb">
                                            <img class="lazy" data-src="https://img.youtube.com/vi/{{ $video->youtube_id }}/maxresdefault.jpg">
                                        </div>
                                    </div>
                                </figure>
                                <div class="post-content">
                                    <ul class="post-categories">
                                        <li><a href="{{ $video->playlist->routes($video->playlist_id) }}" class="badge badge-dark" title="Video">Video</a></li>
                                    </ul>
                                    <h5 class="post-title f-bold" ellipsis><a class="hover-effect-1" href="{{ $video->playlist->routes($video->playlist_id) }}" title="{!! $video->fieldLang('title') !!}">{!! $video->fieldLang('title') !!}</a></h5>
                                    <div class="post-meta txt-grey">
                                        <span><i class="far fa-clock txt-dark"></i>{{ $video->created_at->format('M d, Y') }}</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @if ($widget['video']->count() == 0)
                            <div class="d-flext justify-content-center">
                                <h4 style="color: red;">! <i>Video Kosong</i> !</h4>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div id="berita-terbaru" class="tab-pane">
                        <div class="list-blog">
                            @foreach ($widget['berita_terbaru'] as $terbaru)
                            <div class="blog-post blog-3">
                                @if ($terbaru->category_id == 3)
                                <figure class="post-image post-video">
                                    <div class="box-img">
                                        <a class="btn btn-circle btn-play btn-lg" data-poster="https://img.youtube.com/vi/{{ $terbaru->cover['file_path'] }}/maxresdefault.jpg" href="https://youtu.be/{{ $terbaru->cover['file_path'] }}"><i class="fas fa-play"></i></a>
                                        <div class="thumb">
                                            <img class="lazy" data-src="https://img.youtube.com/vi/{{ $terbaru->cover['file_path'] }}/maxresdefault.jpg" title="{{ $terbaru->cover['title'] }}" alt="{{ $terbaru->cover['alt'] }}">
                                        </div>
                                    </div>
                                </figure>
                                @else
                                <figure class="post-image">
                                    <div class="box-img">
                                        <div class="overlay">
                                            <i class="fal fa-long-arrow-right"></i>
                                        </div>
                                        <div class="thumb">
                                            <img class="lazy" data-src="{{ $terbaru->coverSrc($terbaru) }}" title="{{ $terbaru->cover['title'] }}" alt="{{ $terbaru->cover['alt'] }}">
                                        </div>
                                    </div>
                                </figure>
                                @endif
                                <div class="post-content">
                                    <ul class="post-categories">
                                        <li><a href="{{ $terbaru->routes($terbaru->section_id, $terbaru->section->slug).'?=category_id'.$terbaru->category_id }}" class="badge badge-{{ $terbaru->category_id == 3 ? 'dark' : 'orange' }}" title="{!! $terbaru->category->fieldLang('name') !!}">{!! $terbaru->category->fieldLang('name') !!}</a></li>
                                    </ul>
                                    <h5 class="post-title f-bold fs-18" ellipsis><a class="hover-effect-1" href="{{ $terbaru->routes($terbaru->id, $terbaru->slug) }}" title="{!! $terbaru->fieldLang('title') !!}">{!! $terbaru->fieldLang('title') !!}</a></h5>
                                    <div class="post-meta txt-grey">
                                        <span><i class="far fa-user txt-dark"></i>@lang('common.text_baca') {{ $terbaru->viewer }}</span>
                                        <span><i class="far fa-clock txt-dark"></i>{{ $terbaru->created_at->format('M d, Y') }}</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @if ($widget['berita_terbaru']->count() == 0)
                            <div class="d-flext justify-content-center">
                                <h4 style="color: red;">! <i>Berita Terbaru Kosong</i> !</h4>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div id="berita-trending" class="tab-pane">
                        <div class="list-blog">
                            @foreach ($widget['berita_trending'] as $trending)
                            <div class="blog-post blog-3">
                                @if ($trending->category_id == 3)
                                <figure class="post-image post-video">
                                    <div class="box-img">
                                        <a class="btn btn-circle btn-play btn-lg" data-poster="https://img.youtube.com/vi/{{ $trending->cover['file_path'] }}/maxresdefault.jpg" href="https://youtu.be/{{ $trending->cover['file_path'] }}"><i class="fas fa-play"></i></a>
                                        <div class="thumb">
                                            <img class="lazy" data-src="https://img.youtube.com/vi/{{ $trending->cover['file_path'] }}/maxresdefault.jpg" title="{{ $trending->cover['title'] }}" alt="{{ $trending->cover['alt'] }}">
                                        </div>
                                    </div>
                                </figure>
                                @else
                                <figure class="post-image">
                                    <div class="box-img">
                                        <div class="overlay">
                                            <i class="fal fa-long-arrow-right"></i>
                                        </div>
                                        <div class="thumb">
                                            <img class="lazy" data-src="{{ $trending->coverSrc($trending) }}" title="{{ $trending->cover['title'] }}" alt="{{ $trending->cover['alt'] }}">
                                        </div>
                                    </div>
                                </figure>
                                @endif
                                <div class="post-content">
                                    <ul class="post-categories">
                                        <li><a href="{{ $trending->routes($trending->section_id, $trending->section->slug).'?=category_id'.$trending->category_id }}" class="badge badge-{{ $trending->category_id == 3 ? 'dark' : 'orange' }}" title="{!! $trending->category->fieldLang('name') !!}">{!! $trending->category->fieldLang('name') !!}</a></li>
                                    </ul>
                                    <h5 class="post-title f-bold fs-18" ellipsis><a class="hover-effect-1" href="{{ $terbaru->routes($trending->id, $trending->slug) }}" title="{!! $trending->fieldLang('title') !!}">{!! $trending->fieldLang('title') !!}</a></h5>
                                    <div class="post-meta txt-grey">
                                        <span><i class="far fa-user txt-dark"></i>@lang('common.text_baca') {{ $trending->viewer }}</span>
                                        <span><i class="far fa-clock txt-dark"></i>{{ $trending->created_at->format('M d, Y') }}</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @if ($widget['berita_trending']->count() == 0)
                            <div class="d-flext justify-content-center">
                                <h4 style="color: red;">! <i>Berita Trending Kosong</i> !</h4>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="widget">
            <div class="tab-container">
                <ul class="nav nav-tabs">
                    <li class="nav-item"><a class="nav-link active" href="#widget-twitter" data-toggle="tab">Twitter</a></li>
                    <li class="nav-item"><a class="nav-link" href="#widget-facebook" data-toggle="tab">Facebook</a></li>
                    <li class="nav-item"><a class="nav-link" href="#widget-instagram" data-toggle="tab">Instagram</a></li>
                </ul>
                <div class="tab-content">
                    <div id="widget-twitter" class="tab-pane active">
                        <a class="twitter-timeline" data-height="460" href="{!! $config['twitter'] !!}?ref_src=twsrc%5Etfw"></a> <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
                    </div>
                    <div id="widget-facebook" class="tab-pane">
                        <iframe id="fb-container" src="https://www.facebook.com/plugins/page.php?href={!! $config['facebook'] !!}%2F&tabs=timeline&width=400&height=460&small_header=true&adapt_container_width=true&hide_cover=true&show_facepile=true&appId" width="100%" height="460" style="border:none;overflow:hidden;width:100%;" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>
                        <script>
                            $(window).on("load resize", function(){
                                var w = $(".sidebar").width();
                                if($(window).width() <= 991){
                                    $("#fb-container").attr("src","https://www.facebook.com/plugins/page.php?href={!! $config['facebook'] !!}%2F&tabs=timeline&width=330&height=460&small_header=true&adapt_container_width=true&hide_cover=true&show_facepile=true&appId");
                                } else {
                                    $("#fb-container").attr("src","https://www.facebook.com/plugins/page.php?href={!! $config['facebook'] !!}%2F&tabs=timeline&width="+w+"&height=460&small_header=true&adapt_container_width=true&hide_cover=true&show_facepile=true&appId");
                                }
                            });
                        </script>
                    </div>
                    <div id="widget-instagram" class="tab-pane">
                        <div class="widget-box box-img bg-grey_l2">
                            <div class="thumb content px-3 text-center">
                                <h4 class="m-0 f-bold">Widget Instagram</h4>
                            </div>
                        </div>
                        {{-- <script src="https://cdn.lightwidget.com/widgets/lightwidget.js"></script><iframe src="//lightwidget.com/widgets/7b6989afdc1f5cc0a5a1c5323144e251.html" scrolling="no" allowtransparency="true" class="lightwidget-widget" style="width:100%;border:0;overflow:hidden;"></iframe> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
