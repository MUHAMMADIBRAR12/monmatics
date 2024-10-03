@extends('layout.master')
@section('title', 'GIN-Sales')
@section('parentPageTitle', 'Inventory')
@section('parent_title_icon', 'zmdi zmdi-home')
@section('page-style')

<?php  use App\Libraries\appLib; ?>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<!--<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">-->
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="{{asset('public/assets/js/sw.js')}}"></script>
<link rel="stylesheet" href="{{asset('public/assets/plugins/dropify/css/dropify.min.css')}}" />
<style>
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
    var customerURL = "{{ url('customerSearch') }}";
var token =  "{{ csrf_token()}}";
var ItemURL = "{{ url('itemSearch') }}";
var getItemDetailURL = "{{ url('getItemDetail') }}";
var TaxRateURL = "{{ url('getTaxRates') }}";
var rowId=1;
var attachmentURL = "{{ url('attachmentDelete') }}";

function addRow()
{
    let warehouse=$('#warehouse').val();
    if(warehouse!=='')
    {
    rowId++;
    var row = '<tr id="row'+rowId+'">'+
                 '<td><button  type="button" class="btn btn-danger btn-sm" onclick="deleteRow(\'row'+rowId+'\');"><i class="zmdi zmdi-delete"></i></button></td>'+
                 '<td><input type="text" name="item[]" id="item'+rowId+'" class="form-control autocomplete" onkeyup="autoFill(this.id, ItemURL, token)" onblur="getItemDetail('+rowId+');" value="" required autocomplete="off">'+
                 '    <input type="hidden" name="item_ID[]" id="item'+rowId+'_ID" required></td>'+
                 '<td class="description"><input type="text" name="description[]" id="description'+rowId+'" class="form-control"></td>'+
                 '<td class="qty_in_stock"><input type="number" name="qty_in_stock[]" id="qty_in_stock'+rowId+'"  class="form-control qty" readonly></td>'+
                 '<td class="qty_order"><input type="number" name="qty_order[]" id="qty_order'+rowId+'"  class="form-control qty" required></td>'+
                 '<td class="rate"><input type="number" name="rate[]" id="rate'+rowId+'"  class="form-control qty" required></td>'+
                 '<td class="amount"><input type="number" name="amount[]" id="amount'+rowId+'"  class="form-control qty" required></td>'+
                 '<td class="required_date"><input type="date" name="required_date[]" id="required_date'+rowId+'" class="form-control"></td>'+
                 '<td class="packing_detail"><input type="text" name="packing_detail[]" id="packing_detail'+rowId+'"    class="form-control"></td>'+
             '</tr>';
     $('#voucher').append(row);
    }
    else
    {
        alert('fist Select warehouse');
    }
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
        $('#reorder'+number).val(data.reorder); 
        (data.qty_in_stock? stock=data.qty_in_stock : stock=0)
        $('#qty_in_stock'+number).val(stock);  
        
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
            <form method="post" action="{{url('Inventory/GinSalesSave')}}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="hidden" name="id" id="id" value="{{ $gin->id ?? ''  }}">
                <div class="row">
                    <div class="col-lg-3 col-md-6">
                        <label for="fiscal_year">GIN #</label><br>
                        <label for="fiscal_year">{{ $gin->month ?? '' }}-{{appLib::padingZero($gin->number ??
                            '')}}</label>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="date">Date</label>
                        <div class="form-group">
                            <input type="date" name="date" id='vdate' class="form-control"
                                value="{{ $gin->date ?? date('Y-m-d')  }}" required>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="fiscal_year">Warehouse</label>
                        <div class="form-group">
                            <select name="warehouse" id="warehouse" class="form-control show-tick ms select2"
                                data-placeholder="Select" required>
                                <option value="">Select Warehouse</option>
                                @foreach($warehouses as $warehouse)
                                <option value="{{$warehouse->id}}" {{ ( $warehouse->id == ( $gin->warehouse ??'')) ?
                                    'selected' : '' }} >{{$warehouse->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="">Customer</label>
                        <input type="text" name="customer" id="customer" value="{{ $gin->name ?? '' }}"
                            placeholder="Customer Name" class="form-control"
                            onkeyup="autoFill(this.id, customerURL, token)" autocomplete="off">
                        <input type="hidden" name="customer_ID" id="customer_ID" value="{{ $gin->cst_coa_id ?? '' }}"
                            required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-6">
                        <label for="fiscal_year">Sale Invoice</label>
                        <div class="form-group">
                            <select name="inv_num" id="inv_num" class="form-control show-tick ms select2"
                                data-placeholder="Select" required>
                                <option value="">Select Invoice No.</option>
                                @foreach($invoiceNumbers as $invoiceNumber)
                                <option value="{{$invoiceNumber->id}}" {{ ( $invoiceNumber->id == ( $gin->inv_id ??''))
                                    ? 'selected' : '' }}>{{$invoiceNumber->doc_number}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="fiscal_year">Date</label><br>
                        <label for="fiscal_year" id="inv_date">{{ $gin->inv_date ?? '' }}</label>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="fiscal_year">Delivery Order No.</label><br>
                        <input type="text" name="do_num" id="do_num" class="form-control">
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="fiscal_year">Status</label>
                        <div class="form-group">
                            <select name="status" class="form-control show-tick ms select2" data-placeholder="Select"
                                required>
                                <option value="">Select Status</option>
                                <option value="Completed" {{ (( $gin->status ??'') == 'Completed') ? 'selected' : ''
                                    }}>Completed</option>
                                <option value="Hold" {{ (( $gin->status ??'') == 'Hold') ? 'selected' : '' }}>Hold
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="table-responsive">
                        <table id="voucher" class="table table-striped m-b-0">
                            <thead>
                                <tr class="bg-purple">
                                    <th><button type="button" class="btn btn-primary" style="align:right"
                                            onclick="addRow();">+</button></th>
                                    <th class="table_header ">Product</th>
                                    <th class="table_header">Description</th>
                                    <th class="table_header table_header_100">Unit</th>
                                    <th class="table_header table_header_100">Qty in Stock</th>
                                    <th class="table_header table_header_100">Qty Issue</th>
                                    <th class="table_header table_header_100">Rate</th>
                                    <th class="table_header table_header_100">Amount</th>
                                    <th class="table_header table_header_100">Required By Date</th>
                                    <th class="table_header table_header_100">Packing Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $count = (isset($ginDetail))?count($ginDetail):1;

                                for($i=1;$i<=$count; $i++) { if(isset($ginDetail)) { $lineItem=$ginDetail[($i-1)]; }
                                    @endphp <tr id="row{{$i}}" class="rowData">
                                    <td><button type="button" class="btn btn-danger btn-sm"
                                            onclick="deleteRow('row{{$i}}');"><i class="zmdi zmdi-delete"></i></button>
                                    </td>
                                    <td class="autocomplete"><input type="text" name="item[]" id="item{{$i}}"
                                            onkeydown="checkWarehouse(this.id)"
                                            onkeyup="autoFill(this.id, ItemURL, token)" onblur="getItemDetail({{$i}});"
                                            value="{{ $lineItem->name ?? ''  }}" class="form-control autocomplete"
                                            required autocomplete="off">
                                        <input type="hidden" name="item_ID[]" id="item{{$i}}_ID"
                                            value="{{ $lineItem->prod_id ?? ''  }}" required>
                                        <input type="hidden" name="inv_detail_ID[]" id="inv_detail{{$i}}_ID"
                                            value="{{ $lineItem->inv_detail_id ?? ''  }}" required>
                                    </td>
                                    <td class="description"><input type="text" name="description[]"
                                            id="description{{$i}}" value="{{ $lineItem->description ?? ''  }}"
                                            class="form-control"></td>

                                    <td class="qty_in_stock"><input type="number" name="qty_in_stock[]"
                                            id="qty_in_stock{{$i}}" class="form-control qty" readonly></td>
                                    <td class="qty_issue"><input type="number" name="qty_issue[]" id="qty_issue{{$i}}"
                                            value="{{ $lineItem->qty_issue ?? ''  }}" class="form-control qty" required>
                                    </td>
                                    <td class="rate"><input type="number" name="rate[]" id="rate{{$i}}"
                                            value="{{ $lineItem->rate ?? ''  }}" class="form-control qty" required></td>
                                    <td class="amount"><input type="number" name="amount[]" id="amount{{$i}}"
                                            value="{{ $lineItem->amount ?? ''  }}" class="form-control qty" required>
                                    </td>
                                    <td class="required_date"><input type="date" name="required_date[]"
                                            id="required_date{$i}}" value="{{ $lineItem->required_by_date ?? '' }}"
                                            class="form-control"></td>
                                    <td class="packing_detail"><input type="text" name="packing_detail[]"
                                            id="packing_detail{{$i}}" value="{{ $lineItem->packing_detail ?? ''  }}"
                                            class="form-control"></td>
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
                            <textarea name="note" maxlength="120" rows="4" class="form-control no-resize"
                                placeholder="">{{ $gin->note ?? '' }}</textarea>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label for="fiscal_year">Attachment</label>
                        <br>
                        @if($attachmentRecord ?? '')
                        <table>
                            @foreach($attachmentRecord as $attachment)
                            <tr>
                                <td><button type="button" class="btn btn-danger btn-sm" id="{{ $attachment->file }}"
                                        onclick="delattach(attachmentURL,this.id,token)"><i
                                            class="zmdi zmdi-delete"></i></button></td>
                                <td><a target="_blank" href="{{asset('assets/attachments/'. $attachment->file)}}"
                                        download id="attachment">{{ $attachment->file }}</a></td>
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
    $(document).ready(function(){
    
// add detail through invoice number
$('#inv_num').on('change',function(){
    $('.rowData').html('');
     rowId=0;
    var inv_num=$(this).val();
    var url= "{{ url('Inventory/CustomerInvoice') }}";
    $.post(url,{inv_num:inv_num, _token:token},function(data){
        
        data.map(function(val,i){
           (val.qty_in_stock ? stock=val.qty_in_stock : stock=0)
            rowId++;
              var rw='<tr id="row'+rowId+'" class="rowData">'+
                '<input type="hidden" name="inv_detail_ID[]" id="inv_detail'+rowId+'_ID" required value="'+val.inv_detail_id+'"></td>'+
                '<td><button  type="button" class="btn btn-danger btn-sm" onclick="deleteRow(\'row'+rowId+'\');"><i class="zmdi zmdi-delete"></i></button></td>'+
                '<td><input type="text" name="item[]" id="item'+rowId+'" class="form-control autocomplete" onkeydown="checkWarehouse(this.id)" onkeyup="autoFill(this.id, ItemURL, token)" onblur="getItemDetail('+rowId+');" value="'+val.prod_name+'" required autocomplete="off">'+
                '<input type="hidden" name="item_ID[]" id="item'+rowId+'_ID" required value="'+val.prod_id+'"></td>'+
                '<td class="description"><input type="text" name="description[]" id="description'+rowId+'" class="form-control"  value="'+val.description+'"></td>'+
                '<td>'+
                    //sale unit
                    '<input type="text" name="sale_unit[]" id="sale_unit'+rowId+'" class="form-control" value="'+val.sale_unit+'"  readonly>'+
                '</td>'+ 
                 '<td class="qty_in_stock"><input type="number" name="qty_in_stock[]" id="qty_in_stock'+rowId+'" value="'+stock+'"  class="form-control qty" readonly></td>'+
                 '<td class="qty_issue"><input type="number" name="qty_issue[]" id="qty_issue'+rowId+'" value="'+val.qty_issue+'" class="form-control qty" required></td>'+
                 '<td class="rate"><input type="number" name="rate[]" id="rate'+rowId+'" value="'+val.rate+'" class="form-control qty" readonly></td>'+
                 '<td class="amount"><input type="number" name="amount[]" id="amount'+rowId+'" value="'+val.amount+'" class="form-control qty" readonly></td>'+
                 '<td class="required_date"><input type="date" name="required_date[]" id="required_date'+rowId+'" value="'+val.required_by+'" class="form-control"></td>'+
                 '<td class="packing_detail"><input type="text" name="packing_detail[]" id="packing_detail'+rowId+'"  value="'+val.instruction+'"  class="form-control"></td>'+
             '</tr>';
              $('#voucher').append(rw);
              $('#customer').val(val.customer);
              $('#customer_ID').val(val.customer_coa_id);
              $('#inv_date').html(val.inv_date);  
              $('#warehouse').val(val.warehouse_id);    
        }); 
    });
   
});
// add invoice numbers through customer
$("#customer").blur(function(){
    $('#inv_num').html('');
    $('#inv_num').append('<option>Select invoice No.</option>');
     rowId=0;
  var cst_coa_id=$('#customer_ID').val();
  var url= "{{ url('Inventory/CustomerInvoice') }}";
  $.post(url,{cst_coa_id:cst_coa_id, _token:token},function(data){
    data.map(function(val,i){
      var option='<option value="'+val.id+'">'+val.inv_num+'</option>';
      $('#inv_num').append(option);
    });
    
  });
  
});
});
</script>
@stop