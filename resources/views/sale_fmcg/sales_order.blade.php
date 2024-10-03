@extends('layout.master')
@section('title', 'Sales Order')
@section('parentPageTitle', 'Sales')
@section('parent_title_icon', 'zmdi zmdi-home')
@section('page-style')

<?php  use  App\Libraries\appLib; ?>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<!--<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">-->
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<link rel="stylesheet" href="{{asset('public/assets/plugins/dropify/css/dropify.min.css')}}"/>
<link rel="stylesheet" href="{{asset('public/assets/css/sw.css')}}"/>
<style>
    .label_text{
        color: #bababa;
    }
    
</style>
<script lang="javascript/text">
var CustomerURL = "{{ url('customerSearch') }}";
var CustomerDetailURL= "{{ url('customerDetailSearch') }}";
var token =  "{{ csrf_token()}}";
var ItemURL = "{{ url('SaleItemSearch') }}";
var getItemDetailURL = "{{ url('getItemDetail') }}";
//var rowId=1;
function addRow()
{
    console.log('add row'+rowId);
    rowId++;
    
    var row ='<tr id="row'+rowId+'" class="rowData">'+
                '<td>'+
                    '<button  type="button" class="btn btn-danger btn-sm" onclick="deleteRow(\'row'+rowId+'\');decreaseRowNum('+rowId+')"><i class="zmdi zmdi-delete"></i></button>'+
                '</td>'+
                '<td>'+
                    //product
                    '<input type="text" name="item[]" id="item'+rowId+'" class="form-control"  onkeyup="autoFill(this.id, ItemURL, token);chkCustomer()" onblur="getItemDetail('+rowId+');getLatestRateCode('+rowId+')"  required>'+
                    '<input type="hidden" name="item_ID[]" id="item'+rowId+'_ID" class="product">'+
                '</td>'+
                '<td>'+
                    //unit
                    '<input type="text" name="unit[]" id="unit'+rowId+'" class="form-control unit-field" readonly>'+
                '</td>'+
                '<td>'+
                    //stock all warehouses
                    '<input type="number"  name="qty_stock[]" id="qty_stock'+rowId+'"  class="form-control qty"   readonly >'+
                '</td>'+
                '<td>'+
                    //qty ordered
                    '<input type="number" step="any" name="qty_ordered[]" id="qty_ordered'+rowId+'" value="0.00" class="form-control qty qty-num qty_ordered" onblur="qtyRateTotal('+rowId+');checkStock('+rowId+')">'+ 
                    '<span id="error'+rowId+'" class="text-danger"></span>'+
                '</td>'+
                '<td>'+
                    //rate Code
                    '<select name="rate_code_id[]" id="rate_code'+rowId+'" onchange="getRateCode('+rowId+')" class="form-control show-tick ms select2"  required style="width:120px">'+
                        '<option  value="">Select Rate Code</option>'+
                        @foreach($rate_codes as $rate)
                        '<option value="{{$rate->id}}" {{ ( $rate->id == ( $igp->warehouse ??'')) ? 'selected' : '' }}>{{ $rate->code}}</option>'+
                        @endforeach
                    '</select>'+
                '</td>'+
                '<td>'+
                    //base rate
                    '<input type="number"  name="base_rate[]" id="base_rate'+rowId+'" value="0.00"  class="form-control  qty" readonly>'+
                '</td>'+
                '<td>'+
                    //Sales tax 
                    '<input type="number" step="any" name="sales_tax[]"  id="sales_tax'+rowId+'" value="0.00" class="form-control qty tax sales_tax_num" readonly>'+
                '</td>'+
                '<td>'+
                    //FED
                    '<input type="number" step="any" name="fed[]"  id="fed'+rowId+'"  value="0.00" class="form-control qty tax fed_tax_num" readonly>'+
                '</td>'+
                '<td>'+
                    //Further tax
                    '<input type="number" step="any" name="further_tax[]"  id="further_tax'+rowId+'"  value="0.00" class="form-control qty tax further_tax_num" readonly>'+
                '</td>'+
                '<td>'+
                    //inclusive value
                    '<input type="number" step="any" name="inclusive_value[]"  id="inclusive_value'+rowId+'" value="0.00"  class="form-control qty inclusive_num" readonly>'+
                '</td>'+
                '<td>'+
                    //L/U
                    '<input type="number" step="any" name="lu[]"  id="lu'+rowId+'"  value="0.00" class="form-control qty discount" readonly>'+
                '</td>'+
                '<td>'+
                    //Freight
                    '<input type="number" step="any" name="freight[]"  id="freight'+rowId+'" value="0.00" class="form-control qty discount" readonly>'+
                '</td>'+
                '<td>'+
                    //Other
                    '<input type="number" step="any" name="other_discount[]"  id="other'+rowId+'" value="0.00" class="form-control qty discount" readonly>'+
                '</td>'+
                '<td>'+
                    //Rate
                    '<input type="number" step="any" name="rate[]"  id="rate'+rowId+'" value="0.00" class="form-control qty" readonly>'+
                '</td>'+
                '<td>'+
                    //Fixed margin
                    '<input type="number" step="any" name="fixed_margin[]"  id="fixed_margin'+rowId+'" value="0.00" class="form-control qty discount" readonly>'+
                '</td>'+
                '<td>'+
                    //value befor discount 
                    '<input type="number" step="any" name="amount_before_discount[]"  id="before_discount'+rowId+'" value="0.00" class="form-control qty discount before_discount_num" readonly>'+
                '</td>'+
                '<td>'+
                    //Trade offer
                    '<input type="number" step="any" name="trade_offer[]"  id="trade_offer'+rowId+'" value="0.00" class="form-control qty" readonly>'+
                '</td>'+
                '<td>'+
                    //discounts
                    '<input type="number" step="any" name="discount[]"  id="discount'+rowId+'"  value="0.00" class="form-control qty cust_discount customer_discount_num" readonly>'+
                '</td>'+
                '<td>'+
                    //value after discount
                    '<input type="number" step="any" name="amount_after_discount[]"  id="val_after_discount'+rowId+'"  value="0.00" class="form-control qty val_after_discount net_amount_num" readonly>'+
                '</td>'+
                '<td>'+
                    //adv payment
                    '<input type="hidden" id="adv_payment_hide'+rowId+'">'+
                    '<input type="number" step="any" name="adv_payment[]"  id="adv_payment'+rowId+'"  value="0.00" class="form-control qty" readonly>'+
                '</td>'+
            '</tr>';
     $('#voucher').append(row);
}

