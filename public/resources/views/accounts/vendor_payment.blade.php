@extends('layout.master')
@section('title','Vendor Payment')
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
    var vendorURL = "{{ url('vendorCoaSearch') }}";
var token =  "{{ csrf_token()}}";
</script>
@stop
@section('content')
<div class="row clearfix">
    <div class="card col-lg-12">
        <div class="header">
            <h2>Vendor Payment</h2>
        </div>
        @if(session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
        @endif
        @if(session()->has('error'))
        <div class="alert alert-danger">
            {{ session()->get('error') }}
        </div>
        @endif
        <div class="body">
            <form method="post" action="{{url('Account/VendorPaymentSave')}}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="hidden" name="id" value="{{$invoice_payment->id ?? ''}}">
                <input type="hidden" name="trm_id" value="{{$invoice_payment->trm_id ?? ''}}">
                <div class="row">
                    <div class="col-12">
                        <label for="">Document Number: </label>
                        <label>{{$invoice_payment->month ?? ''}}-{{appLib::padingZero($invoice_payment->number ??
                            '')}}</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <label for="name">Date</label>
                        <div class="form-group">
                            <input type="date" name="date" class="form-control" value="{{$invoice_payment->date ?? ''}}"
                                required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="fiscal_year">Type</label>
                        <div class="form-group">
                            <select name="type" id="type" class="form-control show-tick ms select2"
                                data-placeholder="Select" required>
                                <option>Select Type</option>
                                <option value="2" {{(($invoice_payment->type ??'') == '2') ? 'selected' : '' }}>Cash
                                </option>
                                <option value="4" {{(($invoice_payment->type ??'') == '4') ? 'selected' : '' }}>Bank
                                </option>
                                <option value="-1" {{(($invoice_payment->type ??'') == '-1') ? 'selected' : '' }}>From
                                    Account</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3" id="{{(isset($pay_from))? '':'pay_from_field'}}">
                        <label for="email">Pay From</label>
                        @if(isset($pay_from))
                        <div class="form-group">
                            <select name="pay_from_coa_id" id="pay_from" class="form-control show-tick ms select2"
                                data-placeholder="Select" required>
                                <option value="">Select Received In Account</option>
                                @foreach($pay_from as $account)
                                <option value="{{$account->id}}" {{ ( $account->id == ($invoice_payment->coa_id ??'')) ?
                                    'selected' : '' }}>{{$account->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        @else
                        <div class="form-group">
                            <select name="pay_from_coa_id" id="pay_from" class="form-control show-tick ms select2"
                                data-placeholder="Select" required>

                            </select>
                        </div>
                        @endif
                    </div>
                    <div class="col-md-3" id="{{(isset($pay_from))? '':'cheque_field'}}">
                        <label for="cheque_no">Cheque No.</label>
                        <div class="form-group">
                            <input type="text" name="cheque_no" class="form-control"
                                value="{{$invoice_payment->cheque_no ?? ''}}">
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
                                <option {{ ( $cur->code == ($cashAccountDetail->cur_id ?? session()->get('code'))) ?
                                    'selected' : '' }} value="{{ $cur->id }}">{{ $cur->code }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-6 multicurrency">
                        <label for="rate">Rate</label>
                        <div class="form-group">
                            <input type="number" step="any" style="text-align: end;" name="rate" id='rate' class="form-control"
                                value="{{ number_format($cashAccountDetail->cur_rate ?? '1',2)   }}" size='9'
                                maxlength="9" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <label for="fax">Pay To</label>
                        <div class="form-group">
                            <input type="text" name="pay_to" id="pay_to" class="form-control"
                                value="{{$invoice_payment->received_from ?? ''}}" placeholder="Vendor"
                                onkeyup="autoFill(this.id, vendorURL, token)" required>
                            <input type="hidden" name="pay_to_ID" id="pay_to_ID"
                                value="{{$invoice_payment->cst_coa_id ?? ''  }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="fax">Amount</label>
                        <div class="form-group">
                            <input type="number" step="any" name="total_pay_amount" id="amount"
                                class="form-control text-right" onblur="advancePayment()"
                                value="{{ Str::currency($invoice_payment->amount ?? '0')}}" style="text-align: end;" >
                            <span id="amount-error" style="color:red"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label>Note</label>
                        <div class="form-group" id="note">
                            <textarea name="note" maxlength="120" rows="2" class="form-control no-resize"
                                required>{{$invoice_payment->note ?? ''}}</textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-2 col-md-2">
                        <label>Total Amount</label>
                        <div class="form-group">
                            <input type="number" step="any" id="total_amount" class="form-control text-right" readonly
                                value="{{ Str::currency($invoice_payment->amount ?? '0')}}" style="text-align: end;" >
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label>Advance Payment</label>
                        <div class="form-group">
                            <input type="number" name="advance_payment" id="advance_payment"
                                class="form-control text-right"
                                value="{{ Str::currency($invoice_payment->advance_amount ?? '0')}}" style="text-align: end;" readonly>
                        </div>
                    </div>
                    <!--
                        <div class="col-md-8" id="balance">
                            <label>Ledger Balance</label><br>
                            <label id="ledger_balance"></label>
                            <span id="balance_msg" class="text-danger"></span>
                        </div>
                        -->
                </div>

                <div class="row mt-1">
                    <div class="table-responsive">
                        <table id="invoice" class="table table-striped m-b-0">
                            <thead>
                                <tr class="bg-purple">
                                    <th class="table_header ">Invoice No.</th>
                                    <th class="table_header">Date</th>
                                    <th class="table_header table_header_100">Amount</th>
                                    <th class="table_header table_header_100">Payment</th>
                                    <th class="table_header table_header_100">Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $count = (isset($invoice_payment_details))?count($invoice_payment_details):1;

                                for($i=1;$i<=$count; $i++) { if(isset($invoice_payment_details)) {
                                    $lineItem=$invoice_payment_details[($i-1)]; } @endphp <tr id="row{{$i}}"
                                    class="rowData">
                                    <td>
                                        <!-- invoice number -->
                                        <input type="text" name="invoice_num[]" class="form-control"
                                            value="{{ $lineItem->inv_num ?? ''  }}" readonly>
                                        <!-- invoice id -->
                                        <input type="hidden" name="invoice_id[]" value="{{ $lineItem->inv_id ?? ''  }}">
                                    </td>
                                    <td>
                                        <!--Due date -->
                                        <input type="text" name="inv_date[]" class="form-control"
                                            value="{{ $lineItem->inv_date ?? ''  }}" readonly>
                                    </td>
                                    <td>
                                        <!-- amount-->
                                        <input type="text" name="amount[]" id="amount{{$i}}" class="form-control qty"
                                            value="{{ Str::currency($lineItem->amount ?? '0')  }}" readonly>
                                    </td>
                                    <td>
                                        <!-- received -->
                                        <input type="number" step="any" name="received[]" id="received{{$i}}"
                                            value="{{ Str::currency($lineItem->received ?? '0')  }}"
                                            class="form-control received qty" onblur="balance({{$i}});totalSum();" style="text-align: end;" >
                                        <span id="error_msg{{$i}}" class="text-danger"></span>
                                    </td>
                                    <td>
                                        <!-- balance -->
                                        <input type="text" name="balance[]" id="balance{{$i}}"
                                            value="{{ isset($lineItem->amount) ?  $lineItem->amount - $lineItem->received : ''}}"
                                            class="form-control qty" readonly>
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
    $('#table').hide();
var url="{{ url('vendorInvoices') }}";
rowId=0;
var total_id=$("#total").attr("id")
$('#pay_to').on('blur',function(){
    $('.rowData').html('');
    var coa_id=$('#pay_to_ID').val();
    $('#table').show();
    $.post(url,{coa_id:coa_id,_token:token},function(data){
        console.log(data);
        data.map(function(val,i){
                rowId++;
                var rw='<tr id="row'+rowId+'" class="rowData">'+
                        '<td>'+
                            //invoice number
                            '<input type="text" name="invoice_num[]"  value="'+val.inv_num+'" class="form-control" readonly>'+
                            //invoice id
                            '<input type="hidden" name="invoice_id[]" value="'+val.id+'">'+
                        '</td>'+
                        '<td>'+
                            // date
                            '<input type="date" name="inv_date[]"  class="form-control"  value="'+val.date+'" readonly>'+
                        '</td>'+
                        '<td>'+
                            //amount
                            '<input type="text" name="amount[]" id="amount'+rowId+'"  class="form-control qty"  value="'+val.balance+'" readonly></td>'+
                        '<td>'+
                            //received
                            '<input type="number" step="any" name="received[]" id="received'+rowId+'" class="form-control received qty" onblur="balance('+rowId+');totalSum();" required style="text-align: end;" >'+
                            '<span id="error_msg'+rowId+'" class="text-danger"></span>'+
                        '</td>'+
                        '<td>'+
                            //balance
                            '<input type="number"  name="balance[]" id="balance'+rowId+'" class="form-control qty" readonly style="text-align: end;" >'+
                        '</td>'+
                    '</tr>';
            $('#invoice').append(rw);
        });
    });
});

/*balance function subtract received from amount and display value in balance field.
if also check if received value is greater than amount is display error and strop form to submit. */
function balance(row_number)
{
    if($('#amount'+row_number).val() > $('#received'+row_number).val())
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
    if($('#amount').val() > $('#total_amount').val())
        $('#advance_payment').val($('#amount').val() -  $('#total_amount').val());
    else
        $('#advance_payment').val('0');
}


$( "form" ).submit(function( event ) {
    var amount=Math.floor($('#amount').val());
    var total_amount=Math.floor($('#total_amount').val());
    if(amount>=total_amount)
    {

    }
    else
    {
        $('#amount-error').text("This amount is must be equal or greater to total amount");
        event.preventDefault();
    }
    if($('#ledger_balance').html() !=='' && $('#ledger_balance').html()< total_amount)
    {
        console.log('how');
        $('#balance_msg').text("Your Balance is insufficient");
        event.preventDefault();
    }
});

//hide received-in select box and chque no field
$('#pay_from_field').hide();
$('#cheque_field').hide();

//fill dependent received according to type
$('#type').on('change',function(){

    //blank recevied in select-box
    $('#pay_from').html('');
    $('#pay_from').append('<option value="-1">select Account</option>');
    if($(this).val()==4)
    {
        parent_id = 9;
        //show received-in selecte box and chque no field
        $('#pay_from_field').show();
        $('#cheque_field').show();
    }
    else if($(this).val()==2)
    {
        parent_id = 10;
        //show received-in select box
        $('#pay_from_field').show();
        $('#cheque_field').hide();
    }

    else
    {
        $('#pay_from_field').hide();
        $('#cheque_field').hide();

    }
    if(typeof parent_id !== "undefined")
    {
        var url = "{{ url('received_in')}}";
        $.post(url,{parent_id:parent_id, _token:token},function(data){
            data.map(function(val,i){
            var option='<option value="'+val.id+'">'+val.name+'</option>';
                $('#pay_from').append(option);
            });
        });
    }
});


</script>
@stop
