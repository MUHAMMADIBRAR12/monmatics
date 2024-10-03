@extends('layout.master')
@section('title', 'Product Type')
@section('parentPageTitle', 'Inventory')
@section('page-style')
<?php  use App\Libraries\appLib; ?>
<link rel="stylesheet" href="{{ asset('public/assets/plugins/footable-bootstrap/css/footable.bootstrap.min.css') }}"/>
<link rel="stylesheet" href="{{ asset('public/assets/plugins/footable-bootstrap/css/footable.standalone.min.css') }}"/>
@stop
@section('content')
<style>
    .table td{
        padding: 0.10rem; 
    }
</style>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="body">
                <div class="table-responsive">
                    <button class="btn btn-primary" style="align:right" onclick="window.location.href = '{{ url('Inventory/productType/Create') }}';" >New Product Type</button>
                    @if (Session::has('msg'))
	                  <div class="alert alert-danger">{{ Session::get('msg') }}</div>
                    @endif
                        
                        <table class="table table-striped m-b-0">
                        <thead>
                            <tr>
                                <th>Type</th>
                                 <th>Action</th>
                            </tr>
                        </thead>
                            <tbody>
                             @foreach($productTypes as $productType)
                             <tr>
                                <td>{{$productType->type}}</td>
                                <td>
                                    <a class="btn btn-primary btn-sm"  href="{{url('Inventory/editProductType/'.$productType->id)}}">
                                        <i class="zmdi zmdi-edit"></i>
                                    </a>
                                    <a class="btn btn-danger btn-sm del" data-toggle="modal" data-target="#modalCenter">
                                           <input type="hidden" id="userId" value="{{$productType->id}}">
                                           <i class="zmdi zmdi-delete text-white"></i>
                                    </a>          
                                </td>
                             </tr>
                             @endforeach
                             
                        </tbody>
                    </table>
                <div class="modal fade" id="modalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Are You Sure to Delete The Role</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-footer">
                                <a class="btn btn-secondary" data-dismiss="modal">No</a>
                                <a class="btn btn-primary model-delete" href="">Yes</a>
                            </div>
                        </div>
                    </div>
                </div> 
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('page-script')
<script src="{{asset('public/assets/bundles/footable.bundle.js')}}"></script>
<script src="{{asset('public/assets/js/pages/tables/footable.js')}}"></script>
<script>
$(document).ready(function(){
$(document).on('click','.del',function(){
var id=$(this).find('#userId').val();
$(".model-delete").attr("href", "remove/"+id);
});
});
</script>
@stop