@extends('layout.master')
@section('title', 'Purchase Requestion')
@section('parentPageTitle', 'Inventory')
@section('parent_title_icon', 'zmdi zmdi-home')
@section('page-style')

<?php  use App\Libraries\appLib; ?>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="{{asset('public/assets/css/sw.css')}}">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<link rel="stylesheet" href="{{asset('public/assets/plugins/dropify/css/dropify.min.css')}}"/>

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
var attachmentURL = "{{ url('attachmentDelete') }}";


function addRow()
{
    rowId++;
    var row ='<tr id="row'+rowId+'" class="rowData">'+
                '<td><button  type="button" class="btn btn-danger btn-sm" onclick="deleteRow(\'row'+rowId+'\');"><i class="zmdi zmdi-delete"></i></button></td>'+
                '<td>'+
                    //product
                    '<input type="text" name="item[]" id="item'+rowId+'" class="form-control product-field" onkeydown="checkWarehouse(this.id)" onkeyup="autoFill(this.id, ItemURL, token)" onblur="getItemDetail('+rowId+');" value="" required autocomplete="off">'+
                    '<input type="hidden" name="item_ID[]" id="item'+rowId+'_ID" required>'+
                '</td>'+
                '<td>'+
                    //description
                    '<textarea name="description[]" id="description'+rowId+'"  class="form-control desc-textarea no-resize"></textarea>'+
                '</td>'+

                '<td  class="text-center">'+
                    //purchase unit
                    '<label id="purchase_unit_label'+rowId+'"></label>'+
                    '<input type="hidden" name="purchase_unit[]" id="purchase_unit'+rowId+'">'+
                '</td>'+
                '<td  class="text-center">'+
                    //qty in stock
                    '<label id="qty_in_stock_label'+rowId+'"></label>'+
                    '<input type="hidden" name="qty_in_stock[]" id="qty_in_stock'+rowId+'">'+
                '</td>'+
                '<td class="text-center">'+
                    //Reorder
                    '<label id="reorder_label'+rowId+'"></label>'+
                    '<input type="hidden" name="reorder[]" id="reorder'+rowId+'">'+
                '</td>'+
                '<td>'+
                    //qty order
                    '<input type="number" step="any" name="qty_order[]" id="qty_order'+rowId+'"  class="form-control qty" required>'+
                '</td>'+
                '<td>'+
                    //required date
                    '<input type="date" name="required_date[]" id="required_date'+rowId+'"  class="form-control">'+
                '</td>'+
                '<td>'+
                    //packing detail
                    '<input type="text" name="packing_detail[]" id="packing_detail'+rowId+'" class="form-control packing">'+
                '</td>'+
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
function getItemDetail(number)
{
    itemId = $('#item'+number+'_ID').val();
    warehouseId=$('#warehouse').val();
    
    $.post("{{ url('getItemDetail') }}",{ id: itemId,warehouseId:warehouseId, _token : "{{ csrf_token()}}" }, function (data){
        (data.qty_in_stock? stock=data.qty_in_stock : stock=0) 
        $('#description'+number).html(data.name);  
        $('#purchase_unit_label'+number).html(data.purchase_unit);  
        $('#purchase_unit'+number).val(data.purchase_unit);
        $('#qty_in_stock_label'+number).html(stock/data.operator_value);   
        $('#qty_in_stock'+number).val(stock/data.operator_value); 
        $('#reorder_label'+number).html(data.reorder);    
        $('#reorder'+number).val(data.reorder);    
        $('#packing_detail'+number).val(data.packing_detail);    
    })
}

function getTaxAmount(taxName, index, token)
{
    taxName = $('#'+taxName).val();
    $.post(TaxRateURL,{ taxName: taxName, _token : token }, function (data){
        
        taxRate = getNum(data);        
        amount = getNum($('#amount'+index).val(),2);        
        taxAmount = percentVal(taxRate, amount, 2);        
        totalAmount = getNum(amount)+getNum(taxAmount);        
        $('#tax_amount'+index).val(taxAmount);    
        $('#total_amount'+index).val(totalAmount);
        $('#sub_total').val(sumAll('total_amount'));
       
    }); 
}

function qtyRateTotal(index)
{
    amount = getNum($('#qty'+index).val())*getNum($('#rate'+index).val());
    $('#amount'+index).val(getNum(amount,2));
    $('#total_amount'+index).val(getNum(amount,2));
    $('#sub_total').val(sumAll('total_amount'));
  
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
        
    }), json;
}
</script>

