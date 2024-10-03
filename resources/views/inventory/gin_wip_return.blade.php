@extends('layout.master')
@section('title','GIN-WIP Return')
@section('parentPageTitle', 'Inventory')
@section('parent_title_icon', 'zmdi zmdi-home')
@section('page-style')

<?php  use App\Libraries\appLib; ?>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="{{asset('public/assets/css/sw.css')}}">
<!--<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">-->
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="{{asset('public/assets/js/sw.js')}}"></script>
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
var token =  "{{ csrf_token()}}";
var ItemURL = "{{ url('rawItemSearch') }}";
var getItemDetailURL = "{{ url('getItemDetail') }}";
var rowId=1;
var attachmentURL = "{{ url('attachmentDelete') }}";
function addRow()
{
    rowId++;
    
    var row ='<tr id="row'+rowId+'" class="rowData">'+
                '<td>'+
                    '<button  type="button" class="btn btn-danger btn-sm" onclick="deleteRow(\'row'+rowId+'\');"><i class="zmdi zmdi-delete"></i></button>'+
                '</td>'+
                '<td>'+
                    //product
                    '<input type="text" name="item[]" id="item'+rowId+'" class="form-control product-field" onkeydown="checkWarehouse(this.id)" onkeyup="autoFill(this.id, ItemURL, token)" onblur="getItemDetail('+rowId+');wipDetail('+rowId+')"  required>'+
                    '<input type="hidden" name="item_ID[]" id="item'+rowId+'_ID">'+
                    '<input type="hidden" name="prod_coa_id" id="prod_coa_id'+rowId+'">'+
                '</td>'+
                '<td>'+
                    //description
                    '<input type="text" name="description[]" id="description'+rowId+'" class="form-control field-width">'+
                '</td>'+
                '<td>'+
                    //unit
                    '<input type="text" name="unit[]" id="unit'+rowId+'" class="form-control">'+
                '</td>'+
                '<td class="px-2">'+
                    // '<select name="rate"  class="form-control show-tick ms select2" data-placeholder="Select">'+
                    //     '<option  value="">Select Rate</option>'+
                    // '</select>'+
                    '<input type="number" name="rate[]" id="rate'+rowId+'" class="form-control qty" readonly>'+
                '</td>'+
                '<td>'+
                    '<input type="number" name="qty_in_stock[]" id="qty_in_stock'+rowId+'" class="form-control qty" readonly>'+
                '</td>'+
                '<td>'+
                    '<input type="number" name="qty_return[]" id="qty_return'+rowId+'" class="form-control qty" value="{{  $lineItem->qty_return ?? ''  }}" onblur="checkQty('+rowId+')">'+
                    '<label id="qty_error'+rowId+'" class="text-danger"></label>'+
                '</td>'+
            '</tr>';
    $('#table').append(row);
}

function getItemDetail(number)
{
    itemId = $('#item'+number+'_ID').val();
    warehouseId=$('#warehouse').val();
    $.post("{{ url('getItemDetail') }}",{ id: itemId,warehouseId:warehouseId, _token : "{{ csrf_token()}}" }, function (data){

        $('#detail'+number).val('Sku:'+data.sku);
        $('#description'+number).val(data.name);
        $('#unit'+number).val(data.purchase_unit);
        $('#rate'+number).val(data.purchase_price);
        $('#packing_detail'+number).val(data.packing_detail);
        $('#prod_coa_id'+number).val(data.coa_id);
    });
}
</script>
@stop