function decreaseRowNum(rowNum)
{
    rowId=--rowNum;
    return rowId;
}

function getItemDetail(number)
{
    itemId = $('#item'+number+'_ID').val();
    warehouseId=$('#warehouse').val();
    $.post("{{ url('getItemDetail') }}",{ id: itemId,warehouseId:warehouseId, _token : "{{ csrf_token()}}" }, function (data){
        (data.qty_in_stock? stock=data.qty_in_stock : stock=0)
        $('#qty_stock'+number).val(stock/data.sale_opt_val);
       // $('#rate'+number).val(getNum(data.sale_price,2));
        $('#unit'+number).val(data.sales_unit);
    });
}
function getCustomerBalance(id)
{
    
    $.post("{{ url('getBalance') }}",{coa_id:$('#'+id+'_ID').val(),_token : "{{ csrf_token()}}"},function(data){
        $('#cust_balance_label').html(data);
        $('#cust_balance').val(data);  
    });
}
</script>
@stop
@section('content')
    <div class="row clearfix">
        <div class="card col-lg-12">
            <div class="body">
                <form method="post" action="{{url('Sales_Fmcg/SaleOrder/Add')}}" enctype="multipart/form-data" id="form">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $sale_order->id ?? ''}}">
                    <div class="row">
                        <div class="col-lg-2 col-md-3">
                            <label for="fiscal_year">Sales Order #</label><br>
                            <label for="fiscal_year">{{ $sale_order->month ?? '' }}-{{appLib::padingZero($sale_order->number  ?? '')}}</label>
                        </div>
                        <div class="col-lg-3 col-md-3">
                            <label for="date">Date</label>
                            <div class="form-group">
                                <input type="date" name="date" id='date'  class="form-control" value="{{ $sale_order->date ?? date('Y-m-d')  }}"  required>
                            </div>
                        </div>     
                    </div> 
                    <div class="row">
                        <div class="col-lg-5 col-md-5">
                            <label for="">Customer</label>
                            <div class="form-group">
                                <input type="text" name="customer" id="customer" onkeyup="autoFill(this.id,CustomerURL,token)" onblur="getCustomerDetail(this.id,CustomerDetailURL,token);getCustomerBalance(this.id)" value="{{ $sale_order->cust_name ?? ''  }}" class="form-control" required>
                                <input type="hidden" name="customer_ID" id="customer_ID" value="{{$sale_order->cust_id ?? ''  }}"> 
                                <input type="hidden" name="cust_stn" id="cust_stn">
                                
                            </div>
                        </div>
                        
                        <div class="col-lg-2 col-md-2">
                            <label for="purchaser">Current Balance</label><br>
                            <label id="cust_balance_label" style="color:#bababa">{{$sale_order->cust_balance ?? ''  }}</label>
                            <input type="hidden" name="cust_balance" id="cust_balance" value="{{$sale_order->cust_balance ?? ''  }}"> 
                        </div>    
                        <div class="col-lg-3 col-md-6">
                            <button class="btn btn-light mt-4" type="button">Ledger</button>
                            <label>Ledger 2 month</label>
                        </div>    
                    </div>  
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <label for="">Address</label><br>
                            <label id="cust_address_label" style="color:#bababa">{{$sale_order->cust_address ?? ''  }}</label> 
                            <input type="hidden" name="cust_address" id="cust_address">
                        </div>
                        <div class="col-lg-2 col-md-2">
                            <label for="">Phone</label><br>
                            <label id="cust_phone_label" style="color:#bababa">{{$sale_order->cust_phone ?? ''  }}</label>
                            <input type="hidden" name="cust_phone" id="cust_phone">
                        </div>
                        <div class="col-lg-2 col-md-2">
                            <label for="">Town</label><br>
                            <label id="cust_location_label" style="color:#bababa">{{$sale_order->cust_town ?? ''  }}</label> 
                            <input type="hidden" name="cust_location" id="cust_location">
                        </div>       
                    </div>                   
                    <div class="row">
                        <div class="table-responsive">
                            <table id="voucher" class="table table-striped m-b-0">
                                <thead>
                                    <tr class="bg-purple">
                                        <th class="table_header table_header_100"><button type="button" class="btn btn-primary" style="align:right" onclick="addRow();" >+</button></th>
                                        <th class="table_header table_header_100">Product</th>
                                        <th class="table_header table_header_100">Unit</th>
                                        <th class="table_header table_header_100">Stock (All warehouses)</th>
                                        <th class="table_header table_header_100">Qty Order</th>
                                        <th class="table_header table_header_100">Rate Code</th>
                                        <th class="table_header table_header_100"> Base Rate</th>
                                        <th class="table_header table_header_100">Sales Tax</th>
                                        <th class="table_header table_header_100">FED</th>
                                        <th class="table_header table_header_100">Further Tax</th>
                                        <th class="table_header table_header_100">Inclusive Value</th>
                                        <th class="table_header table_header_100">L/U</th>
                                        <th class="table_header table_header_100">Freight</th>
                                        <th class="table_header table_header_100">Other</th>
                                        <th class="table_header table_header_100">Rate</th>
                                        <th class="table_header table_header_100">Fixed Margin</th>
                                        <th class="table_header table_header_100">Value Before Discount</th>
                                        <th class="table_header table_header_100">Trade Offer</th>
                                        <th class="table_header table_header_100">Discounts</th>
                                        <th class="table_header table_header_100">Value Aftr discount</th>
                                        <th class="table_header table_header_100">Adv Payment</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @php
                                $count = (isset($sale_order_detail))?count($sale_order_detail):1;
                               
                                for($i=1;$i<=$count; $i++)
                                { 
                                    if(isset($sale_order_detail))
                                    {
                                        $lineItem = $sale_order_detail[($i-1)];
                                        $freeRows = ($lineItem->rate==0)?'freeRows':'';
                                        
                                    }  
                                    if(isset($lineItem->rate_code_id) && $lineItem->rate_code_id==-1)
                                    {
                                        $mute='readonly';
                                        
                                    }  
                                    else
                                    {
                                        $mute='';
                                    }                                
                                    @endphp                               
                                    <tr id="row{{$i}}" class="rowData {{(isset($lineItem->rate_code_id) && $lineItem->rate_code_id==-1)? 'skuRow':''}}">
                                        <td>
                                            <button  type="button" class="btn btn-danger btn-sm" onclick="deleteRow('row{{$i}}');"><i class="zmdi zmdi-delete"></i></button>
                                        </td>
                                        <td>
                                            <!-- Product -->
                                            @if(isset($lineItem->rate_code_id) && $lineItem->rate_code_id==-1)
                                            <input type="text" name="item[]" class="form-control" {{$mute}} value="{{ $lineItem->prod_name ?? ''  }}">
                                            <input type="hidden" name="item_ID[]"  value="{{ $lineItem->prod_id ?? ''  }}" class="product">
                                            @else
                                            <input type="text" name="item[]" id="item{{$i}}"   onkeyup="autoFill(this.id, ItemURL, token);chkCustomer()" onblur="getItemDetail({{$i}});getLatestRateCode({{$i}})" value="{{ $lineItem->prod_name ?? ''  }}" class="form-control prod_field mr-0" required>
                                            <input type="hidden" name="item_ID[]" id="item{{$i}}_ID" value="{{ $lineItem->prod_id ?? ''  }}" class="product">
                                            @endif
                                        </td>
                                        <td>
                                            <!-- unit -->
                                            <input type="text"  name="unit[]" id="unit{{$i}}" value="{{ $lineItem->unit ?? ''  }}" class="form-control unit-field"   readonly >
                                        </td>
                                        <td>
                                            <!-- Stock all warehouses -->
                                            <input type="number"  name="qty_stock[]" id="qty_stock{{$i}}" value="{{  $lineItem->qty_stock ?? ''  }}" class="form-control qty" readonly>
                                        </td>
                                        <td>
                                            <!-- qty ordered -->
                                            <input type="number" step="any" name="qty_ordered[]" id="qty_ordered{{$i}}"  value="{{ $lineItem->qty_ordered ?? '0.00'  }}" class="form-control qty qty-num qty_ordered" onblur="qtyRateTotal({{$i}});checkStock({{$i}})" {{$mute}}>
                                            <span id="error{{$i}}" class="text-danger"></span>
                                        </td>
                                        <td>
                                           @if(isset($lineItem->rate_code_id) && $lineItem->rate_code_id==-1)
                                            <input type="text"  class="form-control" {{$mute}}>
                                            <input type="hidden" name="rate_code_id[]"  value="{{$lineItem->rate_code_id}}">
                                            @else
                                            <!-- rate Code-->
                                            <select name="rate_code_id[]" id="rate_code{{$i}}" onchange="getRateCode({{$i}})" class="form-control show-tick ms select2"  required style="width:120px">
                                                <option  value="">Select Rate Code</option>
                                                @foreach($rate_codes as $rate)
                                                <option value="{{$rate->id}}" {{ ( $rate->id == ( $lineItem->rate_code_id??'')) ? 'selected' : '' }}>{{ $rate->code}}</option>
                                                @endforeach
                                            </select>
                                            @endif
                                        </td>
                                        <td>
                                            <!-- base rate -->
                                            <input type="number" step="any" name="base_rate[]"  id="base_rate{{$i}}"  value="{{ $lineItem->base_rate ?? '0.00'  }}" class="form-control qty" readonly>
                                        </td>
                                        <td>
                                            <!-- Sales tax -->
                                            <input type="number" step="any" name="sales_tax[]"  id="sales_tax{{$i}}"  value="{{ $lineItem->sales_tax ?? '0.00'  }}" class="form-control qty tax sales_tax_num" readonly>
                                        </td>
                                        <td>
                                            <!-- FED -->
                                            <input type="number" step="any" name="fed[]"  id="fed{{$i}}"  value="{{ $lineItem->fed ?? '0.00'  }}" class="form-control qty tax fed_tax_num" readonly>
                                        </td>
                                        <td>
                                            <!-- Further tax -->
                                            <input type="number" step="any" name="further_tax[]"  id="further_tax{{$i}}"  value="{{ $lineItem->further_tax ?? '0.00'  }}" class="form-control qty tax further_tax_num" readonly>
                                        </td>
                                        <td>
                                            <!-- inclusive value -->
                                            <input type="number" step="any" name="inclusive_value[]"  id="inclusive_value{{$i}}"  value="{{ $lineItem->inclusive_value ?? '0.00'  }}" class="form-control qty inclusive_num" readonly>
                                        </td>
                                        <td>
                                            <!-- L/U -->
                                            <input type="number" step="any" name="lu[]"  id="lu{{$i}}"  value="{{ $lineItem->lu ?? '0.00'  }}" class="form-control qty discount" readonly>
                                        </td>
                                        <td>
                                            <!-- Freight -->
                                            <input type="number" step="any" name="freight[]"  id="freight{{$i}}"  value="{{ $lineItem->freight ?? '0.00'  }}" class="form-control qty discount" readonly>
                                        </td>
                                        <td>
                                            <!-- Other -->
                                            <input type="number" step="any" name="other_discount[]"  id="other{{$i}}"  value="{{ $lineItem->other_discount ?? '0.00'  }}" class="form-control qty discount" readonly>
                                        </td>
                                        <td>
                                            <!-- Rate -->
                                            <input type="number" step="any" name="rate[]"  id="rate{{$i}}"  value="{{ $lineItem->rate ?? '0.00'  }}" class="form-control qty" readonly>
                                        </td>
                                        <td>
                                            <!-- Fixed margin -->
                                            <input type="number" step="any" name="fixed_margin[]"  id="fixed_margin{{$i}}"  value="{{ $lineItem->fixed_margin ?? '0.00'  }}" class="form-control qty discount" readonly>
                                        </td>
                                        <td>
                                            <!-- value befor discount -->
                                            <input type="number" step="any" name="amount_before_discount[]"  id="before_discount{{$i}}"  value="{{ $lineItem->amount_before_discount ?? '0.00'  }}" class="form-control qty discount before_discount_num" readonly>
                                        </td>
                                        <td>
                                            <!-- Trade offer -->
                                            <input type="number" step="any" name="trade_offer[]"  id="trade_offer{{$i}}"  value="{{ $lineItem->trade_offer ?? '0.00'  }}" class="form-control qty trade_offer_num" readonly>
                                        </td>
                                        <td>
                                            <!-- discounts -->
                                            <input type="number" step="any" name="discount[]"  id="discount{{$i}}"  value="{{ $lineItem->discount ?? '0.00'  }}" class="form-control qty cust_discount discount_num" readonly>
                                        </td>
                                        <td>
                                            <!-- value after discounts -->
                                            <input type="number" step="any" name="amount_after_discount[]"  id="val_after_discount{{$i}}"  value="{{ $lineItem->amount_after_discount ?? '0.00'  }}" class="form-control qty val_after_discount net_amount_num" readonly>
                                        </td>
                                        <td>
                                            <!-- adv payment-->
                                            <input type="hidden" id="adv_payment_hide{{$i}}">
                                            <input type="number" step="any" name="adv_payment[]"  id="adv_payment{{$i}}"  value="{{ $lineItem->adv_payment ?? '0.00'  }}" class="form-control qty" readonly>
                                        </td>
                                    </tr>
                                @php 
                                        
                                    } 
                                @endphp
                                </tbody>  
                                <script>                                    
                                    rowId = {{$i-1}};
                                </script>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="ml-auto">
                            <button class="btn btn-raised btn-success waves-effect" id="generate-bill" type="button">Apply Discounts & T/O</button>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-2">
                            <label for="">Total Products</label><br>
                            <label id="total_products_label" class="label_text">0.00</label> 
                        </div>
                        <div class="col-sm-2">
                            <label for="">Total Qty</label><br>
                            <label id="total_qty_label" class="label_text">0.00</label>
                        </div>
                        <div class="col-sm-2">
                            <label>Sales Tax</label><br>
                            <label id="total_sales_tax_label" class="label_text">0.00</label>
                        </div>
                        <div class="col-sm-2">
                            <label>FED</label><br>
                            <label id="total_fed_tax_label" class="label_text">0.00</label>
                        </div>
                        <div class="col-sm-2">
                            <label>Further Tax</label><br>
                            <label id="total_further_tax_label" class="label_text">0.00</label>
                        </div>
                        <div class="col-sm-2">
                            <label>Inclusive Value</label><br>
                            <label id="total_inclusive_value_label" class="label_text">0.00</label>
                        </div>
                        <div class="col-sm-3">
                            <label>Amount Before Discount</label><br>
                            <label id="total_before_discount_label" class="label_text">0.00</label>
                        </div>
                        <div class="col-sm-2">
                            <label>Trade Offer</label><br>
                            <label id="total_trade_label" class="label_text">0.00</label>
                        </div>
                        <div class="col-sm-2">
                            <label>Discounts</label><br>
                            <label id="total_discount_label" class="label_text">0.00</label>
                        </div>
                        <div class="col-sm-2">
                            <label>Net Amount</label><br>
                            <label id="total_net_amount_label" class="label_text">0.00</label>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-6">
                            <label for="fiscal_year">Remarks</label>
                            <div class="form-group" id="note">
                                <textarea name="remarks" maxlength="120" rows="4" class="form-control"  placeholder="">{{ $sale_order->remarks ?? '' }}</textarea>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label for="fiscal_year">Attachment</label>
                            <br>
                            @if($attachmentRecord ?? '')
                                <table>
                                @foreach($attachmentRecord as $attachment)
                                <tr>
                                    <td><button  type="button" class="btn btn-danger btn-sm" id="{{ $attachment->file }}" onclick="delattach(attachmentURL,this.id,token)"><i class="zmdi zmdi-delete"></i></button></td>
                                    <td><a target="_blank" href="{{asset('assets/attachments/'. $attachment->file)}}" download id="attachment">{{ $attachment->file }}  <input type="hidden" name="file" value="{{ $attachment->file }}"> </a></td>
                                </tr>
                                @endforeach
                                </table>
                            @endif
                            <input name="file" type="file" class="dropify"> 
                        </div>
                    </div>
                    <div class="row">                        
                        <div class="ml-auto">
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
//when is edit mode sale order this will set total value row
$(document).ready(function(){
    totalValues()
});
/*  When user Add product first chk that user input customer or not.if customer field is blank  don't add product  
    and give a alert to user to input customer */
