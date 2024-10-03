@extends('layout.master')
@section('title', 'Quotation')
@section('parentPageTitle', 'Sales')
<?php  use App\Libraries\appLib; ?>
@section('page-style')
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.23/b-1.6.5/b-colvis-1.6.5/datatables.min.css"/>
<link rel="stylesheet" href="{{asset('public/assets/css/sw.css')}}">
<link rel="stylesheet" href="{{asset('public/assets/css/list.css')}}">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
@stop
@section('content')
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="body">
                <div class="table-responsive">
                    <button class="btn btn-primary" style="align:right" onclick="window.location.href = '{{ url('Sales/Quotation/Create') }}';" >New Quotation</button>
                    <table class="table table-bordered table-striped table-hover" id="quotations">
                        <thead>
                            <tr>
                                <th class="px-1 py-0">Action</th>
                                <th>Number</th>
                                <th>Customer Name</th>
                                <th>Date</th>
                                <th>Status</th>

                            </tr>
                        </thead>
                            <tbody>
                            @foreach($Quotations as $quotation)
                                <tr >
                                    <td class="action">
                                        <button class="btn btn-success btn-sm p-0 m-0" onclick="window.location.href = '{{ url('Sales/Quotation/Create/'.$quotation->id) }}';"><i class="zmdi zmdi-edit px-2 py-1"></i></button>
                                    </td>
                                    <td class="column_size">{{$quotation->month ?? ''}}-{{appLib::padingZero($quotation->number  ?? '')}}</td>
                                    <td class="column_size">{{ $quotation->name }}</td>
                                    <td class="column_size">{{ date(appLib::showDateFormat(), strtotime($quotation->date))}}</td>
                                    <td class="column_size">{{  $quotation->status }}</td>

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
@include('datatable-list');
<script>
$('#quotations').DataTable( {
    dom: 'Bfrtip',
    buttons: [
        { extend: 'pageLength', className:'btn cl mr-2 px-3 rounded'},
        { extend: 'copy', className: 'btn bg-dark mr-2 px-3 rounded', title:'Products'},
        { extend: 'csv', className: 'btn btn-info mr-2 px-3 rounded', title:'Products'},
        { extend: 'pdf', className: 'btn btn-danger mr-2 px-3 rounded', title:'Products'},
        { extend: 'excel', className: 'btn btn-warning mr-2 px-3 rounded',title:'Products'},
        { extend: 'print', className: 'btn btn-success mr-2 px-3 rounded',title:'Products'},
        { extend: 'colvis', className:'visible btn rounded'},
        ],
    "bDestroy": true,
    "lengthMenu": [[100, 200, 500, -1], [100, 200, 500, "All"]],

});
</script>
@stop
