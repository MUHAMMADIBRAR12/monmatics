@extends('layout.master')
@section('title', 'Email Template')
@section('parentPageTitle', 'Crm')

@section('page-style')
<link rel="stylesheet" href="{{ asset('public/assets/plugins/footable-bootstrap/css/footable.bootstrap.min.css') }}" />
<link rel="stylesheet" href="{{ asset('public/assets/plugins/footable-bootstrap/css/footable.standalone.min.css') }}" />
<!-- <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet"> -->
<style>
    ul {
        list-style-type: none;
    }
</style>
@stop
@section('content')

<div class="row clearfix">
    <div class="card col-lg-12">
        <div class="header">
            <div class="row">
                <div class="col-md-9">
                    <h2><strong>Email</strong> Template</h2>
                </div>
                <div class="col-md-3">

                    <a href="{{ url('role_management')}}" class="btn btn-primary float-right"><i class="zmdi zmdi-arrow-left"></i></a>
                </div>
            </div>
        </div>
        @if(session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
        @endif
        <div class="body">
            <form method="POST" action="{{url('Crm/EmailTemplates/createTemplate')}}">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="role">Template name</label>
                            <input type="text" name="temp_name" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="role">Subject</label>
                            <input type="text" name="subject" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="role">Subject</label>
                            <!-- <textarea id="summernote" name="bodytext"></textarea> -->
                            <textarea name="bodytext"></textarea>
                        </div>
                    </div>

                </div>
                <div class="row form-group" id="h2">
                    <div class="col-md-12 mt-3" style="text-align: center; width: 35%;height: 105%;">
                        <input type="submit" value="Save Template" class="btn btn-primary py-2 px-4 text-white">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@stop
@section('page-script')
<script src="{{asset('public/assets/bundles/footable.bundle.js')}}"></script>
<script src="{{asset('public/assets/js/pages/tables/footable.js')}}"></script>
<script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
<script>
    $(document).ready(function() {
        CKEDITOR.replace('bodytext');
    });
</script>
@stop