function chkCustomer()
{
        
    if($('#customer_ID').val()==='')
    {
        alert('Plese First Add Customer');
    }
}

//this function send ajax call to server to get latest rate code for this customer and related product.This function call on blur of product.
function getLatestRateCode(row)
{ 
    console.log('row number'+row);
    var url="{{ url('applyRateCode') }}";
    $.post(url,{coa_id:$('#customer_ID').val(),prod_id:$('#item'+row+'_ID').val(),_token:token},function(data){
        console.log(data);
        $('#rate_code'+row).html('');
        data.map(function(list,i){
            //here we add all latest and old rate code of releted user into rate-code selection box
            var option='<option value="'+list.id+'">'+list.rate_num+'</option>';
            $('#rate_code'+row).append(option);
            //then we apply latest record and populate data to their respective fields
            if(i==0)
            {
                $('#rate_code'+row+' option[value='+list.id+']').prop("selected", true);
                setRowData(row,list);
            }
        });  
    });
}

// this function cal if user select any rate code from selection box 
function getRateCode(row)
{
    var url="{{ url('applyRateCode') }}";
    $.post(url,{coa_id:$('#customer_ID').val(),prod_id:$('#item'+row+'_ID').val(),code_id:$('#rate_code'+row).val(),_token:token},function(data){
        setRowData(row,data);
        qtyRateTotal(row);

    });
}

