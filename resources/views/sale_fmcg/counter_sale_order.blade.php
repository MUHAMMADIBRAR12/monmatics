@extends('layout.master')
@section('title', 'Counter Sales Order')
@section('parentPageTitle', 'Sales')
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
                    '<input type="text" name="item[]" id="item'+rowId+'" class="form-control" onkeydown="checkWarehouse(this.id)" onkeyup="autoFill(this.id, ItemURL, token)" onblur="getItemDetail('+rowId+');" value="" required>'+
                    '<input type="hidden" name="item_ID[]" id="item'+rowId+'_ID" class="product">'+
                '</td>'+
                '<td>'+
                    //unit
                    '<input type="text" name="unit[]" id="unit'+rowId+'" class="form-control" readonly>'+
                '</td>'+
                '<td>'+
                    //stock all warehouses
                    '<input type="number"  name="qty_in_stock[]" id="qty_in_stock'+rowId+'"  class="form-control"   readonly >'+
                '</td>'+
                '<td>'+
                    //batch
                    '<input type="text" name="batch[]" id="batch'+rowId+'" class="form-control" readonly>'+
                '</td>'+
                '<td>'+
                    //Expire Date
                    '<input type="date" name="expire_date[]" id="expire_date'+rowId+'" class="form-control" >'+
                '</td>'+
                '<td>'+
                    //qty order
                    '<input type="number" step="0.01" name="qty[]" id="qty'+rowId+'" value="0.00" class="form-control qty"  >'+
                    
                '</td>'+
                '<td>'+
                    //rate
                    '<input type="number" step="0.01" name="rate[]" id="rate'+rowId+'" value="0.00"  class="form-control total_amount qty" onblur="sumAllFields();">'+
                '</td>'+
                '<td>'+
                    //amount
                    '<input type="number" step="0.01" name="amount[]" id="amount'+rowId+'" value="0.00"  class="form-control qty" >'+
                '</td>'+
            '</tr>';
     $('#voucher').append(row);
}

function getItemDetail(number)
{
    itemId = $('#item'+number+'_ID').val();
    warehouseId=$('#warehouse').val();
    $.post("{{ url('getItemDetail') }}",{ id: itemId,warehouseId:warehouseId, _token : "{{ csrf_token()}}" }, function (data){
        (data.qty_in_stock? stock=data.qty_in_stock : stock=0)
        $('#qty_in_stock'+number).val(stock);
        $('#rate'+number).val(getNum(data.sale_price,2));
        $('#detail'+number).val('Sku:'+data.sku);
        $('#unit'+number).val(data.sales_unit);
    });
}
//customer update
function getCustomerDetail()
{
    customerId = $('#customer_ID').val();
    console.log(customerId);
    $.post("{{ url('getCustomerDetail') }}",{coa_id:customerId, _token : "{{ csrf_token()}}" }, function (data){
        $('#cust_phone_label').html(data.phone);    
        $('#cust_phone').val(data.phone);    
        $('#cust_address_label').html(data.address);    
        $('#cust_address').val(data.address);    
        $('#cust_location_label').html(data.location);    
        $('#cust_location').val(data.location);    
    });
}


