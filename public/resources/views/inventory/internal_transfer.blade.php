@extends('layout.master')
@section('title', 'Internal Transfer')
@section('parentPageTitle', 'Inventory')
@section('parent_title_icon', 'zmdi zmdi-home')
@section('page-style')

<?php  use App\Libraries\appLib; ?>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<!--<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">-->
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
var vendorURL= "{{ url('vendorSearch') }}";

function addRow()
{
    rowId++;
    var row = '<tr id="row'+rowId+'">'+
                 '<td><button  type="button" class="btn btn-danger btn-sm" onclick="deleteRow(\'row'+rowId+'\');"><i class="zmdi zmdi-delete"></i></button></td>'+
                 '<td><input type="text" name="item[]" id="item'+rowId+'" class="form-control autocomplete" onkeyup="autoFill(this.id, ItemURL, token)" onblur="getItemDetail('+rowId+');" value="" required autocomplete="off">'+
                 '    <input type="hidden" name="item_ID[]" id="item'+rowId+'_ID" required></td>'+
                 '<td class="description"><input type="text" name="description[]" id="description'+rowId+'" class="form-control"></td>'+
                 '<td class="unit"><select name="unit[]" id="unit'+rowId+'" class="form-control">'+
                                  '<option  value="">Select unit</option>'+
                                  ' @foreach($units as $unit)'+
                                  '<option value="{{$unit->name}}">{{$unit->name}}</option>'+
                                  '@endforeach'+
                                  '</select></td>'+
                 '<td class=""></td>'+
                 '<td class="qty_transfer"><input type="number" name="qty_transfer[]" id="qty_transfer'+rowId+'"  class="form-control qty" value="{{ $lineItem->tax_amount ?? '' }}" required onblur="QtyRateAmountbyId(\'qty_transfer'+rowId+'\',\'rate'+rowId+'\',\'amount'+rowId+'\');"></td>'+
                 '<td class="rate"><input type="number" name="rate[]" id="rate'+rowId+'"  class="form-control qty" value="{{ $lineItem->tax_amount ?? ''  }}" required onblur="QtyRateAmountbyId(\'qty_transfer'+rowId+'\',\'rate'+rowId+'\',\'amount'+rowId+'\');"></td>'+
                 '<td class="amount"><input type="number" name="amount[]" id="amount'+rowId+'"  class="form-control qty" value="{{ $lineItem->tax_amount ?? '' }}" required></td>'+
                 '<td class="packing_detail"><input type="text" name="packing_detail[]" id="packing_detail'+rowId+'" class="form-control"></td>'+
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
    $.post("{{ url('getItemDetail') }}",{ id: itemId, _token : "{{ csrf_token()}}" }, function (data){
        $('#reorder'+number).val(data.reorder); 
        $('#rate'+number).val(data.purchase_price); 
        
    });
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
            
            @if(session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
            @endif
            
            <div class="body">
            <form method="post" action="{{url('Inventory/InternalTransferSave')}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" id="id" value="{{ $internalTransfer->id ?? ''  }}">
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <label for="fiscal_year">ITN #</label><br>
                            <label for="fiscal_year">{{ $internalTransfer->month ?? '' }}-{{appLib::padingZero($internalTransfer->number  ?? '')}}</label>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label for="date">Date</label>
                            <div class="form-group">
                                <input type="date" name="date" id='vdate'  class="form-control" value="{{ $internalTransfer->date ?? date('Y-m-d')  }}"  required>
                            </div>
                        </div>                        
                        <div class="col-lg-3 col-md-6">
                            <label for="fiscal_year">Status</label>
                            <div class="form-group">
                                <select name="status" class="form-control show-tick ms select2" data-placeholder="Select" required>
                                    <option value="">Select Status</option>
                                    <option value="completed" {{ (( $internalTransfer->status ?? '' ) == 'completed') ? 'selected' : '' }}>Completed</option>
                                    <option value="hold" {{ (( $internalTransfer->status ?? '' ) == 'hold') ? 'selected' : '' }}>Hold</option>
                                </select>
                            </div>
                            
                        </div>      
                    </div>  
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <label for="fiscal_year">From (Warehouse)</label>
                            <div class="form-group">
                                <select name="warehouse_from" class="form-control show-tick ms select2" data-placeholder="Select" required>
                                   <option  value="">Select Warehouse</option>
                                    @foreach($warehouses as $warehouse)
                                    <option value="{{$warehouse->name}}" {{ ( $warehouse->name == ( $internalTransfer->warehouse_from ??'')) ? 'selected' : '' }}>{{$warehouse->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div> 
                        <div class="col-lg-3 col-md-6">
                            <label for="fiscal_year">To (Warehouse)</label>
                            <div class="form-group">
                                <select name="warehouse_to" class="form-control show-tick ms select2" data-placeholder="Select" required>
                                   <option  value="">Select Warehouse</option>
                                    @foreach($warehouses as $warehouse)
                                    <option value="{{$warehouse->name}}" {{ ( $warehouse->name == ( $internalTransfer->warehouse_to ??'')) ? 'selected' : '' }}>{{$warehouse->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div> 
                    </div>               
                    <div class="row mt-1">
                        <div class="table-responsive">
                            <table id="voucher" class="table table-striped m-b-0">
                                <thead>
                                    <tr class="bg-purple">
                                        <th><button type="button" class="btn btn-primary" style="align:right" onclick="addRow();" >+</button></th>
                                        <th class="table_header ">Product</th>
                                        <th class="table_header">Description</th>
                                        <th class="table_header table_header_100">Unit</th> 
                                        <th class="table_header table_header_100">Qty in Stock</th> 
                                        <th class="table_header table_header_100">Qty Transfer</th>
                                        <th class="table_header table_header_100">Rate</th>
                                        <th class="table_header table_header_100">Amount</th>
                                        <th class="table_header table_header_100">Packing Detail</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @php
                                $count = (isset($internalTransferDetail))?count($internalTransferDetail):1;
                               
                                for($i=1;$i<=$count; $i++)
                                { 
                                    
                                    if(isset($internalTransferDetail))
                                    {
                                        $lineItem = $internalTransferDetail[($i-1)];
                                        
                                    }                                    
                                    @endphp                               
                                    <tr id="row{{$i}}" class="rowData">
                                        <td><button  type="button" class="btn btn-danger btn-sm" onclick="deleteRow('row{{$i}}');"><i class="zmdi zmdi-delete"></i></button></td>
                                        <td class="autocomplete"><input type="text" name="item[]" id="item{{$i}}" onkeyup="autoFill(this.id, ItemURL, token)" onblur="getItemDetail({{$i}});" value="{{ $lineItem->name ?? ''  }}" class="form-control autocomplete" required autocomplete="off">
                                            <input type="hidden" name="item_ID[]" id="item{{$i}}_ID" value="{{ $lineItem->prod_id ?? ''  }}" required>
                                        </td>
                                        <td class="description"><input type="text" name="description[]" id="description{{$i}}" value="{{ $lineItem->description ?? ''  }}" class="form-control"></td>
                                        <td class="unit">
                                        <select name="unit[]" class="form-control show-tick ms select2" data-placeholder="Select">
                                            <option  value="">Select unit</option>
                                            @foreach($units as $unit)
                                            <option value="{{$unit->name}}" {{ ( $unit->name == ( $lineItem->unit ??'')) ? 'selected' : '' }} >{{$unit->name}}</option>
                                            @endforeach
                                        </select>
                                        </td>
                                        <td></td>
                                        <td class="qty_transfer"><input type="number" name="qty_transfer[]" id="qty_transfer{{$i}}" value="{{ $lineItem->qty ?? ''  }}" class="form-control qty" required  onblur="QtyRateAmountbyId('qty_transfer{{$i}}','rate{{$i}}','amount{{$i}}');"></td>
                                        <td class="rate"><input type="number" name="rate[]" id="rate{{$i}}" value="{{ $lineItem->rate ?? ''  }}" class="form-control qty" required onblur="QtyRateAmountbyId('qty_transfer{{$i}}','rate{{$i}}','amount{{$i}}');"></td>
                                        <td class="amount"><input type="number" name="amount[]" id="amount{{$i}}" value="{{ $lineItem->amount ?? ''  }}" class="form-control qty" required></td>
                                        <td class="packing-detail"><input type="text" name="packing_detail[]" id="packing_detail{{$i}}"  value="{{ $lineItem->packing_detail ?? ''  }}"  class="form-control"></td>
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
                                <textarea name="note" maxlength="120" rows="4" class="form-control no-resize"  placeholder="">{{  $internalTransfer->note ?? '' }}</textarea>
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