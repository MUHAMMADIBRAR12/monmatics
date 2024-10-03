@extends('layout.master')
@section('title','Product Detail')
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
<script>
    var token =  "{{ csrf_token()}}";
    var ItemURL = "{{ url('itemSearch') }}";
</script>
@stop
@section('content')
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header">
                <h2><strong>Product</strong> Detail </h2>
            </div>
            <div class="body">
                <div class="row clearfix">
                    <div class="col-md-3 col-sm-12">
                        <div class="form-group"> 
                            <label>Product</label>
                            <input type="text"  id="item"  onkeyup="autoFill(this.id, ItemURL, token)"  class="form-control" required>
                            <input type="hidden"  id="item_ID">
                        </div> 
                    </div>
                     <div class="col-md-3 col-sm-12">
                        <div class="form-group"> 
                            <label>From</label>
                            <div class="input-group">
                            <input type="date"  id="from_date" class="form-control">
                            </div>
                        </div> 
                       
                    </div>
                     <div class="col-md-3 col-sm-12">
                        <div class="form-group"> 
                            <label>To</label>
                            <div class="input-group">
                            <input type="date"  id="to_date" class="form-control">
                            
                            </div>
                        </div>
                    </div>
                  
                     <div class="col-md-3 col-sm-12">
                    <button id="generate" type="button" class="my-4 btn btn-raised btn-primary btn-round waves-effect">Generate </button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="ledger" class="table w-100  table-bordered table-striped table-hover js-exportable  ">
                        <thead>
                            <tr>
                                <th>Sr</th>
                                <th>Date</th>
                                <th style="text-align:center">Source</th>
                                <th style="text-align:center">Description</th>
                                <th style="text-align:center">Qty In</th>
                                <th style="text-align:center">Qty Out</th>
                                <th style="text-align:center">Balance</th>    
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
var url = "{{ url('Reports/productDetail') }}";
$('#generate').click(function(){
    var mydata = [];
    var sr=0;
    var item_id=$('#item_ID').val();
    var from_date=$('#from_date').val();
    var to_date=$('#to_date').val();
    $.post(url,{item_id:item_id,from_date:from_date,to_date:to_date, _token:token},function(data){
       data.map(function(val,i){
        var da={
        "Sr":sr++,
        "Date": val.date,
        "Source": val.source,
        "Description":val.description,
        "Qty In":val.qty_in,
        "Qty Out":val.qty_out,
        "Balance":val.balance,
        };
    mydata.push(da);
    });
    console.log(mydata);
    $('#ledger').DataTable( {
        data:mydata,
        columns: [
        { data: 'Sr'},
        { data: 'Date' },
        { data: 'Source' },
        { data: 'Description' },
        { data: 'Qty In' },
        { data: 'Qty Out' },  
        { data: 'Balance' },  
    ],
    dom: 'Bfrtip',
    
    buttons: [
        { extend: 'pageLength', className:'btn cl mr-2 px-3 rounded'},
        { extend: 'copy', className: 'btn btn-secondary mr-2 px-3 rounded', title:'General Ledger'},
        { extend: 'csv', className: 'btn btn-info mr-2 px-3 rounded', title:'General Ledger'},
        { extend: 'pdf', className: 'btn btn-danger mr-2 px-3 rounded', title:'General Ledger',},
        { extend: 'excel', className: 'btn btn-warning mr-2 px-3 rounded', title:'General Ledger'},
        { extend: 'print', className: 'btn btn-success mr-2 px-3 rounded',title:'General Ledger'},
        { extend: 'colvis'},

        ],
        "lengthMenu": [[100, 200, 500, -1], [100, 200, 500, "All"]],
    "columnDefs": [
      { className: "dt-center", "targets": [0,1] },
      { className: "dt-right", "targets": [4,5,6] },

    ],
        "bDestroy": true,
        
        
}); 
});
}); 

</script>
@stop