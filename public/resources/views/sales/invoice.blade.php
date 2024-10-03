@extends('layout.master')
@section('title', 'Invoice')
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

function addRow()
{
    rowId++;
    var row = '<tr id="row'+rowId+'">'+
                '<td><button  type="button" class="btn btn-danger btn-sm" onclick="deleteRow(\'row'+rowId+'\');"><i class="zmdi zmdi-delete"></i></button></td>'+
                '<td>'+
                    //item
                    '<input type="text" name="name[]" id="name'+rowId+'" class="form-control product-field" onkeyup="autoFill(this.id, ItemURL, token)" onblur="getItemDetail('+rowId+');" value="" required>'+
                    '<input type="hidden" name="name_ID[]" id="name'+rowId+'_ID">'+
                '</td>'+
                '<td>'+
                    //description
                    '<input type="text" name="description[]" id="description'+rowId+'" class="form-control field-width">'+
                '</td>'+
                @if($type=='p')
                '<td>'+
                    //unit
                    '<input type="text" name="unit[]" id="unit'+rowId+'"  class="form-control unit-field" readonly>'+
                '</td>'+
                '<td>'+
                    //qty in stock
                    '<input type="text" name="qty_in_stock[]" id="qty_in_stock'+rowId+'"  class="form-control qty" readonly>'+
                '</td>'+
                @endif
                '<td class="qty"><input type="number" name="qty[]" id="qty'+rowId+'" class="qty form-control"  step="any"  required onblur="qtyRateTotal('+rowId+')"></td>'+
                 '<td class="qty"><input type="number" name="rate[]" id="rate'+rowId+'" step="any" class="form-control qty" onblur="qtyRateTotal('+rowId+')"></td>'+
                 '<td class="qty"><input type="number" name="amount[]" id="amount'+rowId+'" step="any" class="form-control qty" value="1.000"></td>'+
                 '<td class="qty"><select name="tax_class[]" id="tax_class'+rowId+'" class="form-control show-tick ms select2" data-placeholder="Select" onchange="getTaxAmount(this.id, '+rowId+', token);">'+
                            '<option value="">-select-</option>'+
                        @foreach($taxList as $tax)
                            '<option {{ ( $tax->name == ($cashAccountDetail->coa_id ?? '')) ? 'selected' : '' }} value="{{ $tax->name }}">{{ $tax->name }} @ {{ $tax->rate }}</option>'+
                        @endforeach
                    '</select></td>'+
                 '<td class="qty"><input type="number" name="tax_amount[]" id="tax_amount'+rowId+'" step="any" class="form-control qty" value="0.00"></td>'+
                 '<td class="qty"><input type="number" name="total_amount[]" id="total_amount'+rowId+'" step="any" class="total_amount form-control qty"  readonly="readonly"></td>'+
                '<td>'+
                    //instruction
                    '<input type="insturction" name="insturction[]" id="insturction'+rowId+'" class="form-control field-width">'+
                '</td>'+
                 '<td><input type="date" name="required_by[]" id="required_by'+rowId+'" class="form-control" value=""></td>'+
             '</tr>';
     $('#voucher').append(row);
}



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

    <div class="row clearfix">
        <div class="card col-lg-12">
            <div class="header">
                <h2><strong>Sales</strong> Invoice</h2>
                {{-- <button class="btn btn-primary" style="align:right" onclick="window.location.href = '{{ url('Sales/Invoice') }}';" >New Invoice</button> --}}
            </div>
            @if(session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
            @endif
            <div class="body">
                <form method="post" action="{{url('Sales/Invoice/Add')}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    @php
                        $id = $Invoice->id ?? '';
                    @endphp
                    @if (Request::is('Sales/Invoice/Regenerate/' . $type . '/' . $id))
                    <input type="hidden" name="id" value="">
                    <input type="hidden" name="trans_id" value="">
                    @else
                    <input type="hidden" name="id" value="{{ $Invoice->id ?? ''  }}">
                    <input type="hidden" name="trans_id" value="{{ $Invoice->trans_id ?? ''  }}">
                    @endif

                    <input type="hidden" name="type" value="{{$type }}">
                    <div class="row">
                        <div class="col-lg-3 col-md-8 ">
                            <label for="rate">Subject</label>
                            <div class="form-group">
                                <input type="text" name="subject" value="{{ $Invoice->subject ?? '' }}"  class="form-control" required>
                                <button type="button" class="btn btn-primary" target='_blank' onclick="window.location.href = '{{ url('Crm/Customers/Create') }}';" >+</button>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <label for="fiscal_year">Customer</label>
                            <div class="form-group">
                                <input type="text" name="customer" id="customer" onkeyup="autoFill(this.id,CustomerURL,token)" value="{{ $Invoice->name ?? ''  }}" class="form-control autocomplete" required  onblur="getQuotations()">
                                <input type="hidden" name="customer_ID" id="customer_ID" value="{{ $Invoice->cst_coa_id ?? ''  }}" required>

                                {{-- <button type="button" class="btn btn-primary" target='_blank' onclick="window.location.href = '{{ url('Crm/Customer/l') }}';" >+</button> --}}
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
                                @php $toDate = date('m/d/Y'); @endphp
                                <input type="date" name="date" id='date'  class="form-control" value="{{ $Invoice->date ?? date("Y-m-d")  }}"  required {{ $readonly ?? '' }}>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label for="date">Due Date</label>
                            <div class="form-group">
                                @php $toDate = date('m/d/Y'); @endphp
                                <input type="date" name="due_date" id='due_date'  class="form-control" value="{{ $Invoice->due_date ?? date("Y-m-d")  }}"  required {{ $readonly ?? '' }}>
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-6 ">
                            <label for="fiscal_year">Currency</label>
                            <div class="form-group">
                                <select name="cur_id" id="cur_id" class="form-control show-tick ms select2" data-placeholder="Select" onchange="getCurrencyRate(this.id, 'rate', CurrencyURL, token);"  required>
                                    <option value="">--Select--</option>
                                    @foreach($currencies as $cur)
                                        <option {{ ( $cur->code == ($cashAccountDetail->cur_id ?? session()->get('code'))) ? 'selected' : '' }} value="{{ $cur->id }}">{{  $cur->code  }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-6 ">
                            <label for="rate">Rate</label>
                            <div class="form-group">
                                <input type="number" step="any" name="cur_rate" id='cur_rate'   class="form-control" value="{{ number_format($Invoice->cur_rate ?? '1',2)   }}" size='9' maxlength="9" required>
                            </div>
                        </div>



                    </div>
                    <div class="row">
                        @if($type=='p')
                        <div class="col-lg-3 col-md-6">
                            <label for="fiscal_year">Delivery Order Number</label>
                            <div class="form-group">
                                <select name="do_id" id="do_id" class="form-control show-tick ms select2" data-placeholder="Select">
                                    <option value="">--Select--</option>
                                    @foreach($deliveryOrders as $deliveryOrder)
                                        <option value="{{ $deliveryOrder->id ?? '' }}" selected>{{ $deliveryOrder->month ?? '' }}-{{appLib::padingZero($deliveryOrder->number  ?? '')}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @else
                        <div class="col-lg-3 col-md-6">
                            <label for="fiscal_year">Quotation No.</label>
                            <div class="form-group">
                                <select name="quotation_id" id="quotation_id" class="form-control show-tick ms select2" data-placeholder="Select">
                                    <option value="">--Select--</option>
                                    @if($Invoice->quot_id ?? '')
                                        <option value="{{ $Invoice->quot_id ?? '' }}" selected>{{ $Invoice->qmonth ?? '' }}-{{appLib::padingZero($Invoice->qnumber  ?? '')}}</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        @endif
                        <div class="col-lg-3 col-md-3">
                            <label for="fiscal_year">Sales Account</label>
                            <div class="form-group">
                            <!--
                            {{ (($Invoice->status ?? '' )=='approved')? 'disabled':''}}
                            -->
                                <select name="sale_coa_id" id="sale_coa_id" class="form-control show-tick ms select2" data-placeholder="Select" required >

                                    <option value="">--Select---</option>
                                    @foreach($SalesAccounts as $SalesAccount)
                                        <option {{ ( $SalesAccount->id == ($Invoice->sale_coa_id ??'')) ? 'selected' : '' }} value="{{ $SalesAccount->id }}">{{  $SalesAccount->name  }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3">
                            <label for="fiscal_year">Payment Terms</label>
                            <div class="form-group">
                                <select name="payment_terms" id="payment_terms" class="form-control show-tick ms select2" data-placeholder="Select">
                                    <option value="">--Select--</option>
                                    @foreach($paymentTerms as $paymentTerm)
                                        <option {{ ( $paymentTerm->terms == ($Invoice->payment_terms ??'')) ? 'selected' : '' }} value="{{ $paymentTerm->terms }}">{{  $paymentTerm->terms  }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3">
                            <label for="fiscal_year">Project</label>
                            <div class="form-group">
                                <select name="cost_center" id="cost_center" class="form-control show-tick ms select2" data-placeholder="Select">
                                    <option value="">--Select--</option>
                                    @foreach($projects as $project)
                                        <option {{ ( $project->id == ($Invoice->cost_center_id ??'')) ? 'selected' : '' }} value="{{ $project->id  ?? '' }}">{{  $project->name  ?? ''  }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <label for="fiscal_year">Customer Ref.</label>
                            <div class="form-group">
                                <input type="text" name="customer_ref" id="customer_ref" class="form-control " value="{{ $Invoice->customer_ref ?? '' }}" >
                            </div>
                        </div>
                        @if($type=='p')
                        <div class="col-lg-3 col-md-3">
                            <label for="fiscal_year">Warehouse</label>
                            <div class="form-group">
                                <select name="warehouse" id="warehouse" class="form-control show-tick ms select2" data-placeholder="Select">
                                    <option value="">--Select--</option>
                                    @foreach($warehouses as $warehouse)
                                        <option {{ ( $warehouse->id == ($Invoice->warehouse ??'')) ? 'selected' : '' }} value="{{ $warehouse->id  ?? '' }}">{{  $warehouse->name  ?? ''  }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif
                        <div class="col-lg-3 col-md-3">
                            <label for="recuring">Recuring</label>
                            <div class="form-group">
                                <select name="recuring"  class="form-control show-tick ms select2" data-placeholder="Select">
                                    <option value="">--Select--</option>
                                    @foreach($recurings as $recuring)
                                        <option {{ ( $recuring->name == ($Invoice->recuring ?? '')) ? 'selected' : '' }}  value="{{$recuring->name}}">{{$recuring->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="table-responsive">
                            <table id="voucher" class="table table-striped m-b-0">
                                <thead>
                                    <tr class="bg-purple">
                                        <th><button type="button" class="btn btn-primary" style="align:right" onclick="addRow();" >+</button></th>
                                        <th class="table_header ">Item</th>
                                        <th class="table_header">Description</th>
                                    @if($type=='p')
                                        <th class="table_header">Unit</th>
                                        <th class="table_header">Qty In Stock</th>
                                    @endif
                                        <th class="table_header table_header_100">Qty</th>
                                        <th class="table_header table_header_100">Rate</th>
                                        <th class="table_header table_header_100">Amount</th>
                                        <th class="table_header table_header_100">Tax</th>
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
                                        <td>
                                            <!-- button delete row -->
                                            <button  type="button" class="btn btn-danger btn-sm" onclick="deleteRow('row{{$i}}');"><i class="zmdi zmdi-delete"></i></button>
                                        </td>
                                        <td class="autocomplete">
                                            <!-- item field -->
                                            <input type="text" name="name[]" id="name{{$i}}"  onkeydown="checkWarehouse(this.id)" onkeyup="autoFill(this.id, ItemURL, token)" onblur="getItemDetail({{$i}});" value="{{ $lineItem->name ?? ''  }}" class="form-control product-field" required>
                                            <input type="hidden" name="name_ID[]" id="name{{$i}}_ID" value="{{ $lineItem->prod_id ?? ''  }}" required>
                                        </td>
                                        <td>
                                            <!-- description -->
                                            <input type="text" name="description[]" id="description{{$i}}"  value="{{ $lineItem->inv_description ?? ''  }}" class="form-control field-width">
                                        </td>
                                        @if($type=='p')
                                        <td>
                                            <!-- unit -->
                                            <input type="text" name="unit[]" id="unit{{$i}}" value="{{ $lineItem->prod_unit ?? ''  }}"  class="form-control unit-field"  readonly>
                                        </td>
                                        <td>
                                            <!-- qty stock -->
                                            <input type="text" name="qty_in_stock[]" id="qty_in_stock{{$i}}" value="{{ Str::currency($lineItem->qty_in_stock ?? '0')  }}"  class="form-control qty"  readonly>
                                        </td>
                                        @endif
                                        <td>
                                            <!-- qty -->
                                            <input type="number" step="any" name="qty[]" id="qty{{$i}}" class="form-control qty" value="{{ Str::currency($lineItem->qty ?? '0') }}" onblur="qtyRateTotal({{$i}})">
                                        </td>
                                        <td>
                                            <!-- rate-->
                                            <input type="number" name="rate[]" id="rate{{$i}}" step="any" class="form-control qty" value="{{ Str::currency($lineItem->rate ?? '0' ) }}"  onblur="qtyRateTotal({{$i}})"></td>
                                        <td>
                                            <!-- amount -->
                                            <input type="number" name="amount[]" id="amount{{$i}}" step="any" class="form-control qty" value="{{ Str::currency($lineItem->amount ?? '0') }}" readonly>
                                        </td>
                                        <td>
                                            <!-- tax  -->
                                            <select name="tax_class[]" id="tax_class{{$i}}" class="form-control show-tick ms select2" data-placeholder="Select" onchange="getTaxAmount(this.id, {{$i}}, token);">
                                                <option value="0">-select-</option>
                                            @foreach($taxList as $tax)
                                                <option {{ ( $tax->name == ($lineItem->tax_class ?? '')) ? 'selected' : '' }} value="{{ $tax->name }}">{{ $tax->name }} @ {{ $tax->rate }}</option>
                                            @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <!-- tax amount -->
                                            <input type="number" name="tax_amount[]" id="tax_amount{{$i}}" step="any" class="form-control qty" value="{{ Str::currency($lineItem->tax_amount ?? '0') }}" readonly>
                                        </td>
                                        <td>
                                            <!-- total amount -->
                                            <input type="number" name="total_amount[]" id="total_amount{{$i}}" step="any" class="total_amount form-control qty" value="{{ Str::currency($lineItem->total_amount ?? '0') }}" readonly>
                                        </td>
                                        <td>
                                            <!-- instruction -->
                                            <input type="text" name="insturction[]"  class="form-control field-width" value="{{ $lineItem->instruction ?? '' }}">
                                        </td>
                                        <td>
                                            <!-- required by date -->
                                            <input type="date" name="required_by[]" id="required_by" class="form-control" value="{{ $lineItem->required_by ?? ''  }}" >
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
                                <label class="mr-2">Total Amount :</label>
                                <input type="number" name="sub_total" id="sub_total" step="any" class="form-control qty" value="{{ $subTotal ?? 0 }}">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <div class="form-inline float-right">
                                <label class="mr-2">Tax :</label>
                                <select name="tax_coa_id" id="tax" class="form-control show-tick ms select2 mr-2" style="width:130px">
                                    <option value="">Select Tax </option>
                                    @foreach($taxList as $tax)
                                    <option value="{{$tax->coa_id}}" {{ ( $tax->coa_id == ($invoice_tax->tax_coa_id ?? '')) ? 'selected' : '' }} >{{$tax->name}}</option>
                                    @endforeach
                                </select>
                                <input type="text" name="total_tax_rate" value="{{ Str::currency($invoice_tax->tax_rate ?? '0') }}" id="tax_rate" class="form-control mr-2" readonly style="width:70px">
                                <input type="text" name="total_tax_amount" id="tax_amount" value="{{ Str::currency($invoice_tax->tax_amount ?? '0') }}" class="form-control" readonly style="width:100px">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <div class="form-inline float-right">
                                <label class="mr-2">Discount :</label>
                                <select name="discount_coa_id" id="discount" class="form-control show-tick ms select2 mr-2" style="width:130px">
                                    <option value="">Select Discount </option>
                                    @foreach($discountList as $discount)
                                    <option value="{{$discount->coa_id}}" data-category="{{$discount->category}}" {{ ( $discount->coa_id == ($invoice_tax->disc_coa_id ?? '')) ? 'selected' : '' }}>{{$discount->name}}</option>
                                  @endforeach
                                </select>
                                <input type="text" name="total_discount_rate" value="{{ Str::currency($invoice_tax->disc_rate ?? '0') }}" id="discount_rate" class="form-control mr-2" readonly style="width:70px">
                                <input type="text" name="total_discount_amount" id="discount_amount" value="{{ Str::currency($invoice_tax->disc_amount ?? '0') }}" class="form-control" style="width:100px">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <div class="form-inline float-right">
                                <label class="mr-2">Advance Received</label>
                                <input type="number" step="any" name="advance_amount" value="{{$invoice_tax->advance_amount ?? 0.00}}" id="advance_amount" class="form-control qty" onblur="getNetPayAble();">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <div class="form-inline float-right">
                                <label class="mr-2">Net Payable Amount</label>
                                <input type="text" name="net_payable_amount" value="{{ Str::currency($invoice_tax->net_payable ?? '0') }}" id="net_payable_amount" class="form-control qty" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <label for="fiscal_year">Note</label>
                            <div class="form-group" id="note">
                                <textarea name="note" maxlength="120" rows="4" class="form-control no-resize"  placeholder="">{{ $Invoice->note ?? '' }}</textarea>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label for="fiscal_year">Attachment</label>
                            <br>
                            @if($attachmentRecord ?? '')
                                <table>
                                @foreach($attachmentRecord as $attachment)
                                <tr>
                                    <td><button  type="button" class="btn btn-danger btn-sm" onclick="alert('Delete option is not available now.');"><i class="zmdi zmdi-delete"></i></button></td>
                                    <td><a target="_blank" href="{{asset('assets/attachments/'. $attachment->file)}}" download >{{ $attachment->file }}</a></td>
                                </tr>
                                @endforeach
                                </table>
                            @endif
                            <input name="file" type="file" class="dropify">
                        </div>
                    </div>
                    <div class="row mt-2">
                    <div class="ml-auto">
                        <input type="checkbox" id="printCheckbox" name="print" class="ml-2">
                        <label for="printCheckbox">Print</label>
                    </div>
                </div>


                        <input type="hidden" id="typeInput" name="type" value="{{ $type }}">
                        <input type="hidden" id="idInput" name="id" value="{{ $invoiceId ?? '' }}">

                    <div class="row">
                        <div class="ml-auto">
                            <input id="remember_me" name="post_voucher" type="checkbox" {{ (($Invoice->status ?? '' )=='approved')? 'readonly checked':''}} name="post"> Post Voucher
                            <button class="btn btn-raised btn-primary waves-effect" type="submit">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
          $('#discount').on('change', function() {
            var selectedOption = $(this).val();

            // Get the category value based on the selected option
            var category = $('option:selected', this).data('category');

            if (category === 'Rate') {
              $('#discount_amount').prop('disabled', true);
            } else {
              $('#discount_amount').prop('disabled', false);
            }
          });
        });
      </script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var typeInput = document.getElementById('typeInput');
        var idInput = document.getElementById('idInput');

        // Set the initial value of typeInput dynamically based on the page type
        if($pageType === 'services')
            typeInput.value = 'c';
        elseif($pageType === 'product')
            typeInput.value = 'p';
        endif

        // Update the value of typeInput when the checkbox is clicked
        var printCheckbox = document.getElementById('printCheckbox');
        printCheckbox.addEventListener('change', function() {
            if (this.checked) {
                typeInput.value = 'p';
            } else {
                typeInput.value = 'c';
            }
        });
    });
</script>

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
