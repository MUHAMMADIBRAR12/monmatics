@extends('layout.master')
@section('title','Sale Inovie FMCG List')
@section('parentPageTitle','Sale FMCG')
@section('page-style')
<?php  use App\Libraries\appLib; ?>
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.23/b-1.6.5/b-colvis-1.6.5/datatables.min.css"/>
<link rel="stylesheet" href="{{asset('public/assets/css/sw.css')}}">
<link rel="stylesheet" href="{{asset('public/assets/css/list.css')}}">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
@stop
@section('content')
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header">
              <button class="btn btn-primary" style="align:right" onclick="window.location.href = '{{ url('Sales_Fmcg/SaleInvoice/Create') }}';" >New Sale Invoice</button>
            </div>
            <div class="body">
                    <table class="table table-bordered table-striped table-hover " id="igp">
                        <thead>
                            <tr>
                                <th class="px-1 py-0">Actions</th>
                                <th>Invoice #</th>
                                <th>S.O No.</th>
                                <th>Customer Name</th>
                                <th>Date</th>
                                <th>Do #</th>
                                <th>warehouse</th>
                                <th>Total Qty</th>
                                <th>Gross Amount</th>
                                <th>Trade Offer</th>
                                <th>Discount</th>
                                <th>Net Amount</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($sale_invoices_fmcg as $invoice)
                            <tr>
                                <td class="action column_size">
                                    
                                    @if($invoice['editable']==1)
                                        <button class="btn btn-success btn-sm p-0 m-0" onclick="window.location.href = '{{ url('Sales_Fmcg/SaleInvoice/Create/'.$invoice['id']) }}';" ><i class="zmdi zmdi-edit px-2 py-1"></i></button>
                                    @endif
                                    <button class="btn btn-primary btn-sm p-0 m-0" onclick="window.location.href = '{{ url('Sales_Fmcg/SaleInvoice/View/'.$invoice['id']) }}';" ><i class="zmdi zmdi-eye px-2 py-1"></i></button>
                                    <button class="btn btn-danger btn-sm p-0 m-0"  onclick="window.open('{{ url('Sales_Fmcg/Print/'.$invoice['id']) }}');"><i class="zmdi zmdi-receipt px-2 py-1"></i></button>
                                </td>
                                <td class="column_size">{{$invoice['inv_num']}}</td>
                                <td class="column_size">{{$invoice['so_num']}}</td>
                                <td class="column_size">{{$invoice['cust_name']}}</td>
                                <td class="column_size">{{$invoice['date']}}</td>
                                <td class="column_size">{{$invoice['do_num']}}</td>
                                <td class="column_size">{{$invoice['warehouse_name']}}</td>
                                <td class="column_size">{{$invoice['total_qty']}}</td>
                                <td class="column_size">{{$invoice['total_gross_amount']}}</td>
                                <td class="column_size">{{$invoice['total_trade_offer']}}</td>
                                <td class="column_size">{{$invoice['total_discount']}}</td>
                                <td class="column_size">{{$invoice['total_net_amount']}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
            </div>
        </div>
    </div>
</div>
@stop
@section('page-script')
@include('datatable-list');
<script>
$('#igp').DataTable( {
    scrollY: '50vh',
    scrollX: true,
    scrollCollapse: true,
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
    "columnDefs": [
      { className: "dt-right column_size", "targets": [7,8,9,10,11]},
      { className: "column_size", "targets": [1,2,3,4,5,6] },
    ],
        
}); 
</script>
@stop