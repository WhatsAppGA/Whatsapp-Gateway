<!DOCTYPE html>
<html class="semi-dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ in_array(app()->getLocale(), ['ar', 'he', 'fa']) ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{config('config.site_name')}} ,Whatsapp gateway Multi device Beta version">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="keywords" content="waapi,wa gateway, whatsapp blast, wamp, mpwa, m pedia wa gateway, wa gateway m pedia, ">
    <!-- The above 6 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <!-- Title -->
    <title>{{ $title }} | {{config('config.site_name')}}</title>

    <!-- Styles -->
    <link href="{{ asset('assets/css/bootstrap.' . (in_array(app()->getLocale(), ['ar', 'he', 'fa']) ? 'rtl' : 'ltr') . '.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/bootstrap-extended.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/style.' . (in_array(app()->getLocale(), ['ar', 'he', 'fa']) ? 'rtl' : 'ltr') . '.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

    <!-- jQuery -->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
</head>

<body class="bg-login">
<style>
    .navbar-nav .dropdown-menu {
        background-color: #fff;
        border-radius: 5px;
    }

    .navbar-nav .nav-item .nav-link.dropdown-toggle {
        background-color: #f5f5f5; 
        border-radius: 5px; 
		border-top-right-radius: 30px;
        padding: 0.5rem 1rem; 
        display: inline-flex;
        align-items: center;
    }

    .navbar-nav .nav-item .nav-link.dropdown-toggle i {
        margin-right: 0.5rem;
    }
	
	.me-auto {
		position: absolute;
		top: 5px;
		right: 5px;
	}
</style>

    <div class="wrapper">
        {{ $slot }}
    </div>

    <!-- Bootstrap JS -->
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/pace.min.js') }}"></script>
</body>

</html>
