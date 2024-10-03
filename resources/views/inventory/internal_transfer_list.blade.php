@extends('layout.master')
@section('title', 'Internal Transfers')
@section('parentPageTitle', 'internal')
@section('page-style')
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
<link rel="stylesheet" href="{{asset('public/assets/plugins/footable-bootstrap/css/footable.bootstrap.min.css')}}" />
<link rel="stylesheet" href="{{asset('public/assets/plugins/footable-bootstrap/css/footable.standalone.min.css')}}" />
<?php  use App\Libraries\appLib; ?>
@stop
@section('content')


<div class="row clearfix">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="header">
                <button class="btn btn-primary" style="align:right" onclick="window.location.href = '{{ url('Inventory/InternalTransfer') }}';" >ADD</button>
              </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0 c_list c_table" id="internal_transfers">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>From (warehouse)</th>
                            <th>To (Warehouse)</th>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Qty</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @php
                            use app\Libraries\appLib;
                        @endphp --}}
                        @foreach($internalTransfer as $transfer)
                        <tr>
                            <td> <button class="btn btn-primary btn-sm" onclick="window.location.href = '{{ url('Inventory/InternalTransfer/'.$transfer->id) }}';"><i class="zmdi zmdi-edit"></i></button></td>
                            <td>{{$transfer->warehouse_from}}</td>
                            <td>{{$transfer->warehouse_to}}</td>
                            <td>{{ date(appLib::showDateFormat(), strtotime($transfer->date))}}</td>
                            <td>{{$transfer->description}}</td>
                            <td>{{$transfer->status}}</td>
                            <td>{{$transfer->qty}}</td>
                            <td>{{$transfer->amount}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{-- {{$links()->internalTransfers}} --}}
            </div>
        </div>
    </div>
</div>
@stop
@section('page-script')
@include('datatable-list');

<script src="{{asset('public/assets/js/pages/tables/footable.js')}}"></script>
<script>
    $('#internal_transfers').DataTable( {
        scrollY: '50vh',
        "scrollX": true,
        scrollCollapse: true,
        dom: 'Bfrtip',
        buttons: [
            { extend: 'pageLength', className:'btn cl mr-2 px-3 rounded'},
            { extend: 'copy', className: 'btn bg-dark mr-2 px-3 rounded', title:'Internal Transfers List'},
            { extend: 'csv', className: 'btn btn-info mr-2 px-3 rounded', title:'Internal Transfers List'},
            { extend: 'pdf', className: 'btn btn-danger mr-2 px-3 rounded', title:'Internal Transfers List'},
            { extend: 'excel', className: 'btn btn-warning mr-2 px-3 rounded',title:'Internal Transfers List'},
            { extend: 'print', className: 'btn btn-success mr-2 px-3 rounded',title:'Internal Transfers List'},
            { extend: 'colvis', className:'visible btn rounded'},

        ],
        "bDestroy": true,
        "lengthMenu": [[100, 200, 500, -1], [100, 200, 500, "All"]],
        "columnDefs": [
          { className: "dt-center", "targets": [0,1,2,3] },
        ],

    });
    </script>
@stop
