@extends('layout.master')
@section('title', 'Opening Balance')
@section('parentPageTitle','Inventory')
@section('parent_title_icon', 'zmdi zmdi-home')
@section('page-style')
<?php  use App\Libraries\appLib; ?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<!--<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">-->
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<link rel="stylesheet" href="{{asset('public/assets/plugins/dropify/css/dropify.min.css')}}"/>
@stop
@section('content')
    <div class="row clearfix">
        <div class="card col-lg-12">
            <div class="body">
            <form method="post" action="{{url('Inventory/OpeningBalance/Add')}}" autocomplete="off">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <label>Date</label>
                            <div class="form-group">
                                <input type="date" name="date" id='date'  class="form-control"  required>
                            </div>
                        </div>  
                        <div class="col-lg-3 col-md-6">
                            <label>Warehouse</label>
                            <div class="form-group">
                                <select name="warehouse" id="warehouse" class="form-control show-tick ms select2" data-placeholder="Select" required>
                                   <option  value="">Select Warehouse</option>
                                    @foreach($warehouses as $warehouse)
                                    <option value="{{$warehouse->id}}" {{ ( $warehouse->id == ( $opening_balance[0]->warehouse_id ??'')) ? 'selected' : '' }}>{{$warehouse->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>  
                    </div>
                    <div class="row mt-1">
                        <div class="table-responsive">
                            <table id="opening_balance_table" class="table table-striped m-b-0">
                                <thead>
                                    <tr class="bg-purple">
                                        <th class="table_header serial_number">Sr.</th>
                                        <th class="table_header ">Product</th>
                                        <th class="table_header qty">Quantity</th>
                                        <th class="table_header  amount">Rate</th>
                                       
                                    </tr>
                                </thead>
                                <tbody>
                                @php
                                $count = (isset($opening_balance))?count($opening_balance):1;
                               
                                for($i=1;$i<=$count; $i++)
                                { 
                                    
                                    if(isset($opening_balance))
                                    {
                                        $lineItem = $opening_balance[($i-1)];
                                        
                                    }                                    
                                    @endphp                               
                                    <tr id="row{{$i}}" class="rowData">
                                        <td><label class="serial_number">{{$i}}</label> </td>
                                        <td>
                                            <!-- product -->
                                            <input type="text" name="item[]" value="{{ $lineItem->name ?? ''  }}"   class="form-control" readonly>
                                            <input type="hidden" name="item_ID[]" id="item{{$i}}_ID" value="{{ $lineItem->id ?? ''  }}">
                                        </td>
                                        <td>
                                            <!-- Quantity -->
                                            <input type="number" step="any"  name="qty[]" id="qty{{$i}}" class="form-control qty" style="text-align:right" value="{{ $lineItem->balance ?? 0.00 }}">
                                        </td>
                                        <td>
                                            <!-- Rate -->
                                            <input type="number" step="any"  name="rate[]" id="rate{{$i}}" class="form-control amount" style="text-align:right" value="{{ $lineItem->rate ?? '0.00'  }}">
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
    $('#warehouse').on('change',function(){
        $('.rowData').html('');
        var warehouse_id=$(this).val();
        console.log(warehouse_id);
        var token =  "{{ csrf_token()}}";
        var openingBalanceUrl = "{{ url('itemBalanceSearch') }}";
        $.post(openingBalanceUrl,{warehouse_id:warehouse_id, _token:token},function(data){
            data.map(function(val,i){
                var rw='<tr  class="rowData">'+
                        '<td><label class="serial_number">'+(i+1)+'</label></td>'+
                        '<td>'+
                            //product
                            '<input type="text" name="item[]" id="item-'+i+'"    class="form-control" readonly>'+
                            '<input type="hidden" name="item_ID[]" value="'+val.id+'">'+
                        '</td>'+
                        '<td>'+
                            //quantity
                            '<input type="number" step="any" name="qty[]" class="form-control qty " style="text-align:right" value="'+val.balance+'">'+
                        '</td>'+
                        '<td>'+
                            //rate
                            '<input type="number" step="any" name="rate[]" class="form-control amount" style="text-align:right" value="'+val.rate+'">'+
                        '</td>'+
                    '</tr>';
                $('#opening_balance_table').append(rw);
                $('#item-'+i).val(val.code + ' | ' + val.name);
                $('#date').val(val.date);
            });
            
        });
    });
</script>
@stop