//this function set value in their respective fields  in a  row
function setRowData(row,data)
{
    $('#base_rate'+row).val(data.base_rate);
    $('#sales_tax'+row).val(data.gst_tax_amount);
    $('#fed'+row).val(data.fed_tax_amount);
    $('#further_tax'+row).val(data.others_tax_amount*$('#cust_stn').val());
    $('#inclusive_value'+row).val(data.amount_after_tax);
    $('#lu'+row).val(data.lu_margin_amount);
    $('#freight'+row).val(data.freight_amount);
    $('#other'+row).val(data.others_margin_amount);
    $('#rate'+row).val(data.amount_after_tax - data.lu_margin_amount - data.freight_amount - data.others_margin_amount);
    $('#fixed_margin'+row).val(data.fix_margin_amount);
    $('#before_discount'+row).val( $('#rate'+row).val() - data.fix_margin_amount);
    $('#adv_payment_hide'+row).val(data.advance_payment);

}

// this function wrk on the blur of qty order and rate code. qty is multiple with base rate,all taxes and all discounts
function qtyRateTotal(row)
{
    qty=$('#qty_ordered'+row).val();
    if(qty>0)
    {
        $('#base_rate'+row).val(qty * $('#base_rate'+row).val());
        $('#sales_tax'+row).val(qty * $('#sales_tax'+row).val());
        $('#fed'+row).val(qty * $('#fed'+row).val());
        $('#further_tax'+row).val(qty * $('#further_tax'+row).val());
        $('#inclusive_value'+row).val(qty * $('#inclusive_value'+row).val());
        $('#lu'+row).val(qty * $('#lu'+row).val());
        $('#freight'+row).val(qty * $('#freight'+row).val());
        $('#other'+row).val(qty * $('#other'+row).val());
        $('#rate'+row).val($('#inclusive_value'+row).val() - $('#lu'+row).val() - $('#freight'+row).val() - $('#other'+row).val());
        $('#fixed_margin'+row).val(qty * $('#fixed_margin'+row).val());
        $('#before_discount'+row).val( $('#rate'+row).val() - $('#fixed_margin'+row).val());
    }
}

