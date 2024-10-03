@extends('layout.master')
@section('title', 'Delivery Order')
@section('parentPageTitle','Sales')
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
<link rel="stylesheet" href="{{asset('public/assets/css/sw.css')}}">   
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
                <form method="post" action="{{url('Sales_Fmcg/DeliveryOrder/Add')}}" enctype="multipart/form-data">
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
                            <label>Approval No.</label>
                            @if(isset($do))
                            <br>
                            <label style="color:#bababa">{{ $do->approvel_no ?? '' }}</label>
                            @else
                            <div class="form-group">
                                <select name="approvel_no" id="approvel_no" class="form-control show-tick ms select2" data-placeholder="Select">
                                   <option  value="-1">Select Sale Order Approvel</option>
                                   @foreach($so_approvels as $so_approvel)
                                    <option  value="{{$so_approvel->id}}" {{($so_approvel->id == ($do->soa_id ??'')) ? 'selected' : '' }}>{{$so_approvel->doc_number}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif 
                        </div>
                        <div class="col-sm-2 col-md-2">
                            <label>Date</label><br>
                            <label id="app_date" style="color:#bababa">{{$do->soa_date ?? '' }}</label>
                        </div>
                        <div class="col-sm-2 col-md-2">
                            <label>Sale Order By</label>
                            <label id="sale_order_by" style="color:#bababa">{{$do->user_name ?? '' }}</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3 col-md-3">
                            <label>Customer</label><br>
                            <label id="customer" style="color:#bababa">{{$do->cust_name ?? '' }}</label>
                            <input type="hidden" name="customer_id" id="customer_id" value="{{$do->cust_id ?? '' }}"> 
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
                            @if(isset($do))
                                <label style="color:#bababa">{{$do->warehouse_name ?? ''}}</label>
                                <input type="hidden" name="warehouse" value="{{$do->warehouse?? ''}}">
                            @else
                            <div class="form-group">
                                <select name="warehouse" id="warehouse" class="form-control show-tick ms select2" data-placeholder="Select" required>
                                   <option  value="-1">Select Warehouse</option>
                                   @foreach($warehouses as $warehouse)
                                    <option  value="{{$warehouse->id}}" {{($warehouse->id == ($do->warehouse ??'')) ? 'selected' : '' }}>{{$warehouse->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                        </div>
                        
                    </div>
                    <div class="row">
                        <div class="table-responsive">
                            <table id="voucher" class="table table-striped m-b-0">
                                <thead>
                                    <tr class="bg-purple">
                                       <!-- <th><button type="button" class="btn btn-primary" style="align:right;display:none" onclick="addRow();" >+</button></th> -->
                                        <th>Product</th>
                                        <th>Unit</th>
                                        <th class="qty">Qty In Stock</th>
                                        <th class="table_header table_header_100">Qty Approved</th>
                                        <th class="table_header table_header_100">Qty Delivered</th>
                                        <!-- 
                                        <th class="table_header table_header_100">Batch No.</th>
                                        <th class="table_header table_header_100">Expiry Date</th> -->
                                        <th class="table_header table_header_100">Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @php
                                $count = (isset($do_detail))?count($do_detail):1;
                               
                                for($i=1;$i<=$count; $i++)
                                { 
                                    
                                    if(isset($do_detail))
                                    {
                                        $lineItem =  $do_detail[($i-1)];
                                    }                                    
                                    @endphp                               
                                    <tr id="row{{$i}}" class="rowData">
                                        <td>
                                            <!-- sale order approvel detail id -->
                                            <input type="hidden" name="soa_detail_id[]" value="{{ $lineItem['soa_detail_id'] ?? ''  }}">
                                            <!-- Product -->
                                            <label class="prod_field">{{ $lineItem['prod_name'] ?? ''  }}</label>
                                            <input type="hidden" name="item_ID[]" value="{{ $lineItem['prod_id'] ?? ''  }}" class="product">
                                        </td>
                                        <td>
                                            <!-- unit -->
                                            <label>{{ $lineItem['unit'] ?? ''  }}</label>
                                            <input type="hidden"  name="unit[]"  value="{{ $lineItem['unit'] ?? ''  }}">
                                            <!-- base unit -->
                                            <input type="hidden" name="base_unit[]"  value="{{ $lineItem['base_unit'] ?? ''  }}">
                                            <!-- operator value -->
                                            <input type="hidden" name="operator_value[]"  value="{{ $lineItem['operator_value']?? ''  }}">
                                        </td>
                                        <td >
                                            <label id="stock{{$i}}" class="qty">{{ $lineItem['qty_stock'] ?? ''  }}</label>
                                        </td>
                                        <td>
                                            <!-- qty approved -->
                                            <label class="qty">{{ $lineItem['qty_approved'] ?? ''  }}</label>
                                            <input type="hidden"  name="qty_approved[]" id="qty_approved{{$i}}" value="{{ $lineItem['qty_approved'] ?? ''  }}" required>
                                        </td>
                                        <td>
                                            <!-- qty delivered -->
                                            <input type="number"  name="qty_delivered[]" id="qty_delivered{{$i}}" value="{{ $lineItem['qty_delivery'] ?? ''  }}" class="form-control qty" onblur="checkQty({{$i}})">
                                            <label id="qty_error{{$i}}" class="text-danger"></label>
                                        </td>
                                        <!--  baTCH AND expiry date
                                        <td>
                                            
                                            <input type="text"  name="batch[]" id="batch{{$i}}" value="{{ $lineItem->batch_no ?? ''  }}" class="form-control">
                                            
                                        </td>
                                        <td>
                                          
                                            <input type="text"  name="expire_date[]" id="expire_date{{$i}}" value="{{ $lineItem->qty ?? ''  }}" class="form-control">
                                            
                                        </td>  -->
                                        <td>
                                            <!-- Remarks -->
                                            <input type="text"  name="remark[]" id="remark{{$i}}"  value="{{ $lineItem['remarks'] ?? ''  }}" class="form-control">
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
                        <div class="col-sm-3 col-md-3">
                            <label>No of Sku</label>
                        </div> 
                        <div class="col-sm-3 col-md-3">
                            <label>Total Approved</label>
                            
                        </div>
                        <div class="col-sm-3 col-md-3">
                            <label>Total Delivered</label>
                        </div>   
                    </div>
                    <div class="row mt-2">                        
                        <div class="col-sm-3 col-md-3">
                            <label>Veh No</label>
                            <input type="text" name="veh_no" id="" class="form-control" value="{{$do->veh_no ?? '' }}" >
                        </div> 
                        <div class="col-sm-3 col-md-3">
                            <label>Bilty No</label>
                            <input type="text" name="bilty_no" id="" class="form-control" value="{{$do->bilty_no ?? '' }}">
                        </div>
                        <div class="col-sm-3 col-md-3">
                            <label>Delivery Name</label>
                            <input type="text" name="delivery_name" id="" class="form-control" value="{{$do->delivery_name ?? '' }}">
                        </div>   
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="">Remarks</label>
                            <textarea name="remarks" class="form-control" id="note">{{$do->remarks ?? '' }}</textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="ml-auto">
                            <button class="btn btn-raised btn-primary waves-effect" type="submit" id="save">Save</button>
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

$('#approvel_no').on('change',function(){
    $('.rowData').html('');
     rowId=0;
    var soa_id=$(this).val();
    var url= "{{ url('Sales_Fmcg/SaleOrderApprovelDetail') }}";
    $.post(url,{soa_id:soa_id, _token:token},function(data){
        data.map(function(val,i){
            if(val.qty_approved==val.remain_qty)
            {

            }
            else
            {
                rowId++;
                var rw='<tr id="row'+rowId+'" class="rowData">'+
                    '<td>'+
                        //sale order approvel detail id
                        '<input type="hidden" name="soa_detail_id[]" value="'+val.id+'">'+
                        //product
                        '<label class="prod_field product">'+val.prod_name+'</label>'+
                        '<input type="hidden" name="item_ID[]" id="item'+rowId+'_ID" value="'+val.prod_id+'">'+
                    '</td>'+
                    '<td>'+
                        //unit
                        '<label>'+val.unit+'</label>'+
                        '<input type="hidden" name="unit[]" id="unit'+rowId+'" value="'+val.unit+'" readonly>'+
                        //base unit
                        '<input type="hidden" name="base_unit[]" id="base_unit'+rowId+'" value="'+val.base_unit+'">'+
                        //operator value
                        '<input type="hidden" name="operator_value[]" id="operator_value'+rowId+'" value="'+val.operator_value+'">'+
                    '</td>'+
                    '<td >'+
                        '<label id="stock'+rowId+'" class="qty">0</label>'+
                    '</td>'+
                    '<td>'+
                        //qty approved
                        '<input type="text" name="qty_approved[]" id="qty_approved'+rowId+'" class="form-control" value="'+(val.qty_approved - val.remain_qty)+'" readonly>'+
                    '</td>'+
                    '<td>'+
                        //qty delivered
                        '<input type="number" step="any" name="qty_delivered[]" id="qty_delivered'+rowId+'" class="form-control" onblur="checkQty('+rowId+')" required>'+
                        '<label id="qty_error'+rowId+'" class="text-danger"></label>'+
                    '</td>'+
                    /*
                    '<td>'+
                        //batch
                        '<input type="text"  name="batch[]" id="batch'+rowId+'" class="form-control" >'+
                    '</td>'+
                    '<td>'+
                        //expiry date
                        '<input type="date"  name="expire_date[]" id="expire_date'+rowId+'" class="form-control" >'+
                    '</td>'+ */
                    '<td>'+
                        //remarks
                        '<input type="text"  name="remark[]" id="remark'+rowId+'" class="form-control prod_field">'+
                    '</td>'+
                '</tr>';
                $('#voucher').append(rw);
                $('#app_date').html(val.date);
                $('#sale_order_by').html(val.user_name);
                $('#customer').html(val.cust_name);
                $('#customer_id').val(val.cust_id); 
                $('#cust_address_label').html(val.cust_address);
                $('#cust_location_label').html(val.cust_town);
                $('#note').html(val.note);

            }     
        }); 
        
    });
   
});
$('#warehouse').on('change',function(){
    if($('#approvel_no').val()==='')
    {
        alert('First select approvel Number');
        $("option:selected").prop("selected", false)
    }
    else
    {
        warehouse_id=$(this).val();
        if(warehouse_id!=='')
        {
            var url = "{{ url('Inventory/getStock')}}";
            $('.product').each(function(i){
                i++
                $.post(url,{warehouse_id:warehouse_id,prod_id:$('#item'+i+'_ID').val(), _token:token},function(data){
                    if(data==='')
                        stock=0;
                    else
                        stock=data;
                    $('#stock'+i).html(stock);
                });
            });
        }
    }
});
});
$( "form" ).submit(function( event ) {
        $('.product').each(function(i){
            i++
            if(parseFloat($('#qty_approved'+i).val()) < parseFloat($('#qty_delivered'+i).val()) || parseFloat($('#qty_delivered'+i).val()) > parseFloat($('#stock'+i).text()))
            {
                $('#qty_error'+i).html('qty delivered must be less than qty approved and stock');
                event.preventDefault();
            }
        });
    });
function checkQty(row)
    {
        if(parseFloat($('#qty_approved'+row).val()) < parseFloat($('#qty_delivered'+row).val()) || parseFloat($('#qty_delivered'+row).val()) > parseFloat($('#stock'+row).text()))
        {
            $('#qty_error'+row).html('qty delivered must be less than qty approved and stock');
        }
        else
        {
            $('#qty_error'+row).html('');
        
        }
    }
</script>
@stop