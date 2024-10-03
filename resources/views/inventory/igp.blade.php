@extends('layout.master')
@section('title', 'Inward Gate Pass')
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
/*
function addRow()
{
    rowId++;
    var row = '<tr id="row'+rowId+'">'+
                '<td><button  type="button" class="btn btn-danger btn-sm" onclick="deleteRow(\'row'+rowId+'\');"><i class="zmdi zmdi-delete"></i></button></td>'+
                '<td>'+
                    //product
                    '<input type="text" name="item[]" id="item'+rowId+'" class="form-control autocomplete" onkeyup="autoFill(this.id, ItemURL, token)" onblur="getItemDetail('+rowId+');" value="" required autocomplete="off">'+
                    '<input type="hidden" name="item_ID[]" id="item'+rowId+'_ID" required>'+
                '</td>'+
                '<td>'+
                    //description
                    '<input type="text" name="description[]" id="description'+rowId+'" class="form-control">'+
                '</td>'+
                '<td>'+
                    //unit
                    '<input type="text" name="unit" id="unit'+rowId+'" class="form-control" readonly>'+
                '</td>'+
                '<td>'+
                    '<input type="number" name="qty_order[]" id="qty_order'+rowId+'"  class="form-control qty" required>'+
                '</td>'+
                 '<td class="qty_received"><input type="number" name="qty_received[]" id="qty_received'+rowId+'" onblur="checkQty('+rowId+')"  class="form-control qty" required></td>'+
                 '<td><input type="text" name="packing_detail[]" id="packing_detail'+rowId+'" class="form-control"></td>'+
             '</tr>';
     $('#voucher').append(row);
}
   
*/

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
@section('content')

    <div class="row clearfix">
        <div class="card col-lg-12">
            <div class="body">
            <form method="post" action="{{url('Inventory/IGP/Add')}}" enctype="multipart/form-data" id="igp_form">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" id="id" value="{{ $igp->id ?? ''  }}">
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <label for="fiscal_year">IGP #</label><br>
                            <label for="fiscal_year">{{ $igp->month ?? '' }}-{{appLib::padingZero($igp->number  ?? '')}}</label>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label for="date">Date</label>
                            <div class="form-group">
                                <input type="date" name="date" id='vdate'  class="form-control" value="{{ $igp->date ?? date('Y-m-d')  }}"  required>
                            </div>
                        </div>                        
                        <div class="col-lg-3 col-md-6">
                            <label for="fiscal_year">Warehouse</label>
                            <div class="form-group">
                                <select name="warehouse" class="form-control show-tick ms select2" data-placeholder="Select" required>
                                   <option  value="">Select Warehouse</option>
                                    @foreach($warehouses as $warehouse)
                                    <option value="{{$warehouse->id}}" {{ ( $warehouse->id == ( $igp->warehouse ??'')) ? 'selected' : '' }}>{{$warehouse->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label for="">Vendor</label>
                            <input type="text" name="vendor" id="vendor" value="{{ $igp->ven_name ?? '' }}" placeholder="Vendor Name" class="form-control" onkeyup="autoFill(this.id, vendorURL, token)">
                            <input type="hidden" name="vendor_ID" id="vendor_ID" value="{{ $igp->ven_id ?? '' }}" required>
                        </div>      
                    </div>  
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <label for="fiscal_year">P.O No.</label><br>
                            @if(isset($igp))
                            <label>{{($igp->po_month ?? '').'-'.appLib::padingZero($igp->po_number  ?? '')}}</label>
                            @else
                            <div class="form-group">
                                <select name="poNum" id="poNum" class="form-control show-tick ms select2" data-placeholder="Select">
                                   <option  value="">Select P.O No.</option>
                                   @foreach($purchaseOrders as $purchaseOrder)
                                    <option value="{{$purchaseOrder->id}}" {{ ( $purchaseOrder->id == ( $igp->po_id ??'')) ? 'selected' : '' }}>{{$purchaseOrder->doc_number}}</option>
                                   @endforeach
                                </select>
                            </div>
                            @endif
                        </div>
                        <div class="col-lg-3 col-md-6">
                           <label for="fiscal_year">P.O Date</label><br>
                           <label for="fiscal_year" id="po_date">{{ $igp->po_date ?? '' }}</label>
                        </div> 
                        <div class="col-lg-3 col-md-6">
                            <label for="fiscal_year">D.O Ref</label>
                            <input type="text" name="do_ref" id="do_ref" class="form-control" value="{{ $igp->do_ref ?? '' }}">
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label for="fiscal_year">Status</label>
                            <div class="form-group">
                                <select name="status" class="form-control show-tick ms select2" data-placeholder="Select" required>
                                    
                                   <option  value="">Select Status</option>
                                    <option  value="Completed" {{ (( $igp->status ??'') == 'Completed') ? 'selected' : '' }}>Completed</option>
                                    <option  value="Hold" {{ (( $igp->status ??'') == 'Hold') ? 'selected' : '' }}>Hold</option>
                                </select>
                            </div>   
                        </div>  
                    </div> 
                    <div class="row">
                        <div class="col-md-3">
                            <label for="">Veh Type & Number</label>
                            <div class="form-group">
                                <input type="text" name="veh_type" id="veh_type" class="form-control" value="{{$igp->veh_type ?? ''}}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="">Delivery Man</label>
                            <div class="form-group">
                                <input type="text" name="delivery_man" id="delivery_man" class="form-control" value="{{$igp->delivery_man ?? ''}}">
                            </div>
                        </div>
                    </div>              
                    <div class="row mt-1">
                        <div class="table-responsive">
                            <table id="voucher" class="table table-striped m-b-0">
                                <thead>
                                    <tr class="bg-purple">
                                        <th><button type="button" class="btn btn-primary" style="align:right;display: none" onclick="addRow();" >+</button></th>
                                        <th class="table_header ">Product</th>
                                        <th class="table_header">Description</th>
                                        <th class="table_header table_header_100">Unit</th>     
                                        <th class="table_header table_header_100">Qty Order</th>
                                        <th class="table_header table_header_100">Qty Received</th>
                                        <th class="table_header table_header_100">Packing Detail</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @php
                                $count = (isset($igpDetails))?count($igpDetails):1;
                               
                                for($i=1;$i<=$count; $i++)
                                { 
                                    
                                    if(isset($igpDetails))
                                    {
                                        $lineItem = $igpDetails[($i-1)];
                                        
                                    }                                    
                                    @endphp                               
                                    <tr id="row{{$i}}" class="rowData">
                                        <!-- base rate -->
                                        <input type="hidden" name="base_rate[]" id="base_rate{{$i}}" value="{{ $lineItem->base_rate ?? ''  }}">
                                        <!--po detail id -->
                                        <input type="hidden" name="po_detail_id[]" id="po_detail_id{{$i}}" value="{{ $lineItem->po_detail_id ?? ''  }}">
                                        
                                        <td><button  type="button" class="btn btn-danger btn-sm" onclick="deleteRow('row{{$i}}');" style="display:none"><i class="zmdi zmdi-delete"></i></button></td>
                                        <td>
                                            <!-- product -->
                                            <input type="text" name="item[]" id="item{{$i}}" onkeyup="autoFill(this.id, ItemURL, token)" onblur="getItemDetail({{$i}});" value="{{ $lineItem->name ?? ''  }}" class="form-control autocomplete" required autocomplete="off">
                                            <input type="hidden" name="item_ID[]" id="item{{$i}}_ID" value="{{ $lineItem->prod_id ?? ''  }}">
                                        </td>
                                        <td>
                                            <!-- description -->
                                            <input type="text" name="description[]" id="description{{$i}}" value="{{ $lineItem->description ?? ''  }}" class="form-control">
                                        </td>
                                        <td>
                                            <!--  unit -->
                                            <input type="text" name="unit[]" id="unit{{$i}}" class="form-control" value="{{ $lineItem->unit ?? ''  }}" readonly>
                                            <!-- purcahse rate -->
                                            <input type="hidden" name="rate[]" value="{{ $lineItem->rate ?? ''  }}">
                                        </td>
                                        <td>
                                            <!-- qty order -->
                                            <input type="number" step="any" name="qty_order[]" id="qty_order{{$i}}" value="{{ $lineItem->qty_order ?? ''  }}" class="form-control qty" required>
                                        </td>
                                        <td>
                                            <!-- qty received -->
                                            <input type="number" name="qty_received[]" step="any" id="qty_received{{$i}}" value="{{ $lineItem->qty_received ?? ''  }}" onblur="checkQty({{$i}})"  class="form-control">
                                            <span class="text-danger font-weight-bold" id="error_msg{{$i}}"></span>
                                        </td>
                                        <td>
                                            <!-- packing detail -->
                                            <input type="text" name="packing_detail[]" id="packing_detail{{$i}}"  value="{{ $lineItem->packing_detail ?? ''  }}"  class="form-control">
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
                                <textarea name="note" maxlength="120" rows="4" class="form-control no-resize"  placeholder="">{{  $igp->note ?? '' }}</textarea>
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
                            <button class="btn btn-raised btn-primary waves-effect" type="submit" id="submit-btn">Save</button>
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
// add purchase order details through purchase order number
$('#poNum').on('change',function(){
    $('.rowData').html('');
     rowId=1;
    var poNum=$(this).val();
    var itemTotal;
    var url = "{{ url('Inventory/aj_PoDetail')}}";
    $.post(url,{poNum:poNum, _token:token},function(data){
        data.map(function(val,i){
            if(val.qty_received==val.qty)
            {

            }
            else
            {
            itemTotal = QtyRateAmount(val.qty_ordered, val.purchase_price);
            rowId++;
              var rw='<tr id="row'+rowId+'" class="rowData">'+
                //base rate
                '<input type="hidden" name="base_rate[]" id="base_rate'+rowId+'" value="'+val.base_rate+'">'+
                //po detail id
                '<input type="hidden" name="po_detail_id[]" id="po_detail_id'+rowId+'" value="'+val.id+'">'+
                '<td><button  type="button" class="btn btn-danger btn-sm" onclick="deleteRow(\'row'+rowId+'\');"><i class="zmdi zmdi-delete"></i></button></td>'+
                '<td>'+
                    //product
                    '<input type="text" name="item[]" id="item'+rowId+'" class="form-control autocomplete" onkeyup="autoFill(this.id, ItemURL, token)" onblur="getItemDetail('+rowId+');" value="'+val.name+'" required autocomplete="off">'+
                    '<input type="hidden" name="item_ID[]" id="item'+rowId+'_ID" required value="'+val.prod_id+'">'+
                '</td>'+
                '<td>'+
                    //description
                    '<input type="text" name="description[]" id="description'+rowId+'" class="form-control" value="'+val.description+'">'+
                '</td>'+
                '<td>'+
                    //unit
                    '<input type="text" name="unit[]" id="unit'+rowId+'" class="form-control" value="'+val.unit+'" readonly>'+
                    //purcahse rate
                    '<input type="hidden" name="rate[]" value="'+val.rate+'">'+
                '</td>'+
                '<td>'+
                    //qty order
                    '<input type="number" step="any" name="qty_order[]" id="qty_order'+rowId+'"  class="form-control qty" value="'+(val.qty - val.qty_received)+'">'+
                '</td>'+
                '<td>'+
                    //qty received
                    '<input type="number" step="any" name="qty_received[]" id="qty_received'+rowId+'" onblur="checkQty('+rowId+')" class="form-control qty" required>'+
                    '<span class="text-danger font-weight-bold" id="error_msg'+rowId+'"></span>'+
                '</td>'+
                '<td>'+
                    //packing detail
                    '<input type="text" name="packing_detail[]" id="packing_detail'+rowId+'" class="form-control" value="'+val.packing_detail+'">'+
                '</td>'+
             '</tr>';
              $('#voucher').append(rw);
              $('#vendor').val(val.vname);
              $('#vendor_ID').val(val.vid);
              $('#po_date').html(val.po_date);
              $('#pr_id').val(val.pr_id);  
              $('option[value='+val.warehouse+']').prop("selected", true);  
            }  
        }); 
        
    
    });
   
});
// add purchase order numbers through vendor
$("#vendor").blur(function(){
    $('#poNum').html('');
     rowId=0;
  var ven_id=$('#vendor_ID').val();
  var url= "{{ url('Inventory/VendorPoDetail') }}";
  $.post(url,{ven_id:ven_id, _token:token},function(data){
    data.map(function(val,i){
      var option='<option>select p.o Number</option>'+
              '<option value="'+val.id+'">'+val.poNum+'</option>';
      $('#poNum').append(option);
    });
    
  });
  
});
});

function checkQty(numt)
{
    var qty_order=getNum($('#qty_order'+numt).val(),4);
    var qty_received=getNum($('#qty_received'+numt).val(),4);
    
    if(parseFloat(qty_received) > parseFloat(qty_order) )
    {
        $('#error_msg'+numt).html('qty received must be less to qty order');
        $('#submit-btn').hide();
    }
    else
    {
        $('#error_msg'+numt).html('');
        $('#submit-btn').show();
    }
}
</script>
@stop