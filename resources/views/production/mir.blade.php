@extends('layout.master')
@section('title', 'Material Issue Request')
@section('parentPageTitle', 'Production')
@section('parent_title_icon', 'zmdi zmdi-home')
@section('page-style')

<?php  use App\Libraries\appLib; ?>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
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
var customerURL = "{{ url('customerSearch') }}";
var token =  "{{ csrf_token()}}";
var ItemURL = "{{ url('rawItemSearch') }}";
var getItemDetailURL = "{{ url('getItemDetail') }}";
var TaxRateURL = "{{ url('getTaxRates') }}";
var batchURL = "{{ url('batchSearch') }}";
var rowId=1;
var attachmentURL = "{{ url('attachmentDelete') }}";

function addRow()
{
    let warehouse=$('#warehouse').val();
    if(warehouse!=='')
    {
    rowId++;
    var row='<tr id="row'+rowId+'">'+
                '<td><button  type="button" class="btn btn-danger btn-sm" onclick="deleteRow(\'row'+rowId+'\');"><i class="zmdi zmdi-delete"></i></button></td>'+
                '<td><input type="text" name="item[]" id="item'+rowId+'" class="form-control autocomplete" onkeyup="autoFill(this.id, ItemURL, token)" onblur="getItemDetail('+rowId+');" value="" required autocomplete="off">'+
                '    <input type="hidden" name="item_ID[]" id="item'+rowId+'_ID" required></td>'+
                '<td>'+
                    '<input type="text" name="description[]" id="description'+rowId+'"  class="form-control">'+
                '</td>'+
                '<td class="text-center">'+
                    '<label  id="unit_label'+rowId+'"></label>'+
                    '<input type="hidden" name="unit[]" id="unit'+rowId+'"  class="form-control">'+
                '</td>'+
                '<td>'+
                    '<input type="number" name="qty_in_stock[]" id="s_qty_in_stock'+rowId+'"  class="form-control qty" readonly>'+
                '</td>'+
                '<td>'+
                    '<input type="number" step="any" name="qty_order[]" id="qty_order'+rowId+'"  class="form-control qty" required>'+
                '</td>'+
            '</tr>';
     $('#voucher').append(row);
    }
    else
    {
        alert('fist Select warehouse');
    }
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
            @if(session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
            @endif
            <div class="body">
            <form method="post" action="{{url('Production/MIR/Add')}}">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" id="id" value="{{ $mir->id ?? ''  }}">
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <label for="fiscal_year">MIR #</label><br>
                            <label for="fiscal_year">{{ $mir->month ?? '' }}-{{appLib::padingZero($mir->number  ?? '')}}</label>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label for="date">Date</label>
                            <div class="form-group">
                                <input type="date" name="date" id='vdate'  class="form-control" value="{{ $mir->date ?? date('Y-m-d')  }}"  required>
                            </div>
                        </div>                        
                        <div class="col-lg-3 col-md-6">
                            <label for="fiscal_year">Warehouse</label>
                            <div class="form-group">
                                <select name="warehouse" id="warehouse" class="form-control show-tick ms select2" data-placeholder="Select" required>
                                   <option  value="">Select Warehouse</option>
                                    @foreach($warehouses as $warehouse)
                                    <option value="{{$warehouse->id}}" {{ ( $warehouse->id == ( $mir->warehouse ??'')) ? 'selected' : '' }} >{{$warehouse->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label for="">Person</label>
                            <input type="text" name="person" id="person" value="{{ $mir->person ?? '' }}" placeholder="Person Name" class="form-control">
                        </div>      
                    </div>  
                    <div class="row">
                     <!--
                        <div class="col-lg-3 col-md-6">
                            <label for="fiscal_year">Project</label>
                            <div class="form-group">
                                <select name="project" id="project" class="form-control show-tick ms select2" data-placeholder="Select" required>
                                   <option  value="">Select Project</option>
                                    @foreach($projects as $project)
                                    <option value="{{$project->id}}" {{ ( $project->id == ( $mir->project ??'')) ? 'selected' : '' }} >{{$project->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div> 
                    -->
                        <div class="col-lg-3 col-md-6">
                            <label for="fiscal_year">Department</label>
                            <div class="form-group">
                                <select name="department" id="department" class="form-control show-tick ms select2" data-placeholder="Select" required>
                                   <option  value="">Select Department</option>
                                    @foreach($departments as $department)
                                    <option value="{{$department->id}}" {{ ( $department->id == ( $mir->department ??'')) ? 'selected' : '' }} >{{$department->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div> 
                        <!-- batch number field 
                        <div class="col-lg-3 col-md-6">
                           <label for="fiscal_year">Batch No.</label><br>
                           <input type="text" name="batch_no" id="batch_no" class="form-control" value="{{ $mir->batch ?? '' }}" placeholder="Batch No." onblur="checkDataExist(batchURL,token,this.id,)">
                           <span class="text-danger font-weight-bold" id="error_msg"></span>
                        </div> 
                        -->
                        <div class="col-lg-3 col-md-6">
                            <label for="fiscal_year">Status</label>
                            <div class="form-group">
                                <select name="status" class="form-control show-tick ms select2" data-placeholder="Select" required>
                                   <option  value="">Select Status</option>
                                    <option  value="Completed" {{ (( $mir->status ??'') == 'Completed') ? 'selected' : '' }}>Completed</option>
                                    <option  value="Hold" {{ (( $mir->status ??'') == 'Hold') ? 'selected' : '' }}>Hold</option>
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
                                        <th class="table_header ">Description</th>
                                        <th class="table_header table_header_100">Unit</th>  
                                        <th class="table_header table_header_100">Qty in Stock</th>   
                                        <th class="table_header table_header_100">Qty Order</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                @php
                                $count = (isset($mirDetails))?count($mirDetails):1;
                               
                                for($i=1;$i<=$count; $i++)
                                { 
                                    
                                    if(isset($mirDetails))
                                    {
                                        $lineItem = $mirDetails[($i-1)];
                                        
                                    }                                    
                                    @endphp                               
                                    <tr id="row{{$i}}" class="rowData">
                                        <td><button  type="button" class="btn btn-danger btn-sm" onclick="deleteRow('row{{$i}}');"><i class="zmdi zmdi-delete"></i></button></td>
                                        <td class="autocomplete"><input type="text" name="item[]" id="item{{$i}}" onkeydown="checkWarehouse(this.id)" onkeyup="autoFill(this.id, ItemURL, token)" onblur="getItemDetail({{$i}});" value="{{ $lineItem->name ?? ''  }}" class="form-control autocomplete" required autocomplete="off">
                                            <input type="hidden" name="item_ID[]" id="item{{$i}}_ID" value="{{ $lineItem->prod_id ?? ''  }}" required>
                                        </td>
                                        <td>
                                            <input type="text" name="description[]" id="description{{$i}}" value="{{ $lineItem->description ?? ''  }}" class="form-control">
                                        </td>
                                        <td class="text-center">
                                            <label for="" id="unit_label{{$i}}">{{ $lineItem->unit ?? ''  }}</label>
                                            <input type="hidden" name="unit[]" id="unit{{$i}}" value="{{ $lineItem->unit ?? ''  }}" class="form-control">
                                        </td>
                                        <td>
                                            <input type="number" name="qty_in_stock[]" id="s_qty_in_stock{{$i}}" value="{{ $lineItem->qty_in_stock ?? ''  }}"  class="form-control qty" readonly>
                                        </td>
                                        <td>
                                            <input type="number" step="any" name="qty_order[]" id="qty_order{{$i}}" value="{{ $lineItem->qty_order ?? ''  }}" class="form-control qty" required>
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
                                <textarea name="note" maxlength="120" rows="4" class="form-control no-resize"  placeholder="">{{ $mir->note ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>
                   
                    <div class="row">                        
                        <div class="ml-auto">
                            <button class="btn btn-raised btn-primary waves-effect save" type="submit">Save</button>
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