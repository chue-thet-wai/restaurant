<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Restaurant</title>
    <link rel="icon" href="{{ asset('restaurant.jpg') }}" type="image/x-icon">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <script src="{{ asset('js/admin.js') }}" defer></script>

    <!-- Styles -->
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

</head>

<script>
    $(document).ready(function() {
        var currentUrl = window.location.href;

        // Iterate through each link in the navigation
        $('#sidebar-nav a').each(function() {
            // Check if the href attribute matches the current URL
            if ($(this).attr('href') === currentUrl) {
                // Add the active-link class to the matching link
                $(this).addClass('active');

                // Collapse the parent item if it has children
                var parentItem = $(this).closest('.nav-item');
                if (parentItem.find('.collapse').length > 0) {
                    parentItem.find('.collapse').collapse('show');
                }
            }  
        });

        // Collapse sidebar-nav on child link click
        $('#sidebar .nav-content a').click(function() {
            $('#sidebar .collapse').collapse('hide');
        });
    });

</script>

<body>
    <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <i class="bi bi-list toggle-sidebar-btn"></i>
      <div class="logo d-flex align-items-center ml-3">
        <img src="{{ asset('restaurant.jpg') }}" alt="">
        <span class="d-none d-sm-block">Restaurant</span>
      </div>
    </div>

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <li class="nav-item dropdown pe-3">
          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            {{ Auth::user()->name }}
            <span class="d-none d-md-block dropdown-toggle ps-2"></span>
          </a>

            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>

        </li><!-- End Profile Nav -->

      </ul>
    </nav><!-- End Icons Navigation -->
  </header><!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link collapsed" href="{{ url('/home') }}">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li><!-- End Dashboard Nav -->

        @php
            $dashboard = App\Http\Controllers\HomeController::getDashboardPermission();
            $icon_array = [
                "Role Management"      => "bi bi-menu-button-wide",
                "User Management"      => "bi bi-person-fill-add",
                "Branch"               => "bi bi-shop-window",
                "Restaurant Menu"      => "bi bi-journal-plus",
                "Reservation"          => "bi bi-bookmark-plus-fill",
                "Reporting"            => "bi bi-file-arrow-down",
            ];
            $menu_child_notexist = array('Role Management','User Management','Branch',
                                            "Restaurant Menu","Reservation","Reporting");

            foreach ($dashboard as $main_menu => $sub_menu) {
            $targetNavName = '#'.str_replace(' ', '', $main_menu).'-nav';
            $navId         =  str_replace(' ', '', $main_menu).'-nav';
            @endphp
            @if (!in_array($main_menu, $menu_child_notexist)) 
                <li class="nav-item">
                            <a class="nav-link collapsed sidebar-link" data-target-nav="{{ $navId }}" data-bs-target="{{$targetNavName}}" data-bs-toggle="collapse" href="#">
                                <i class="{{$icon_array[$main_menu]}}"></i><span>{{" ".$main_menu." "}}</span><i class="bi bi-chevron-down ms-auto"></i>
                            </a>
                            <ul id="{{$navId}}" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                                @php 
                                foreach ($sub_menu as $menu) { 
                                @endphp
                                    @if ($menu['type'] == 'route')
                                        <li>
                                        <a class="nav-link sidebar-link" href="{{route($menu['menu_route'])}}">
                                            <span>{{$menu['sub_menu']}}</span>
                                        </a>
                                        </li>
                                    @else 
                                        <li>
                                        <a class="nav-link sidebar-link" href="{{ url($menu['menu_route']) }}">
                                            <span>{{$menu['sub_menu']}}</span>
                                        </a>
                                        </li>
                                    @endif
                                @php
                                }
                                @endphp
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            @if ($sub_menu[0]['type'] == 'route')
                            <a class="nav-link collapsed sidebar-link" data-target-nav="{{ $navId }}" href="{{route($sub_menu[0]['menu_route'])}}"> 
                            @else
                            <a class="nav-link collapsed sidebar-link" data-target-nav="{{ $navId }}" href="{{ url($sub_menu[0]['menu_route']) }}">
                            @endif
                                <i class="{{$icon_array[$main_menu]}}"></i>
                                <span>{{$main_menu}}</span>
                            </a>
                        </li>
                    @endif
                @php } @endphp
                <li class="nav-item">
                    <a class="nav-link collapsed" href="{{ route('logout') }}" 
                        onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                        <i class="bi bi-box-arrow-in-right"></i>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>

  </aside><!-- End Sidebar-->

  <main id="main" class="main">
    @include('layouts.error')
    @yield('content')

  </main><!-- End #main -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
</body>

</html>
