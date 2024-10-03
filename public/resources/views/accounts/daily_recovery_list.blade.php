@extends('layout.master')
@section('title', 'Daily Recovery List')
@section('parentPageTitle', 'Accounts')
@section('page-style')
<?php  use App\Libraries\appLib; ?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.23/b-1.6.5/b-colvis-1.6.5/datatables.min.css"/>
<link rel="stylesheet" href="{{asset('public/assets/css/sw.css')}}">
<link rel="stylesheet" href="{{asset('public/assets/css/list.css')}}">
<style>
.dataTables_filter, .dataTables_info { display: none; }
</style>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script>
var CustomerURL = "{{ url('customerSearch') }}";
var token =  "{{ csrf_token()}}"
</script>
@stop
@section('content')
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header">
            <button class="btn btn-primary" style="align:right" onclick="window.location.href = '{{ url('Accounts/DailyRecorvery/Create') }}';" >Add Daily Recovery</button>
            </div>
            <div class="body">
                    <div class="row mt-1">
                        <div class="col-md-3">
                            <label>Customer</label>
                            <input type="text"  id="customer" onkeyup="autoFill(this.id,CustomerURL,token)" class="form-control">
                            <input type="hidden" id="customer_ID" >
                        </div>
                        <div class="col-md-3">
                            <label>Start Date</label>
                            <input type="date" id="from_date" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label>End Date</label>
                            <input type="date" id="to_date" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label>Status</label>
                            <select name="status" id="status" class="form-control show-tick ms select2" data-placeholder="Select" required>
                                <option  value="">Select Status</option>
                                <option  value="Cleared">Cleared</option>
                                <option  value="Pending">Pending</option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-success m-0 px-1 mt-4" id="report">Report</button>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped table-hover js-exportable" id="table">
                        <thead>
                            <tr>
                                <th class="px-1 py-0">Actions</th>
                                <th>Recovery No.</th>
                                <th>Date</th>
                                <th>Received In</th>
                                <th>Customer</th>
                                <th>Ref No.</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($daily_recovery_list as $daily_recovery)
                            <tr>
                                <td class="action">
                                        <button class="btn btn-success btn-sm p-0 m-0" onclick="window.location.href = '{{ url('Accounts/DailyRecorvery/Create/'.$daily_recovery->id)}}';" ><i class="zmdi zmdi-edit px-2 py-1"></i></button>
                                </td>
                                <td class="column_size">{{$daily_recovery->month ?? ''}}-{{appLib::padingZero($daily_recovery->number  ?? '')}}</td>
                                <td class="column_size">{{date(appLib::showDateFormat(),strtotime($daily_recovery->date))}}</td>
                                <td class="column_size">{{$daily_recovery->received_in}}</td>
                                <td class="column_size">{{$daily_recovery->received_from}}</td>
                                <td class="column_size">{{$daily_recovery->ref_no}}</td>
                                <td class="column_size text-right">{{$daily_recovery->amount}}</td>
                                <td class="column_size">{{$daily_recovery->status}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="row" id="total_amount_row">
                        <div class="col-md-2">
                           <label class="font-weight-bold"> Total Amount</label>
                        </div>
                        <div class="col-md-2">
                            <label id="total_amount">0.00</label>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('page-script')
@include('datatable-list');
<script src="{{asset('public/assets/plugins/dropify/js/dropify.min.js')}}"></script>
<script src="{{asset('public/assets/js/pages/forms/dropify.js')}}"></script>
<script src="{{asset('public/assets/js/sw.js')}}"></script>
<script>
$('#total_amount_row').hide();
t=$('#table').DataTable( {
    scrollY: '50vh',
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
      { className: "dt-right column_size", "targets": [6] },
      { className: "column_size", "targets": [0,1,2,3,4,5,6,7] },
    ],

});
</script>
<script>
    $('#report').click(function(){
        $('#total_amount_row').show();
        $('.rowData').html('');
        $('.even').html('');
        $('.odd').html('');
        var url = "{{ url('DailyRecovery/Report')}}";
        var token =  "{{ csrf_token()}}";
        from_date=$('#from_date').val();
        to_date=$('#to_date').val();
        cust_coa_id=$('#customer_ID').val();
        var status=$('#status').find(":selected").val();
        total_amount=0;
        $.post(url,{status:status,from_date:from_date,to_date:to_date,cust_coa_id:cust_coa_id, _token:token},function(data){
            data.map(function(val,i){
                var myUrl= "{{ url('Accounts/DailyRecorvery/Create')}}";
                finalUrl=myUrl.concat('/',val.id);
                btn=(val.post_status !=='posted') ? '<button class="btn btn-success btn-sm p-0 m-0" onclick=\"(goTo(\'' + finalUrl + '\'))"><i class="zmdi zmdi-edit px-2 py-1"></i></button>' : '';
                t.row.add([
                    '<td class="action">'+btn+'</td>',
                    val.recovery_num,
                    val.timefor,
                    val.received_in,
                    val.received_from,
                    val.ref_no,
                    showBalance(val.amount),
                    val.status,
                    val.date,
                ]).draw( false );
                total_amount=+getNum(total_amount) + +getNum(val.amount);
            });
            $('#total_amount').html(showBalance(total_amount));
        });
    });
function goTo(url)
{
    document.location.href=url;
}
</script>
@stop
