<!DOCTYPE html>

<html lang="{{ app()->getLocale() }}" class="layout-fixed default-style layout-collapsed">

<head>
    <!-- CSRF token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Meta default -->
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="IE=edge,chrome=1">
    <meta name="title" content="{{ isset($title) ? $title : 'CMS' }}">
    <meta name="description" content="Backend panel, content management system">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">

    <title>{{ isset($title) ? $title.' | ' : 'CMS | ' }} @yield('title') {{ __('Content Management System') }}</title>

    <!-- Open graph -->
    <meta property="og:locale" content="{{ app()->getlocale().'_'.strtoupper(app()->getlocale()) }}" />
    <meta property="og:url" name="url" content="{{ url()->full() }}">
    <meta property="og:site_name" content="{{ route('backend.login') }}">
    <meta property="og:title" content="{{ isset($title) ? $title : 'CMS' }}"/>
    <meta property="og:description" content="Backend panel, content management system"/>
    <meta property="og:image" content="{{ $config['open_graph'] }}"/>
    <meta property="og:image:width" content="650" />
    <meta property="og:image:height" content="366" />
    <meta property="og:type" content="website" />

    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/tmplts_backend/images/favicon.ico') }}" sizes="32x32">

    <!-- Main font -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900" rel="stylesheet">

    <!-- Icon fonts -->
    <link rel="stylesheet" href="{{ asset('assets/tmplts_backend/vendor/fonts/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/tmplts_backend/vendor/fonts/ionicons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/tmplts_backend/vendor/fonts/linearicons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/tmplts_backend/vendor/fonts/open-iconic.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/tmplts_backend/vendor/fonts/pe-icon-7-stroke.css') }}">

    <!-- Core stylesheets -->
    <link rel="stylesheet" href="{{ asset('assets/tmplts_backend/vendor/css/rtl/bootstrap.css') }}" class="theme-settings-bootstrap-css">
    <link rel="stylesheet" href="{{ asset('assets/tmplts_backend/vendor/css/rtl/appwork.css') }}" class="theme-settings-appwork-css">
    <link rel="stylesheet" href="{{ asset('assets/tmplts_backend/vendor/css/rtl/theme-corporate.css') }}" class="theme-settings-theme-css">
    <link rel="stylesheet" href="{{ asset('assets/tmplts_backend/vendor/css/rtl/colors.css') }}" class="theme-settings-colors-css">
    <link rel="stylesheet" href="{{ asset('assets/tmplts_backend/vendor/css/rtl/uikit.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/tmplts_backend/css/demo.css') }}">

    <!-- Additional CSS -->
    <link rel="stylesheet" href="{{ asset('assets/tmplts_backend/css/custom-alsen.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/tmplts_backend/css/line-awesome.css') }}">

    <!-- Load polyfills -->
    <script src="{{ asset('assets/tmplts_backend/vendor/js/polyfills.js') }}"></script>
    <script>document['documentMode']===10&&document.write('<script src="https://polyfill.io/v3/polyfill.min.js?features=Intl.~locale.en"><\/script>')</script>

    <!-- Layout helpers -->
    <script src="{{ asset('assets/tmplts_backend/vendor/js/layout-helpers.js') }}"></script>

    <!-- Libs -->
    <style type="text/css">
		.preloader {
			position: fixed;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			z-index: 9999;
			background-color: rgba(255,255,255,0.5);
		}
		.preloader .loading {
			position: absolute;
			left: 50%;
			top: 50%;
			transform: translate(-50%,-50%);
			font: 14px arial;
		}
    </style>

    <!-- Core scripts -->
    <script src="{{ asset('assets/tmplts_backend/vendor/js/pace.js') }}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <!-- `perfect-scrollbar` library required by SideNav plugin -->
    <link rel="stylesheet" href="{{ asset('assets/tmplts_backend/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/tmplts_backend/vendor/libs/toastr/toastr.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/tmplts_backend/vendor/libs/spinkit/spinkit.css') }}">

    @yield('styles')

