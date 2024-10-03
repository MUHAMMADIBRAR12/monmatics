@extends('layout.master')
@section('title', 'Invoice View')
@section('parentPageTitle', 'Sales')
@section('parent_title_icon', 'zmdi zmdi-home')
@section('page-style')


<?php  use App\Libraries\appLib; ?>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<!--<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">-->
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<link rel="stylesheet" href="{{asset('public/assets/plugins/dropify/css/dropify.min.css')}}"/>
<link rel="stylesheet" href="{{asset('public/assets/css/sw.css')}}">
<style>

    .amount{
        width: 150px;
        text-align: right;
    }
    .table td{
        padding: 0.10rem;
    }
    .dropify
    {
        width: 200px;
        height: 200px;
    }
</style>
<script lang="javascript/text">

var CustomerURL = "{{ url('customerSearch') }}";
var token =  "{{ csrf_token()}}";
var ItemURL = "{{ url('itemSearch') }}";
var getItemDetailURL = "{{ url('getItemDetail') }}";
var TaxRateURL = "{{ url('getTaxRates') }}";
var rowId=1;

// CurId is dropdown Id and rateId is Rate element id
function getCurrencyRate(curId, rateId)
{
    curCode = $('#'+curId).val();
    $.post("{{ url('getCurrencyRate') }}",{ code: curCode, _token : "{{ csrf_token()}}" }, function (data){
        $('#'+rateId).val(data);
    });

}
function totalAmount()
{
    totalAmountX = sumAll('amount');
    $('#total_amount').html($('#cur_id').val()+totalAmountX+'/-');
}

function getItemDetail(number)
{
    itemId = $('#name'+number+'_ID').val();
    warehouseId=$('#warehouse').val();
    $.post("{{ url('getItemDetail') }}",{ id: itemId,warehouseId:warehouseId, _token : "{{ csrf_token()}}" }, function (data){
        $('#description'+number).val(data.name);
        $('#rate'+number).val(data.sale_price);
        (data.qty_in_stock? stock=data.qty_in_stock : stock=0)
        $('#qty_in_stock'+number).val(stock);
        $('#unit'+number).val(data.sales_unit);
    });
}

function getTaxAmount(taxName, index, token)
{
    taxName = $('#'+taxName).val();
    $.post(TaxRateURL,{ taxName: taxName, _token : token }, function (data){

        taxRate = getNum(data);
        amount = getNum($('#amount'+index).val(),2);
        taxAmount = percentVal(taxRate, amount, 2);
        totalAmount = +getNum(amount)+ +getNum(taxAmount);
        $('#tax_amount'+index).val(taxAmount);
        $('#total_amount'+index).val(getNum(totalAmount));
        $('#sub_total').val(sumAll('total_amount'));
        //call percentVal function which calculate tax amount
        tax_amount=percentVal( $('#tax_rate').val(), $('#sub_total').val(),2);
        $('#tax_amount').val(getNum(tax_amount,2));
        //call percentVal function which calculate discount amount
        discount_amount=percentVal( $('#discount_rate').val(), $('#sub_total').val(),2);
        $('#discount_amount').val(getNum(discount_amount,2));

        getNetPayAble();  //calculate net payable amount

    });
}

function qtyRateTotal(index)
{
    amount = getNum($('#qty'+index).val())*getNum($('#rate'+index).val());
    $('#amount'+index).val(getNum(amount,2));
    $('#total_amount'+index).val(getNum(amount,2));
    $('#sub_total').val(sumAll('total_amount'));
    //call percentVal function which calculate tax amount
    tax_amount=percentVal( $('#tax_rate').val(), $('#sub_total').val(),2);
    $('#tax_amount').val(getNum(tax_amount,2));

    //call percentVal function which calculate discount amount
    discount_amount=percentVal( $('#discount_rate').val(), $('#sub_total').val(),2);
    $('#discount_amount').val(getNum(discount_amount,2));

    getNetPayAble();  //calculate net payable amount

}

function getQuotations()
{

    cstId = $('#customer_ID').val();
    $('#quotation_id').empty();
    $('#quotation_id').append("<option value='' >-Select-</option>");
    $.post("{{ url('getQuotations') }}",{ id: cstId, _token : "{{ csrf_token()}}" }, function (data){
       $.each( data, function( key, value ) {
           $('#quotation_id').append("<option value='"+value.id+"' >"+value.month+"-"+value.number+"</option>");
      });

    });
}
</script>

@stop
@php
    if($Invoice->id ?? '')
    {
        $disabled = "disabled";
        $readonly = "readonly";
    }

