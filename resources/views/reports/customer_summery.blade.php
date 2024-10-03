@extends('layout.master')
@section('title', 'Customer Outstanding Summery')
@section('parentPageTitle', 'Reports')
@section('page-style')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="{{asset('public/assets/plugins/jquery-datatable/dataTables.bootstrap4.min.css')}}"/>
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.23/b-1.6.5/b-colvis-1.6.5/datatables.min.css"/>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<style>
#ledger tr td {
    padding-top:1px;
    padding-bottom:1px;
}

</style>
@stop
@section('content')
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header">
                <h2><strong>Customer Outstanding</strong> Summery</h2>
            </div>
            <div class="body">
                <div class="row clearfix">
                    <div class="col-md-3 col-sm-12">
                        <div class="form-group"> 
                            <label>As On</label>
                            <div class="input-group">
                            <input type="date"  id="on_date" class="form-control">
                            </div>
                        </div> 
                    </div>
                     <div class="col-md-3 col-sm-12">
                    <button id="generate" type="button" class="my-4 btn btn-raised btn-primary btn-round waves-effect">Generate </button>
                    </div>
                    <div class="col-md-3">
                        <label>Total Balance</label><br>
                        <label id="total_balance"></label>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="ledger" class="table w-100  table-bordered table-striped table-hover js-exportable  ">
                        <thead>
                            <tr>
                                <th>Sr</th>
                                <th>Customer</th>
                                <th>Balance</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                           
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
<script src="{{asset('public/assets/plugins/jquery-datatable/buttons/buttons.colVis.min.js')}}"></script>
<script src="{{asset('public/assets/plugins/jquery-datatable/buttons/buttons.flash.min.js')}}"></script>
<script src="{{asset('public/assets/plugins/jquery-datatable/buttons/buttons.html5.min.js')}}"></script>
<script src="{{asset('public/assets/plugins/jquery-datatable/buttons/buttons.print.min.js')}}"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script src="{{asset('public/assets/plugins/jquery-datatable/buttons/buttons.excel.min.js')}}"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.colVis.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="{{asset('public/assets/js/sw.js')}}"></script>
<script>
var token =  "{{ csrf_token()}}";  
var url = "{{ url('customerOutstandingSummery')}}";
$('#generate').click(function(){
    var mydata = [];
    var sr=1;
    var date=$('#on_date').val();
    total_balance=0;
    $.post(url,{date:date, _token:token},function(data){
       console.log(data);
       data.map(function(val,i){
           var da='';
           if(val.balance>0)
           {
        da={
        "Sr":sr++,
        "Customer": val.name,
        "Balance":showBalance(val.balance),
        };
        total_balance =+getNum(total_balance) + +getNum(val.balance);
    mydata.push(da);
        }
    
    });
    $('#total_balance').html(showBalance(total_balance));
    $('#ledger').DataTable( {
        data:mydata,
        columns: [
        { data: 'Sr'},
        { data: 'Customer' },
        { data: 'Balance'}, 
    ],
    dom: 'Bfrtip',
    
    buttons: [
        { extend: 'pageLength', className:'btn cl mr-2 px-3 rounded'},
        { extend: 'copy',exportOptions:{columns: ':visible',}, className: 'btn btn-secondary mr-2 px-3 rounded', title:'Customer Outstanding Summery'},
        { extend: 'csv',exportOptions:{columns: ':visible',}, className: 'btn btn-info mr-2 px-3 rounded', title:'Customer Outstanding Summery'},
        { extend: 'pdf',exportOptions:{columns: ':visible',}, className: 'btn btn-danger mr-2 px-3 rounded', title:'Customer Outstanding Summery'},
        { extend: 'excel',exportOptions:{columns: ':visible',}, className: 'btn btn-warning mr-2 px-3 rounded', title:'Customer Outstanding Summery'},
        { extend: 'print',exportOptions:{columns: ':visible',}, className: 'btn btn-success mr-2 px-3 rounded',title:'Customer Outstanding Summery'},
        { extend: 'colvis'},
        ],
    "lengthMenu": [[100, 200, 500, -1], [100, 200, 500, "All"]],
    "columnDefs": [
      { className: "dt-right", "targets": [2] },],
    "bDestroy": true,
        
        
}); 
});
}); 

</script>
@stop