</head>
<body class="alsen @yield('body-class')">

    <div class="page-loader">
        <div class="bg-primary"></div>
    </div>

    <div class="preloader">
        <div class="loading">
            <div class="col-xs-12">
                <div class="sk-double-bounce sk-primary">
                    <div class="sk-child sk-double-bounce1"></div>
                    <div class="sk-child sk-double-bounce2"></div>
                </div>
            </div>
        </div>
    </div>

    @yield('layout-content')

    <!-- Core scripts -->
    <script src="{{ asset('assets/tmplts_backend/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/tmplts_backend/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/tmplts_backend/vendor/js/sidenav.js') }}"></script>

    <!-- Libs -->

    <!-- `perfect-scrollbar` library required by SideNav plugin -->
    <script src="{{ asset('assets/tmplts_backend/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/tmplts_backend/vendor/libs/toastr/toastr.js') }}"></script>
    @yield('scripts')
    <script src="{{ asset('assets/tmplts_backend/js/demo.js') }}"></script>
    <script>
         $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // FILE BROWSE
        function callfileBrowser() {
            $(".custom-file-input").on("change", function() {
                const fileName = Array.from(this.files).map((value, index) => {
                    if (this.files.length == index + 1) {
                        return value.name
                    } else {
                        return value.name + ', '
                    }
                });
                $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
            });
        }
		callfileBrowser();

         // FOCUSED-FORM
		FormControl = function() {
			var e = $(".form-control, label");
			e.length && e.on("focus blur", function(e) {
				var $this = $(this);
				// console.log($this);
				if ($this.val() !== '') {
					$this.parents('.form-group').addClass('completed');
				} else {
					$this.parents('.form-group').removeClass('completed');
				}
				$(this).parents(".form-group").toggleClass("focused", "focus" === e.type)
			}).trigger("blur")
        }();

        //PRE-LOAD
        $(document).ready(function(){
			$('.preloader').delay(1000).fadeOut();
			$('#main').delay(1000).fadeIn();
		});
    </script>

    @if (auth()->guard()->check() == true)
    <script>
        function totalNotif() {
            $.ajax({
                url : "/admin/notification/json",
                type : "GET",
                dataType : "json",
                data : {},
                success:function(data) {
                if (data.total > 0) {
                    var html = data.total + 'New Notification';
                } else {
                    var html = 'Notification';
                }

                $('#count-new-notif').html(html);
                    if (data.total > 0) {
                        $('#notif-dot').css({
                            display : 'inline block'
                        });
                        $('.count-dot').html(data.total);
                    } else {
                        $('#notif-dot').css({
                            display : 'none'
                        });
                    }
                    setTimeout(totalNotif, 60 * 1000);
                }
            })
        };
        $(document).ready(function() {
            totalNotif();
        });
        function relativeTime(date_str) {
            if (!date_str) {return;}
            date_str = $.trim(date_str);
            date_str = date_str.replace(/\.\d\d\d+/,"");
            date_str = date_str.replace(/-/,"/").replace(/-/,"/");
            date_str = date_str.replace(/T/," ").replace(/Z/," UTC");
            date_str = date_str.replace(/([\+\-]\d\d)\:?(\d\d)/," $1$2");
            var parsed_date = new Date(date_str);
            var relative_to = (arguments.length > 1) ? arguments[1] : new Date();
            var delta = parseInt((relative_to.getTime()-parsed_date)/1000);
            delta=(delta<2)?2:delta;
            var r = '';
            if (delta < 60) {
            r = delta + ' seconds ago';
            } else if(delta < 120) {
            r = 'a minute ago';
            } else if(delta < (45*60)) {
            r = (parseInt(delta / 60, 10)).toString() + ' min ago';
            } else if(delta < (2*60*60)) {
            r = 'an hour ago';
            } else if(delta < (24*60*60)) {
            r = '' + (parseInt(delta / 3600, 10)).toString() + ' hour ago';
            } else if(delta < (48*60*60)) {
            r = 'a day ago';
            } else {
            r = (parseInt(delta / 86400, 10)).toString() + ' day ago';
            }
            return r;
        };
        $('#click-notif').click(function () {
            $.ajax({
                url : "/admin/notification/json",
                type : "GET",
                dataType : "json",
                data : {},
                success:function(data) {
                    $('#list-notification').html(' ');
                    if (data.latest.length > 0) {
                        $.each(data.latest ,function(index, value) {
                            var read = '';
                            var status = 'Already seen';
                            if (value.read == 0) {
                                read = 'style="background-color:#f5f5f5;"';
                                status = 'Not yet seen';
                            }
                            var titik = '';
                            if (value.title.length > 35) {
                                titik = '...';
                            }
                            if (value.content.length > 35) {
                                titik = '...';
                            }
                            $('#list-notification').append(`
                            <a href="{{ url('/') }}/`+value.link+`" class="list-group-item list-group-item-action media d-flex align-items-center" `+read+` title="`+status+`">
                                <div class="ui-icon ui-icon-sm `+value.icon+` bg-`+value.color+` border-0 text-white"></div>
                                    <div class="media-body line-height-condenced ml-3">
                                    <div class="text-body">`+value.title.substring(0, 35)+titik+`</div>
                                        <div class="text-light small mt-1">
                                            `+value.content.substring(0, 35)+titik+`
                                        </div>
                                    <div class="text-light small mt-1">`+relativeTime(value.created_at)+`</div>
                                </div>
                            </a>
                            `);
                        });
                    } else {
                        $('#list-notification').html(`
                            <a href="javascript:void(0)" class="list-group-item list-group-item-action media d-flex align-items-center text-center">
                                <i><strong style="color:red;">! No new notification !</strong></i>
                            </a>
                        `);
                    }
                },
            });
        });
    </script>
    @endif

    @yield('jsbody')

</body>
</html>
