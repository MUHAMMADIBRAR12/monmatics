@extends('layout.master')
@section('title',($type==6)?'Adjustment':'Received From Customer')
@section('parentPageTitle', 'Accounts')
@section('parent_title_icon', 'zmdi zmdi-home')
@section('page-style')
<?php  use App\Libraries\appLib; ?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="{{asset('public/assets/plugins/select2/select2.css')}}" />
<link rel="stylesheet" href="{{asset('public/assets/plugins/morrisjs/morris.css')}}" />
<link rel="stylesheet" href="{{asset('public/assets/plugins/jquery-spinner/css/bootstrap-spinner.css')}}" />
<link rel="stylesheet" href="{{asset('public/assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css')}}" />
<link rel="stylesheet" href="{{asset('public/assets/plugins/bootstrap-select/css/bootstrap-select.css')}}" />
<link rel="stylesheet" href="{{asset('public/assets/plugins/nouislider/nouislider.min.css')}}" />
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<link rel="stylesheet" href="{{asset('public/assets/plugins/dropify/css/dropify.min.css')}}" />
<style>
    .input-group-text {
        padding: 0 .75rem;
    }

    .amount {
        width: 150px;
        text-align: right;
    }

    .table td {
        padding: 0.10rem;
    }

    .dropify {
        width: 200px;
        height: 200px;
    }
</style>
<script lang="javascript/text">
    var accountURL = "{{ url('accountSearch') }}";
var customerURL = "{{ url('customerSearch') }}";
var token =  "{{ csrf_token()}}";
</script>
@stop
@section('content')

