@extends('layout.master')
@section('title','Delivery Order')
@section('parentPageTitle','Inventory')
@section('page-style')
<?php  use App\Libraries\appLib; ?>
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.23/b-1.6.5/b-colvis-1.6.5/datatables.min.css"/>
<link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.0.2/css/dataTables.dateTime.min.css">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="{{asset('public/assets/css/sw.css')}}">
<link rel="stylesheet" href="{{asset('public/assets/css/list.css')}}">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script>
    var CustomerURL = "{{ url('customerSearch') }}";
    var token =  "{{ csrf_token()}}";
</script>
@stop
@section('content')
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="body">
                <div class="table-responsive p-0">
                    <button class="btn btn-primary m-0"  onclick="window.location.href = '{{ url('Inventory/Do/Create') }}';" >New Delivery Order</button>
                    <div class="row mt-1">
                        <div class="col-md-4">
                            <table>
                                <tr>
                                    <td>Customer:</td>
                                    <td>
                                        <input type="text"  id="customer" onkeyup="autoFill(this.id,CustomerURL,token)"  class="form-control" style="height:30px;width:200px">
                                        <input type="hidden" id="customer_ID">
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-3">
                            <select  class="form-control show-tick ms select2" id="delivery_status" style="height:30px">
                                <option value="">Delivery Status</option>
                                <option value="Delivered">Delivered</option>
                                <option value="Un Delivered">Un Delivered</option>
                            </select>
                        </div>
                        <div class="col-md-2">

                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-success m-0" id="report">Generate Report</button>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped table-hover" id="invoices" style="width:100%">
                        <thead>
                            <tr>
                                <th class="px-1 py-0">Sr.</th>
                                <th class="px-1 py-0">Action</th>
                                <th>Number</th>
                                <th>Date</th>
                                <th style="text-align:center">Customer Name</th>
                                <th style="text-align:center">Veh No</th>
                                <th style="text-align:center">Delivery Name</th>
                            </tr>
                        </thead>
                        <tbody id="invoice_body">
                            @php $i=1 @endphp
                        @foreach($delivery_orders as $do)
                            <tr>
                                <td class="action">{{ $i++ }}</td>
                                <td class="action">
                                    @if(!$do->inv_id)
                                    <button class="btn btn-success btn-sm p-0 m-0" onclick="window.location.href = '{{ url('Inventory/Do/Create/'.$do->id)}}';" ><i class="zmdi zmdi-edit px-2 py-1"></i></button>
                                    @endif
                                </td>
                                <td class="column_size">{{$do->month ?? ''}}-{{appLib::padingZero($do->number  ?? '')}}</td>
                                <td class="column_size text-center">{{ date(appLib::showDateFormat(), strtotime($do->date))}}</td>
                                <td class="column_size">{{$do->cust_name}}</td>
                                <td class="column_size text-right">{{$do->veh_no}}</td>
                                <td class="column_size">{{$do->delivery_name}}</td>
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
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
<script src="https://cdn.datatables.net/datetime/1.0.2/js/dataTables.dateTime.min.js"></script>
<script src="{{asset('public/assets/js/sw.js')}}"></script>
@include('datatable-list');
<script>
$(document).ready(function(){

    $('#invoices').DataTable( {
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
      { className: "dt-center", "targets": [0,1,2] },
      //{ className: "dt-right", "targets": [2] },
    ],
});
});
</script>
<script>
    $('#report').click(function(){
        $('.rowData').html('');
        $('.even').html('');
        $('.odd').html('');
        var url = "{{ url('check/DeliveryStatus')}}";
        var token =  "{{ csrf_token()}}";
        delivery_status=$('#delivery_status option:selected').val();
        customer_id=$('#customer_ID').val();
        $.post(url,{customer_id:customer_id,delivery_status:delivery_status, _token:token},function(data){
            data.map(function(val,i){
                var myUrl= "{{ url('Inventory/Do/Create')}}";
                finalUrl=myUrl.concat('/',val.id);
                console.log(finalUrl);
               // goTo(finalUrl);
                var row='<tr class="rowData">'+
                    '<td class="action"> <button class="btn btn-success btn-sm p-0 m-0" onclick=\"(goTo(\'' + finalUrl + '\'))"><i class="zmdi zmdi-edit px-2 py-1"></i></button></td>'+
                    '<td class="column_size">'+val.do_num+'</td>'+
                    '<td class="column_size">'+val.cust_name+'</td>'+
                    '<td class="column_size">'+val.date+'</td>'+
                    '<td class="column_size">'+val.veh_no+'</td>'+
                    '<td class="column_size">'+val.delivery_name+'</td>'+
                    '</tr>';
                $('#invoices').append(row);
            });
        });
    });
function goTo(url)
{
    document.location.href=url;
}
</script>
@stop