//function apply trade offer if exist and return data
$('#generate-bill').on('click',function(){
    console.log('row count'+rowId);
    tradeOfferURL = "{{ url('getTradeOffer') }}";
    total_discount=0;
    total_trade=0;
    total_net_amount=0;
    cust_id=$('#customer_ID').val();
    cust_location=$('#cust_location').val();
    date=$('#date').val();
    
    /*
     * Remove Free row Class     
     */
    $('.skuRow').remove();
    $('.freeRows').remove();
    $('.product').each(function(i){
        i++
        item_id=$('#item'+i+'_ID').val();
        qty=getNum($('#qty_ordered'+i).val());
        rate=getNum($('#before_discount'+i).val());
        console.log('item :'+item_id+'cust_id :'+cust_id+'date :'+date+'qty'+qty+'rate'+rate+'cust location'+cust_location);
        $.post(tradeOfferURL,{item_id:item_id,cust_id:cust_id,date:date,qty:qty,rate:rate,cust_location:cust_location, _token:token},function(data){
            $('#trade_offer'+i).val(data.discount_amount);
            value_after_trade=$('#before_discount'+i).val() - data.discount_amount;
            total_trade+=data.discount_amount;
            $('#total_trade_label').html(total_trade);
            if(data.free_qty>0)
            {
                rowId++;
                var rw='<tr id="row'+rowId+'" class="rowData freeRows">'+
                    
                    '<td><button  type="button" class="btn btn-danger btn-sm" onclick="deleteRow(\'row'+rowId+'\');"><i class="zmdi zmdi-delete"></i></button></td>'+
                    '<td>'+
                        '<input type="text" name="item[]" id="item'+rowId+'" class="form-control"  value="'+data.free_sku+'" class="product">'+
                        '<input type="hidden" name="item_ID[]" id="item'+rowId+'_ID" class="product"  value="'+data.free_sku+'">'+
                    '</td>'+
                    '<td>'+
                        //unit
                        '<input type="text" name="unit[]"   class="form-control" value="TRAY">'+
                    '</td>'+
                    '<td>'+
                        '<input type="number" name="qty_stock[]" id="qty_order'+rowId+'"  class="form-control qty">'+
                    '</td>'+
                    '<td>'+
                        //qty ordered                        
                        '<input type="number" step="any" name="qty_ordered[]" id="qty_ordered'+rowId+'" value="'+data.free_qty+'" class="form-control qty qty-num">'+
                    '</td>'+
                    '<td>'+
                        //rate Code
                        '<input type="text" id="rate_code{{$i}}" class="form-control">'+
                        '<input type="hidden"  name="rate_code_id[]" id="rate_code{{$i}}" class="form-control" value="-1">'+
                    '</td>'+
                    '<td>'+
                        //base rate
                        '<input type="number" step="any" name="base_rate[]"  id="base_rate{{$i}}"  value="{{ $lineItem->base_rate ?? '0.00'  }}" class="form-control qty" readonly>'+
                    '</td>'+
                    '<td>'+
                        //Sales tax
                        '<input type="number" step="any" name="sales_tax[]"  id="sales_tax{{$i}}"  value="{{ $lineItem->sales_tax ?? '0.00'  }}" class="form-control qty tax sales_tax_num" readonly>'+
                    '</td>'+
                    '<td>'+
                        //FED
                        '<input type="number" step="any" name="fed[]"   class="form-control qty tax fed_tax_num" readonly>'+
                    '</td>'+
                    '<td>'+
                    //Further tax
                    '<input type="number" step="any" name="further_tax[]"  id="further_tax'+rowId+'"  value="0.00" class="form-control qty tax further_tax_num" readonly>'+
                '</td>'+
                '<td>'+
                    //inclusive value
                    '<input type="number" step="any" name="inclusive_value[]"  id="inclusive_value'+rowId+'" value="0.00"  class="form-control qty inclusive_num" readonly>'+
                '</td>'+
                '<td>'+
                    //L/U
                    '<input type="number" step="any" name="lu[]"  id="lu'+rowId+'"  value="0.00" class="form-control qty discount" readonly>'+
                '</td>'+
                '<td>'+
                    //Freight
                    '<input type="number" step="any" name="freight[]"  id="freight'+rowId+'" value="0.00" class="form-control qty discount" readonly>'+
                '</td>'+
                '<td>'+
                    //Other
                    '<input type="number" step="any" name="other_discount[]"  id="other'+rowId+'" value="0.00" class="form-control qty discount" readonly>'+
                '</td>'+
                '<td>'+
                    //Rate
                    '<input type="number" step="any" name="rate[]"  id="rate'+rowId+'" value="0.00" class="form-control qty" readonly>'+
                '</td>'+
                '<td>'+
                    //Fixed margin
                    '<input type="number" step="any" name="fixed_margin[]"  id="fixed_margin'+rowId+'" value="0.00" class="form-control qty discount" readonly>'+
                '</td>'+
                '<td>'+
                    //value befor discount 
                    '<input type="number" step="any" name="amount_before_discount[]"  id="before_discount'+rowId+'" value="0.00" class="form-control qty discount before_discount_num" readonly>'+
                '</td>'+
                '<td>'+
                    //Trade offer
                    '<input type="number" step="any" name="trade_offer[]"  id="trade_offer'+rowId+'" value="0.00" class="form-control qty" readonly>'+
                '</td>'+
                '<td>'+
                    //discounts
                    '<input type="number" step="any" name="discount[]"  id="discount'+rowId+'"  value="0.00" class="form-control qty cust_discount customer_discount_num" readonly>'+
                '</td>'+
                '<td>'+
                    //value after discount
                    '<input type="number" step="any" name="amount_after_discount[]"  id="val_after_discount'+rowId+'"  value="0.00" class="form-control qty val_after_discount net_amount_num" readonly>'+
                '</td>'+
                '<td>'+
                    //adv payment
                    '<input type="hidden" id="adv_payment_hide'+rowId+'">'+
                    '<input type="number" step="any" name="adv_payment[]"  id="adv_payment'+rowId+'"  value="0.00" class="form-control qty" readonly>'+
                '</td>'+    
            '</tr>';
                $('#voucher').append(rw);  
            }
           customerDiscount(i,value_after_trade);
        });
        
    });
    totalValues();
});

