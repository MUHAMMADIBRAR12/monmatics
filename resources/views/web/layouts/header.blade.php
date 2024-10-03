
<header class="" id="">
    <!-- Navbar -->
    <div class="" style="border-top: solid 1px #023C82">
        <nav class="navbar navbar-expand-lg"style="height:40px" >
            <div class="container " style="max-width: 100% ;" id="navmob">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navmob" aria-controls="navmob">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <nav class="navbar navbar-expand-lg" >
                    <div class="container">
                        <div class="collapse navbar-collapse" id="nvbCollapse">
                            <ul class="navbar-nav col mr-5">
                                <li class="nav-item pl-1 mr-3 navbarFontClass"  style="height: 20px; color:#B1B0B0; margin-left:70px">
                                    <span><b>Let the platform lead the way</b></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>

                <div class="collapse navbar-collapse" id="nav2" style="margin-right:100px">
                    <ul class="navbar-nav ms-auto">

                        <!-- <li class="nav-item">
                            <a class="nav-link mx-2 UppernavbarFontClass" style="color:  black;" id="nav2" href="#"><b>Pakistan</b> : +92 300 1234567</a>
                        </li> -->
                        <li class="nav-item">
                            <a class="nav-link mx-2 UppernavbarFontClass" style="color:  black;" id="nav2" href="#"><b>Email</b> : hi@monmatics.com</a>
                        </li>
                        @if(Request::is('/'))
                        <li class="nav-item dropdown">
                            <a href="{{ URL('authentication/login') }}" class="nav-link mx-2 navbarFontClass mr-5 " style="color:  #023C82
                            ; " id="nav2" href="#" id="" role="button"aria-expanded="false">

                                <b> Log In</b>                            </a>
                        </li>
                        @else
                        <li class="nav-item dropdown">
                            <a href="{{ URL('/') }}" class="nav-link mx-2 navbarFontClass mr-5 " style="color:  #023C82
                            ; " id="nav2" href="#" id="" role="button"aria-expanded="false">
                              BACK
                            </a>
                        </li>

                        @endif

                    </ul>
                </div>
            </div>
        </nav>
    </div>
    <nav class="navbar navbar-expand-lg navbar-light" style="border-top: solid 1px #023C82;border-bottom: solid 1px #023C82">
        <div class="container">
            <a class="navbar-brand" href="{{url('/')}}">
                <img class="" src="{{asset('public/assets/web_assets/images/home/Logo1.svg')}}" alt="" style="height: 30px;">
            </a>
            <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarTogglerDemo01">

                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item dropdown paddingInLists">
                        <a class="nav-link dropdown-toggle paddingInLists text-dark" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Features
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <li><a class="dropdown-item" href="#">Action</a></li>
                            <li><a class="dropdown-item" href="#">Another action</a></li>
                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                        </ul>
                    </li>
                    {{-- next dropdown --}}
                    <li class="nav-item dropdown paddingInLists">
                        <a class="nav-link dropdown-toggle paddingInLists text-dark" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Plans for small Businesses
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <li><a class="dropdown-item" href="#">Action</a></li>
                            <li><a class="dropdown-item" href="#">Another action</a></li>
                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                        </ul>
                    </li>
                    {{-- next dropdown --}}
                    <li class="nav-item dropdown paddingInLists">
                        <a class="nav-link dropdown-toggle paddingInLists text-dark" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            For accountants and bookkeepers
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <li><a class="dropdown-item" href="#">Action</a></li>
                            <li><a class="dropdown-item" href="#">Another action</a></li>
                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                        </ul>
                    </li>
                    {{-- next dropdown --}}
                    <li class="nav-item dropdown paddingInLists">
                        <a class="nav-link dropdown-toggle paddingInLists text-dark" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Apps
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <li><a class="dropdown-item" href="#">Action</a></li>
                            <li><a class="dropdown-iteheight: 20px; color:#B1B0B0; margin-left:85pxm" href="#">Another action</a></li>
                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                        </ul>
                    </li>
                    {{-- next dropdown --}}
                    <li class="nav-item dropdown paddingInLists">
                        <a class="nav-link dropdown-toggle paddingInLists text-dark" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Support
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <li><a class="dropdown-item" href="#">Action</a></li>
                            <li><a class="dropdown-item" href="#">Another action</a></li>
                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                        </ul>
                    </li>
                </ul>

                <form class="d-flex">
                    <style>
                        .btn-outline-primary {
                            color: #023C82;
                            border-color: #023C82;
                            background-color: transparent;
                        }

                        /* Remove hover color */
                        .btn-outline-primary:hover {
                            color: #023C82;
                            background-color: transparent;
                        }
                    </style>
                    <div class="dropdown" style="padding-right: 14px;">
                        <button style="border-radius: 12px" class="btn btn-outline-primary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                          Try Monmatics
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                          <li><a class="dropdown-item" href="{{ URL('/Individual-Registration') }}">Individual</a></li>
                          <li><a class="dropdown-item" href="{{ URL('/Business-Registration') }}">Business</a></li>
                          <li><a class="dropdown-item" href="{{ URL('/Accountant&Bookkeeper') }}">Accountant/Bookkeeper</a></li>
                        </ul>
                      </div>
                </form>
            </div>
        </div>
    </nav>
</header>
