@extends('layout.master')
@section('title', 'Sales Order List')
@section('parentPageTitle', 'Sales FMCG')
@section('page-style')
<?php  use App\Libraries\appLib; ?>


<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.23/b-1.6.5/b-colvis-1.6.5/datatables.min.css"/>
<link rel="stylesheet" href="{{asset('public/assets/css/list.css')}}">
<link rel="stylesheet" href="{{asset('public/assets/css/sw.css')}}">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
@stop
@section('content')
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header">
            <button class="btn btn-primary" style="align:right" onclick="window.location.href = '{{ url('Sales_Fmcg/SaleOrder/Create') }}';" >New Sale Order</button>
            </div>
            <div class="body">
                <div>
                    <table class="table table-bordered table-striped table-hover js-exportable" id="grn">
                        <thead>
                            <tr>
                                <th>Actions</th>
                                <th>Sale Order No.</th>
                                <th>Date</th>
                                <th>Customer</th>
                                <th>Total Qty</th>
                                <th>Gross Amount</th>
                                <th>Trade Offer</th>
                                <th>Discount</th>
                                <th>Net Amount</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sale_orders as $sale_order)
                            <tr>
                                <td class="action">
                                    @if($sale_order['editable']==1)
                                    <button class="btn btn-success btn-sm p-0 m-0" onclick="window.location.href = '{{ url('Sales_Fmcg/SaleOrder/Create/'.$sale_order['id']) }}';" > <i class="zmdi zmdi-edit px-2 py-1"></i></button>                                        
                                    @endif
                                    <button class="btn btn-primary btn-sm p-0 m-0" onclick="window.location.href = '{{ url('Sales_Fmcg/SaleOrder/View/'.$sale_order['id']) }}';" > <i class="zmdi zmdi-eye px-2 py-1"></i></button>                                        
                                </td>
                                <td class="column_size">{{$sale_order['month'] ?? '' }}-{{appLib::padingZero($sale_order['number']  ?? '')}}</td>
                                <td class="column_size">{{$sale_order['date']}}</td>
                                <td class="column_size">{{$sale_order['cust_name']}}</td>
                                <td class="column_size">{{$sale_order['total_qty']}}</td>
                                <td class="column_size">{{$sale_order['total_gross_amount']}}</td>
                                <td class="column_size">{{$sale_order['total_trade_offer']}}</td>
                                <td class="column_size">{{$sale_order['total_discount']}}</td>
                                <td class="column_size">{{$sale_order['total_net_amount']}}</td>
                                <td class="column_size">{{$sale_order['remarks']}}</td>
                                
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
<script src="{{asset('public/assets/bundles/datatablescripts.bundle.js')}}"></script>
<script src="{{asset('public/assets/plugins/jquery-datatable/buttons/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('public/assets/plugins/jquery-datatable/buttons/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('public/assets/plugins/jquery-datatable/buttons/buttons.html5.min.js')}}"></script>
<script src="{{asset('public/assets/plugins/jquery-datatable/buttons/buttons.print.min.js')}}"></script>
<script src="{{asset('public/assets/plugins/jquery-datatable/buttons/buttons.colVis.min.js')}}"></script>
<script src="{{asset('public/assets/plugins/jquery-datatable/buttons/buttons.pdfMake.min.js')}}"></script>
<script src="{{asset('public/assets/plugins/jquery-datatable/buttons/buttons.pdfMakeVfs.min.js')}}"></script>
<script src="{{asset('public/assets/plugins/jquery-datatable/buttons/buttons.excel.min.js')}}"></script>
<script>
$('#grn').DataTable( {
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
      { className: "dt-right column_size", "targets": [4,5,6,7,8]},
      { className: "column_size", "targets": [1,2,3,9] },
    ],
   
        
}); 
</script>
@stop