function customerDiscount(row,valu_after_trade)
{
    customer_discounts=0;
    net_Amount_after_cust_discount=0;
    console.log(valu_after_trade);
    $.post("{{url('customerDiscount') }}",{date:$('#date').val(),coa_id:$('#customer_ID').val(),_token : "{{ csrf_token()}}"},function(data){
            if(data==0)
            {
                customer_discounts=0;
                $('#discount'+row).val(customer_discounts);
                net_Amount_after_cust_discount=valu_after_trade - customer_discounts;
                $('#val_after_discount'+row).val(net_Amount_after_cust_discount);
            }
            else
            {
                //if(data.amount_discount_percent===null || parseFloat(data.amount_discount_percent)==0)
                if(parseFloat(data.amount_discount_percent)>0)
                {  
                   customer_discounts=$('#before_discount'+row).val() * data.amount_discount_percent / 100;
                    $('#discount'+row).val(customer_discounts);
                    net_Amount_after_cust_discount=valu_after_trade - customer_discounts;
                    $('#val_after_discount'+row).val(net_Amount_after_cust_discount);
                }
                else if(parseFloat(data.qty_discount)>0 )
                {
                     customer_discounts=($('#qty_ordered'+row).val() * data.qty_discount);
                    $('#discount'+row).val(customer_discounts); 
                    net_Amount_after_cust_discount= valu_after_trade -  customer_discounts;
                    $('#val_after_discount'+row).val(net_Amount_after_cust_discount);
                    
                }
            }

            if( $('#cust_balance').val() >  net_Amount_after_cust_discount)
            {
                const [adv_payment_discount,net_amount_after_adv_payment]=applyAdvPaymentDiscount(row, net_Amount_after_cust_discount);
                customer_discounts+=adv_payment_discount;
                net_Amount_after_cust_discount+=net_amount_after_adv_payment;
            }
        total_discount+=customer_discounts;
        total_net_amount+=net_Amount_after_cust_discount
        $('#total_discount_label').html(total_discount);
        $('#total_net_amount_label').html(total_net_amount);
    });
    
}
function applyAdvPaymentDiscount(row,val_Aft_cus_dis)
{
    adv_payment_discount=val_Aft_cus_dis * $('#adv_payment_hide'+row).val() / 100 ;
    net_Amount=val_Aft_cus_dis - adv_payment_discount;
    $('#val_after_discount'+row).val(net_Amount);
    $('#adv_payment'+row).val(adv_payment_discount);
    return [adv_payment_discount,net_Amount];
}

