@extends('layout.authentication')
@section('title', '500')
@section('content')
<div class="row">
    <div class="col-lg-4 col-sm-12">
        <form class="card auth_form">
            <div class="header">
                <img class="logo" src="{{asset('assets/images/logo.jpg')}}" alt="">
                <h5>Error 500</h5>
                <span>Something's broken :-(</span>
            </div>
            <div class="body">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Search...">
                    <div class="input-group-append">
                        <span class="input-group-text"><i class="zmdi zmdi-search"></i></span>
                    </div>
                </div>
                <a href="{{route('dashboard.index')}}" class="btn btn-primary btn-block waves-effect waves-light">GO TO HOMEPAGE</a>                        
                <div class="signin_with mt-3">
                    <a href="javascript:void(0);" class="link">Need Help?</a>
                </div>
            </div>
        </form>
        <div class="copyright text-center">
            &copy;
            <script>document.write(new Date().getFullYear())</script>,
            <span>Designed by <a href="https://thememakker.com/" target="_blank">ThemeMakker</a></span>
        </div>
    </div>
    <div class="col-lg-8 col-sm-12">
        <div class="card">
            <img src="{{asset('assets/images/signin.svg')}}" alt="500"/>
        </div>
    </div>
</div>
@stop