@section('content')
    <div class="row clearfix">
        <div class="card col-lg-12">
            <div class="body">
            <form method="post" action="{{url('Inventory/GIN_WIP_Return/Add')}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" id="id" value="{{$gin_wip_return->id ?? ''  }}">
                    <input type="hidden" name="trm_id"  value="{{$gin_wip_return->trm_id ?? ''  }}">
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <label for="fiscal_year">GIN Return #</label><br>
                            <label for="fiscal_year">{{$gin_wip_return->month ?? '' }}-{{appLib::padingZero($gin_wip_return->number  ?? '')}}</label>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label for="date">Date</label>
                            <div class="form-group">
                                <input type="date" name="date" id='vdate'  class="form-control" value="{{ $gin_wip_return->date ?? date('Y-m-d')  }}"  required>
                            </div>
                        </div>                        
                        <div class="col-lg-3 col-md-6">
                            <label for="date">Warehouse</label>
                            <div class="form-group">
                                <select name="warehouse" id="warehouse" class="form-control show-tick ms select2" data-placeholder="Select" required>
                                   <option  value="">Select Warehouse</option>
                                    @foreach($warehouses as $warehouse)
                                    <option value="{{$warehouse->id}}" {{ ($warehouse->id == ( $gin_wip_return->warehouse ??'')) ? 'selected' : '' }}>{{$warehouse->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>                        
                        <div class="col-lg-3 col-md-6">
                            <label >Account</label>
                            <div class="form-group">
                                <select name="wip_coa_id"  class="form-control show-tick ms select2" data-placeholder="Select">
                                   <option  value="">Select Account Name</option>
                                   @foreach($accounts as $account)
                                   <option value="{{$account->id}}" {{ ( $account->id == ( $gin_wip_return->account ??'')) ? 'selected' : '' }}>{{$account->name}}</option>
                                   @endforeach
                                </select>
                            </div>   
                        </div>                        
                    </div>          
                    <div class="row mt-1">
                        <div class="table-responsive">
                            <table id="table" class="table table-striped m-b-0">
                                <thead>
                                    <tr class="bg-purple">
                                        <th><button type="button" class="btn btn-primary" style="align:right" onclick="addRow();" >+</button></th>
                                        <th>Product</th>
                                        <th>Description</th>
                                        <th>Unit</th>    
                                        <th>Rate</th>
                                        <th class="qty">Qty In Stock</th>
                                        <th class="qty">Qty Return</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $count = (isset($gin_wip_return_details))?count($gin_wip_return_details):1;
                                
                                    for($i=1;$i<=$count; $i++)
                                    { 
                                        
                                        if(isset($gin_wip_return_details))
                                        {
                                            $lineItem = $gin_wip_return_details[($i-1)];
                                            
                                        }                                    
                                        @endphp                               
                                        <tr id="row{{$i}}" class="rowData">
                                            <td>
                                                <button  type="button" class="btn btn-danger btn-sm" onclick="deleteRow('row{{$i}}');"><i class="zmdi zmdi-delete"></i></button>
                                            </td>
                                            <td>
                                                <!-- Product -->
                                                <input type="text" name="item[]" id="item{{$i}}" onkeydown="checkWarehouse(this.id)"  onkeyup="autoFill(this.id, ItemURL, token)" onblur="getItemDetail({{$i}});wipDetail({{$i}})" value="{{ $lineItem['prod_name'] ?? ''  }}" class="form-control product-field" required>
                                                <input type="hidden" name="item_ID[]" id="item{{$i}}_ID" value="{{ $lineItem['prod_id'] ?? ''  }}">
                                                <input type="hidden" name="prod_coa_id" id="prod_coa_id{{$i}}" value="{{ $lineItem['prod_coa_id'] ?? ''  }}">
                                            </td>
                                            <td>
                                                <!-- description -->
                                                <input type="text" name="description[]" id="description{{$i}}" class="form-control field-width" value="{{ $lineItem['description'] ?? ''  }}">
                                            </td>
                                            <td>
                                                <!-- unit -->
                                                <input type="text"  name="unit[]" id="unit{{$i}}" value="{{ $lineItem['unit'] ?? ''  }}" class="form-control"  readonly style="width:100px;">
                                            </td>
                                            <td class="px-2">
                                                <!-- <select name="rate"  class="form-control show-tick ms select2" data-placeholder="Select">
                                                    <option  value="">Select Rate</option>
                                                </select> -->
                                                <input type="number" name="rate[]" id="rate{{$i}}" class="form-control qty" value="{{ $lineItem['rate'] ?? ''  }}" readonly>
                                            </td>
                                            <td>
                                                <input type="number" name="qty_in_stock[]" id="qty_in_stock{{$i}}" value="{{  $lineItem['qty_in_stock'] ?? ''  }}" class="form-control qty" readonly>
                                            </td>
                                            <td>
                                                <input type="number" name="qty_return[]" id="qty_return{{$i}}" class="form-control qty" value="{{  $lineItem['qty_return'] ?? ''  }}" onblur="checkQty({{$i}})">
                                                <label id="qty_error{{$i}}" class="text-danger"></label>
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
                                <textarea name="note" maxlength="120" rows="4" class="form-control no-resize"  placeholder="">{{ $gin_wip_return->note ?? '' }}</textarea>
                            </div>
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
function wipDetail(row)
{
    itemId = $('#item'+row+'_ID').val();
    $.post("{{ url('getWipDetail') }}",{ id: itemId, _token : "{{ csrf_token()}}" }, function (data){
        $('#qty_in_stock'+row).val(data.stock);
        $('#rate'+row).val(data.rate);
    });
}
function checkQty(row)
    {
        if(parseFloat($('#qty_return'+row).val()) > parseFloat($('#qty_in_stock'+row).val()))
        {
            $('#qty_error'+row).html('qty return must be less than stock');
            $('#save').hide();
        }
        else
        {
            $('#qty_error'+row).html('');
            $('#save').show();
        }
    }
</script>
@stop