function totalValues()
{
    $('#total_qty_label').html(sumAll('qty-num'));
    $('#total_sales_tax_label').html(sumAll('sales_tax_num'));
    $('#total_fed_tax_label').html(sumAll('fed_tax_num'));
    $('#total_further_tax_label').html(sumAll('further_tax_num'));
    $('#total_further_tax_label').html(sumAll('further_tax_num'));
    $('#total_inclusive_value_label').html(sumAll('inclusive_num'));
    $('#total_before_discount_label').html(sumAll('before_discount_num'));
    $('#total_discount_label').html(sumAll('discount_num'));
    $('#total_net_amount_label').html(sumAll('net_amount_num'));
    var numItems = $('.product').length;
    $('#total_products_label').html(numItems);
    $('#total_trade_label').html(sumAll('trade_offer_num'));
}
function checkStock(row)
{
    
    if(parseFloat($('#qty_ordered'+row).val()) > parseFloat($('#qty_stock'+row).val()))
    {
        $('#error'+row).html('we have not such amount of qty in stock');
        $("#save").attr("disabled", true);
    }
    else
    {
        $('#error'+row).html('');
        $("#save").attr("disabled", false);
    }   
}

$("#form").submit(function(event) {
    
    $('.qty_ordered').each(function(i){
       ++i;
       if(parseFloat($('#qty_ordered'+i).val()) > parseFloat($('#qty_stock'+i).val()))
       {
            event.preventDefault();
            $('#error'+i).html('we have not such amount of qty in stock');
       }

    });
});
</script>
@stop