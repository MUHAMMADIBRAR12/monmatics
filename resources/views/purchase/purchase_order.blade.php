@extends('layout.master')
@section('title', 'Purchase Order')
@section('parentPageTitle', 'Purchase')
@section('parent_title_icon', 'zmdi zmdi-home')
@section('page-style')

<?php  use App\Libraries\appLib; ?>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<!--<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">-->
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<link rel="stylesheet" href="{{asset('public/assets/plugins/dropify/css/dropify.min.css')}}"/>
<link rel="stylesheet" href="{{asset('public/assets/css/sw.css')}}"/>
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
var attachmentURL = "{{ url('prAttachmentDelete') }}";
var vendorURL= "{{ url('vendorSearch') }}";

function addRow()
{
    rowId++;
    
    var row ='<tr id="row'+rowId+'" class="rowData">'+
                '<td>'+
                    '<button  type="button" class="btn btn-danger btn-sm" onclick="deleteRow(\'row'+rowId+'\');"><i class="zmdi zmdi-delete"></i></button>'+
                '</td>'+
                '<td>'+
                    //product
                    '<input type="text" name="item[]" id="item'+rowId+'" class="form-control product-field" onkeydown="checkWarehouse(this.id)" onkeyup="autoFill(this.id, ItemURL, token)" onblur="getItemDetail('+rowId+');" value="" required>'+
                    '<input type="hidden" name="item_ID[]" id="item'+rowId+'_ID">'+
                '</td>'+
                '<td>'+
                    //detail
                    '<input type="text" name="detail[]" id="detail'+rowId+'" class="form-control field-width" readonly>'+
                '</td>'+
                '<td>'+
                    //description
                    '<input type="text" name="description[]" id="description'+rowId+'" class="form-control field-width">'+
                '</td>'+
                '<td>'+
                    //unit
                    '<input type="text" name="unit[]" id="unit'+rowId+'" class="form-control">'+
                '</td>'+
                '<td>'+
                    //Qty-order 
                    '<input type="number" step="any" name="qty[]" id="qty'+rowId+'"  class="form-control amount qty" value="{{ $lineItem->tax_amount ?? '0' }}"  onblur="rowCalculate('+rowId+')"  required >'+
                '</td>'+
                '<td>'+
                    //Rate
                    '<input type="number" step="any" name="rate[]" id="rate'+rowId+'" value="0.00" class="form-control amount qty" onblur="rowCalculate('+rowId+')" >'+
                '</td>'+
                '<td>'+
                    //amount
                    '<input type="text" name="amount[]" id="amount'+rowId+'" value="0.00"  class="form-control total_amount qty" onblur="sumAllFields();" readonly>'+
                '</td>'+
                '<td>'+
                    //discount
                    '<input type="number" step="any" name="discount[]" id="discount'+rowId+'" value="0.00"  class="form-control amount qty" onblur="rowCalculate('+rowId+')">'+
                '</td>'+
                '<td>'+
                    //discount amount
                    '<input type="text"  name="discount_amount[]" id="discount_amount'+rowId+'" value="0.00" class="form-control  discount_amount qty" onblur="sumAllFields();" readonly>'+
                '</td>'+
                '<td>'+
                    //Tax 
                    '<input type="number" step="any" name="sales_tax[]" id="sales_tax'+rowId+'" value="0.00"  class="form-control amount qty" onblur="rowCalculate('+rowId+')">'+
                     '<input type="hidden" name="sales_tax_amount[]" id="sales_tax'+rowId+'_id" class="total_sales_tax" > </td> '+
                '<td>'+
                    //Tax Amount
                    '<input type="text" step="any" name="further_tax[]" id="further_tax'+rowId+'" value="0.00"  class="form-control further_tax qty" onblur="sumAllFields();"  readonly>'+
                     '<input type="hidden" name="further_tax_amount[]" id="further_tax'+rowId+'_id" class="total_further_tax" >'+
                '</td>'+
                '<td>'+
                    //delivery charges
                    '<input type="number" step="any" name="delivery_charges[]" id="delivery_charges'+rowId+'" value="0.00" class="form-control amount qty total_delivery_charges" onblur="rowCalculate('+rowId+');unitRate('+rowId+')">'+
                '</td>'+
                '<td>'+
                    //net Amount
                    '<input type="number" step="any" name="net_amount[]" id="net_amount'+rowId+'" value="0.00"  class="form-control amount qty total_net_amount">'+
                '</td>'+
                '<td>'+
                    //required by date
                    '<input type="date" name="required_date[]" id="required_date'+rowId+'"  class="form-control">'+
                '</td>'+
                '<td>'+
                    //packing detail
                    '<input type="text" name="packing_detail[]" id="packing_detail'+rowId+'"  class="form-control field-width">'+
                '</td>'+
            '</tr>';
     $('#voucher').append(row);
}

