@extends('layout.master')
@section('title', 'App List')
@section('parentPageTitle', 'Admin')
@section('page-style')
    <link rel="stylesheet" href="{{ asset('public/assets/plugins/footable-bootstrap/css/footable.bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/assets/plugins/footable-bootstrap/css/footable.standalone.min.css') }}" />
@stop
@section('content')
    <div class="row">
        <div class="col-md-12">
            @foreach ($modules as $module)
                <a href="{{ url('Admin/Modules/View/' . $module->id) }}" class="btn btn-primary">{{ $module->module }}</a>
            @endforeach
        </div>
    </div>
@stop
