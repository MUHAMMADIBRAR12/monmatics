@extends('layout.master')
@section('title', 'Delivery Order')
@section('parentPageTitle','Inventory')
@section('parent_title_icon', 'zmdi zmdi-home')
<?php  use App\Libraries\appLib; ?>
@section('page-style')
<link rel="stylesheet" href="{{asset('public/assets/plugins/select2/select2.css')}}"/>
<link rel="stylesheet" href="{{asset('public/assets/plugins/morrisjs/morris.css')}}"/>
<link rel="stylesheet" href="{{asset('public/assets/plugins/jquery-spinner/css/bootstrap-spinner.css')}}"/>
<link rel="stylesheet" href="{{asset('public/assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css')}}"/>
<link rel="stylesheet" href="{{asset('public/assets/plugins/bootstrap-select/css/bootstrap-select.css')}}"/>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="{{asset('public/assets/plugins/dropify/css/dropify.min.css')}}"/>
<style>
.input-group-text {
    padding: 0 .75rem;
}

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
<script>
var ItemURL = "{{ url('itemSearch') }}";
var token =  "{{ csrf_token()}}";
</script>
@stop

@section('content')

    <div class="row clearfix">
        <div class="card col-lg-12">
            <div class="header">
                <h2><strong>Delivery</strong>Order</h2>
            </div>
            @if(session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
            @endif
            
            
            <div class="body">
                <form method="post" action="{{url('Inventory/Do/Add')}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $do->id ?? ''  }}">

                    <div class="row">
                        <div class="col-sm-2 col-md-2">
                            <label>D.O No</label><br>
                            <label for="fiscal_year">{{ $do->month ?? '' }}-{{appLib::padingZero($do->number  ?? '')}}</label>
                        </div> 
                        <div class="col-sm-3 col-md-3">
                            <label> Date</label>
                            <input type="date" name="date" class="form-control" value="{{ $do->date ?? date('Y-m-d') }}">
                        </div>
                        
                        <div class="col-sm-3 col-md-3">
                            <label>Sale Order No.</label>                            
                            <div class="form-group">
                                @if($do->id ?? '')
                                    <label for="fiscal_year">{{ $saleOrders->number ?? '' }}</label>
                                    <input type="hidden"  name="saleOrderNo" id="saleOrderNo" value="{{$do->sale_orders_id}}" >
                                @else
                                <select name="saleOrderNo" id="saleOrderNo" class="form-control show-tick ms select2" data-placeholder="Select">
                                    <option  value="">Select Sale Order</option>
                                    @foreach($saleOrders as $saleOrder)
                                    <option  value="{{$saleOrder->id}}">{{$saleOrder->number}}</option>
                                    @endforeach
                                </select>
                                 @endif
                            </div>                           
                        </div>
                        <div class="col-sm-2 col-md-2">
                            <label>Date</label><br>
                            <label id="invoice_date"></label>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-3 col-md-3">
                            <label>Customer</label><br>
                            <label id="customer">{{$do->name ?? '' }}</label>
                            <input type="hidden" name="customer_id" id="customer_id" value="{{$do->cust_coa_id ?? '' }}"> 
                        </div> 
                        <div class="col-sm-3 col-md-3">
                            <label>Address</label><br>
                            <label id="cust_address_label" style="color:#bababa">{{$do->address ?? '' }}</label>
                        </div> 
                        <div class="col-sm-3 col-md-3">
                            <label>Town</label><br>
                            <label id="cust_location_label" style="color:#bababa">{{$do->location ?? '' }}</label>
                        </div> 
                        <div class="col-sm-3 col-md-3">
                            <label>Warehouse</label>
                            <div class="form-group">
                                <select name="warehouse" id="warehouse" class="form-control show-tick ms select2" data-placeholder="Select">
                                   <option  value="">Select Warehouse</option>
                                   @foreach($warehouses as $warehouse)
                                    <option  value="{{$warehouse->id}}" {{($warehouse->id == ($do->warehouse ??'')) ? 'selected' : '' }}>{{$warehouse->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <label><input type="checkbox" name="delivery_status" value="delivered"></label> 
                            <label for="">Delivered</label>
                        </div>
                        
                    </div>
                    <div class="row">
                        <div class="table-responsive">
                            <table id="voucher" class="table table-striped m-b-0">
                                <thead>
                                    <tr class="bg-purple">
                                       <!-- <th><button type="button" class="btn btn-primary" style="align:right;display:none" onclick="addRow();" >+</button></th> -->
                                        <th class="table_header ">Product</th>
                                        <th class="table_header ">Description</th>
                                        <th class="table_header">Unit</th>
                                        <th class="table_header table_header_100">Qty Stock</th>
                                        <th class="table_header table_header_100">Qty</th>
                                        <th class="table_header table_header_100">Required Date</th>
                                        <th class="table_header table_header_100">Instruction</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @php
                               
                                //dd($lineItems);
                                $count = (isset($lineItems))?count($lineItems):1;
                               
                                for($i=1;$i<=$count; $i++)
                                { 
                                    
                                    if(isset($lineItems))
                                    {
                                        $lineItem =  $lineItems[($i-1)];
                                         //dd($lineItem);
                                    }                                    
                                    @endphp                               
                                    <tr id="row{{$i}}" class="rowData">
                                        <td>
                                            <!-- Product -->                                            
                                            <input type="text" name="item_ID[]" id="item{{$i}}_ID" value="{{ $lineItem['prod_name'] ?? ''  }}" class="form-control" readonly>
                                            <input type="hidden" name="prd_id[]" id="item{{$i}}_ID" value="{{ $lineItem['prod_id'] ?? ''  }}" class="product">
                                        </td>
                                        <td>
                                            <!-- description -->                                          
                                            <input type="text" name="description[]" id="description{{$i}}" value="{{  $lineItem['description'] ?? ''  }}" class="form-control">                                    
                                        </td>
                                        <td>
                                            <!-- unit -->                                             
                                             <input type="text" name="unit[]" id="unit{{$i}}" class="form-control" value="{{ $lineItem['unit'] ?? ''  }}" readonly>
                                             <input type="hidden" name="base_unit[]" id="base_unit{{$i}}" class="form-control" value="{{ $lineItem['base_unit'] ?? ''  }}" readonly>
                                             <input type="hidden" name="operator_value[]" id="operator_value{{$i}}" class="form-control" value="{{ $lineItem['operator_value'] ?? ''  }}" readonly>
                                           
                                        </td>
                                        <td>
                                            <!-- qty stock -->                                           
                                            <input type="number"  name="qty_stock[]" id="qty_stock{{$i}}" class="form-control qty" value="{{ $lineItem['qty_stock'] ?? ''  }}"  readonly>
                                            
                                        </td>
                                        <td>
                                            <!-- qty -->                                            
                                            <input type="hidden"  name="qty_balance[]" id="qty_balance{{$i}}" value="{{ ($lineItem['qty_balance'] ?? '0')  }}">
                                            <input type="number"  name="qty[]" id="qty{{$i}}" value="{{ Str::currency($lineItem['qty'] ?? '0')  }}" class="form-control text-right" onblur="checkQty({{$i}})">
                                            <span class="text-danger font-weight-bold" id="error_msg{{$i}}"></span>
                                        </td>
                                        <td>
                                            <!-- required date-->                                           
                                            <input type="text"  name="required_date[]" id="required_date{{$i}}" value="{{ $lineItem['require_date'] ?? ''  }}" class="form-control" readonly>
                                            
                                        </td>
                                        <td>
                                            <!-- instruction -->                                          
                                            <input type="text"  name="instruction[]" id="instruction{{$i}}"  value="{{ $lineItem['instruction'] ?? ''  }}" class="form-control">
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
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-sm-3 col-md-6">
                                    <label>Veh No</label>
                                    <input type="text" name="veh_no" id="" class="form-control" value="{{$do->veh_no ?? '' }}" >
                                </div> 
                                <div class="col-sm-3 col-md-6">
                                    <label>Delivery Name</label>
                                    <input type="text" name="delivery_name" id="" class="form-control" value="{{$do->delivery_name ?? '' }}">
                                </div> 
                            </div>
                            <div class="row mt-4">
                                <div class="col-sm-3 col-md-6">
                                    <label>Trucking Company</label>
                                    <input type="text" name="trucking_company" id="trucking_company" class="form-control" value="{{$do->trucking_company ?? '' }}">
                                </div> 
                                <div class="col-sm-3 col-md-6">
                                    <label>Bilty No</label>
                                    <input type="text" name="bilty_no" id="bilty_no" class="form-control" value="{{$do->bilty_no ?? '' }}">
                                </div> 
                            </div> 
                        </div>
                        <div class="col-md-6">
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
                        <div class="col-md-6">
                            <label for="">Remarks</label>
                            <textarea name="remarks" id="remarks" class="form-control">{{$do->remarks ?? '' }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="">Receiving Detail</label>
                            <textarea name="receiving_detail" class="form-control">{{$do->receiving_detail ?? '' }}</textarea>
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

    $('#saleOrderNo').on('change',function(){
        $('.rowData').html('');
        rowId=0;
        var id=$(this).val();
        var url= "{{ url('Sales/SaleOrder/GetSaleOrder') }}";
        $.post(url,{id:id, _token:token},function(data){
            console.log(data[0]);
            // console.log(data['detail']);
            var main = data[0];
            var details = data[1];
            var arrQtyInStock = data[2];
            
            details.map(function(val,i){
                    rowId++;
                    var rw='<tr id="row'+rowId+'" class="rowData">'+
                        '<td class="text-center">'+
                            //product
                        // '<label>'+val.prod_name+'</label>'+
                            '<input type="hidden" name="prd_id[]" id="item'+rowId+'_ID" value="'+val.prod_id+'">'+

                            '<input type="text" name="item_ID[]" id="item'+rowId+'_ID" class="form-control" value="'+val.name+'" readonly>'+
                        '</td>'+
                        '<td class="text-center">'+
                            //description
                            //'<label>'+val.description+'</label>'+
                            '<input type="text" name="description[]" id="description'+rowId+'" class="form-control" value="'+val.description+'" >'+
                        '</td>'+
                        '<td class="text-center">'+
                            //unit
                            //'<label>'+removeNull(val.prod_unit)+'</label>'+
                            '<input type="text" name="unit[]" id="unit'+rowId+'"   class="form-control" value="'+removeNull(val.unit_name)+'" readonly>'+
                            //base_unit
                            '<input type="hidden" name="base_unit[]" id="base_unit'+rowId+'"  value="'+val.base_unit+'">'+
                            //operator value
                            '<input type="hidden" name="operator_value[]" id="operator_value'+rowId+'"  value="'+val.operator_value+'">'+
                        '</td>'+
                        '<td class="text-center">'+
                            //qty stock
                            //'<label>'+removeNull(val.qty_in_stock)+'</label>'+
                            '<input type="text" name="qty_stock[]" id="qty_stock'+rowId+'" class="form-control qty" value="'+removeNull(arrQtyInStock[val.prod_id])+'" readonly>'+
                        '</td>'+
                        '<td>'+
                            //qty
                            //'<label>'+removeNull(val.qty)+'</label>'+
                            '<input type="hidden" name="qty_balance[]" id="qty_balance'+rowId+'" value="'+val.qty_balance+'" class="form-control qty">'+
                            '<input type="text"  name="qty[]" id="qty'+rowId+'" class="form-control text-right qty" onblur="checkQty('+rowId+')" value="'+removeNull(getNum(val.qty_balance))+'">'+
                            '<span class="text-danger font-weight-bold" id="error_msg'+rowId+'"></span>'+
                        '</td>'+
                        '<td class="text-center">'+
                            //required date
                        // '<label>'+removeNull(val.required_by)+'</label>'+
                            '<input type="text"  name="required_date[]" class="form-control" id="required_date'+rowId+'" value="'+setEmpty(val.required_by)+'" readonly>'+
                        '</td>'+
                        '<td class="text-center">'+
                            //instruction
                            //'<label>'+removeNull(val.instruction)+'</label>'+
                            '<input type="text"  name="instruction[]" class="form-control" id="instruction'+rowId+'" value="'+removeNull(val.instruction)+'">'+
                        '</td>'+
                    '</tr>';
                    $('#voucher').append(rw);
                    $('#invoice_date').html(main.date);
                    $('#customer').html(main.name);
                    $('#customer_id').val(main.cst_coa_id); 
                    $('#cust_address_label').html(main.address);
                    $('#cust_location_label').html(main.location);  
                    $('#remarks').html(main.note);  
                    $('option[value='+main.warehouse+']').prop("selected", true);
                       
            });

                        
            
        }, 'json' );

    
    });
});
//check qty in a stock or not 
function checkQty(numt)
{
    //alert('helo');
    var qty_stock=$('#qty_stock'+numt).val();
    var qty=$('#qty'+numt).val();
    var qty_balance= $('#qty_balance'+numt).val();
   // alert(qty_balance+'|'+qty+'|'+numt);
    if(parseFloat(qty)>parseFloat(qty_stock))
    {
        //alert('qty is not enough in stock !');
        $('#error_msg'+numt).html('qty is not enough in stock !');
        return false;
        $('#submit-btn').hide();
    }
    else if(parseFloat(qty)>parseFloat(qty_balance))
    {
        $('#error_msg'+numt).html('balance is not enough !');
        return false;
        $('#submit-btn').hide();
    }

    else
    {
        //alert('in else');
        $('#error_msg'+numt).html('');
        $('#submit-btn').show();
        return true;
    }
    
}

</script>
@stop