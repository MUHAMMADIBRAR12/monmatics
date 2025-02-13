@extends('layout.master')
@section('title')
  {{ $title }}
@stop
@section('parentPageTitle', 'Reports')
@section('page-style')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.23/b-1.6.5/b-colvis-1.6.5/datatables.min.css"/>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.11.0/af-2.3.7/b-2.0.0/b-colvis-2.0.0/b-html5-2.0.0/cr-1.5.4/date-1.1.1/fc-3.3.3/fh-3.1.9/kt-2.6.4/r-2.2.9/rg-1.1.3/rr-1.2.8/sc-2.0.5/sb-1.2.0/sp-1.4.0/sl-1.3.3/datatables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.11.0/af-2.3.7/b-2.0.0/b-colvis-2.0.0/b-html5-2.0.0/cr-1.5.4/date-1.1.1/fc-3.3.3/fh-3.1.9/kt-2.6.4/r-2.2.9/rg-1.1.3/rr-1.2.8/sc-2.0.5/sb-1.2.0/sp-1.4.0/sl-1.3.3/datatables.min.js"></script>

<link rel="stylesheet" href="{{asset('public/assets/css/list.css')}}">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<style>
#ledger tr td {
    padding-top:1px;
    padding-bottom:1px;
}
.print:visited{color: white;}
</style>
@stop
@section('content')
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header">
                <h2><strong>Project</strong> Ledger </h2>
            </div>
            <div class="body">
                <div class="row clearfix">
                    <div class="col-md-3 col-sm-12">
                        <div class="form-group"> 
                           <label>Project</label>
                           <input type="text" name="project" id="project" onkeyup="autoFill(this.id,projectURL,token)"  class="form-control autocomplete">
                            <input type="hidden" name="project_ID" id="project_ID" value="">
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
                <table id="table" class="table w-100  table-bordered table-striped table-hover js-exportable  ">
                    <thead>
                        <tr>
                            <th>Sr</th>
                            <th>Print</th>
                            <th>Document#</th>
                            <th>Date</th>
                            <th style="text-align:center">Description</th>
                            <th style="text-align:center">Debit</th>
                            <th style="text-align:center">Credit</th>
                            <th style="text-align:center">Balance</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
                <div class="row">
                    <div class="col-md-2">
                        <label>Total Debit</label>
                    </div>
                    <div class="col-md-2">
                        <label id="total_debit">0.00</label>
                    </div>
                    <div class="col-md-2">
                        <label>Total Credit</label>
                    </div>
                    <div class="col-md-2">
                        <label id="total_credit">0.00</label>
                    </div>
                    <div class="col-md-2">
                        <label>Total Balance</label>
                    </div>
                    <div class="col-md-2">
                        <label id="total_balance">0.00</label>
                    </div>
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
     

function myDataTable()
{
    fname = 'Project Ledger of '+ $('#project').val();

    dt=$('#table').DataTable({
        scrollY: '50vh',
        scrollX:true,
        scrollCollapse: true,
        dom: 'Bfrtip',
        buttons: [
            { extend: 'pageLength', className:'btn cl mr-2 px-3 rounded'},
            { extend: 'copy', className: 'btn bg-dark mr-2 px-3 rounded',  filename: fname , title:fname, exportOptions: { columns: ':visible'}},
            { extend: 'csv', className: 'btn btn-info mr-2 px-3 rounded', filename: fname , title:fname, exportOptions: { columns: ':visible'}},
            { extend: 'pdf', className: 'btn btn-danger mr-2 px-3 rounded', filename: fname , title:fname, exportOptions: { columns: ':visible'}},
            { extend: 'excel', className: 'btn btn-warning mr-2 px-3 rounded', filename: fname , title:fname, exportOptions: { columns: ':visible'}},
            { extend: 'print', className: 'btn btn-success mr-2 px-3 rounded', filename: fname , title:fname, exportOptions: { columns: ':visible'}},
            { extend: 'colvis', className:'visible btn rounded'},
            ],
        "lengthMenu": [[100, 200, 500, -1], [100, 200, 500, "All"]],
        "columnDefs": [
          { className: "dt-center column_size", "targets": [0,1,2,3]},
          { className: "dt-right column_size", "targets": [5,6,7]},
          { className: "column_size", "targets": [1,2,3,4] },
        ],
        "bDestroy": true,
    }); 
    
    return dt;
    
}
</script>
<script>
var projectURL = "{{ url('projectSearch') }}";
var token =  "{{ csrf_token()}}";  
var url = "{{ url('projectLedger') }}";
$('#generate').click(function(){
    
    
    
    t = myDataTable();
    t.rows().remove().draw();
    $('.even').remove();
    $('.odd').remove();
    var mydata = [];
    var project_id=$('#project_ID').val();
    var from_date=$('#from_date').val();
    var to_date=$('#to_date').val();
    var total_debit=0;
    var total_credit=0;
    var total_balance=0;
    $.post(url,{project_id:project_id,from_date:from_date,to_date:to_date, _token:token},function(data){
        console.log(data);
        var remainBalance=0;
        var sr=1;
        data.map(function(val,i){
            var balance=val.debit-val.credit;
            remainBalance=+remainBalance + +balance;
            t.row.add( [
                '<td>'+sr+'</td>',
                '<td><button class="btn btn-danger btn-sm p-0 m-0"><a target="_blank" href="'+val.print_route+'" class="btn btn-danger print btn-sm p-0 m-0"><i class="zmdi zmdi-receipt px-2 py-1"></i></a></button></td>',
                '<td><a href="'+val.route+'" class="text-danger">'+val.document+'</a></td>',
                '<td>'+val.date+'</td>',
                '<td>'+val.description+'</td>',
                '<td>'+currencyPatrn(val.debit)+'</td>',
                '<td>'+currencyPatrn(val.credit)+'</td>',
                '<td>'+showBalance(remainBalance)+'</td>',
                '</tr>',
                ]).draw( false);
                sr++;
            total_debit=+getNum(total_debit) + +getNum(val.debit);
            total_credit=+getNum(total_credit) + +getNum(val.credit);
            total_balance=+getNum(total_balance) + +getNum(remainBalance);
        });
        $('#total_debit').html(showBalance(total_debit));
        $('#total_credit').html(showBalance(total_credit));
        $('#total_balance').html(showBalance(total_balance));
    });
});
</script>
@stop