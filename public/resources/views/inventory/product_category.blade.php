@extends('layout.master')
@section('title', 'Product Category')
@section('parentPageTitle', 'Inventory')
@section('page-style')
<?php  use App\Libraries\appLib; ?>
<link rel="stylesheet" href="{{ asset('public/assets/plugins/footable-bootstrap/css/footable.bootstrap.min.css') }}"/>
<link rel="stylesheet" href="{{ asset('public/assets/plugins/footable-bootstrap/css/footable.standalone.min.css') }}"/>
@stop
@section('content')
<!-- Horizontal Layout -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="body">
                <form class="form-horizontal" action="{{url('Inventory/productCategory')}}" method="post">
                   {{ csrf_field() }}
                   @if(isset($productCategory))
                    <input type="hidden" name="id" value="{{$productCategory->id}}">
                    @endif
                    <div class="row clearfix">
                        <div class="col-lg-2 col-md-2 col-sm-4 form-control-label">
                            <label>Product Category</label>
                        </div>
                         <div class="col-lg-4 col-md-4 col-sm-4">
                            <div class="form-group">
                                <input type="text" id="category" name="category" class="form-control" placeholder="Product Type --" value="@if(isset($productCategory)){{$productCategory->category}}@endif" required>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4">
                            <div class="form-group">
                               <button type="submit" class="btn btn-raised btn-primary btn-round waves-effect">SAVE</button>
                            </div>
                        </div>
                    </div>
                   
                </form>
            </div>
        </div>
    </div>
</div>
@stop
@section('page-script')
<script src="{{asset('public/assets/bundles/footable.bundle.js')}}"></script>
<script src="{{asset('public/assets/js/pages/tables/footable.js')}}"></script>
@stop