<div class="row clearfix">
    <div class="card col-lg-12">
        <div class="header">
            <h2>Received From Customer</h2>
        </div>
        @if(session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
        @endif
        @if(session()->has('error'))
        <div class="alert alert-danger">{{ session()->get('error') }}</div>
        @endif
        <div class="body">
            <form method="post" action="{{url('Account/ReceivedAmountSave')}}" enctype="multipart/form-data" id="form">
                {{ csrf_field() }}
                <input type="hidden" name="id" value="{{ $received_from_customer->id ?? ''}}">
                <input type="hidden" name="trm_id" value="{{ $received_from_customer->trm_id ?? ''}}">
                <input type="hidden" name="route_type" value="{{$type}}">
                <div class="row">
                    <div class="col-12">
                        <label for="">Document Number: </label>
                        <label>{{$received_from_customer->month ??
                            ''}}-{{appLib::padingZero($received_from_customer->number ?? '')}}</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <label for="name">Date</label>
                        <div class="form-group">
                            <input type="date" name="date" class="form-control"
                                value="{{ $received_from_customer->date ?? ''}}" required>
                        </div>
                    </div>
                    @if($type==6)
                    <input type="hidden" name="type" value="6">
                    @else
                    <div class="col-md-3">
                        <label for="fiscal_year">Type</label>
                        <div class="form-group">
                            <select name="type" id="type" class="form-control show-tick ms select2"
                                data-placeholder="Select" required>
                                <option value="no">Select Type</option>
                                <option value="1" {{(($received_from_customer->type ??'') == '1') ? 'selected' : ''
                                    }}>Cash</option>
                                <option value="3" {{(($received_from_customer->type ??'') == '3') ? 'selected' : ''
                                    }}>Bank</option>
                                <option value="-1" {{(($received_from_customer->type ??'') == '-1') ? 'selected' : ''
                                    }}>From Account</option>
                            </select>
                        </div>
                    </div>
                    @endif
                    @if($type==6)
                    <div class="col-md-3">
                        <label for="fiscal_year">Adjustment Account</label>
                        <div class="form-group">
                            <input type="text" id="received_in" class="form-control"
                                value="{{ $received_from_customer->received_from ?? ''}}"
                                placeholder="Adjustment Account" onkeyup="autoFill(this.id,accountURL, token)" required>
                            <input type="hidden" name="received_in" id="received_in_ID"
                                value="{{ $received_from_customer->coa_id ?? ''  }}">
                        </div>
                    </div>
                    @else
                    <div class="col-md-3" id="{{(isset($received_in))? '':'received_in_field'}}">
                        <label for="email">Received In</label>
                        @if(isset($received_in))
                        <div class="form-group">
                            <select name="received_in" id="received_in" class="form-control show-tick ms select2"
                                data-placeholder="Select" required>
                                <option value="">Select Received In Account</option>
                                @foreach($received_in as $account)
                                <option value="{{$account->id}}" {{ ( $account->id == ($received_from_customer->coa_id
                                    ??'')) ? 'selected' : '' }}>{{$account->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        @else
                        <div class="form-group">
                            <select name="received_in" id="received_in" class="form-control show-tick ms select2"
                                data-placeholder="Select" required>

                            </select>
                        </div>
                        @endif
                    </div>
                    @endif
                    <div class="col-md-3" id="{{(isset($received_in))? '':'cheque_field'}}">
                        <label for="cheque_no">Cheque No.</label>
                        <div class="form-group">
                            <input type="text" name="cheque_no" class="form-control"
                                value="{{ $received_from_customer->cheque_no ?? ''}}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-2 col-md-6 multicurrency">
                        <label for="fiscal_year">Currency</label>
                        <div class="form-group">
                            <select name="cur_id" id="cur_id" class="form-control show-tick ms select2"
                                data-placeholder="Select"
                                onchange="getCurrencyRate(this.id, 'rate', CurrencyURL, token);" required>
                                <option value="">--Select--</option>
                                @foreach($currencies as $cur)
                                <option {{ ( $cur->id == ($received_from_customer->cur_id ?? old('cur_id'))) ?
                                    'selected' : '' }} value="{{ $cur->id }}">{{ $cur->code }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <div class="col-lg-2 col-md-6 multicurrency">
                        <label for="rate">Rate</label>
                        <div class="form-group">
                            <input type="number" step="any" name="rate" id='rate' class="form-control"
                                value="{{ number_format($cashAccountDetail->cur_rate ?? '1',2)   }}" size='9'
                                maxlength="9" required style="text-align: end;">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <label for="fax">Received From</label>
                        <div class="form-group">
                            <input type="text" name="received_from" id="received_from" class="form-control"
                                value="{{ $received_from_customer->received_from ?? ''}}" placeholder="Customer"
                                onkeyup="autoFill(this.id, customerURL, token)" required>
                            <input type="hidden" name="received_from_ID" id="received_from_ID"
                                value="{{ $received_from_customer->cst_coa_id ?? ''  }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="fax">Amount</label>
                        <div class="form-group">
                            <input type="number" step="any" name="total_pay_amount" id="amount"
                                class="form-control text-right clear" onblur="advancePayment()"
                                value="{{ $received_from_customer->amount ?? ''}}" style="text-align: end;" required>
                            <span id="amount-error" style="color:red"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label>Note</label>
                        <div class="form-group" id="note">
                            <textarea name="note" maxlength="120" rows="2" class="form-control no-resize"
                                required>{{ $received_from_customer->note ?? ''}}</textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-2 col-md-2">
                        <label>Total Amount</label>
                        <div class="form-group">
                            <input type="number" step="any" id="total_amount" class="form-control text-right clear"
                                readonly value="{{ $received_from_customer->amount ?? ''}}" style="text-align: end;">
                        </div>
                    </div>
                    <div class="col-md-4" id="advance_payment_field">
                        <label>Advance Payment</label>
                        <div class="form-group">
                            <input type="number" step="any" name="advance_payment" id="advance_payment"
                                class="form-control text-right clear"
                                value="{{ $received_from_customer->advance_amount ?? ''}}" readonly>
                        </div>
                    </div>
                    <div class="col-md-8" id="balance">
                        <label>Ledger Balance</label><br>
                        <label id="ledger_balance"></label>
                        <input type="hidden" id="ledger_balance_val" value="0">
                        <span id="balance_msg" class="text-danger"></span>
                    </div>

                </div>

                <div class="row mt-1">
                    <div class="table-responsive">
                        <table id="invoice" class="table table-striped m-b-0">
                            <thead>
                                <tr class="bg-purple">
                                    <th class="table_header ">Subject</th>
                                    <th class="table_header ">Invoice No.</th>
                                    <th class="table_header">Due Date</th>
                                    <th class="table_header table_header_100">Amount</th>
                                    <th class="table_header table_header_100">Adjustment</th>
                                    <th class="table_header table_header_100">Balance</th>
                                    <th class="table_header table_header_100">View</th>

                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $count =
                                (isset($received_from_customer_details))?count($received_from_customer_details):1;

                                for($i=1;$i<=$count; $i++) { if(isset($received_from_customer_details)) {
                                    $lineItem=$received_from_customer_details[($i-1)]; } @endphp <tr id="row{{$i}}"
                                    class="rowData">
                                    <td>
                                        <!--Due date -->
                                        <input type="text" name="subject[]" class="form-control"
                                            value="{{ $lineItem->subject ?? ''  }}" readonly style="text-align: end;">
                                    </td>
                                    <td>
                                        <!-- invoice number -->
                                        <input type="text" name="invoice_num[]" class="form-control"
                                            value="{{ $lineItem->inv_num ?? ''  }}" readonly style="text-align: end;">
                                        <!-- invoice id -->
                                        <input type="hidden" name="invoice_id[]" value="{{ $lineItem->inv_id ?? ''  }}">
                                    </td>
                                    <td>
                                        <!--Due date -->
                                        <input type="text" name="due_date[]" class="form-control"
                                            value="{{ $lineItem->due_date ?? ''  }}" readonly style="text-align: end;">
                                    </td>
                                    <td>
                                        <!-- amount-->
                                        <input type="text" name="amount[]" id="amount{{$i}}" class="form-control qty"
                                            value="{{ $lineItem->amount ?? ''  }}" readonly>
                                    </td>
                                    <td>
                                        <!-- received -->
                                        <input type="number" step="any" name="received[]" id="received{{$i}}"
                                            value="{{ $lineItem->received ?? '0.00'  }}"
                                            class="form-control received qty"
                                            onblur="balance({{$i}});totalSum();advancePayment()">
                                        <span id="error_msg{{$i}}" class="text-danger"></span>
                                    </td>
                                    <td>
                                        <!-- balance -->
                                        <input type="text" name="balance[]" id="balance{{$i}}"
                                            value="{{ isset($lineItem->amount) ?  $lineItem->amount - $lineItem->received : ''}}"
                                            class="form-control qty" readonly>
                                    </td>
                                    <td>
                                        <a href=""><i class="zmdi zmdi-eye" style="font-size: 30px; margin: 1px 0px 0px 30px;color: #3e5fdb;"></i></a>
                                    </td>
                                    </tr>
                                    @php
                                    }
                                    @endphp
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class=" ml-auto">
                        <button class="btn btn-raised btn-primary waves-effect" id="save" type="submit">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@stop
@section('page-script')
<script src="{{asset('public/assets/plugins/dropify/js/dropify.min.js')}}"></script>
<script src="{{asset('public/assets/js/pages/forms/dropify.js')}}"></script>
<script src="{{asset('public/assets/js/sw.js')}}"></script>
<script>
function myFunction1() {
    $('#table').hide();
var url="{{ url('customerInvoices') }}";
rowId=0;
var total_id=$("#total").attr("id")

    $('.clear').val('');
    $('.rowData').html('');
    var coa_id=$('#received_from_ID').val();
    var currency=$('#cur_id').val();
    // console.log(currency);
    $('#table').show();
    $.post(url,{coa_id:coa_id,_token:token,currency:currency},function(data){
        console.log(data);
        data.map(function(val,i){
                rowId++;
                var rw='<tr id="row'+rowId+'" class="rowData">'+
                        '<td>'+
                        '<input type="text" name="subject[]"  value="'+val.subject+'" class="form-control" readonly>'+
                        '</td>'+
                        '<td>'+
                            //invoice number
                            '<input type="text" name="invoice_num[]"  value="'+val.inv_num+'" class="form-control" readonly>'+
                            //invoice id
                            '<input type="hidden" name="invoice_id[]" value="'+val.id+'">'+
                        '</td>'+
                        '<td>'+
                            //due date
                            '<input type="date" name="due_date[]"  class="form-control"  value="'+val.due_date+'" readonly>'+
                        '</td>'+
                        '<td>'+
                            //amount
                            '<input type="text" name="amount[]" id="amount'+rowId+'"  class="form-control qty"  value="'+val.balance+'" readonly></td>'+
                        '<td>'+
                            //received
                            '<input type="number" step="any" name="received[]" id="received'+rowId+'" class="form-control received qty" value="0.00" onblur="balance('+rowId+');totalSum();advancePayment()">'+
                            '<span id="error_msg'+rowId+'" class="text-danger"></span>'+
                        '</td>'+
                        '<td>'+
                            //balance
                            '<input type="number"  name="balance[]" id="balance'+rowId+'" value="'+val.balance+'" class="form-control qty" readonly>'+
                        '</td>'+
                        '<td>'+
                            '<a href="{{ url('Sales/Invoice/View/c/') }}/' + val.id + '"><i class="zmdi zmdi-eye" style="font-size: 30px; margin: 1px 0px 0px 30px;color: #3e5fdb;"></i></a>'+
                        '</td>'

                    '</tr>';
            $('#invoice').append(rw);
            if($('#type option:selected').val()==-1)
                $('#ledger_balance').html(val.general_ledger_balance * -1 );
                $('#ledger_balance_val').val(val.general_ledger_balance * -1 );
        });
    });
};


 $('#received_from').on('blur',function(){
    myFunction1();
 });

 $('#cur_id').on('change',function(){
    myFunction1();
 });

/*balance function subtract received from amount and display value in balance field.
if also check if received value is greater than amount is display error and strop form to submit. */
function balance(row_number)
{
    if(parseFloat($('#amount'+row_number).val()) >= parseFloat($('#received'+row_number).val()))
    {
        $('#balance'+row_number).val($('#amount'+row_number).val() - $('#received'+row_number).val());
        $('#error_msg'+row_number).text('');
    }
    else
    {
        $('#error_msg'+row_number).text('Received amount must be less than amount');
    }

}

function totalSum()
{

    var total=sumAll('received');
    $('#total_amount').val(total);
}

//the function subtract amount from total amount.if amount is greater it set the  value in advance payment text box.
function advancePayment()
{
    amount=parseFloat($('#amount').val());
    total_amount=parseFloat($('#total_amount').val());

    if(parseFloat(amount) > parseFloat(total_amount) )
        $('#advance_payment').val(parseFloat(amount) -  parseFloat(total_amount));
    else
        $('#advance_payment').val('0');
}


$( "#form" ).submit(function( event ) {
    //amount is received amount
    var amount=parseFloat($('#amount').val());
    var total_amount=parseFloat($('#total_amount').val());
     if(amount>=total_amount)
     {
        $('#amount-error').text("");
     }
    else
    {
        $('#amount-error').text("This amount is must be equal or greater to total amount");
        event.preventDefault();
    }

    if($('#type').val() == -1 && parseFloat($('#ledger_balance_val').val())!=0 && parseFloat($('#ledger_balance_val').val())< parseFloat(amount))
    {
        console.log($('#ledger_balance_val').val());
        console.log(total_amount);
        $('#balance_msg').text("Your Balance is insufficient");
       // event.preventDefault();
       return false;
    }

});

//hide received-in select box and chque no field
$('#received_in_field').hide();
$('#cheque_field').hide();
$('#balance').hide();
$('#advance_payment_field').hide();

//fill dependent received according to type
$('#type').on('change',function(){

    //blank recevied in select-box
    $('#received_in').html('');
    $('#received_in').append('<option>select Account</option>');
    if($(this).val()==3)
    {
        parent_id = 9;
        //show received-in selecte box and chque no field
        $('#received_in_field').show();
        $('#cheque_field').show();
        $('#balance').hide();
        $('#ledger_balance_val').val(0);
        $('#advance_payment_field').show();

    }
    else if($(this).val()==1)
    {
        parent_id = 10;
        //show received-in select box
        $('#received_in_field').show();
        $('#cheque_field').hide();
        $('#balance').hide();
        $('#ledger_balance_val').val(0);
        $('#advance_payment_field').show();
    }

    else
    {
        $('#received_in_field').hide();
        $('#cheque_field').hide();
        $('#advance_payment_field').hide();
       /* if($('#received_from_ID').val() ==='')
        {
            //$('option[value='-1']').prop("selected", true);
            $('#type option[value="no"]').attr("selected", "selected");
            alert('first add customer name');
        }
        else
        {
            $('#balance').show();
        } */
        $('#balance').show();

    }
    if(typeof parent_id !== "undefined")
    {
        var url = "{{ url('received_in')}}";
        $.post(url,{parent_id:parent_id, _token:token},function(data){
            data.map(function(val,i){
            var option='<option value="'+val.id+'">'+val.name+'</option>';
                $('#received_in').append(option);
            });
        });
    }
});


</script>
@stop