@endphp
@section('content')

    @php
    $type = $type ?? 'c';
    @endphp

    <div class="row clearfix">
        <div class="col-md-12">
            <div class="header">
                <button class="btn btn-primary" style="align: right;" onclick="window.location.href = '{{ url('Sales/Invoice/Regenerate/' . $type . '/' . $Invoice->id) }}';">Regenerate Invoice</button>
                <button class="btn btn-primary" style="align: right;" onclick="window.open('{{ url('Sales/Invoice/Create/pdf/' . $type . '/' . $Invoice->id) }}');">Print</button>
            </div>
        </div>
        <div class="card col-lg-12">
            @if(session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
            @endif

            <div class="body">
                <form method="post" action="{{url('Sales/Invoice/Add')}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $Invoice->id ?? ''  }}">
                    <input type="hidden" name="trans_id" value="{{ $Invoice->trans_id ?? ''  }}">
                    <input type="hidden" name="type" value="{{$type }}">
                    <div class="row">
                        <div class="col-lg-4 col-md-6">
                            <label for="fiscal_year">Customer</label>
                            <div class="form-group">
                               <label>  {{ $Invoice->name ?? ''  }} </label>
                            </div>
                        </div>
                        @if(1)
                        <div class="col-lg-2 col-md-6">
                            <label for="code">Invoice No.</label>
                            <div class="form-group">
                                {{ ($Invoice->month ?? '') }}-{{ appLib::padingZero($Invoice->number ?? '') }}
                            </div>
                        </div>
                       @endif
                        <div class="col-lg-3 col-md-6">
                            <label for="date">Invoice Date</label>
                            <div class="form-group">
                                <label>  {{ $Invoice->date ?? ''  }} </label>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label for="date">Due Date</label>
                            <div class="form-group">
                                <label> {{ $Invoice->due_date ??''  }} </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <label for="fiscal_year">Quotation No.</label>
                            <div class="form-group">
                                    @if($Invoice->quot_id ?? '')
                                        <label> {{ $Invoice->quot_id ?? '' }} {{ $Invoice->qmonth ?? '' }}-{{appLib::padingZero($Invoice->qnumber  ?? '')}}</label>
                                    @endif
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3">
                            <label for="fiscal_year">Sales Account</label>
                            <div class="form-group">
                                <label>{{  $Invoice->sale_account  ?? '' }}</label>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3">
                            <label for="fiscal_year">Payment Terms</label>
                            <div class="form-group">
                                    <label>{{  $Invoice->payment_terms ??''  }}</label>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3">
                            <label for="fiscal_year">Project</label>
                            <div class="form-group">
                                <option>{{  $Invoice->project_name  ?? ''  }}</option>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <label for="fiscal_year">Customer Ref.</label>
                            <div class="form-group">
                                <label> {{ $Invoice->customer_ref ?? '' }} </label>
                            </div>
                        </div>
                        @if($type=='p')
                        <div class="col-lg-3 col-md-3">
                            <label for="fiscal_year">Warehouse</label>
                            <div class="form-group">
                                <label > {{  $warehouse->name  ?? ''  }}</label>

                            </div>
                        </div>
                        @endif
                        <div class="col-lg-3 col-md-3">
                            <label for="recuring">Recuring</label>
                            <div class="form-group">
                                <label > {{$Invoice->recuring ?? ''}} </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="table-responsive">
                            <table id="voucher" class="table table-striped m-b-0">
                                <thead>
                                    <tr class="bg-purple">
                                        <th class="table_header col-3">Item</th>
                                        <th class="table_header col-3">Description</th>
                                    @if($type=='p')
                                        <th class="table_header">Unit</th>
                                        <th class="table_header">Qty In Stock</th>
                                    @endif
                                        <th class="table_header table_header_100">Qty</th>
                                        <th class="table_header table_header_100 ">Rate</th>
                                        <th class="table_header table_header_100">Amount</th>
                                        <th class="table_header col-1">Tax</th>
                                        <th class="table_header table_header_100">Tax</th>
                                        <th class="table_header table_header_100">Total Amount</th>
                                        <th class="table_header">Instruction</th>
                                        <th class="table_header">Required by</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @php
                                $count = (isset($InvoiceDetail))?count($InvoiceDetail):1;
                                $subTotal = 0;
                                for($i=1;$i<=$count; $i++)
                                {

                                    if(isset($InvoiceDetail))
                                    {
                                        $lineItem = $InvoiceDetail[($i-1)];
                                        $description = "sku:". $lineItem->sku ."| Unit:" .$lineItem->primary_unit;
                                    }
                                    @endphp
                                    <tr id="row{{$i}}">
                                        <td class="autocomplete">
                                            <!-- item field -->
                                            <div class="form-group mw-100"><p>{{ $lineItem->name ?? ''  }}</p> </div>
                                        </td>
                                        <td>
                                            <!-- description -->
                                           <div class="form-group mw-100"><p>{{  $lineItem->inv_description ?? ''  }}</p> </div>
                                        </td>
                                        @if($type=='p')
                                            <!-- unit -->
                                            <div class="form-group mw-100"><p> {{ $lineItem->prod_unit ?? ''  }} </p> </div>
                                        </td>
                                        <td>
                                            <!-- qty stock -->
                                            <div class="form-group mw-100"><p> {{ Str::currency($lineItem->qty_in_stock ?? '0')  }}  </p> </div>
                                        </td>
                                        @endif
                                        <td>
                                            <!-- qty -->
                                            <div class="form-group mw-100"><p>{{ Str::currency($lineItem->qty ?? '0') }} </p> </div>
                                        </td>
                                        <td>
                                            <!-- rate-->
                                            <div class="form-group mw-100"><p> {{ Str::currency($lineItem->rate ?? '0' ) }} </p> </div>
                                        <td>
                                            <!-- amount -->
                                            <div class="form-group mw-100"><p> {{ Str::currency($lineItem->amount ?? '0') }} </p> </div>
                                        </td>
                                        <td>
                                            <!-- tax  -->
                                            {{   $lineItem->tax_class ??''}}
                                        </td>
                                        <td>
                                            <!-- tax amount -->
                                            <div class="form-group mw-100"><p> {{ Str::currency($lineItem->tax_amount ?? '0') }} </p> </div>
                                        </td>
                                        <td>
                                            <!-- total amount -->
                                            <div class="form-group mw-100"><p>{{ Str::currency($lineItem->total_amount ?? '0') }} </p> </div>
                                        </td>
                                        <td>
                                            <!-- instruction -->
                                            <div class="form-group mw-100"><p> {{ $lineItem->instruction ?? '' }} </p> </div>
                                        </td>
                                        <td>
                                            <!-- required by date -->
                                            <div class="form-group mw-100"><p> {{ $lineItem->required_by ?? ''  }} </p> </div>
                                        </td>
                                    </tr>
                                @php
                                        $subTotal += ( $lineItem->total_amount ?? 0);
                                    }
                                @endphp
                                </tbody>
                                <script>
                                    rowId = {{$i}};
                                </script>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-inline float-right">
                                <label class="mr-2 font-weight-bold">Total Amount :</label>
                                <label> {{ Str::currency($subTotal ?? '0') }}  </label>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                    <div class="col-md-6" >
                            <label for="fiscal_year"> Note :  {{ $Invoice->note ?? '' }}</label>
                        </div>
                        <div class="col-md-6">
                            <div class="form-inline float-right">
                                <label class="mr-2 font-weight-bold">Tax@ :{{$tax->name ?? ''}}</label>
                                 <label class="primary"> {{ Str::currency($invoice_tax->tax_rate ?? '0') }} </label> &nbsp;&nbsp;
                                 <label class="primary float-right"> {{ Str::currency($invoice_tax->tax_amount ?? '0') }} </label>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <div class="form-inline float-right" >
                                <label class="mr-2 font-weight-bold">Discount@ : {{$discount->name ?? '' }}</label>

                                <label class="primary"> {{ Str::currency($invoice_tax->disc_rate ?? '0') }} </label> &nbsp;&nbsp;
                                <label class="primary"> {{ Str::currency($invoice_tax->disc_amount ?? '0') }} </label>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <div class="form-inline float-right" >
                                <label class="mr-2 font-weight-bold">Advance Received :</label>
                                <label class="primary"> {{ Str::currency($invoice_tax->advance_amount ?? '0')}} </label>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <div class="form-inline float-right">
                                <label class="mr-2 font-weight-bold">Net Payable Amount :</label>
                                <label class="tetx-primary"> {{ Str::currency($invoice_tax->net_payable ?? '0') }} </label>
                            </div>
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
var token =  "{{ csrf_token()}}";
$(document).ready(function(){
    //tax rate
    $('#tax').on('change',function(){
        let tax_coa_id=$(this).val();
        var url= "{{ url('Sales/TaxRate') }}";
        $.post(url,{tax_coa_id:tax_coa_id, _token:token},function(data){
            $('#tax_rate').val(data[0].rate);
            //call percentVal function which calculate tax amount
            $('#tax_amount').val(getNum(percentVal(data[0].rate,$('#sub_total').val(),2),2));
            getNetPayAble();  //calculate net payable amount
        });

    });

    //discount rate
    $('#discount').on('change',function(){
        let discount_coa_id=$(this).val();
        var url= "{{ url('Sales/DiscountRate') }}";
        $.post(url,{discount_coa_id:discount_coa_id, _token:token},function(data){
            $('#discount_rate').val(data[0].rate);
            //call percentVal function which calculate discount amount
            $('#discount_amount').val(getNum(percentVal(data[0].rate,$('#sub_total').val(),2),2));
            getNetPayAble();  //calculate net payable amount
        });

    });
});
//calculate net payable amount
function getNetPayAble()
    {
        total_amount=getNum($('#sub_total').val(),2);
        console.log('total amount'+total_amount);
        tax_amount=getNum($('#tax_amount').val(),2);
        console.log('tax amount'+tax_amount);
        discount_amount=getNum($('#discount_amount').val(),2);
        console.log('discount amount'+discount_amount);
        advance_amount=getNum($('#advance_amount').val(),2);
        console.log('advance amount'+advance_amount);
        net_payable_amount=getNum(parseFloat(total_amount)+parseFloat(tax_amount)-parseFloat(discount_amount)-parseFloat(advance_amount),2);
        $('#net_payable_amount').val(getNum(net_payable_amount,2));
    }
</script>
@stop