function getItemDetail(number)
{
    itemId = $('#item'+number+'_ID').val();
    warehouseId=$('#warehouse').val();
    $.post("{{ url('getItemDetail') }}",{ id: itemId,warehouseId:warehouseId, _token : "{{ csrf_token()}}" }, function (data){
        $('#reorder'+number).val(data.reorder); 
        (data.qty_in_stock? stock=data.qty_in_stock : stock=0)
        $('#qty_in_stock'+number).val(stock);  
        $('#s_qty_in_stock'+number).val(getNum(stock/data.sale_opt_val,2));
        $('#description'+number).val(data.name);  
        $('#description_label'+number).html(data.description);  
        $('#unit_label'+number).html(data.sales_unit);  
        $('#unit'+number).val(data.sales_unit);
    });
}

</script>
@stop
@section('content')
    <div class="row clearfix">
        <div class="card col-lg-12">
            <div class="body">
                <form method="post" action="{{url('Purchase/PurchaseOrder/Add')}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $purchaseOrder->id ?? ''}}">
                    <div class="row">
                        <div class="col-lg-2 col-md-3">
                            <label for="fiscal_year">Purchase Order #</label><br>
                            <label for="fiscal_year">{{ $purchaseOrder->month ?? '' }}-{{appLib::padingZero($purchaseOrder->number  ?? '')}}</label>
                        </div>
                        <div class="col-lg-2 col-md-3">
                               <label for="po_type">P.O Type</label>
                                <select name="po_type" class="form-control show-tick ms select2" data-placeholder="Select" required>
                                   <option  value="">Select P.O Type</option>
                                   <option value="Import" {{ (( $purchaseOrder->po_type ??'') == 'Import') ? 'selected' : '' }}>Import</option>
                                   <option value="Local" {{ (( $purchaseOrder->po_type ??'') == 'Local') ? 'selected' : '' }}>Local</option>
                                   <option value="Project" {{ (( $purchaseOrder->po_type ??'') == 'Project') ? 'selected' : '' }}>Project</option>
                                </select> 
                        </div> 
                                           
                        <div class="col-lg-2 col-md-3">
                            <label for="fiscal_year">P.R No.</label>
                            <div class="form-group">
                                <select name="prNum" id="prNum" class="form-control show-tick ms select2" data-placeholder="Select">
                                   <option  value="">Select P.R No.</option>
                                   @foreach($purchaseRequestions as $purchaseRequestion)
                                     @php
                                     $prNum= ($purchaseRequestion->month ?? '').'-'.appLib::padingZero($purchaseRequestion->number  ?? '');
                                     @endphp
                                   <option value="{{$purchaseRequestion->id}}" {{ ( $purchaseRequestion->id == ( $purchaseOrder->pr_id ??'')) ? 'selected' : '' }}>{{$prNum}}</option>
                                   @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3">
                            <label for="date">Date</label>
                            <div class="form-group">
                                <input type="date" name="date" id='vdate'  class="form-control" value="{{ $purchaseOrder->date ?? date('Y-m-d')  }}"  required>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <label for="fiscal_year">Warehouse</label>
                                <div class="form-group">
                                <select name="warehouse" id="warehouse" class="form-control show-tick ms select2" data-placeholder="Select" required>
                                   <option  value="">Select Warehouse</option>
                                   
                                    @foreach($warehouses as $warehouse)
                                    <option value="{{$warehouse->id}}" {{ ( $warehouse->id == ( $purchaseOrder->warehouse ??'')) ? 'selected' : '' }}>{{$warehouse->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>     
                    </div> 
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <label for="">Vendor</label>
                            <input type="text" name="vendor" id="vendor" value="{{ $purchaseOrder->name ?? '' }}" placeholder="Vendor Name" class="form-control" onkeyup="autoFill(this.id, vendorURL, token)" required>
                            <input type="hidden" name="vendor_ID" id="vendor_ID" value="{{ $purchaseOrder->v_id ?? '' }}" required>
                        </div>
                        
                
                        <div class="col-lg-3 col-md-6">
                            <label for="fiscal_year">Status</label>
                            <div class="form-group">
                                <select name="status" class="form-control show-tick ms select2" data-placeholder="Select" required>
                                    
                                   <option  value="">Select Status</option>
                                    <option  value="Completed" {{ (( $purchaseOrder->status ??'') == 'Completed') ? 'selected' : '' }}>Completed</option>
                                    <option  value="Hold" {{ (( $purchaseOrder->status ??'') == 'Hold') ? 'selected' : '' }}>Hold</option>
                                </select>
                            </div>   
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="form-group">
                                <label for="fiscal_year">Currency</label>
                                <select name="currency" id="currency" class="form-control show-tick ms select2" data-placeholder="Select" onchange="getCurrencyRate(this.id, 'rate', CurrencyURL, token);">
                                    @foreach($currencies as $cur)
                                        <option value="{{ $cur->code }}" @if (@isset($oldCurrencyCode)&&$oldCurrencyCode==$cur->code) selected @endif>{{ $cur->code }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label for="purchaser">Purchaser</label> 
                            <input type="text" name="purchaser" id="purcahser" placeholder="Purchaser"  value="{{ $purchaseOrder->purchaser ?? '' }}" class="form-control"> 
                        </div>    
                        <div class="col-lg-3 col-md-6">
                            <label for="importance">Importance</label> 
                            <select name="importance" class="form-control show-tick ms select2" data-placeholder="Select" required>
                                <option  value="">Select Importance</option>
                                <option  value="High" {{ (( $purchaseOrder->importance ??'') == 'High') ? 'selected' : '' }}>High</option>
                                <option  value="Normal" {{ (( $purchaseOrder->importance ??'') == 'Normal') ? 'selected' : '' }}>Normal</option>
                                <option  value="Low" {{ (( $purchaseOrder->importance ??'') == 'Low') ? 'selected' : '' }}>Low</option>
                            </select>
                        </div>    
                    </div>                   
                    <div class="row">
                        <div class="table-responsive">
                            <table id="voucher" class="table table-striped m-b-0">
                                <thead>
                                    <tr class="bg-purple">
                                        <th><button type="button" class="btn btn-primary" style="align:right" onclick="addRow();" >+</button></th>
                                        <th class="table_header ">Product</th>
                                        <th class="table_header ">Details</th>
                                        <th class="table_header">Description</th>
                                        <th class="table_header">Unit</th>
                                        <th class="table_header table_header_100">Qty</th>
                                        <th class="table_header table_header_100">Rate</th>
                                        <th class="table_header table_header_100">Amount</th>
                                        <th class="table_header table_header_100">Discount %</th>
                                        <th class="table_header table_header_100">Discount Amount</th>
                                        <th class="table_header table_header_100">Tax %</th>
                                        <th class="table_header table_header_100">Tax Amount </th>
                                        <th class="table_header table_header_100">Delivery Charges</th>
                                        <th class="table_header table_header_100">Net Amount</th>
                                        <th class="table_header table_header_100">Required By date</th>
                                        <th class="table_header table_header_100">Packing Detail</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @php
                                $count = (isset($purchaseOrderDetails))?count($purchaseOrderDetails):1;
                               
                                for($i=1;$i<=$count; $i++)
                                { 
                                    
                                    if(isset($purchaseOrderDetails))
                                    {
                                        $lineItem = $purchaseOrderDetails[($i-1)];
                                        $amount=$lineItem->qty*$lineItem->rate;
                                        $sales_tax_amount=$lineItem->amount * $lineItem->tax_percent / 100;
                                        $further_tax_amount=$lineItem->amount * $lineItem->tax_amount / 100;
                                    }                                    
                                    @endphp                               
                                    <tr id="row{{$i}}" class="rowData">
                                        <td>
                                            <button  type="button" class="btn btn-danger btn-sm" onclick="deleteRow('row{{$i}}');"><i class="zmdi zmdi-delete"></i></button>
                                        </td>
                                        <td>
                                            <!-- Product -->
                                            <input type="text" name="item[]" id="item{{$i}}" onkeydown="checkWarehouse(this.id)"  onkeyup="autoFill(this.id, ItemURL, token)" onblur="getItemDetail({{$i}});" value="{{ $lineItem->name ?? ''  }}" class="form-control product-field" required>
                                            <input type="hidden" name="item_ID[]" id="item{{$i}}_ID" value="{{ $lineItem->prod_id ?? ''  }}" required>
                                        </td>
                                        <td>
                                            <!-- detail -->
                                            <input type="text" name="detail[]" id="detail{{$i}}" class="form-control field-width" value="{{ $lineItem->detail ?? ''  }}" readonly>
                                        <td>
                                            <!-- description -->
                                            <input type="text" name="description[]" id="description{{$i}}" class="form-control field-width" value="{{ $lineItem->description ?? ''  }}">
                                        </td>
                                        <td>
                                            <!-- unit -->
                                            <input type="text"  name="unit[]" id="unit{{$i}}" value="{{ $lineItem->unit ?? ''  }}" class="form-control"  readonly style="width:100px;">
                                        </td>
                                        <td>
                                            <!-- qty -->
                                            <input type="number" step="any"  name="qty[]" id="qty{{$i}}" value="{{  Str::currency($lineItem->qty ?? '0')  }}" class="form-control  qty" required onblur="rowCalculate({{$i}})" >
                                        </td>
                                        <td>
                                            <!-- Rate -->
                                            <input type="number" step="any" name="rate[]" id="rate{{$i}}"  value="{{  Str::currency($lineItem->rate ?? '0.00')  }}" class="form-control qty" onblur="rowCalculate({{$i}})">
                                        </td>
                                        <td>
                                            <!--  amount -->
                                            <input type="text" step="any" name="amount[]"  id="amount{{$i}}"  value="{{  Str::currency($amount ?? '0.00')  }}" class="form-control qty total_amount"  readonly >
                                        </td>
                                        <td>
                                            <!-- discount-->
                                            <input type="number" step="any" name="discount[]" step="0.01" id="discount{{$i}}"  value="{{   Str::currency($lineItem->discount ?? '0.00')  }}" onblur="rowCalculate({{$i}})" class="form-control qty">
                                        </td>
                                        <td>
                                            <!-- discount amount -->
                                            <input type="text" name="discount_amount[]" id="discount_amount{{$i}}"  value="{{  Str::currency($lineItem->discount_amount ?? '0.00') }}"   class="form-control qty discount_amount"  readonly>
                                        </td>
                                        <td>
                                            <!--  tax -->
                                            <input type="number" step="any" name="sales_tax[]"  id="sales_tax{{$i}}"  value="{{  Str::currency($lineItem->tax_percent ?? '0.00') }}" onblur="rowCalculate({{$i}})"  class="form-control qty">
                                           <input type="hidden" name="sales_tax_amount[]" id="sales_tax{{$i}}_id" class="total_sales_tax" value="{{$sales_tax_amount ?? '0.00'}}" >
                                        </td>
                                        <td>
                                            <!--  tax  Amount-->
                                            <input type="text" name="further_tax[]" id="further_tax{{$i}}"  value="{{  Str::currency($lineItem->tax_amount ?? '0.00') }}"  class="form-control qty further_tax" readonly>
                                           <input type="hidden" name="further_tax_amount[]" id="further_tax{{$i}}_id" class="total_further_tax" value="{{  Str::currency($further_tax_amount ?? '0.00')}}">
                                        </td>
                                        <td>
                                            <!-- delivery charges -->
                                            <input type="number" step="any" name="delivery_charges[]" id="delivery_charges{{$i}}"  value="{{  Str::currency($lineItem->delivery_charges ?? '0.00')  }}" onblur="rowCalculate({{$i}})"  class="form-control qty total_delivery_charges">
                                        </td>   
                                        <td>
                                            <!-- net amount -->
                                            <input type="number" step="any"  name="net_amount[]" id="net_amount{{$i}}"  value="{{  Str::currency($lineItem->net_amount ?? '0.00')  }}" class="form-control qty total_net_amount" readonly>
                                        </td>
                                        <td>
                                            <!-- required date -->
                                            <input type="date" name="required_date[]" id="required_date{{$i}}"  value="{{ $lineItem->required_by_date ?? '' }}" class="form-control">
                                        </td>
                                        <td>
                                            <!-- packing_detail -->
                                           <input type="text" name="packing_detail[]" id="packing_detail{{$i}}" class="form-control field-width"  value="{{ $lineItem->packing_detail ?? ''  }}">
                                        </td>
                                    </tr>
                                @php 
                                        
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
                        <div class="col-sm-6">
                            <label for="fiscal_year">Note</label>
                            <div class="form-group" id="note">
                                <textarea name="note" maxlength="120" rows="4" class="form-control"  placeholder="">{{ $purchaseOrder->note ?? '' }}</textarea>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="row ">
                                <div class="col-sm-6">
                                    <label for="">Gross Amount</label>
                                    <input type="text"   class="form-control" id="gross_amount" readonly>
                                </div>
                                <div class="col-sm-6">
                                    <label for="">discount</label>
                                    <input type="text"   class="form-control" id="total_discount" readonly>
                                </div>
                            </div>
                            <div class="row ">
                                <div class="col-sm-6">
                                    <label for="">Tax Amount</label>
                                    <input type="text"   class="form-control" id="total_sales_tax" readonly>
                                </div>
                                <div class="col-sm-6">
                                    <label for="">Delivery Charges</label>
                                    <input type="text"   class="form-control" id="total_delivery_charges" readonly>
                                </div>
                            </div>
                            <div class="row ">
                               
                            </div>
                            <div class="row ">
                                <div class="col-sm-6">
                                    <label for="">Net amount</label>
                                    <input type="text"   class="form-control" id="total_net_amount" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <label for="fiscal_year">Attachment</label>
                            <br>
                            @if($attachmentRecord ?? '')
                                <table>
                                @foreach($attachmentRecord as $attachment)
                                <tr>
                                    <td><button  type="button" class="btn btn-danger btn-sm" id="{{ $attachment->file }}" onclick="delattach(attachmentURL,this.id,token)"><i class="zmdi zmdi-delete"></i></button></td>
                                    <td><a target="_blank" href="{{asset('assets/attachments/'. $attachment->file)}}" download id="attachment">{{ $attachment->file }}</a></td>
                                </tr>
                                @endforeach
                                </table>
                            @endif
                            <input name="file" type="file" class="dropify"> 
                        </div>
                    </div>
                    <div class="row">                        
                        <div class="ml-auto">
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
sumAllFields();
function rowCalculate(num)
{
  //Amount
  var amount=getNum($('#qty'+num).val() * $('#rate'+num).val(),2);
  console.log('amount'+amount);
  $('#amount'+num).val(amount);
  
  //discount
  var discount=getNum($('#amount'+num).val() * $('#discount'+num).val() / 100,2);
  console.log('discount'+discount);
  $('#discount_amount'+num).val(discount);
  
  //Sales Tax
  var sales_tax=getNum($('#amount'+num).val() * $('#sales_tax'+num).val() / 100,2);
  console.log('sales_tax'+sales_tax);
  $('#further_tax'+num).val(sales_tax);
  
  //further Tax
//   var further_tax=getNum($('#amount'+num).val() * $('#further_tax'+num).val() / 100,4);
//   console.log('further_tax'+further_tax);
//   $('#further_tax'+num+'_id').val(further_tax);
  
  //Delivery Charges
  var delivery_charges=$('#delivery_charges'+num).val() === '' ? 0 : $('#delivery_charges'+num).val() ;
  console.log('delivery_charges'+delivery_charges);
  //Net Amount
  var net_amount=parseFloat(amount,4)-parseFloat(discount,4)+parseFloat(sales_tax,2)+parseFloat(delivery_charges,4);
  console.log('net_amount'+net_amount);
  $('#net_amount'+num).val(getNum(net_amount,2));
   
  //base_qty
  var base_qty=getNum($('#qty'+num).val() * $('#operator_value'+num).val());
  $('#base_qty'+num).val(base_qty);
  sumAllFields();
}

function sumAllFields()
{
    $('#gross_amount').val(sumAll('total_amount'));
    $('#total_discount').val(sumAll('discount_amount'));
    $('#total_sales_tax').val(sumAll('further_tax'));
    //$('#total_further_tax').val(sumAll('total_further_tax'));
    $('#total_delivery_charges').val(sumAll('total_delivery_charges'));
    $('#total_net_amount').val(sumAll('total_net_amount'));

}
/*
$(document).ready(function(){
$('#prNum').on('change',function(){
    $('.rowData').html('');
     rowId=0;
    var prNum=$(this).val();
    var base_qty;
    var url = "{{ url('Purchase/PrDetail')}}";
    $.post(url,{prNum:prNum, _token:token},function(data){

        data.map(function(val,i){
            $('option[value="'+val.warehouse+'"]').prop("selected", true);
            (val.qty_in_stock? stock=val.qty_in_stock : stock=0)
            //base qty
            base_qty=getNum(val.qty_ordered,2)* getNum(val.operator_value,2);
            rowId++;
              var rw='<tr id="row'+rowId+'" class="rowData">'+
                '<td><button  type="button" class="btn btn-danger btn-sm" onclick="deleteRow(\'row'+rowId+'\');"><i class="zmdi zmdi-delete"></i></button></td>'+
                '<td>'+
                    //product
                    '<input type="text" name="item[]" id="item'+rowId+'" class="form-control product-field"  onkeyup="autoFill(this.id, ItemURL, token)" onblur="getItemDetail('+rowId+');" value="'+val.prod_name+'" required autocomplete="off">'+
                    '<input type="hidden" name="item_ID[]" id="item'+rowId+'_ID" required value="'+val.prod_id+'">'+
                '</td>'+
                '<td>'+
                    //detail 
                    '<input type="text" name="detail[]" id="detail'+rowId+'" class="form-control field-width" value="'+val.details+'" readonly>'+
                '</td>'+
                '<td>'+
                    //description
                    '<input type="text" name="description[]" id="description'+rowId+'" class="form-control field-width" value="'+val.description+'">'+
                '</td>'+
                '<td>'+
                    //purchase unit
                    '<input type="text" name="unit[]" id="unit'+rowId+'" class="form-control" value="'+val.unit+'" style="width:100px" readonly>'+
                    //operator value
                    '<input type="hidden" name="operator_value[]" id="operator_value'+rowId+'" value="'+val.operator_value+'">'+ 
                '</td>'+
                '<td>'+
                    //Qty-order
                    '<input type="number" step="0.01" name="qty[]" id="qty'+rowId+'"  class="form-control qty" value="'+val.qty_ordered+'"  onblur="rowCalculate('+rowId+')"  required >'+
                    //base qty
                    '<input type="hidden" name="base_qty[]" id="base_qty'+rowId+'" value="'+val.base_qty+'">'+
                '</td>'+
                '<td>'+
                    //purchase Rate
                    '<input type="number" step="0.01" name="rate[]" id="rate'+rowId+'"  class="form-control qty" value="'+val.purchase_rate+'" onblur="rowCalculate('+rowId+')" >'+
                    //unit rate
                    '<input type="hidden" name="unit_rate[]" id="unit_rate'+rowId+'" value="'+getNum(val.amount/val.qty_ordered/val.operator_value,2)+'">'+
                '</td>'+
                '<td>'+
                    //purchase amount
                    '<input type="number" step="any" name="amount[]" id="amount'+rowId+'"  class="form-control total_amount qty" value="'+getNum(val.amount,2)+'" onblur="sumAllFields();" readonly>'+
                '</td>'+
                '<td>'+
                    //discount
                    '<input type="number" step="0.01" name="discount[]" id="discount'+rowId+'" value="0.00"  class="form-control qty" onblur="rowCalculate('+rowId+')">'+
                '</td>'+
                '<td>'+
                    //discount amount
                    '<input type="number" step="0.01" name="discount_amount[]" id="discount_amount'+rowId+'" value="0.00"  class="form-control discount_amount qty"  readonly>'+
                '</td>'+
                '<td>'+
                    //sales tax
                    '<input type="number" step="0.01" name="sales_tax[]" id="sales_tax'+rowId+'" value="0.00"  class="form-control qty" onblur="rowCalculate('+rowId+')">'+
                    '<input type="hidden" name="sales_tax_amount[]" id="sales_tax'+rowId+'_id" class="total_sales_tax" > </td> '+
                '<td>'+
                    //further tax
                    '<input type="number" step="0.01" name="further_tax[]" id="further_tax'+rowId+'" value="0.00"  class="form-control qty" onblur="rowCalculate('+rowId+')">'+
                    '<input type="hidden" name="further_tax_amount[]" id="further_tax'+rowId+'_id" class="total_further_tax" >'+
                '</td>'+
                '<td>'+
                    //delivery charges
                    '<input type="number" step="0.01" name="delivery_charges[]" id="delivery_charges'+rowId+'" value="0.00" class="form-control qty total_delivery_charges" onblur="rowCalculate('+rowId+')">'+
                '</td>'+
                '<td>'+
                    //net Amount
                    '<input type="number" step="0.01" name="net_amount[]" id="net_amount'+rowId+'" value="'+getNum(val.amount,2)+'" class="form-control qty total_net_amount">'+
                '</td>'+
                '<td>'+
                    //required by date
                    '<input type="date" name="required_date[]" id="required_date'+rowId+'"  class="form-control">'+
                '</td>'+
                '<td>'+
                    //packing detail
                    '<input type="text" name="packing_detail[]" id="packing_detail'+rowId+'" value="'+val.packing_detail+'" class="form-control field-width">'+
                '</td>'+
               
            
                sumAllFields();
             '</tr>';
              $('#voucher').append(rw);
                  
        });
       
    });
    
});

});
*/
</script>
@stop