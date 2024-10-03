@extends('layout.master')
@section('title', 'Good Received')
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

function qtyRateTotal(index)
{
    amount = getNum($('#qty'+index).val())*getNum($('#rate'+index).val());
    $('#amount'+index).val(getNum(amount,2));
    $('#total_amount'+index).val(getNum(amount,2));
    $('#sub_total').val(sumAll('total_amount'));
}
</script>
@stop
@section('content')
    <div class="row clearfix">
        <div class="card col-lg-12">
            <div class="body">
            <form method="post" action="{{url('Inventory/GoodReceived/Add')}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <!-- grn id -->
                    <input type="hidden" name="id" id="id" value="{{ $goodReceived->id ?? ''  }}">
                    <input type="hidden" name="trans_id"  value="{{ $goodReceived->trans_id ?? ''  }}">
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <label for="fiscal_year">GRN #</label><br>
                            <label for="fiscal_year">{{ $goodReceived->month ?? '' }}-{{appLib::padingZero($goodReceived->number  ?? '')}}</label>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label for="date">Date</label>
                            <div class="form-group">
                                <input type="date" name="date" id='vdate'  class="form-control" value="{{ $goodReceived->date ?? date('Y-m-d')  }}"  required>
                            </div>
                        </div>                        
                        <div class="col-lg-3 col-md-6">
                            <label for="fiscal_year">Warehouse</label>
                            <div class="form-group">
                                <select name="warehouse" class="form-control show-tick ms select2" data-placeholder="Select" required>
                                   <option  value="">Select Warehouse</option>
                                    @foreach($warehouses as $warehouse)
                                    <option value="{{$warehouse->id}}" {{ ( $warehouse->id == ( $goodReceived->warehouse ??'')) ? 'selected' : '' }}>{{$warehouse->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label for="">Vendor</label>
                            <input type="text" name="vendor" id="vendor" value="{{ $goodReceived->name ?? '' }}" placeholder="Vendor Name" class="form-control" onkeyup="autoFill(this.id, vendorURL, token)">
                            <input type="hidden" name="vendor_ID" id="vendor_ID" value="{{ $goodReceived->v_id ?? '' }}">
                            <input type="hidden" name="ven_coa_id"  value="{{ $goodReceived->ven_coa_id ?? '' }}">
                        </div>      
                    </div>  
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <label for="fiscal_year">IGP No.</label>
                            <div class="form-group">
                            @if(isset($igp))
                                <label>{{ ( $igp->month ?? '').'-'.appLib::padingZero( $igp->number  ?? '') }}</label>
                                <input type="hidden" name="igp_id" value="{{$igp->id}}">
                            @else
                                <select name="igp_id" id="igp_number" class="form-control show-tick ms select2" data-placeholder="Select">
                                   <option  value="">Select I.G.P No.</option>
                                   @foreach($igps as  $igp)
                                    <option value="{{ $igp->id}}">{{$igp->doc_number}}</option>
                                   @endforeach
                            @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label>IGP Date</label><br>
                           <label  id="igp_date">{{ $goodReceived->igp_date ?? '' }}</label>
                        </div>
                        <div class="col-lg-3 col-md-6">
                           <label>P.O No.</label><br>
                           <label for="fiscal_year"  id="po_num">{{ $goodReceived->month ?? '' }}-{{appLib::padingZero($goodReceived->number  ?? '')}}</label>
                        </div>
                        <div class="col-lg-3 col-md-6">
                           <label>P.O date</label><br>
                           <label id="po_date">{{ $goodReceived->po_date ?? '' }}</label>
                           <input type="hidden" name="po_id" id="po_id" value="{{ $goodReceived->po_id ?? '' }}">
                        </div>
                    </div>  
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <label for="fiscal_year">D.O Ref</label>
                            <input type="text" name="do_ref" id="do_ref" class="form-control" value="{{ $goodReceived->do_ref ?? '' }}">
                        </div>    
                    </div>             
                    <div class="row mt-1">
                        <div class="table-responsive">
                            <table id="voucher" class="table table-striped m-b-0">
                                <thead>
                                    <tr class="bg-purple">
                                        <th class="table_header ">Product</th>
                                        <th class="table_header">Description</th>
                                        <th class="table_header table_header_100">Unit</th>     
                                        <th class="table_header table_header_100">Qty Received</th>
                                        <th class="table_header table_header_100">Qty approved</th>
                                        <th class="table_header table_header_100">Qty Rejected</th>
                                        <th class="table_header table_header_100">Packing Detail</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @php
                                $count = (isset($goodReceivedDetails))?count($goodReceivedDetails):0;
                               
                                for($i=1;$i<=$count; $i++)
                                { 
                                    
                                    if(isset($goodReceivedDetails))
                                    {
                                        $lineItem = $goodReceivedDetails[($i-1)];
                                        
                                    }                                    
                                    @endphp                               
                                    <tr id="row{{$i}}" class="rowData">
                                        <!-- igp_detail id -->
                                        <input type="hidden" name="igp_detail_id[]" value="{{ $lineItem->igp_detail_id ?? ''  }}">
                                        <!-- po_detail rate -->
                                        <input type="hidden" name="rate[]" value="{{ $lineItem->po_rate ?? ''  }}">
                                        <!-- po_detail discount -->
                                        <input type="hidden" name="discount[]" value="{{ $lineItem->discount_amount ?? ''  }}">
                                        <!-- po_detail sales_tax -->
                                        <input type="hidden" name="sales_tax[]" value="{{ $lineItem->tax_amount ?? ''  }}">                                        
                                        <!-- po_detail delivery charges -->
                                        <input type="hidden" name="delivery_charges[]" value="{{ $lineItem->delivery_charges ?? ''  }}">
                                        <td class="text-center">
                                             <!--item name  -->
                                            <label for="">{{ $lineItem->name ?? ''  }}</label>
                                            <!-- item id -->
                                            <input type="hidden" name="item_ID[]"  value="{{ $lineItem->prod_id ?? ''  }}">
                                            <!-- product coa_id -->
                                            <input type="hidden" name="prod_coa_id" value="{{ $lineItem->coa_id ?? ''  }}">
                                        </td>
                                        <td class="text-center">
                                             <!-- description -->
                                            <label for="">{{ $lineItem->description ?? ''  }}</label>
                                            <input type="hidden" name="description[]" value="{{ $lineItem->description ?? ''  }}" >
                                        </td>
                                        <td class="text-center">
                                            <!--  unit -->
                                            <label for="">{{ $lineItem->unit ?? ''  }}</label>
                                            <input type="hidden" name="unit[]" value="{{ $lineItem->unit ?? ''  }}" >
                                            <!-- base unit -->
                                            <input type="hidden" name="base_unit[]" id="base_unit{{$i}}" value="{{ $lineItem->base_unit ?? ''  }}">
                                             <!-- base rate -->
                                             <input type="hidden" name="base_rate[]" id="base_rate{{$i}}" value="{{ $lineItem->base_rate ?? ''  }}">
                                        </td>
                                        <td class="text-center">
                                             <!--qty received -->
                                            <label for="">{{ $lineItem->qty_received ?? ''  }}</label>
                                            <input type="hidden" name="qty_received[]" id="qty_received{{$i}}"  value="{{ Str::currency($lineItem->qty_received ?? '0')  }}" >
                                        </td>
                                        <td class="text-center">
                                             <!-- qty approved -->
                                            <input type="number" step="any" name="qty_approved[]" id="qty_approved{{$i}}" value="{{ Str::currency($lineItem->qty_approved ?? '0')  }}" class="form-control qty" onblur="qtyReject({{$i}})" required>
                                        </td>
                                        <td class="text-center">
                                             <!-- qty rejected -->
                                            <input type="number" step="any" name="qty_rejected[]" id="qty_rejected{{$i}}" value="{{ Str::currency($lineItem->qty_rejected ?? '0')  }}" class="form-control qty"  readonly>
                                        </td>
                                        <td class="packing-detail">
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
                                <textarea name="note" maxlength="120" rows="4" class="form-control no-resize"  placeholder="">{{  $goodReceived->note ?? '' }}</textarea>
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
$('#igp_number').on('change',function(){
    $('.rowData').html('');
    rowId=0;
    var igp_id=$(this).val();
    var url = "{{ url('Inventory/aj_igpDetail')}}";
    $.post(url,{igp_id:igp_id, _token:token},function(data){
        data.map(function(val,i){
                    if(val.description === null)
                        desc='';
                    else
                        desc=val.description;
                 var rate = parseFloat(val.rate)+ parseFloat(val.delivery_charges)+ parseFloat(val.tax_amount) - parseFloat (val.discount_amount);
                rowId++
                var rw='<tr id="row'+rowId+'" class="rowData">'+
                //igp_detail id
                '<input type="hidden" name="igp_detail_id[]" value="'+val.id+'">'+
                //base rate
                '<input type="hidden" name="base_rate[]" id="base_rate'+rowId+'" value="'+val.base_rate+'">'+
                //base unit
                '<input type="hidden" name="base_unit[]" id="base_unit'+rowId+'" value="'+val.primary_unit+'">'+
                //po_detail rate 
                '<input type="hidden" name="rate[]" value="'+rate+'">'+
                //po_detail discount
                '<input type="hidden" name="discount[]" value="'+val.discount_amount+'">'+
                //po_detail sales_tax 
                '<input type="hidden" name="sales_tax[]" value="'+val.tax_amount+'">'+
                //po_detail further_tax
                //'<input type="hidden" name="further_tax[]" value="'+val.further_tax+'">'+
                //po_detail delivery charges
                '<input type="hidden" name="delivery_charges[]" value="'+val.delivery_charges+'">'+

                '<td class="text-center">'+
                    //product
                    '<label>'+val.prod_name+'</label>'+
                    '<input type="hidden" name="item_ID[]" id="item'+rowId+'_ID"  value="'+val.prod_id+'">'+
                    '<input type="hidden" name="prod_coa_id" value="'+val.coa_id+'">'+
                    '<input type="hidden" name="ven_coa_id" value="'+val.ven_coa_id+'">'+
                '</td>'+
                '<td class="text-center">'+
                    //description
                    +'<label>'+desc+'</label>'+
                    '<input type="hidden" name="description[]" value="'+desc+'">'+
                '</td>'+
                '<td class="text-center">'+
                    //unit
                    '<label>'+val.unit+'</label>'+
                    '<input type="hidden" name="unit[]" value="'+val.unit+'">'+
                '</td>'+
                '<td class="text-center">'+
                    //qty received
                    '<label>'+val.qty_received+'</label>'+
                    '<input type="hidden" name="qty_received[]" id="qty_received'+rowId+'" value="'+val.qty_received+'">'+   
                '</td>'+
                '<td>'+
                    //qty approved
                    '<input type="number" step="any" name="qty_approved[]" id="qty_approved'+rowId+'"  class="form-control qty" onblur="qtyReject('+rowId+');checkQty('+rowId+')">'+
                    '<span class="text-danger font-weight-bold" id="error_msg'+rowId+'"></span>'+
                '</td>'+
                '<td>'+
                    //qty rejected
                    '<input type="number" step="any" name="qty_rejected[]" id="qty_rejected'+rowId+'" class="form-control qty" readonly>'+
                '</td>'+
                '<td class="text-center">'+
                    //packing detail
                    '<label>'+val.packing_detail+'</label>'+
                    '<input type="hidden" name="packing_detail[]" value="'+val.description+'">'+   
                '</td>'+
            '</tr>';
            $('#voucher').append(rw);
            $('#igp_num').html(val.month+ '-'+leftPad(val.number)); 
            $('#igp_date').html(val.igp_date); 
            $('#igp_id').val(val.igp_id);
            $('#vendor').val(val.vendor);
            $('#vendor_ID').val(val.ven_id);
            $('option[value='+val.warehouse+']').prop("selected", true);  
            $('#do_ref').val(val.do_ref);  
            $('#po_num').html(val.month+ '-'+leftPad(val.po_num));  
            $('#po_date').html(val.po_date);
            $('#po_id').val(val.po_id);
        }); 
        
    
    });
   
});
// add purchase order numbers through vendor
$("#vendor").blur(function(){
    $('#poNum').html('');
    $('#poNum').append('<option>select p.o Number</option>');
     rowId=0;
  var ven_id=$('#vendor_ID').val();
  var url= "{{ url('Inventory/aj_igpDetail') }}";
  $.post(url,{ven_id:ven_id, _token:token},function(data){
    data.map(function(val,i){
      var option='<option value="'+val.id+'">'+val.poNum+'</option>';
      $('#poNum').append(option);
    });
    
  });
  
});
});
function qtyReject(num)
{
  $('#qty_rejected'+num).val(getNum($('#qty_received'+num).val() - $('#qty_approved'+num).val(),4));
}

function checkQty(numt)
{

    var qty_received=getNum($('#qty_received'+numt).val(),4);
    var qty_approved=getNum($('#qty_approved'+numt).val(),4);

        if(parseFloat(qty_approved) > parseFloat(qty_received) )
        {
            $('#error_msg'+numt).html('Qty Approved must be less then Qty Received');
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