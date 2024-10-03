@extends('layout.master')
@section('title', 'Purchase Expenses')
@section('parentPageTitle', 'Purchase')
@section('page-style')
<?php
use App\Libraries\appLib;
?>


<link rel="stylesheet" href="{{ asset('public/assets/plugins/footable-bootstrap/css/footable.bootstrap.min.css') }}" />
<link rel="stylesheet" href="{{ asset('public/assets/plugins/footable-bootstrap/css/footable.standalone.min.css') }}" />
<link rel="stylesheet" href="{{asset('public/assets/css/sw.css')}}">
<!-- <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet"> -->
<style>
    ul {
        list-style-type: none;
    }
</style>
<script lang="javascript/text">
    var searchCoaURL = "{{ url('coaSearch')}}";
    var token = "{{ csrf_token()}}";
    var projectURL = "{{ url('projectSearch') }}";
    var customerURL = "{{ url('customerSearch') }}";
    var userURL = "{{ url('userSearch') }}";
    var searchCoaURL = "{{ url('coaSearch')}}";
    var vendorURL= "{{ url('vendorSearch') }}";
</script>
@stop
@section('content')

<div class="row clearfix">
    <div class="card col-lg-12">
        <div class="header">
            <div class="row">
                <div class="col-md-9">
                    <h2><strong>Purchase</strong> Expenses</h2>
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
            <form method="POST" action="" enctype="multipart/form-data">
                @csrf
                <div class="row col-12">
                    <div class="col-lg-4 col-md-6 multicurrency">

                        <label for="fiscal_year">Purchase Order</label>
                        <div class="form-group">
                            <select name="pur_ord" id="pur_ord" class="form-control show-tick ms select2" data-placeholder="Select" required>
                                <option value="">--Select order no--</option>
                                @foreach($purchaseExpenses as $purch)
                                <option value="{{ $purch->id ?? '' }}">{{ $purch->month ?? '' }}-{{appLib::padingZero($purch->number ?? '')}}</option>
                                @endforeach

                            </select>
                        </div>

                    </div>
                    <div class="col-lg-4">
                    <label for="fiscal_year">Account Head</label>
                        <select id="select" name="parent_id" class="form-control show-tick ms select2 mb-3" data-placeholder="Select" required>
                            <option value="-1">Select Account</option>
                            @foreach ($coalist as $coalists)
                            <option value="{{ $coalists->id }}">{{ $coalists->name }}</option>
                            @endforeach
                        </select>
                    </div>
                   
                    <div class="col-md-4">

                        <div class="form-group">
                            <label for="role">Date</label>
                            <input type="date" name="exp_date" class="form-control" required>
                        </div>
                    </div>
                </div>

                <div class="row form-group" id="h2">
                    <div class="col-lg-10 m-mt-5 m-sm-3" style="text-align: center;  margin-top: 15px;">
                        <button class="btn btn-primary btn-sm" typ="submit" href="" style="height: 38px; font-size: 18px;">
                            <i class="">Generate</i>
                        </button>
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
<script src="{{asset('public/assets/js/sw.js')}}"></script>



@stop