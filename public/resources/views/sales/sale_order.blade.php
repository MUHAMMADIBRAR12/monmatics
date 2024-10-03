@extends('layout.master')
@section('title', 'Sale Order')
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
                '<td>'+
                    //unit
                    '<input type="text" name="unit[]" id="unit'+rowId+'"  class="form-control unit-field" readonly>'+
                '</td>'+
                '<td>'+
                    //qty in stock
                    '<input type="text" name="qty_in_stock[]" id="qty_in_stock'+rowId+'"  class="form-control qty" readonly>'+
                '</td>'+               
                '<td class="qty"><input type="number" name="qty[]" id="qty'+rowId+'" class="qty form-control"  step="any"  required onblur="qtyRateTotal('+rowId+')"></td>'+
                 '<td class="qty"><input type="number" name="rate[]" id="rate'+rowId+'" step="any" class="form-control qty" onblur="qtyRateTotal('+rowId+')"></td>'+
                 '<td class="qty"><input type="number" name="amount[]" id="amount'+rowId+'" step="any" class="form-control qty" value="1.000"></td>'+
                 '<td class="qty"><select name="tax_class[]" id="tax_class'+rowId+'" class="form-control show-tick ms select2" data-placeholder="Select" onchange="getTaxAmount(this.id, '+rowId+', token);">'+
                            '<option value="">-select-</option>'+
                        @foreach($taxList as $tax)
                            '<option {{ ( $tax->name == ($cashAccountDetail->coa_id ?? '')) ? 'selected' : '' }} value="{{ $tax->name }}">{{ $tax->name }} @ {{ $tax->rate }}</option>'+
                        @endforeach    
                    '</select></td>'+
                 '<td class="qty"><input type="number" name="tax_amount[]" id="tax_amount'+rowId+'" step="any" class="form-control qty"></td>'+
                 '<td class="qty"><input type="number" name="total_amount[]" id="total_amount'+rowId+'" step="any" class="total_amount form-control qty" value="1.000" readonly="readonly"></td>'+
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
        (data.qty_in_stock? stock=(data.qty_in_stock/data.sale_opt_val) : stock=0)
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
    if($saleOrder->id ?? '')
    {
        $disabled = "disabled";
        $readonly = "readonly";
        
    }
        
