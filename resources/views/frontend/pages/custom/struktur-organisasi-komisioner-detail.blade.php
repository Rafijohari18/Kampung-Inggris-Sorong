@extends('layouts.frontend.layout')

@section('content')
<section class="content-wrap">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-8 section-left">
                <div class="page-content sticky-top">
                    @include('frontend.components.breadcrumbs')
                    <div class="blog-post blog-6">
                        <div class="post-content">
                            <h1 class="post-title">{!! !empty($data['read']->fieldLang('intro')) ? strip_tags($data['read']->fieldLang('intro')) : $data['read']->fieldLang('title') !!}</h1>
                            <div class="single-post">
                                @php
                                    $child = $data['read']->childPublish()->limit(1)->first();
                                @endphp
                                <input type="hidden" id="first" value="{{ $child->id }}">
                                <div class="post-entry" id="content">

                                </div>
                                <div class="form-group">
                                    <label class="form-label">Periode</label>
                                    <select class="child form-control"  name="child" data-id="{{ $data['read']->id }}">
                                        <option value=" " selected disabled>Pilih</option>
                                        @foreach ($data['read']->childPublish as $child)
                                        <option value="{{ $child->id }}">{!! $child->fieldLang('title') !!}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('frontend.includes.widget')
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script src="{{ asset('assets/tmplts_frontend/js/jquery.ellipsis.min.js') }}"></script>
<script src="{{ asset('assets/tmplts_frontend/js/lazyload.min.js') }}"></script>
@endsection

@section('jsbody')
<script>

    $('.child').on('change', function () {
        const id = $(this).val();
        let initElement = '';
        $.ajax({
            type : 'GET',
            url : '/json/page/child/'+id,
            success : function (data) {

                initElement += `
                    <img class="lazy" data-src="`+data.cover+`" title="`+data.cover_title+`" alt="`+data.cover_alt+`">
                    `+data.content+`
                `;
                $('#content').html(initElement);

                //lazy
                var lazyLoadInstance = new LazyLoad();
                $("img.lazy").each(function(){
                    $(this).parent(".thumb").css("background-color","rgba(154,158,160,.5)")
                });
            }
        });
    });

    $( document ).ready(function() {
        const id = $('#first').val();
        $('select option[value='+id+']').attr('selected', 'selected');
        let initElement = '';

        $.ajax({
            type : 'GET',
            url : '/json/page/child/'+id,
            success : function (data) {

                initElement += `
                    <img class="lazy" data-src="`+data.cover+`" title="`+data.cover_title+`" alt="`+data.cover_alt+`">
                    `+data.content+`
                `;
                $('#content').html(initElement);

                //lazy
                var lazyLoadInstance = new LazyLoad();
                $("img.lazy").each(function(){
                    $(this).parent(".thumb").css("background-color","rgba(154,158,160,.5)")
                });
            }
        });
    });

    //ellipsis
    $("[ellipsis]").dotdotdot({
        ellipsis	: "...",
    });

    //lazy
    var lazyLoadInstance = new LazyLoad();
    $("img.lazy").each(function(){
        $(this).parent(".thumb").css("background-color","rgba(154,158,160,.5)")
    });

</script>
@endsection
