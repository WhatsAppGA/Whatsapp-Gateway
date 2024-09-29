<!DOCTYPE html>
<html class="semi-dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ in_array(app()->getLocale(), ['ar', 'he', 'fa']) ? 'rtl' : 'ltr' }}">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>{{ $title }} | {{ config('config.site_name') }}</title>
    <link rel="icon" href="{{ asset('assets/images/favicon.png') }}" type="image/png" />
    <!--plugins-->
    <link href="{{ asset('assets/plugins/vectormap/jquery-jvectormap-2.0.2.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/metismenu/css/metisMenu.min.css') }}" rel="stylesheet" />
    <!-- Bootstrap CSS -->
    <link href="{{ asset('assets/css/bootstrap.' . (in_array(app()->getLocale(), ['ar', 'he', 'fa']) ? 'rtl' : 'ltr') . '.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/bootstrap-extended.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/style.' . (in_array(app()->getLocale(), ['ar', 'he', 'fa']) ? 'rtl' : 'ltr') . '.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" />

    <!-- loader-->
    <link href="{{ asset('assets/css/pace.min.css') }}" rel="stylesheet" />

    <!--Theme Styles-->
    <link href="{{ asset('assets/css/dark-theme.' . (in_array(app()->getLocale(), ['ar', 'he', 'fa']) ? 'rtl' : 'ltr') . '.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/light-theme.' . (in_array(app()->getLocale(), ['ar', 'he', 'fa']) ? 'rtl' : 'ltr') . '.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/semi-dark.' . (in_array(app()->getLocale(), ['ar', 'he', 'fa']) ? 'rtl' : 'ltr') . '.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/header-colors.' . (in_array(app()->getLocale(), ['ar', 'he', 'fa']) ? 'rtl' : 'ltr') . '.css') }}" rel="stylesheet" />
    {{-- csrf --}}
    <meta name="csrf-token" content="{{ csrf_token() }}" />

	<style>
        .float-btn {
            position: fixed;
            bottom: 45px;
            right: 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            font-size: 24px;
            cursor: pointer;
            box-shadow: 0 2px 10px rgba(0,0,0,0.3);
			z-index: 99999;
        }
		.float-btn-rtl {
			position: fixed;
            left: 20px !important;
			right: auto !important;
        }
    </style>
</head>

<body>
    <button class="float-btn float-btn-{{(in_array(app()->getLocale(), ['ar', 'he', 'fa']) ? 'rtl' : 'ltr') }}" onclick="cycleTheme()">🎨</button>
	<script>
        const themes = ['semi-dark', 'light-theme', 'dark-theme'];
        let currentThemeIndex = 0;

        function cycleTheme() {
            document.documentElement.classList.remove(...themes);

            currentThemeIndex = (currentThemeIndex + 1) % themes.length;

            const newTheme = themes[currentThemeIndex];
            document.documentElement.classList.add(newTheme);

            localStorage.setItem('selectedTheme', newTheme);
        }

        function applyStoredTheme() {
            const storedTheme = localStorage.getItem('selectedTheme');
            if (storedTheme && themes.includes(storedTheme)) {
                document.documentElement.classList.remove(...themes);
                document.documentElement.classList.add(storedTheme);
                currentThemeIndex = themes.indexOf(storedTheme);
            } else {
                document.documentElement.classList.add('semi-dark');
                currentThemeIndex = 0;
            }
        }
        applyStoredTheme();
    </script>
    {{-- <x-sidebar></x-sidebar> --}}
    <div class="wrapper">
        <x-header></x-header>
        <x-aside></x-aside>

        <!--start content-->
        <main class="page-content">




            {{ $slot }}
          
          
         

        </main>
        <!--end page main-->
		<footer class="footer">
        <div class="container-fluid"> 
            <div class="row">
                <div class="col-sm-6"> 
                    © {{config('config.footer_name')}}
                </div>
                <div class="col-sm-6">
                    <div class="text-sm-end d-none d-sm-block">
					    {!! config('config.footer_copyright') !!}
                    </div>
                </div>
            </div>
        </div>
		</footer>
    </div>


    <!-- Javascripts -->



    <!-- Bootstrap bundle JS -->
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <!--plugins-->

    <script src="{{ asset('assets/plugins/simplebar/js/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/metismenu/js/metisMenu.min.js') }}"></script>
    {{-- <script src="{{asset('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js')}}"></script> --}}
    <script src="{{ asset('assets/js/pace.min.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script src="{{ asset('assets/plugins/smart-wizard/js/jquery.smartWizard.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <link href="{{ asset('assets/plugins/smart-wizard/css/smart_wizard_all.min.css') }}" rel="stylesheet"
        type="text/css" />
    {{-- <script>
    new PerfectScrollbar(".review-list")
    new PerfectScrollbar(".chat-talk")
 </script> --}}

    <script>
        toastr.options = {
            closeButton: false,
            debug: false,
            newestOnTop: false,
            progressBar: false,
            positionClass: "toast-top-right",
            preventDuplicates: false,
            onclick: null,
            showDuration: "300",
            hideDuration: "1000",
            timeOut: "5000",
            extendedTimeOut: "1000",
            showEasing: "swing",
            hideEasing: "linear",
            showMethod: "fadeIn",
            hideMethod: "fadeOut",
        };
    </script>
</body>

</html>