@stop
@section('content')
    <div class="row clearfix">
        <div class="card col-lg-12">
            <div class="body">
            <form method="post" action="{{url('Inventory/PurchaseRequistion/Add')}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $purchaseRequestion->id ?? ''  }}">
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <label for="fiscal_year">Purchase Requistion #</label>
                            <label for="fiscal_year">{{ $purchaseRequestion->month ?? '' }}-{{appLib::padingZero($purchaseRequestion->number  ?? '')}}</label>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label for="date">Date</label>
                            <div class="form-group">
                                <input type="date" name="date" id='vdate'  class="form-control" value="{{ $purchaseRequestion->date ?? date('Y-m-d')  }}"  required>
                            </div>
                        </div>                        
                        <div class="col-lg-3 col-md-6">
                            <label for="fiscal_year">Warehouse</label>
                            <div class="form-group">
                                <select name="warehouse" id="warehouse" class="form-control show-tick ms select2" data-placeholder="Select" required>
                                   <option  value="">Select Warehouse</option>
                                    @foreach($warehouses as $warehouse)
                                    <option value="{{$warehouse->id}}" {{ ( $warehouse->id == ( $purchaseRequestion->warehouse ??'')) ? 'selected' : '' }}>{{$warehouse->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label for="fiscal_year">Status</label>
                            
                            <div class="form-group">
                                <select name="status" class="form-control show-tick ms select2" data-placeholder="Select" required>
                                    
                                   <option  value="">Select Status</option>
                                    <option  value="Completed" {{ (( $purchaseRequestion->status ??'') == 'Completed') ? 'selected' : '' }}>Completed</option>
                                    <option  value="Hold" {{ (( $purchaseRequestion->status ??'') == 'Hold') ? 'selected' : '' }}>Hold</option>
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
                                        <th class="table_header ">Product</th>
                                        <th class="table_header">Description</th>
                                        <th class="table_header table_header_100">Unit</th>
                                        <th class="table_header table_header_100">Qty in stock</th>
                                        <th class="table_header table_header_100">Reorder Level</th>
                                        <th class="table_header table_header_100">Qty Order</th>
                                        <th class="table_header table_header_100">Required By date</th>
                                        <th class="table_header table_header_100">Packing Detail</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @php
                                $count = (isset($purchaseRequestionDetails))?count($purchaseRequestionDetails):1;
                               
                                for($i=1;$i<=$count; $i++)
                                { 
                                    
                                    if(isset($purchaseRequestionDetails))
                                    {
                                        $lineItem = $purchaseRequestionDetails[($i-1)];
                                        
                                    }                                    
                                    @endphp                               
                                    <tr id="row{{$i}}" class="rowData">
                                        <td><button  type="button" class="btn btn-danger btn-sm" onclick="deleteRow('row{{$i}}');"><i class="zmdi zmdi-delete"></i></button></td>
                                        <td>
                                            <!-- product -->
                                            <input type="text" name="item[]" id="item{{$i}}" onkeydown="checkWarehouse(this.id)" onkeyup="autoFill(this.id, ItemURL, token)" onblur="getItemDetail({{$i}});" value="{{ $lineItem->name ?? ''  }}" class="form-control product-field" required>
                                            <input type="hidden" name="item_ID[]" id="item{{$i}}_ID" value="{{ $lineItem->prod_id ?? ''  }}">
                                        </td>
                                        <td>
                                            <!-- description -->
                                            <textarea name="description[]" id="description{{$i}}"  class="form-control desc-textarea no-resize">{{ $lineItem->description ?? ''  }}</textarea>
                                        </td>
                                        <td class="text-center">
                                            <!-- purchase unit -->
                                            <label id="purchase_unit_label{{$i}}">{{ $lineItem->unit ?? ''  }}</label>
                                            <input type="hidden" name="purchase_unit[]" id="purchase_unit{{$i}}" value="{{ $lineItem->unit ?? ''  }}">
                                        </td>
                                        <td class="text-center">
                                            <!-- qty in stock -->
                                            <label id="qty_in_stock_label{{$i}}">{{ $lineItem->qty_in_stock ?? ''  }}</label>
                                            <input type="hidden" name="qty_in_stock[]" id="qty_in_stock{{$i}}" value="{{ $lineItem->qty_in_stock ?? ''  }}">
                                        </td>
                                        <td class="text-center">
                                            <!-- Reorder -->
                                            <label id="reorder_label{{$i}}">{{$lineItem->reorder_level ?? ''}}</label>
                                            <input type="hidden" name="reorder[]" id="reorder{{$i}}" value="{{ $lineItem->reorder_level ?? ''  }}">
                                        </td>
                                        <td>
                                            <!-- qty order -->   
                                            <input type="number" step="any" name="qty_order[]" id="qty_order{{$i}}" value="{{ $lineItem->qty_ordered ?? ''  }}" class="form-control qty" required>
                                        </td>
                                        <td>
                                            <!-- required date -->   
                                            <input type="date" name="required_date[]" id="required_date{{$i}}"  value="{{ $lineItem->required_by_date ?? ''  }}" class="form-control ">
                                        </td>
                                        <td>
                                             <!-- packing detail -->  
                                            <input type="text" name="packing_detail[]" id="packing_detail{{$i}}"  value="{{ $lineItem->packing_detail ?? ''  }}"  class="form-control packing">
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
                                <textarea name="note" maxlength="120" rows="4" class="form-control no-resize"  placeholder="">{{ $purchaseRequestion->note ?? '' }}</textarea>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label for="fiscal_year">Attachment</label>
                            <br>
                            @if($purchaseRequestionAttachment ?? '')
                                <table>
                                @foreach($purchaseRequestionAttachment as $attachment)
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

@stop