<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>{!! $config['meta_title'] !!} | @lang('common.maintenance_title')</title>
		<meta name="title" content="{!! isset($data['meta_title']) ? $data['meta_title'] : $config['meta_title'] !!}">
        <meta name="description" content="{!! isset($data['meta_description']) ? $data['meta_description'] : $config['meta_description'] !!}">
        <meta name="keywords" content="{!! isset($data['meta_keywords']) ? $data['meta_keywords'] : $config['meta_keywords'] !!}">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<meta property="og:locale" content="{{ app()->getlocale().'_'.strtoupper(app()->getlocale()) }}" />
        <meta property="og:site_name" content="{{ route('home') }}">
        <meta property="og:title" content="{!! isset($data['meta_title']) ? $data['meta_title'] : $config['meta_title'] !!}"/>
        <meta property="og:url" name="url" content="{{ url()->full() }}">
        <meta property="og:description" content="{!! isset($data['meta_description']) ? $data['meta_description'] : $config['meta_description'] !!}"/>
        <meta property="og:image" content="{!! isset($data['cover']) ? $data['cover'] : $config['open_graph'] !!}"/>
        <meta property="og:image:width" content="650" />
        <meta property="og:image:height" content="366" />
        <meta property="og:type" content="website" />

		<meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{!! isset($data['meta_title']) ? $data['meta_title'] : $config['meta_title'] !!}">
        <meta name="twitter:site" content="{{ url()->full() }}">
        <meta name="twitter:creator" content="{!! isset($data['creator']) ? $data['creator'] : 'Administrator Web' !!}">
        <meta name="twitter:description" content="{!! isset($data['meta_description']) ? $data['meta_description'] : $config['meta_description'] !!}">
        <meta name="twitter:image" content="{!! isset($data['cover']) ? $data['cover'] : $config['open_graph'] !!}">

		<link rel="shortcut icon" href="{{ asset('assets/tmplts_frontend/images/favicon.ico') }}"/>

		<link href="https://fonts.googleapis.com/css?family=Merriweather+Sans:400,700" rel="stylesheet">

		<!-- Css Global -->
		<link rel="stylesheet" href="{{ asset('assets/tmplts_maintenance/css/bootstrap.css') }}">
		<link rel="stylesheet" href="{{ asset('assets/tmplts_maintenance/css/style.css') }}">

	 	<script src="{{ asset('assets/tmplts_maintenance/js/jquery.min.js') }}"></script>

       	<!-- jQuery Additional-->
		<script src="{{ asset('assets/tmplts_maintenance/js/scrollreveal.min.js') }}"></script>

        <!-- jQuery Global-->
        <script src="{{ asset('assets/tmplts_maintenance/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('assets/tmplts_maintenance/js/main.js') }}"></script>

	</head>
	<body>
		<div class="intro-content">
			<div class="container">
				<div class="intro-logo">
					<div class="wrap-logo sr-top">
						<div class="bg-logo">
							<div class="logo">logo</div>
						</div>
					</div>
					<ul class="list-socmed">
						<!-- <li class="sr-repeat">
							<a href="https://www.facebook.com/ajushi.id" target="_blank">
								<img src="images/facebook.svg">
							</a>
						</li>
						<li class="sr-repeat">
							<a href="https://www.instagram.com/ajushi.id" target="_blank">
								<img src="images/instagram.svg">
							</a>
						</li>
						<li class="sr-repeat">
							<a href="https://www.tokopedia.com/ajushiofficial" target="_blank">
								<img src="images/toped.svg">
							</a>
						</li>
						<li class="sr-repeat">
							<a href="https://www.bukalapak.com/u/ajushi" target="_blank">
								<img src="images/bukalapak.svg">
							</a>
						</li>
						<li class="sr-repeat">
							<a href="https://shopee.co.id/ajushi.id" target="_blank">
								<img src="images/shopee.svg">
							</a>
						</li> -->
					</ul>

				</div>
				<div class="intro-desc sr">
					<h5>@lang('common.maintenance_title')</h5>
					<h3>@lang('common.maintenance_description')</h3>
					<span>{!! $config['website_name'] !!}</span>
				</div>
			</div>
		</div>

	</body>
</html>
