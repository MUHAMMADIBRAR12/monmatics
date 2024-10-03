@extends('layout.master')
@section('title', 'GRN-WIP')
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
var token =  "{{ csrf_token()}}";
var ItemURL = "{{ url('itemSearch') }}";
var reciepeURL = "{{ url('reciepeSearch') }}";
var getItemDetailURL = "{{ url('getItemDetail') }}";
// add row to products issue section
var pi_rowId=1;
function addRow_pi()
{
    pi_rowId++;
    var row ='<tr id="row_pi'+pi_rowId+'">'+
                '<td>'+
                    //delet row button
                    '<button  type="button" class="btn btn-danger btn-sm" onclick="deleteRow(\'row_pi'+pi_rowId+'\');"><i class="zmdi zmdi-delete"></i></button>'+
                '</td>'+
                '<td>'+
                    //product
                    '<input type="text" name="item_pi[]" id="item_pi'+pi_rowId+'" class="form-control"  onkeyup="autoFill(this.id, ItemURL, token)" onblur="getItemDetail(\'pi'+pi_rowId+'\');">'+
                    '<input type="hidden" name="item_pi_ID[]" id="item_pi'+pi_rowId+'_ID" required>'+
                '</td>'+
                '<td>'+
                    //description
                    '<input type="text" name="description_pi[]" id="description_pi'+pi_rowId+'" class="form-control">'+
                '</td>'+
                '<td class="text-center">'+
                    //unit
                    '<label id="unit_label_pi'+pi_rowId+'"></label>'+
                    '<input type="hidden" name="unit_pi[]" id="unit_pi'+pi_rowId+'">'+
                '</td>'+
                '<td>'+
                    //qty 
                    '<input type="number" step="any" name="qty_pi[]" id="qty_pi'+pi_rowId+'"  class="form-control qty qty-sum" onblur="total(\'pi'+pi_rowId+'\')">'+
                '</td>'+
                '<td>'+
                    //Rate
                    '<input type="number" step="any" name="rate_pi[]" id="rate_pi'+pi_rowId+'"  class="form-control qty" onblur="total(\'pi'+pi_rowId+'\')">'+
                '</td>'+
                '<td>'+
                    //amount
                    '<input type="number" step="any" name="amount_pi[]" id="amount_pi'+pi_rowId+'" class="form-control qty amount" readonly>'+
                '</td>'+
            '</tr>';
     $('#pi').append(row);
}
// add row to products used by vendor section
var pv_rowId=1;
function addRow_pv()
{
    pv_rowId++;
    var row ='<tr id="row_pv'+pv_rowId+'">'+
                '<td>'+
                    //delet row button
                    '<button  type="button" class="btn btn-danger btn-sm" onclick="deleteRow(\'row_pv'+pv_rowId+'\');"><i class="zmdi zmdi-delete"></i></button>'+
                '</td>'+
                '<td>'+
                    //product
                    '<input type="text" name="item_pv[]" id="item_pv'+pv_rowId+'" class="form-control"  onkeyup="autoFill(this.id, ItemURL, token)" onblur="getItemDetail(\'pv'+pv_rowId+'\');">'+
                    '<input type="hidden" name="item_pv_ID[]" id="item_pv'+pv_rowId+'_ID" required>'+
                '</td>'+
                '<td>'+
                    //description
                    '<input type="text" name="description_pv[]" id="description_pv'+pv_rowId+'" class="form-control">'+
                '</td>'+
                '<td class="text-center">'+
                    //unit
                    '<label id="unit_label_pv'+pv_rowId+'"></label>'+
                    '<input type="hidden" name="unit_pv[]" id="unit_pv'+pv_rowId+'">'+
                '</td>'+
                '<td>'+
                    //qty 
                    '<input type="number" step="any" name="qty_pv[]" id="qty_pv'+pv_rowId+'"  class="form-control qty qty-sum" onblur="total(\'pv'+pv_rowId+'\')">'+
                '</td>'+
                '<td>'+
                    //Rate
                    '<input type="number" step="any" name="rate_pv[]" id="rate_pv'+pv_rowId+'"  class="form-control qty" onblur="total(\'pv'+pv_rowId+'\')">'+
                '</td>'+
                '<td>'+
                    //amount
                    '<input type="number" step="any" name="amount_pv[]" id="amount_pv'+pv_rowId+'" class="form-control qty amount" readonly>'+
                '</td>'+
            '</tr>';
     $('#pv').append(row);
}
// add row to services used by vendor section
var sv_rowId=1;
function addRow_sv()
{
    sv_rowId++;
    var row ='<tr id="row_sv'+sv_rowId+'">'+
                '<td>'+
                    //delet row button
                    '<button  type="button" class="btn btn-danger btn-sm" onclick="deleteRow(\'row_sv'+sv_rowId+'\');"><i class="zmdi zmdi-delete"></i></button>'+
                '</td>'+
                '<td>'+
                    //product
                    '<input type="text" name="item_sv[]" id="item_sv'+sv_rowId+'" class="form-control">'+
                '</td>'+
                '<td>'+
                    //description
                    '<input type="text" name="description_sv[]" id="description_sv'+sv_rowId+'" class="form-control">'+
                '</td>'+
                '<td class="text-center">'+
                    //unit
                    '<input type="text" name="unit_sv[]" id="unit_sv'+sv_rowId+'" class="form-control">'+
                '</td>'+
                '<td>'+
                    //qty 
                    '<input type="number" step="any" name="qty_sv[]" id="qty_sv'+sv_rowId+'"  class="form-control qty-sum" onblur="total(\'sv'+sv_rowId+'\')">'+
                '</td>'+
                '<td>'+
                    //Rate
                    '<input type="number" step="any" name="rate_sv[]" id="rate_sv'+sv_rowId+'"  class="form-control qty" onblur="total(\'sv'+sv_rowId+'\')">'+
                '</td>'+
                '<td>'+
                    //amount
                    '<input type="number" step="any" name="amount_sv[]" id="amount_sv'+sv_rowId+'" class="form-control qty amount" readonly>'+
                '</td>'+
            '</tr>';
     $('#sv').append(row);
}
   
