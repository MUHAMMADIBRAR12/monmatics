<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('public/tablogo.png') }}" type="image/x-icon"> <!-- Favicon-->
    <title>{{ config('app.name') }} - @yield('title')</title>
    <meta name="description" content="@yield('meta_description', config('app.name'))">
    <meta name="author" content="@yield('meta_author', config('app.name'))">
    @yield('meta')
    {{-- See https://laravel.com/docs/5.5/blade#stacks for usage --}}
    @stack('before-styles')
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  
    {{-- <link rel="stylesheet" href="{{ asset('public/assets/plugins/bootstrap/css/bootstrap.min.css') }}"> --}}
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    @if (trim($__env->yieldContent('page-style')))
    @yield('page-style')
    @endif
    <!-- Custom Css -->
    <link rel="stylesheet" href="{{ asset('public/assets/css/style.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/css/sw.css') }}">
    @stack('after-styles')

    <style>
        /* Styles for light theme */
        .light-theme {
            background-color: #f5f5f5;
            color: #333;
        }

        /* Styles for dark theme */
        .dark-theme {
            background-color: #333;
            color: #f5f5f5;
        }
        a{
            text-decoration: none
        }
        .dt-buttons{
            gap: 6px;
        }
        .btn-icon{
            float: inline-end;
        }
    </style>



</head>
<?php
$setting = !empty($_GET['theme']) ? $_GET['theme'] : '';
$theme = 'theme-blush';
$menu = '';
if ($setting == 'p') {
    $theme = 'theme-purple';
} elseif ($setting == 'b') {
    $theme = 'theme-blue';
} elseif ($setting == 'g') {
    $theme = 'theme-green';
} elseif ($setting == 'o') {
    $theme = 'theme-orange';
} elseif ($setting == 'bl') {
    $theme = 'theme-cyan';
} else {
    $theme = 'theme-blush';
}

if (Request::segment(2) === 'rtl') {
    $theme .= ' rtl';
}
?>


<body class="ls-toggle-menu" >



    {{-- @if (Session::get('multi_currency') == 1)
    <style>
        .multicurrency {
            display: block !important;
        }
    </style>
    @else
    <style>
        .multicurrency {
            display: none !important;
        }
    </style>
    @endif --}}
    <!-- Page Loader -->
    <div class="page-loader-wrapper">
        <div class="loader">
            <div class="m-t-30"><img class="zmdi-hc-spin" src="{{ asset('public/assets/images/loading.png') }}"
                    height="90px" alt="OfDesk"></div>
            <p>Processing...</p>
        </div>
    </div>
    <!-- Overlay For Sidebars -->
    <div class="overlay"></div>
    @include('layout.navbarright')
    @include('layout.sidebar')
    @include('layout.rightsidebar')
    <section class="content">
        <div class="block-header">
            <div class="row">
                <div class="col-lg-7 col-md-6 col-sm-12">
                    <h2>@yield('title')</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}"><i
                                    class="zmdi zmdi-home"></i> Monmatics</a></li>
                        @if (trim($__env->yieldContent('parentPageTitle')))
                        <li class="breadcrumb-item">@yield('parentPageTitle')</li>
                        @endif
                        @if (trim($__env->yieldContent('title')))
                        <li class="breadcrumb-item active">@yield('title')</li>
                        @endif
                    </ul>
                    <button class="btn btn-primary btn-icon mobile_menu" type="button"><i
                            class="zmdi zmdi-sort-amount-desc"></i></button>
                </div>
                <div class="col-lg-5 col-md-6 col-sm-12">
                    <button class="btn btn-primary btn-icon float-right right_icon_toggle_btn" type="button"><i
                            class="zmdi zmdi-arrow-right"></i></button>
                </div>
            </div>
        </div>
        <div class="container-fluid">

            @yield('content')
        </div>
        <!--   <div>
                <h3>Powered by Solutions Wave</h3>
            </div>  -->
    </section>
    @yield('modal')
    <!-- Scripts -->
    @stack('before-scripts')

    <script src="{{ asset('public/assets/bundles/libscripts.bundle.js') }}"></script>
    <script src="{{ asset('public/assets/bundles/vendorscripts.bundle.js') }}"></script>
    <script src="{{ asset('public/assets/bundles/mainscripts.bundle.js') }}"></script>

    <script src="{{ asset('public/assets/plugins/fullcalendar/jqueryui.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/jquery-ui.js') }}"></script>
    @stack('after-scripts')
    @if (trim($__env->yieldContent('page-script')))
    <script>
            var Tawk_API = Tawk_API || {};

    </script>
    @yield('page-script')
    @endif

</body>
</html>
