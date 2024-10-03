@extends('layout.master')
@section('title', 'GIN-sales')
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
                    <button class="btn btn-primary" style="align:right" onclick="window.location.href = '{{ url('Inventory/Invoice') }}';" >Pending Orders</button>
                    <button class="btn btn-primary" style="align:right" onclick="window.location.href = '{{ url('Sales/GinSalesList') }}';" >Issued Orders</button>
                    <button class="btn btn-primary" style="align:right" onclick="window.location.href = '{{ url('Inventory/GinSales') }}';" >New GIN-sales</button>
                    <table class="table table-striped m-b-0">
                        <thead>
                            <tr>
                                <th>Edit</th>
                                <th>Date</th>
                                <th>GIN No</th>
                                <th>D.O Number</th>
                                <th>Warehouse</th>
                                <th>Description</th>
                                <th>Status</th>

                            </tr>
                        </thead>
                            <tbody>
                            @foreach($gins as $gin)
                                <tr>
                                    <td>
                                        <button class="btn btn-primary btn-sm" onclick="window.location.href = '{{ url('Inventory/GinSales/'.$gin->id) }}';"><i class="zmdi zmdi-view-day"></i></button>
                                    </td>
                                    <td>{{ date(appLib::showDateFormat(), strtotime($gin->date)) }}</td>
                                    <td> {{ ($gin->month ?? '') }}-{{ appLib::padingZero($gin->number ?? '') }}</td>
                                    <td></td>
                                    <td>{{ $gin->name}}</td>
                                    <td></td>
                                    <td>{{  $gin->status }}</td>
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