@endphp
@section('content')

    <div class="row clearfix">
        <div class="card col-lg-12">
            <div class="header">
                <h2><strong>Sales</strong> Order</h2>
                <!--<button class="btn btn-primary" style="align:right" onclick="window.location.href = '{{-- url('Sales/Invoice') --}}';" >New Sale Order</button>-->
            </div>
            @if(session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="body">
                <form method="post" action="{{url('Sales/SaleOrder/Add')}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $saleOrder->id ?? ''  }}">
                    
                    <div class="row">
                        <div class="col-lg-4 col-md-6">
                            <label for="fiscal_year">Customer</label>
                            <div class="form-group">
                                <input type="text" name="customer" id="customer" onkeyup="autoFill(this.id,CustomerURL,token)" value="{{ $saleOrder->name ?? ''  }}" class="form-control autocomplete" required  onblur="getQuotations()">
                                <input type="hidden" name="customer_ID" id="customer_ID" value="{{ $saleOrder->cst_coa_id ?? ''  }}" required>   
                                <button type="button" class="btn btn-primary" target='_blank' onclick="window.location.href = '{{ url('Crm/Customer/l') }}';" >+</button>
                            </div>
                        </div>
                       
                        <div class="col-lg-2 col-md-6">
                            <label for="code">Sale Order No.</label>
                            <div class="form-group">
                                {{ ($saleOrder->month ?? '') }}-{{ appLib::padingZero($saleOrder->number ?? '') }}
                            </div>
                        </div>
                      
                        <div class="col-lg-3 col-md-6">
                            <label for="date">Date</label>
                            <div class="form-group">
                                @php $toDate = date('m/d/Y'); @endphp
                                <input type="date" name="date" id='date'  class="form-control" value="{{ $saleOrder->date ?? date("Y-m-d")  }}"  required {{ $readonly ?? '' }}>
                            </div>
                        </div>                       
                        <div class="col-lg-3 col-md-6">
                            <label for="fiscal_year">Customer Ref.</label>
                            <div class="form-group">
                                <input type="text" name="customer_ref" id="customer_ref" class="form-control " value="{{ $saleOrder->customer_ref ?? '' }}" >
                            </div>
                        </div>   
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <label for="fiscal_year">Quotation No.</label>
                            <div class="form-group">
                                <select name="quotation_id" id="quotation_id" class="form-control show-tick ms select2" data-placeholder="Select">
                                    <option value="">--Select--</option>
                                    @if($saleOrder->quot_id ?? '')
                                        <option value="{{ $saleOrder->quot_id ?? '' }}" selected>{{ $saleOrder->qmonth ?? '' }}-{{appLib::padingZero($saleOrder->qnumber  ?? '')}}</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3">
                            <label for="fiscal_year">Payment Terms</label>
                            <div class="form-group">
                                <select name="payment_terms" id="payment_terms" class="form-control show-tick ms select2" data-placeholder="Select">
                                    <option value="">--Select--</option>
                                    @foreach($paymentTerms as $paymentTerm)
                                        <option {{ ( $paymentTerm->terms == ($saleOrder->payment_terms ??'')) ? 'selected' : '' }} value="{{ $paymentTerm->terms }}">{{  $paymentTerm->terms  }}</option>
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
                                        <option {{ ( $project->id == ($saleOrder->cost_center_id ??'')) ? 'selected' : '' }} value="{{ $project->id  ?? '' }}">{{  $project->name  ?? ''  }}</option>
                                    @endforeach                                                                                       
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3">
                            <label for="fiscal_year">Warehouse</label>
                            <div class="form-group">
                                <select name="warehouse" id="warehouse" class="form-control show-tick ms select2" data-placeholder="Select">
                                    <option value="">--Select--</option>
                                    @foreach($warehouses as $warehouse)
                                        <option {{ ( $warehouse->id == ($saleOrder->warehouse ??'')) ? 'selected' : '' }} value="{{ $warehouse->id  ?? '' }}">{{  $warehouse->name  ?? ''  }}</option>
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
                                        <th class="table_header">Unit</th>
                                        <th class="table_header">Qty In Stock</th>                                    
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
                                $count = (isset($lineItems))?count($lineItems):1;
                                $subTotal = 0;
                                for($i=1;$i<=$count; $i++)
                                { 
                                    
                                    if(isset($lineItems))
                                    {
                                        $lineItem = $lineItems[($i-1)];
                                        //$description = "sku:". $lineItem->sku ."| Unit:" .$lineItem->primary_unit;
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
                                            <input type="text" name="description[]" id="description{{$i}}"  value="{{ $lineItem->description ?? ''  }}" class="form-control field-width">
                                        </td>                                       
                                        <td>
                                            <!-- unit -->
                                            <input type="text" name="unit[]" id="unit{{$i}}" value="{{ $lineItem->prod_unit ?? ''  }}"  class="form-control unit-field"  readonly>
                                        </td>
                                        <td>
                                            <!-- qty stock -->
                                            <input type="text" name="qty_in_stock[]" id="qty_in_stock{{$i}}" value="{{ Str::currency($lineItem->qty_in_stock ?? '0')  }}"  class="form-control qty"  readonly>
                                        </td>                                        
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
                                    rowId = {{($i-1)}};
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
                                    <option value="{{$tax->coa_id}}" {{ ( $tax->coa_id == ($taxNDiscount->tax_coa_id ?? '')) ? 'selected' : '' }} >{{$tax->name}}</option>
                                    @endforeach
                                </select>
                                <input type="text" name="tax_rate" value="{{ Str::currency($taxNDiscount->tax_rate ?? '0') }}" id="tax_rate" class="form-control mr-2" readonly style="width:70px">
                                <input type="text" name="tax_amount_total" id="tax_amount_total" value="{{ Str::currency($taxNDiscount->tax_amount ?? '0') }}" class="form-control" readonly style="width:100px">
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
                                    <option value="{{$discount->coa_id}}" {{ ( $discount->coa_id == ($taxNDiscount->disc_coa_id ?? '')) ? 'selected' : '' }}>{{$discount->name}}</option>
                                    @endforeach
                                </select>
                                <input type="text" name="discount_rate" value="{{ Str::currency($taxNDiscount->disc_rate ?? '0') }}" id="discount_rate" class="form-control mr-2" readonly style="width:70px">
                                <input type="text" name="discount_amount" id="discount_amount" value="{{ Str::currency($taxNDiscount->disc_amount ?? '0') }}" class="form-control" readonly style="width:100px">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <div class="form-inline float-right">
                                <label class="mr-2">Net Payable Amount</label>
                                <input type="text" name="net_payable_amount" value="{{ Str::currency($taxNDiscount->net_payable ?? '0') }}" id="net_payable_amount" class="form-control qty" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <label for="fiscal_year">Note</label>
                            <div class="form-group" id="note">
                                <textarea name="note" maxlength="120" rows="4" class="form-control no-resize"  placeholder="">{{ $saleOrder->note ?? '' }}</textarea>
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
                   
                    <div class="row">                        
                        <div class="ml-auto">
                            <input id="remember_me" name="post_voucher" type="checkbox" {{ (($saleOrder->status ?? '' )=='approved')? 'readonly checked':''}} name="post"> Post Voucher
                            <button class="btn btn-raised btn-primary waves-effect" type="submit">Save</button>
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
            $('#tax_amount_total').val(getNum(percentVal(data[0].rate,$('#sub_total').val(),2),2));
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
        tax_amount=getNum($('#tax_amount_total').val(),2);
        console.log('tax amount'+tax_amount);
        discount_amount=getNum($('#discount_amount').val(),2);
        console.log('discount amount'+discount_amount);        
        net_payable_amount=getNum(parseFloat(total_amount)+parseFloat(tax_amount)-parseFloat(discount_amount),2);
        $('#net_payable_amount').val(getNum(net_payable_amount,2));
    }
</script>
@stop