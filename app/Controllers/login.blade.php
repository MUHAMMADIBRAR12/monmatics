@extends('layout.authentication')
@section('title', 'Login')
@section('content')

<style>
    .header .logo{
        width: 160px !important;
    }
</style>

<div class="row">
    <div class="col-lg-4 col-sm-12">
        <form class="card auth_form" method="POST" action="authenticate" >
             {{@csrf_field() }}
            <div class="header">
                <img class="logo" src="{{asset('public/assets/images/logo2.png')}}" alt="">
                <h5>Log in</h5>
                @if (Session::has('message'))
	            <div class="text-danger">{{ Session::get('message') }}</div>
                @endif
            </div>
            <div class="body">
                <div class="input-group mb-3">
                    <input type="email" name='email' class="form-control" placeholder="Email" required>
                    <div class="input-group-append">
                        <span class="input-group-text"><i class="zmdi zmdi-account-circle"></i></span>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <input type="password" name='password' class="form-control" placeholder="Password" required>
                    <div class="input-group-append">
                        <span class="input-group-text"><i class="zmdi zmdi-lock"></i></span>
                    </div>
                </div>
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="checkbox">
                        <input id="remember_me" name="remember" type="checkbox">
                        <label for="remember_me">Remember Me</label>
                    </div>
                    <div class="input-group mb-3">
                        <button type="submit" class="btn btn-primary" style="width: 300px">
                            {{ __('Login') }}
                        </button>
                    </div>
                </form>
                
            </div>
        </form>
    </div>
    <div class="col-lg-8 col-sm-12">
        <div class="card">
            <img src="{{asset('public/assets/images/signin.svg')}}" alt="Log In" />
        </div>
    </div>
</div>
@stop
