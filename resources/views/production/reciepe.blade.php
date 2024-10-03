@extends('layout.master')
@section('title', 'Reciepe')
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
var token =  "{{ csrf_token()}}";
var ItemURL = "{{ url('itemSearch') }}";
var getItemDetailURL = "{{ url('getItemDetail') }}";
var rowId=1;
function addRow()
{
    rowId++;
    var row='<tr id="row'+rowId+'">'+
                '<td><button  type="button" class="btn btn-danger btn-sm" onclick="deleteRow(\'row'+rowId+'\');"><i class="zmdi zmdi-delete"></i></button></td>'+
                '<td>'+
                    //type
                    '<select name="type[]" id="type'+rowId+'" class="form-control show-tick ms select2">'+
                        '<option value="Own">Own</option>'+
                        '<option value="Vendor" {{ (( $gin->status ??'') == 'Vendor') ? 'selected' : '' }}>Vendor</option>'+
                    '</select>'+ 
                '</td>'+
                '<td>'+
                    //product
                    '<input type="text" name="item[]" id="item'+rowId+'" class="form-control autocomplete" onkeyup="autoFill(this.id, ItemURL, token)" onblur="getItemDetail('+rowId+');" value="" required autocomplete="off">'+
                    '<input type="hidden" name="item_ID[]" id="item'+rowId+'_ID" required>'+
                '</td>'+
                '<td>'+
                    //description
                    '<input type="text" name="description[]" id="description'+rowId+'"  class="form-control">'+
                '</td>'+
                '<td class="text-center">'+
                    // unit
                    '<label  id="unit_label'+rowId+'"></label>'+
                    '<input type="hidden" name="unit[]" id="unit'+rowId+'"  class="form-control">'+
                '</td>'+
                '<td>'+
                    // qty
                    '<input type="number" step="any" name="qty[]" id="qty'+rowId+'"  class="form-control qty" required>'+
                '</td>'+
            '</tr>';
     $('#voucher').append(row);
}
   
function getItemDetail(number)
{
    itemId = $('#item'+number+'_ID').val();
    warehouseId=$('#warehouse').val();
    $.post("{{ url('getItemDetail') }}",{ id: itemId, _token : "{{ csrf_token()}}" }, function (data){
        $('#description'+number).val(data.name);  
        $('#unit_label'+number).html(data.primary_unit);  
        $('#unit'+number).val(data.primary_unit);  
        
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
            <form method="post" action="{{url('Production/Reciepe/Add')}}">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" id="id" value="{{ $product->id ?? ''  }}">
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <label for="fiscal_year">Product</label>
                            <input type="text" name="product" id="item"  onkeyup="autoFill(this.id, ItemURL, token)"  value="{{ $product->name ?? ''  }}" class="form-control autocomplete" required>
                        </div>   
                    </div>       
                    <div class="row mt-1">
                        <div class="table-responsive">
                            <table id="voucher" class="table table-striped m-b-0">
                                <thead>
                                    <tr class="bg-purple">
                                        <th><button type="button" class="btn btn-primary" style="align:right" onclick="addRow();" >+</button></th>
                                        <th class="table_header ">Type</th>
                                        <th class="table_header ">Product</th>
                                        <th class="table_header ">Description</th>
                                        <th class="table_header table_header_100">Unit</th>  
                                        <th class="table_header table_header_100">Qty</th>   
                                    </tr>
                                </thead>
                                <tbody>
                                @php
                                $count = (isset($reciepe))?count($reciepe):1;
                               
                                for($i=1;$i<=$count; $i++)
                                { 
                                    
                                    if(isset($reciepe))
                                    {
                                        $lineItem = $reciepe[($i-1)];
                                        
                                    }                                    
                                    @endphp                               
                                    <tr id="row{{$i}}" class="rowData">
                                        <td><button  type="button" class="btn btn-danger btn-sm" onclick="deleteRow('row{{$i}}');"><i class="zmdi zmdi-delete"></i></button></td>
                                        <td>
                                            <!-- type -->
                                            <select name="type[]" id="type{{$i}}" class="form-control show-tick ms select2">
                                                <option value="Own" {{(($lineItem->type ??'') == 'Own') ? 'selected' : '' }}>Own</option>
                                                <option value="Vendor" {{(($lineItem->type ??'') == 'Vendor') ? 'selected' : '' }}>Vendor</option>
                                            </select>
                                        </td>
                                        <td>
                                            <!-- product -->
                                            <input type="text" name="item[]" id="item{{$i}}"  onkeyup="autoFill(this.id, ItemURL, token)" onblur="getItemDetail({{$i}});" value="{{ $lineItem->prod_name ?? ''  }}" class="form-control autocomplete" required>
                                            <input type="hidden" name="item_ID[]" id="item{{$i}}_ID" value="{{ $lineItem->prod_id ?? ''  }}" required>
                                        </td>
                                        <td>
                                            <!-- description -->
                                            <input type="text" name="description[]" id="description{{$i}}" value="{{ $lineItem->description ?? ''  }}" class="form-control">
                                        </td>
                                        <td class="text-center">
                                            <!-- unit -->
                                            <label for="" id="unit_label{{$i}}">{{ $lineItem->unit ?? ''  }}</label>
                                            <input type="hidden" name="unit[]" id="unit{{$i}}" value="{{ $lineItem->unit ?? ''  }}" class="form-control">
                                        </td>
                                        <td>
                                            <!-- Qty -->
                                            <input type="number" step="any" name="qty[]" id="qty{{$i}}" value="{{ $lineItem->qty ?? ''  }}"  class="form-control qty" required>
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