@php
$source_label=array(
    "Expense"=>"Expense",
    "Bank"=>"Bank",
    "Income"=>"Income",
);
@endphp
@extends('layout.master')
@section('title',$source.' List')
@section('parentPageTitle', 'Accounts')
@section('page-style')
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
                    <button class="btn btn-primary" style="align:right" onclick="window.location.href = '{{ url('Accounts/Expense/'.$source) }}';" >New&nbsp{{$source}} </button>
                    <table class="table table-striped m-b-0">
                        <thead>
                            <tr>     
                                <th>Edit</th>
                                <th> Code</th>
                                <th data-breakpoints="xs"> Name</th>
                                <th data-breakpoints="xs">Parent Account</th>                                
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sourceData as $data)
                            <tr>
                                <td><button class="btn btn-primary btn-sm" onclick="window.location.href = '{{ url('Accounts/Expense/'.$source.'/'.$data->id) }}';"><i class="zmdi zmdi-edit"></i></button></td>
                                <td>{{$data->code}}</td>
                                <td>{{$data->name}}</td>
                                {{-- <td>{{$category}}</td> --}}
                                <td>{{$data->status}}</td>
                            </tr>
                            @endforeach                                          
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('page-script')
<script src="{{asset('public/assets/bundles/footable.bundle.js')}}"></script>
<script src="{{asset('public/assets/js/pages/tables/footable.js')}}"></script>
@stop