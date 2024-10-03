@extends('layout.master')
@section('title', 'Purchase Expenses')
@section('parentPageTitle', 'Purchase')
@section('page-style')
<?php use App\Libraries\appLib;  ?>

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
                    <h2><strong>View Purchase</strong> Expenses</h2>
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

            <form method="POST"  enctype="multipart/form-data"  action="{{url('Purchase/PurchaseExpenses/costDeploymentView/')}}">
                <input type="hidden" name="peId" value="{{$peId}}" >
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="role">Purchase Order No</label>
                            <p>{{ $poNumber ?? '' }}</p>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="card">
                        <a href="{{url('Purchase/PurchaseExpenses/bookInvoice/'.$peId)}}" class="btn btn-primary">
                            <b><i class="zmdi zmdi-plus"></i> Add Expenses</b>
                        </a>
                    </div>
                    <div class="card">
                        <input type="submit" value="Cost Deployment" class="btn btn-raised btn-primary waves-effect">
                              
                    </div>
                </div>
            </form>
        </div>
        @foreach($purchaseInvoices as $purchaseInvoice)
        
        <div class="body mt-2">
            <form method="post" action="" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="hidden" name="id" value="{{ $project->id ?? ''  }}">
                <div class="row">                    
                    <div class="col-md-4">
                        <label>Vendor</label>
                        <p>{{ $purchaseInvoice->name }}</p>                        
                    </div>
                    <div class="col-md-2">
                        <label for="name">Vendor Reference</label>
                        <div class="form-group"><p>{{ $purchaseInvoice->vendor_reference }}</p></div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="role">Date</label>
                            <p>{{ $purchaseInvoice->date }}</p>
                        </div>
                    </div>
                    <div class="col-md-1 multicurrency">
                        <label for="fiscal_year">Currency</label>
                        <div class="form-group"><p>{{ $purchaseInvoice->cur_name }}</p></div>
                    </div>
                    <div class="col-md-1 multicurrency">
                        <label for="rate">Rate</label>
                        <div class="form-group"><p>{{ $purchaseInvoice->cur_rate }}</p></div>
                    </div>
                    
                    <div class="col-md-2">
                        <label for="name">Amount</label>
                        <div class="form-group"><p>{{ $purchaseInvoice->total_inv_amount }}</p></div>
                    </div>
                    <div class="col-md-1">
                        <a class="btn btn-primary btn-sm " href="{{url('Purchase/PurchaseExpenses/bookInvoice/'.$peId.'/'.$purchaseInvoice->id)}}" style="height: 27px;width: 65px;">
                            <i class="">Edit</i>
                        </a>
                    </div>
                </div>
                
            </form>
        </div>
        @endforeach        
    </div>
</div>
@stop
@section('page-script')
<script src="{{asset('public/assets/bundles/footable.bundle.js')}}"></script>
<script src="{{asset('public/assets/js/pages/tables/footable.js')}}"></script>
<script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>


@stop