function qtyRateTotal(index)
{
    amount = getNum($('#qty'+index).val())*getNum($('#rate'+index).val());
    $('#amount'+index).val(getNum(amount,2));
    $('#total_amount'+index).val(getNum(amount,2));
    $('#sub_total').val(sumAll('total_amount'));
  
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
                <form method="post" action="{{url('Purchase/PurchaseOrder/Add')}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $purchaseOrder->id ?? ''}}">
                    <div class="row">
                        <div class="col-lg-2 col-md-3">
                            <label for="fiscal_year">Sales Order #</label><br>
                            <label for="fiscal_year">{{ $purchaseOrder->month ?? '' }}-{{appLib::padingZero($purchaseOrder->number  ?? '')}}</label>
                        </div>
                        <div class="col-lg-3 col-md-3">
                            <label for="date">Date</label>
                            <div class="form-group">
                                <input type="date" name="date" id='date'  class="form-control" value="{{ $purchaseOrder->date ?? date('Y-m-d')  }}"  required>
                            </div>
                        </div>  
                        <div class="col-lg-3 col-md-3">
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
                    </div> 
                    <div class="row">
                        <div class="col-lg-5 col-md-5">
                            <label for="">Customer</label>
                            <div class="form-group">
                                <input type="text" name="customer" id="customer" onkeyup="autoFill(this.id,CustomerURL,token)" value="{{ $Invoice->name ?? ''  }}" class="form-control autocomplete" required  onblur="getCustomerDetail()">
                                <input type="hidden" name="customer_ID" id="customer_ID" value="{{ $Invoice->cst_coa_id ?? ''  }}"> 
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2">
                            <label for="">Phone</label><br>
                            <label id="cust_phone_label" style="color:#bababa"></label>
                            <input type="hidden" name="cust_phone" id="cust_phone">
                        </div>  
                    </div>  
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <label for="">Address</label><br>
                            <label id="cust_address_label" style="color:#bababa"></label> 
                            <input type="hidden" name="cust_address" id="cust_address">
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <label for="">Town</label><br>
                            <label id="cust_location_label" style="color:#bababa"></label> 
                            <input type="hidden" name="cust_location" id="cust_location">
                        </div>       
                    </div>                   
                    <div class="row">
                        <div class="table-responsive">
                            <table id="voucher" class="table table-striped m-b-0">
                                <thead>
                                    <tr class="bg-purple">
                                        <th><button type="button" class="btn btn-primary" style="align:right" onclick="addRow();" >+</button></th>
                                        <th class="table_header ">Product</th>
                                        <th class="table_header">Unit</th>
                                        <th class="table_header table_header_100">Stock (All warehouses)</th>
                                        <th class="table_header table_header_100">Batch</th>
                                        <th class="table_header table_header_100">Expiry Date</th>
                                        <th class="table_header table_header_100">Qty Order</th>
                                        <th class="table_header table_header_100">Rate</th>
                                        <th class="table_header table_header_100">Amount</th>
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
                                    }                                    
                                    @endphp                               
                                    <tr id="row{{$i}}" class="rowData">
                                        <td>
                                            <button  type="button" class="btn btn-danger btn-sm" onclick="deleteRow('row{{$i}}');"><i class="zmdi zmdi-delete"></i></button>
                                        </td>
                                        <td>
                                            <!-- Product -->
                                            <input type="text" name="item[]" id="item{{$i}}" onkeydown="checkWarehouse(this.id)"  onkeyup="autoFill(this.id, ItemURL, token)" onblur="getItemDetail({{$i}});" value="{{ $lineItem->name ?? ''  }}" class="form-control" required>
                                            <input type="hidden" name="item_ID[]" id="item{{$i}}_ID" value="{{ $lineItem->prod_id ?? ''  }}" class="product">
                                        </td>
                                        <td>
                                            <!-- unit -->
                                            <input type="text"  name="unit[]" id="unit{{$i}}" value="{{ $lineItem->unit ?? ''  }}" class="form-control"   readonly >
                                        </td>
                                        <td>
                                            <!-- Stock all warehouses -->
                                            <input type="number"  name="qty_in_stock[]" id="qty_in_stock{{$i}}" value="{{ $lineItem->qty ?? ''  }}" class="form-control" readonly>
                                            
                                        </td>
                                        <td>
                                            <!-- batch -->
                                            <input type="text"  name="batch[]" id="batch{{$i}}"  value="{{ $lineItem->rate ?? ''  }}" class="form-control" readonly>
                                        </td>
                                        <td>
                                            <!-- Expiry Date -->
                                            <input type="date"  name="expire_date[]" id="expire_date{{$i}}"  value="{{ $lineItem->rate ?? ''  }}" class="form-control" >
                                        </td>
                                        <td>
                                            <!-- qty order -->
                                            <input type="number" step="0.01" name="qty[]" id="qty{{$i}}"  value="{{ $lineItem->rate ?? '0.00'  }}" class="form-control qty" onblur="rowCalculate({{$i}})">
                                        
                                        </td>
                                        <td>
                                            <!-- rate -->
                                            <input type="number" step="0.01" name="rate[]"  id="rate{{$i}}"  value="{{ $amount ?? '0.00'  }}" class="form-control qty total_amount"  readonly >
                                        </td>
                                        <td>
                                            <!-- amount -->
                                            <input type="text" step="0.01" name="amount[]" step="0.01" id="amount{{$i}}"  value="{{ $lineItem->discount ?? '0.00'  }}" onblur="rowCalculate({{$i}})" class="form-control qty">
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
                    <div class="row mt-2">
                        <div class="col-md-3">
                            <label for="">Total Products</label><br>
                            <label for=""> XXXXXXX</label> 
                        </div>
                        <div class="col-md-3">
                            <label for="">Gross Amount</label><br>
                            <label for=""> XXXXXXX</label>
                        </div>
                        <div class="col-md-3">
                            <label for="">Net Amount</label><br>
                            <label for=""> XXXXXXX</label>
                        </div>
                        <div class="col-md-3">
                            <label for="">Total Sku</label><br>
                            <label for=""> XXXXXXX</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <fieldset class="border px-2">
                                <legend>Discounts:</legend>
                                    <label for="lname">COD %:</label><br>
                                    <label for="">xxx</label>
                            </fieldset>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-6">
                            <label for="fiscal_year">Note</label>
                            <div class="form-group" id="note">
                                <textarea name="note" maxlength="120" rows="4" class="form-control"  placeholder="">{{ $purchaseOrder->note ?? '' }}</textarea>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label for="fiscal_year">Attachment</label>
                            <br>
                            @if($purchaseOrderAttachment ?? '')
                                <table>
                                @foreach($purchaseOrderAttachment as $attachment)
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
$('#generate-bill').on('click',function(){
    tradeOfferURL = "{{ url('getTradeOffer') }}";
    cust_id=$('#customer_ID').val();
    date=$('#date').val();
    $('.product').each(function(i){
        i++
        item_id=$('#item'+i+'_ID').val();
        qty=getNum($('#qty'+i).val(),2);
        rate=getNum($('#rate'+i).val(),2);
        amount=getNum(qty,2) * getNum(rate,2);
        $.post(tradeOfferURL,{item_id:item_id,cust_id:cust_id,date:date,qty:qty,rate:rate, _token:token},function(data){
                discount=data[0].discount;
                discount_amount=getNum(amount * discount / 100,2);
            $('#discount'+i).val(discount);
            $('#amount'+i).val(amount-discount_amount);

        });
    })
});

</script>
@stop