@extends('layout.master')
@section('title', 'Bank Reconciliation')
@section('parentPageTitle', 'Reports')
@section('page-style')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.23/b-1.6.5/b-colvis-1.6.5/datatables.min.css"/>
<link rel="stylesheet" href="{{asset('public/assets/css/list.css')}}">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
@stop
@section('content')
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header">
                <h2><strong>Bank</strong> Book </h2>
            </div>
            <div class="body">
                <div class="row clearfix">
                    <div class="col-md-3 col-sm-12">
                        <div class="form-group"> 
                            <label>Account</label>
                            <select name="account_id" id="account_ID" class="form-control show-tick ms select2" data-placeholder="Select" required>
                                   <option  value="">Select Accounts</option>
                                   @foreach($bankbooks as $bankbook)
                                   <option value="{{$bankbook->id}}">{{$bankbook->name}}</option>
                                   @endforeach
                            </select>
                        </div> 
                    </div>
                    <div class="col-md-2 col-sm-12 pl-1">
                        <div class="form-group"> 
                            <label>Type</label>
                            <select name="type" id="type" class="form-control show-tick ms select2" data-placeholder="Select" >
                                   <option  value="0">All</option>
                                   <option  value="4">Payment</option>
                                   <option  value="3">Receipt</option>
                            </select>
                        </div> 
                    </div>
                     <div class="col-md-3 col-sm-12 pl-1">
                        <div class="form-group"> 
                            <label>From</label>
                            <div class="input-group">
                            <input type="date"  id="from_date" class="form-control">
                            </div>
                        </div> 
                       
                    </div>
                     <div class="col-md-3 col-sm-12 pl-1">
                        <div class="form-group"> 
                            <label>To</label>
                            <div class="input-group">
                            <input type="date"  id="to_date" class="form-control">
                            
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1 col-sm-12 pl-1">
                        <label for=""></label>
                        <button id="generate" type="button" class="btn  btn-primary waves-effect font-weight-bold px-2">Generate </button>
    
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="bankReconciliation" class="table table-bordered table-striped table-hover js-exportable dataTable" style="width:100%">
                        <thead>
                            <tr>
                                <th class="p-1"><input type="checkbox" id="check-all"></th>
                                <th class="p-1">Sr</th>
                                <th class="p-1">Date</th>
                                <th class="p-1">Description</th>
                                <th class="p-1">Debit</th>
                                <th class="p-1 ">Credit</th>
                                <th class="p-1">Balance</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                           
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <button class="btn btn-success" id="save">Save</button>
                    </div>
                    <div class="col-md-8">
                        <h5 id="br_balance" class="text-primary"></h5>
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
var token =  "{{ csrf_token()}}";  
var url = "{{ url('bankBookReconciliation')}}";
t=$('#bankReconciliation').DataTable({
    scrollY: '50vh',
    "scrollX": true,
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
    "lengthMenu": [[100, 200, 500, -1], [100, 200, 500, "All"]],
    "columnDefs": [
      { className: "dt-center column_size", "targets": [0,1,2,3,4,5,6] },
      { className: "column_size", "targets": [0,1,2,3,4,5,6] },

    ],
    "bDestroy": true,
}); 

$('#generate').click(function(){
    t.rows().remove().draw();
    $('.even').remove();
    $('.odd').remove();
    var sr=1;
    var i=1;
    var account_id=$('#account_ID').val();
    var from_date=$('#from_date').val();
    var to_date=$('#to_date').val();
    var type=$('#type').find(":selected").val();
    $.post(url,{account_id:account_id,from_date:from_date,to_date:to_date,type:type, _token:token},function(data){
        //console.log(data);
        var remainBalance=0;
        var bR_Balance=0;
        data.map(function(val,i){
            if(isNaN(val.debit))
            {
                t.row.add( [
                    '<td></td>',
                    '<td>'+sr+'</td>',
                    '<td>--</td>',
                    '<td>Opening Balance</td>',
                    '<td>'+currencyPatrn(0)+'</td>',
                    '<td>'+currencyPatrn(0)+'</td>',
                    '<td>'+currencyPatrn(val.balance)+'</td>',
                    ]).draw( false);
                    sr++;
            }
            else
            { 
                var balance=val.credit-val.debit;
                remainBalance=+remainBalance + +balance;
                //this check is used for if balance has neqative value then show in to paranthesis like (Rs 10000)
                if(remainBalance < 0)
                    
                    showBalance='('+currencyPatrn(remainBalance)+')';
                else
                    showBalance=currencyPatrn(remainBalance);

                //this check is for bank reconcile value=1 then it checked the check box and also calculate bank reconcile balance
                if(val.bank_reconcile===1)
                {
                    check='checked';
                    var balance=val.credit-val.debit;
                    bR_Balance=+bR_Balance + +balance;
                }
                else
                {
                    check='';
                }
                t.row.add( [
                    '<td><input type="checkbox" name="chk" class="myCheck" id="chk'+i+'" value="'+val.transdetail_id+'"  '+check+'></td>',
                    '<td>'+sr+'</td>',
                    '<td>'+val.date+'</td>',
                    '<td>'+val.description+'</td>',
                    '<td>'+currencyPatrn(val.credit)+'</td>',
                    '<td>'+currencyPatrn(val.debit)+'</td>',
                    '<td>'+showBalance +'</td>',
                ]).draw( false);
                sr++;
                i++;
            }

        });
        $('#br_balance').html('Bank Reconcile Balance is:  '+currencyPatrn(bR_Balance));
    });
}); 

</script>
<script>
    $('#save').on('click',function(){
        $('input[name="chk"]').each(function(i) {
            ++i;
           // console.log(this.value); 
            if($('#chk'+i).prop('checked')== true)
               bank_reconcile='yes';
            else
                bank_reconcile='no';
            var url = "{{ url('updateReconciliation')}}";
            $.post(url,{id:this.value,bank_reconcile:bank_reconcile, _token:token},function(data){
                
            }); 
        });
        $("#generate")[0].click();
    });

    $('#check-all').click(function(){
        if( $("#check-all").prop('checked')== true)
        {
            $(".myCheck").prop("checked", true);
        }
        else
        {
            $(".myCheck").prop("checked",false); 
        }
            
    });
    
</script>
@stop