function getItemDetail(number)
{
    
    itemId = $('#item_'+number+'_ID').val();
    $.post("{{ url('getItemDetail') }}",{ id: itemId, _token : "{{ csrf_token()}}" }, function (data){
        $('#description_'+number).val(data.name);
        $('#unit_label_'+number).html(data.primary_unit);
        $('#unit_'+number).val(data.primary_unit);
        //fill service section 
        $('.unit_sv').val(data.primary_unit);

    });
}

</script>
@stop
@section('content')

    <div class="row clearfix">
        <div class="card col-lg-12">
            
            <div class="body">
            <form method="post" action="{{url('Inventory/GRN_WIP/Add')}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" id="id" value="{{ $grn->id ?? ''  }}">
                    <div class="row">
                        <div class="col-md-2">
                            <label for="fiscal_year">GRN #</label><br>
                            <label for="fiscal_year">{{ $grn->month ?? '' }}-{{appLib::padingZero($grn->number  ?? '')}}</label>
                        </div>
                        <div class="col-md-2">
                            <label for="date">Date</label>
                            <div class="form-group">
                                <input type="date" name="date" id='vdate'  class="form-control" value="{{ $grn->date ?? date('Y-m-d')  }}"  required>
                            </div>
                        </div>                        
                        <div class="col-md-3">
                            <label for="fiscal_year">Warehouse</label>
                            <div class="form-group">
                                <select name="warehouse" class="form-control show-tick ms select2" data-placeholder="Select" required>
                                   <option  value="">Select Warehouse</option>
                                    @foreach($warehouses as $warehouse)
                                    <option value="{{$warehouse->id}}" {{ ( $warehouse->id == ( $grn->warehouse ??'')) ? 'selected' : '' }}>{{$warehouse->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- hide batch 
                        <div class="col-lg-3 col-md-6">
                            <label for="">Batch</label>
                            <div class="form-group">
                                <select name="batch" id="batch" class="form-control show-tick ms select2" data-placeholder="Select" required>
                                   <option  value="">Select Batch No.</option>
                                    @foreach($batch_numbers as $batch_number)
                                    <option value="{{$batch_number->batch}}" {{ ( $batch_number->batch == ( $grn->batch ??'')) ? 'selected' : '' }}>{{$batch_number->batch}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>   
                        --> 
                        <div class="col-md-3">
                            <label for="">Batch</label>
                            <div class="form-group">
                                <input type="text" name="batch" id="batch" class="form-control" value="{{ $grn->batch ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label for="fiscal_year">Status</label>
                            <div class="form-group">
                                <select name="status" class="form-control show-tick ms select2" data-placeholder="Select" required>
                                   <option  value="">Select Status</option>
                                    <option  value="Completed" {{ (($grn->status ??'') == 'Completed') ? 'selected' : '' }}>Completed</option>
                                    <option  value="Hold" {{ (( $grn->status ??'') == 'Hold') ? 'selected' : '' }}>Hold</option>
                                </select>
                            </div>
                        </div>      
                    </div>  
                    <!--
                        <div class="col-lg-3 col-md-6">
                            <label for="">Project</label>
                            <div class="form-group">
                                <select name="project" class="form-control show-tick ms select2" data-placeholder="Select">
                                   <option  value="">Select Project</option>
                                    @foreach($projects as $project)
                                    <option value="{{$project->id}}" {{ ( $project->id == ( $grn->project ??'')) ? 'selected' : '' }}>{{$project->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>  
                    --> 
                    <div class="row mt-2">
                        <div class="col-lg-3 col-md-6">
                            <label for="fiscal_year">Product</label>
                            @if(isset($grn))
                                <br>
                                <label>{{ $grn->prod_name ?? ''}}</label>
                                <input type="hidden" name="item_ID" value="{{ $grn->prod_id ?? ''}}">
                            @else
                                <input type="text" name="product" id="item_1"  onkeyup="autoFill(this.id,reciepeURL, token)" onblur="getItemDetail('1')"  class="form-control" required>
                                <input type="hidden" name="item_ID" id="item_1_ID">
                            @endif
                        </div> 
                        <div class="col-lg-2 col-md-6">
                            <label>Unit</label><br>
                            <label id="unit_label_1" style="color:#bababa">{{ $grn->unit ?? ''}}</label>
                            <input type="hidden" name="unit" id="unit_1" value="{{ $grn->unit ?? ''}}">
                        </div> 
                        <div class="col-lg-2 col-md-6">
                            <label for="fiscal_year">Qty Received</label>
                            <input type="number" step="any" name="qty_received" id="qty_received"  onblur="unitCost();checkBalance(-1);fillServiceSection();totalSection()"  value="{{ $grn->qty_in ?? '0.00'  }}" class="form-control qty" required>
                            <span id="qty_recved_error" class="text-danger"></span>
                        </div> 
                        <div class="col-lg-3 col-md-6">
                            <label for="">Total Product Cost</label><br>
                            <label id="product_cost_label" style="color:#bababa">{{  $grn->amount ?? '0.00'  }}</label>
                            <input type="hidden" name="production_cost" id="product_cost" value="{{ $grn->amount ?? '0.00'  }}">
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <label for="">Unit Cost</label><br>
                            <label id="unit_product_cost_label" style="color:#bababa">{{ $grn->rate ?? '0.00'  }}</label>
                            <input type="hidden" name="unit_production_cost" id="unit_product_cost" value="{{ $grn->rate ?? '0.00'  }}">
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-lg-3 col-md-6">
                            <label>Account</label>
                            <div class="form-group">
                                <select name="wip_coa_id"  class="form-control show-tick ms select2" data-placeholder="Select">
                                   <option  value="">Select Account Name</option>
                                   @foreach($accounts as $account)
                                   <option value="{{$account->id}}" {{ ( $account->id == ( $grn->wip_coa_id ??'')) ? 'selected' : '' }}>{{$account->name}}</option>
                                   @endforeach
                                </select>
                            </div>   
                        </div>    
                    </div>
                    <!-- products issue section start -->  
                    <!-- for defereniate id from other section id i use (pi) with id name for this section --> 
                    <div class="row">
                        <div class="table-responsive">
                            <table id="pi" class="table table-striped m-b-0">
                                <thead>
                                    <tr class="bg-purple">
                                     <!--   <th><button type="button" class="btn btn-primary" style="align:right" onclick="addRow_pi();" >+</button></th> -->
                                        <th class="table_header ">Product</th>
                                        <th class="table_header">Description</th>
                                        <th class="table_header table_header_100">Unit</th>     
                                        <th class="table_header table_header_100">Qty </th>
                                        <th class="table_header table_header_100">Rate</th>
                                        <th class="table_header table_header_100">Amount</th>  
                                    </tr>
                                </thead>
                                <tbody>
                                @php
                                $count = (isset($pi_detail_arr))?count($pi_detail_arr):1;
                               
                                for($i=1;$i<=$count; $i++)
                                { 
                                    
                                    if(isset($pi_detail_arr))
                                    {
                                        $pi_lineItem = $pi_detail_arr[($i-1)];
                
                                    }                                    
                                    @endphp                               
                                    <tr id="row_pi{{$i}}" class="piRowData">
                                       <!-- <td><button  type="button" class="btn btn-danger btn-sm" onclick="deleteRow('row_pi{{$i}}');"><i class="zmdi zmdi-delete"></i></button></td> -->
                                        <td>
                                            <!-- product -->
                                            <input type="text" name="item_pi[]" id="item_pi{{$i}}" onkeyup="autoFill(this.id, ItemURL, token)" onblur="getItemDetail('pi{{$i}}');" value="{{ $pi_lineItem['prod_name'] ?? ''  }}" class="form-control ">
                                            <input type="hidden" name="item_pi_ID[]" id="item_pi{{$i}}_ID" value="{{ $pi_lineItem['prod_id'] ?? ''  }}">
                                        </td>
                                        <td>
                                            <!-- description -->
                                            <input type="text" name="description_pi[]" id="description_pi{{$i}}" value="{{ $pi_lineItem['description'] ?? ''  }}" class="form-control" >
                                        </td>
                                        <td class="text-center">
                                            <!-- unit -->
                                            <label for="" id="unit_label_pi{{$i}}">{{ $pi_lineItem['unit'] ?? ''  }}</label>
                                            <input type="hidden" name="unit_pi[]" id="unit_pi{{$i}}" value="{{ $pi_lineItem['unit'] ?? ''  }}" >
                                        </td>
                                        <td>
                                            <!-- qty  -->  
                                            <input type="hidden" name="pre_qty_pi[]" class="pv_qty" value="{{ $pi_lineItem['pre_qty'] ?? ''  }}">
                                            <!--qty -->
                                            <input type="number" step="any" name="qty_pi[]" id="qty_pi{{$i}}"  value="{{ $pi_lineItem['qty'] ?? ''  }}" class="form-control qty-sum qty qty-issue total_qty_pi" onblur="total('pi{{$i}}')" readonly>
                                            <!-- balance -->
                                            <input type="hidden" id="balance_pi{{$i}}" value="{{ $pi_lineItem['balance'] ?? ''  }}">
                                            <span id="show_msg{{$i}}" class="text-danger"></span>
                                        </td>
                                        <td>
                                            <!--Rate -->
                                            <input type="number" step="any" name="rate_pi[]" id="rate_pi{{$i}}"  value="{{ $pi_lineItem['rate'] ?? ''  }}" class="form-control qty" onblur="total('pi{{$i}}')">
                                        </td>
                                        <td>
                                            <!-- Amount -->
                                            <input type="number" step="any" name="amount_pi[]" id="amount_pi{{$i}}" value="{{ $pi_lineItem['amount'] ?? ''  }}" class="form-control amount qty total_amount_pi" readonly>
                                        </td>
                                    </tr>
                                   
                                
                                @php 
                                $num=$i;    
                                } 
                                @endphp
                                <tr  class="piRowData">
                                        
                                        <td></td>
                                        <td></td>
                                        <td class="qty">Total</td>
                                        <td class="qty" id="total_qty_pi">0.00000</td>
                                        <td></td>
                                        <td class="qty" id="total_amount_pi">0.00000</td>
                                </tr>
                                </tbody>  
                                <script>                                    
                                    pi_rowId = {{$i}};
                                </script>
                            </table>
                        </div>
                    </div>
                    <!-- products issue section end -->

                    <!-- Products Used By Vendor section start -->  
                    <!-- for defereniate id from other section id i use (pv) with id name for this section --> 
                    <div class="row">
                        <div class="table-responsive">
                            <table id="pv" class="table table-striped m-b-0">
                                <thead>
                                    <tr class="bg-purple">
                                       <!-- <th><button type="button" class="btn btn-primary" style="align:right" onclick="addRow_pv();" >+</button></th> -->
                                        <th class="table_header "> Product</th>
                                        <th class="table_header">Description</th>
                                        <th class="table_header table_header_100">Unit</th>     
                                        <th class="table_header table_header_100">Qty </th>
                                        <th class="table_header table_header_100">Rate</th>
                                        <th class="table_header table_header_100">Amount</th>  
                                    </tr>
                                </thead>
                                <tbody>
                                @php
                                $count = (isset($pv_detail))?count($pv_detail):1;
                               
                                for($i=1;$i<=$count; $i++)
                                { 
                                    ++$num;
                                    if(isset($pv_detail))
                                    {
                                        $pv_lineItem = $pv_detail[($i-1)];
                                        
                                    }                                    
                                    @endphp                               
                                    <tr id="row_pv{{$i}}" class="pvRowData">
                                     <!--   <td><button  type="button" class="btn btn-danger btn-sm" onclick="deleteRow('row_pv{{$i}}');"><i class="zmdi zmdi-delete"></i></button></td>  -->
                                        <td>
                                            <!-- product -->
                                            <input type="text" name="item_pv[]"    value="{{ $pv_lineItem->prod_name ?? ''  }}" class="form-control ">
                                            <input type="hidden" name="item_pv_ID[]"  value="{{ $pv_lineItem->prod_id ?? ''  }}">
                                        </td>
                                        <td>
                                            <!-- description -->
                                            <input type="text" name="description_pv[]"  value="{{ $pv_lineItem->description ?? ''  }}" class="form-control" >
                                        </td>
                                        <td class="text-center">
                                            <!-- unit -->
                                            <label>{{ $pv_lineItem->unit ?? ''  }}</label>
                                            <input type="hidden" name="unit_pv[]"  value="{{ $pv_lineItem->unit ?? ''  }}" >
                                        </td>
                                        <td>
                                            <!-- qty  -->  
                                            <input type="hidden" name="pre_qty_pv[]" class="pv_qty" value="{{ $pv_lineItem->pre_qty ?? ''  }}">
                                            <!--qty -->
                                            <input type="number" step="any" name="qty_pv[]" id="qty_pi{{$num}}"  value="{{ $pv_lineItem->qty ?? ''  }}" class="form-control qty-sum qty total_qty_pv" onblur="total('pi{{$num}}')">
                                            <!-- balance -->
                                            <input type="hidden" id="balance_pi{{$num}}" value="0">
                                            <span id="show_msg{{$num}}" class="text-danger"></span>
                                        </td>
                                        <td>
                                            <!--Rate -->
                                            <input type="number" step="any" name="rate_pv[]" id="rate_pi{{$num}}"  value="{{ $pv_lineItem->rate ?? ''  }}" class="form-control qty" onblur="total('pi{{$num}}')">
                                        </td>
                                        <td>
                                            <!-- Amount -->
                                            <input type="number" step="any" name="amount_pv[]" id="amount_pi{{$num}}" value="{{ $pv_lineItem->amount ?? ''  }}" class="form-control qty amount total_amount_pv" readonly>
                                        </td>
                                    </tr>
                                @php 
                                        
                                    } 
                                @endphp
                                <tr  class="pvRowData">
                                       
                                       <td></td>
                                       <td></td>
                                       <td class="qty">Total</td>
                                       <td class="qty" id="total_qty_pv">0.00000</td>
                                       <td></td>
                                       <td class="qty" id="total_amount_pv">0.00000</td>
                                </tr>
                                </tbody>  
                                <script>                                    
                                    pv_rowId = {{$i}};
                                </script>
                                
                            </table>
                        </div>
                    </div>
                    <!-- Products Used By Vendor section end --> 

                    <!-- Services Used By Vendor section start --> 
                    <!-- for defereniate id from other section id i use (sv) with id name for this section -->
                    <div class="row">
                        <div class="table-responsive">
                            <table id="sv" class="table table-striped m-b-0">
                                <thead>
                                    <tr class="bg-purple">
                                      <!--  <th><button type="button" class="btn btn-primary" style="align:right" onclick="addRow_sv();" >+</button></th>  -->
                                        <th class="table_header ">Service</th>
                                        <th class="table_header">Description</th>
                                        <th class="table_header table_header_100">Unit</th>     
                                        <th class="table_header table_header_100">Qty </th>
                                        <th class="table_header table_header_100">Rate</th>
                                        <th class="table_header table_header_100">Amount</th>  
                                    </tr>
                                </thead>
                                <tbody>
                                @php
                                $count = (isset($sv_detail))?count($sv_detail):1;
                               
                                for($i=1;$i<=$count; $i++)
                                { 
                                    
                                    if(isset($sv_detail))
                                    {
                                        $sv_lineItem = $sv_detail[($i-1)];
                                        
                                    }                                    
                                    @endphp                               
                                    <tr id="row_sv{{$i}}">
                                      <!--  <td><button  type="button" class="btn btn-danger btn-sm" onclick="deleteRow('row_sv{{$i}}');"><i class="zmdi zmdi-delete"></i></button></td> -->
                                        <td>
                                            <!-- service -->
                                            <input type="text" name="item_sv[]" id="item_sv{{$i}}"   value="{{ $sv_lineItem->name ?? ''  }}" class="form-control ">
                                        </td>
                                        <td>
                                            <!-- description -->
                                            <input type="text" name="description_sv[]" id="description_sv{{$i}}" value="{{ $sv_lineItem->description ?? ''  }}" class="form-control" >
                                        </td>
                                        <td class="text-center">
                                            <!-- unit -->
                                            <input type="text" name="unit_sv[]" id="unit_sv{{$i}}" value="{{ $sv_lineItem->unit ?? ''  }}" class="form-control unit_sv">
                                        </td>
                                        <td>
                                            <!--qty -->
                                            <input type="number" step="any" name="qty_sv[]" id="qty_sv{{$i}}"  value="{{ $sv_lineItem->qty ?? ''  }}" class="form-control qty-sum qty qty_sv total_qty_sv" onblur="total('sv{{$i}}');totalSection()">
                                        </td>
                                        <td>
                                            <!--Rate -->
                                            <input type="number" step="any" name="rate_sv[]" id="rate_sv{{$i}}"  value="{{ $sv_lineItem->rate ?? ''  }}" class="form-control qty" onblur="total('sv{{$i}}');totalSection()">
                                        </td>
                                        <td>
                                            <!-- Amount -->
                                            <input type="number" step="any" name="amount_sv[]" id="amount_sv{{$i}}" value="{{ $sv_lineItem->amount ?? ''  }}" class="form-control qty amount qty total_amount_sv" readonly>
                                        </td>
                                    </tr>
                                    <tr>
                                        
                                        <td></td>
                                        <td></td>
                                        <td class="qty">Total</td>
                                        <td class="qty" id="total_qty_sv">0.00000</td>
                                        <td></td>
                                        <td class="qty" id="total_amount_sv">0.00000</td>
                                    </tr>
                                @php 
                                        
                                    } 
                                @endphp
                                </tbody>  
                                <script>                                    
                                    sv_rowId = {{$i}};
                                </script>
                            </table>
                        </div>
                    </div>
                    <!-- Services Used By Vendor section end --> 
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
<script>
totalSection();
$(document).ready(function(){
//  add products used by vendor after selecting a product which is called as reciepe here
$('#item_1').on('blur',function(){
    $('.piRowData').html('');
    $('.pvRowData').html('');
    var reciepe=$(this).val();
    var url = "{{ url('Inventory/aj_ReciepeDetail')}}";
    pv_rowId=0;
    $.post(url,{reciepe:reciepe, _token:token},function(data){
        
        data.map(function(val,i){
            if(val.type==='Own')
            {
                pv_rowId++;
                var rw='<tr id="row_pv'+pv_rowId+'" class="pvRowData">'+
                    // '<td>'+
                    //     //add new row
                    //     '<button  type="button" class="btn btn-danger btn-sm" onclick="deleteRow(\'row_pv'+pv_rowId+'\')"><i class="zmdi zmdi-delete"></i></button>'+
                    // '</td>'+
                    '<td>'+
                        //product
                        '<input type="text" name="item_pi[]" id="item'+pv_rowId+'" value="'+val.prod_name+'" class="form-control"  onkeyup="autoFill(this.id, ItemURL, token)" onblur="getItemDetail('+pv_rowId+');" required>'+
                        '<input type="hidden" name="item_pi_ID[]" id="item'+pv_rowId+'_ID" value="'+val.prod_id+'">'+
                    '</td>'+
                    '<td class="text-center">'+
                        //description
                        '<input type="text" name="description_pi[]" id="description'+pv_rowId+'" value="'+val.description+'" class="form-control">'+
                    '</td>'+
                    '<td class="text-center">'+
                        // unit
                        '<label>'+val.base_unit+'</label>'+
                        '<input type="hidden" name="unit_pi[]" value="'+val.base_unit+'">'+
                    '</td>'+
                    '<td>'+
                        //balance
                        '<input type="hidden" id="balance_pi'+pv_rowId+'" value="'+val.balance+'">'+
                        //previous qty
                        '<input type="hidden" name="pre_qty_pi[]"  class="pv_qty" value="'+val.qty+'" >'+
                        //qty
                        '<input type="number" step="any" name="qty_pi[]" id="qty_pi'+pv_rowId+'" value="'+val.qty+'" class="form-control qty  qty-sum qty-issue total_qty_pi" onblur="checkBalance('+pv_rowId+');total(\'pi'+pv_rowId+'\');totalSection()">'+   
                        //error msg
                        '<span id="show_msg'+pv_rowId+'" class="text-danger"></span>'+
                    '</td>'+
                    '<td>'+
                        //rate  
                        '<input type="number" step="any" name="rate_pi[]" id="rate_pi'+pv_rowId+'" value="'+val.rate+'" class="form-control qty" onblur="total(\'pi'+pv_rowId+'\')" readonly>'+   
                    '</td>'+
                    '<td>'+
                        //amount
                        '<input type="number" step="any" name="amount_pi[]" id="amount_pi'+pv_rowId+'"  class="form-control amount qty total_amount_pi" readonly >'+   
                    '</td>'+
                '</tr>';
                
                $('#pi').append(rw);
            }
            else
            {
                pv_rowId++;
                    var rw='<tr id="row_pv'+pv_rowId+'" class="pvRowData">'+
                    // '<td>'+
                    //     //add new row
                    //     '<button  type="button" class="btn btn-danger btn-sm" onclick="deleteRow(\'row_pv'+pv_rowId+'\')"><i class="zmdi zmdi-delete"></i></button>'+
                    // '</td>'+
                    '<td>'+
                        //product
                        '<input type="text" name="item_pv[]" id="item'+pv_rowId+'" value="'+val.prod_name+'" class="form-control"  onkeyup="autoFill(this.id, ItemURL, token)" onblur="getItemDetail('+pv_rowId+');" required>'+
                        '<input type="hidden" name="item_pv_ID[]" id="item'+pv_rowId+'_ID" value="'+val.prod_id+'">'+
                    '</td>'+
                    '<td class="text-center">'+
                        //description
                        '<input type="text" name="description_pv[]" id="description'+pv_rowId+'" value="'+val.description+'" class="form-control">'+
                    '</td>'+
                    '<td class="text-center">'+
                        // unit
                        '<label>'+val.base_unit+'</label>'+
                        '<input type="hidden" name="unit_pv[]" value="'+val.base_unit+'">'+
                    '</td>'+
                    '<td>'+
                        //previous qty
                        '<input type="hidden" name="pre_qty_pv[]" class="pv_qty" value="'+val.qty+'" >'+
                        //qty
                        '<input type="number" step="any" name="qty_pv[]" id="qty_pi'+pv_rowId+'" value="'+val.qty+'" class="form-control qty total_qty_pv  qty-sum total_qty_pv" onblur="total(\'pi'+pv_rowId+'\');totalSection()">'+   
                    '</td>'+
                    '<td>'+
                        //rate
                        '<input type="number" step="any" name="rate_pv[]" id="rate_pi'+pv_rowId+'"  class="form-control qty total_rate_pv" onblur="total(\'pi'+pv_rowId+'\');totalSection()">'+   
                    '</td>'+
                    '<td>'+
                        //amount
                        '<input type="number" step="any" name="amount_pv[]" id="amount_pi'+pv_rowId+'"  class="form-control amount qty total_amount_pv" readonly >'+   
                    '</td>'+
                '</tr>';
                
                $('#pv').append(rw);
            }
        }); 

        piTotalRow = '<tr class="pvRowData">'+
                    '<td></td>'+
                    '<td></td>'+
                    '<td class="qty">Total</td>'+
                    '<td class="qty" id="total_qty_pi">0.00000</td>'+
                    '<td></td>'+
                    '<td class="qty" id="total_amount_pi">0.00000</td>'+
                '</tr>';
        $('#pi').append(piTotalRow);

        $('#pv').append('<tr>'+
                    
                    '<td></td>'+
                    '<td></td>'+
                    '<td class="qty">Total</td>'+
                    '<td class="qty" id="total_qty_pv">0.00000</td>'+
                    '<td></td>'+
                    '<td class="qty" id="total_amount_pv">0.00000</td>'+
        '</tr>');
    });
});
});
function total(num)
{
    
   var amount=getNum($('#qty_'+num).val()) * getNum($('#rate_'+num).val());
   console.log (amount);
   $('#amount_'+num).val(getNum(amount,5));
   var production_cost=sumAll('amount');
   $('#product_cost_label').html(production_cost);
   $('#product_cost').val(production_cost);
   unitCost(-1);
   
}

function unitCost(num=null)
{
    var production_cost=sumAll('amount');
    $('#product_cost_label').html(production_cost);
    $('#product_cost').val(production_cost);
    var qty_received=getNum($('#qty_received').val(),5);
    if(qty_received>0)
    {
       // console.log('unit cost');
        var unit_production_cost=getNum((production_cost/qty_received),2);
        $('#unit_product_cost_label').html(unit_production_cost);
        $('#unit_product_cost').val(unit_production_cost);
        if(num!==-1)
        {
           
            $('.pv_qty').each(function(i){
                i++;
                $('#qty_pi'+i).val(getNum(getNum($(this).val(),5) * getNum(qty_received,5),5));
                $('#amount_pi'+i).val(getNum(getNum($('#qty_pi'+i).val(),5) * getNum($('#rate_pi'+i).val(),5),5));
                production_cost=sumAll('amount');
                $('#product_cost_label').html(production_cost);
                $('#product_cost').val(production_cost);

                unit_production_cost=getNum((production_cost/qty_received),2);
                $('#unit_product_cost_label').html(unit_production_cost);
                $('#unit_product_cost').val(unit_production_cost);  
            });
        }
    }
    else
    {
        $('#unit_product_cost_label').html(0);
        $('#unit_product_cost').val(0);
    }

    //multiply the receipe received qty to qty used  line item of product used by vendor section
    
}

//this function is called on bulr of qty received and aso called on blur of row line qty.
//the functionality of this function is it veify if product has maximum stock(stock is balance which is hidden) then qty it allow to assume otherwise not.
function checkBalance(num)
{
    
    if(num===-1)
    {
        $('.qty-issue').each(function(i){
            i++
            qty=getNum($('#qty_pi'+i).val(),5);
            console.log(qty);
            balance=getNum($('#balance_pi'+i).val(),5);
            console.log(balance);
            if(parseFloat(qty) > parseFloat(balance))
            {
                $('#show_msg'+i).html('Soory we not have such quantity value');
                $('.save').hide();   
            }
            else
            {
                $('#show_msg'+i).html('');
                $('.save').show();  
            }
            num++;
        });
    }
    else
    {
        qty=getNum($('#qty_pi'+num).val(),5);
        console.log(qty);
        balance=getNum($('#balance_pi'+num).val(),5);
        console.log(balance);
        if(parseFloat(qty) > parseFloat(balance))
        {
            $('#show_msg'+num).html('Soory we not have such quantity value'); 
            $('.save').hide(); 
        }
        else
        {
            $('#show_msg'+num).html('');
            $('.save').show(); 
            //console.log( $('#balance_pv'+num).val());
        }
    }

}
$( "form" ).submit(function( event ){
    if(parseFloat($('#qty_received').val()) <= 0)
    {
        event.preventDefault();
        $('#qty_recved_error').html('Qty Must be Greater Than 0');
    }
    else
    {
        $('#qty_recved_error').html('');
    }

})

//add unti and qty received value to service section 
function fillServiceSection()
{
    $('.unit_sv').val($('#unit_1').val());
    $('.qty_sv').val($('#qty_received').val());
}

function totalSection()
{
    $('#total_qty_pi').html(sumAll('total_qty_pi'));
    $('#total_amount_pi').html(sumAll('total_amount_pi'));
    $('#total_qty_pv').html(sumAll('total_qty_pv'));
    $('#total_amount_pv').html(sumAll('total_amount_pv'));
    $('#total_qty_sv').html(sumAll('total_qty_sv'));
    $('#total_amount_sv').html(sumAll('total_amount_sv'));
}
</script>
@stop