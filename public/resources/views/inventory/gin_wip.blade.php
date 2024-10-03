@extends('layout.master')
@section('title', 'MIR-Apprvoel')
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
var ItemURL = "{{ url('itemSearch') }}";
var getItemDetailURL = "{{ url('getItemDetail') }}";
var rowId=1;
var attachmentURL = "{{ url('attachmentDelete') }}";

/*function addRow()
{
    let warehouse=$('#warehouse').val();
    if(warehouse!=='')
    {
    rowId++;
    var row ='<tr id="row'+rowId+'" class="rowData">'+
                '<td><button  type="button" class="btn btn-danger btn-sm" onclick="deleteRow(\'row'+rowId+'\');"><i class="zmdi zmdi-delete"></i></button></td>'+
                '<td><input type="text" name="item[]" id="item'+rowId+'" class="form-control autocomplete" onkeyup="autoFill(this.id, ItemURL, token)" onblur="getItemDetail('+rowId+');" required>'+
                '    <input type="hidden" name="item_ID[]" id="item'+rowId+'_ID" required></td>'+
                '<td>'+   
                '<textarea name="description[]" id="description'+rowId+'"  class="form-control desc-textarea no-resize"></textarea>'+
                '</td>'+
                '<td>'+
                '   <input type="text"  name="unit[]" id="unit'+rowId+'" class="form-control"  readonly>'+
                '</td>'+ 
                '<td>'+
                '   <input type="number" step="any" name="qty_in_stock[]" id="qty_in_stock'+rowId+'"  class="form-control qty" readonly>'+
                '</td>'+
                '<td>'+
                '   <input type="number" step="any" name="qty_order[]" id="qty_order'+rowId+'"  class="form-control qty" required>'+
                '</td>'+
                '<td>'+
                '<input type="number" step="any" name="qty_issue[]" id="qty_issue'+rowId+'"  class="form-control qty" required>'+
                '</td>'+
            '</tr>';
     $('#voucher').append(row);
    }
    else
    {
        alert('fist Select warehouse');
    }
} */

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
            <form method="post" action="{{url('Inventory/GIN_WIP/Add')}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $gin_wip->id ?? ''  }}">
                    <input type="hidden" name="trans_id"  value="{{ $gin_wip->trans_id ?? ''  }}">
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <label for="fiscal_year">GIN #</label><br>
                            <label for="fiscal_year">{{ $gin_wip->month ?? '' }}-{{appLib::padingZero($gin_wip->number  ?? '')}}</label>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label for="date">Date</label>
                            <div class="form-group">
                                <input type="date" name="date" id='vdate'  class="form-control" value="{{ $gin_wip->date ?? date('Y-m-d')  }}"  required>
                            </div>
                        </div>                        
                        <div class="col-lg-3 col-md-6">
                            <label for="fiscal_year">Warehouse</label>
                            <div class="form-group">
                                <select name="warehouse" id="warehouse" class="form-control show-tick ms select2" data-placeholder="Select" required>
                                   <option  value="">Select Warehouse</option>
                                    @foreach($warehouses as $warehouse)
                                    <option value="{{$warehouse->id}}" {{ ( $warehouse->id == ( $gin_wip->warehouse ??'')) ? 'selected' : '' }} >{{$warehouse->name}}</option>
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
                                   <option value="{{$account->id}}" {{ ( $account->id == ( $gin_wip->wip_coa_id ??'')) ? 'selected' : '' }}>{{$account->name}}</option>
                                   @endforeach
                                </select>
                            </div>   
                        </div>      
                    </div>  
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <label for="fiscal_year">Material Issue Request</label>
                            @if(isset($gin_wip->wip_id))
                            <label>{{($gin_wip->mir_month ?? '').'-'.appLib::padingZero($gin_wip->mir_number  ?? '')}}</label>
                            @else
                            <div class="form-group">
                                <select name="mir_num" id="mir_num" class="form-control show-tick ms select2" data-placeholder="Select" required>
                                   <option  value="">Select Material Issue Number</option>
                                   @foreach($mirNumbers as $mirNumber)
                                   <option value="{{$mirNumber->id}}">{{$mirNumber->doc_number}}</option>
                                   @endforeach
                                </select>
                            </div> 
                            @endif
                        </div> 
                        <div class="col-lg-3 col-md-6">
                           <label>M.I.R Date</label><br>
                           <label id="mir_date">{{ $gin_wip->mir_date ?? '-----'}}</label>
                        </div> 
                        <!-- 
                        <div class="col-lg-3 col-md-6">
                            <label>Batch</label><br>
                            <label  id="mir_batch">{{ $gin->inv_date ?? 'xxxxxx'}}</label>
                            <input type="hidden" name="batch" id="mir_batch_input">
                        </div> 
                        -->
                        <div class="col-lg-3 col-md-6">
                            <label>Department</label><br>
                            <label  id="department_name">{{ $gin_wip->department ?? 'xxxxxx'}}</label>
                            <input type="hidden" name="department" id="department_id">
                        </div> 
                        <div class="col-lg-3 col-md-6">
                            <label for="fiscal_year">Status</label>
                            <div class="form-group">
                                <select name="status" class="form-control show-tick ms select2" data-placeholder="Select" required>
                                   <option  value="">Select Status</option>
                                    <option  value="Completed" {{ (( $gin_wip->status ??'') == 'Completed') ? 'selected' : '' }}>Completed</option>
                                    <option  value="Hold" {{ (( $gin_wip->status ??'') == 'Hold') ? 'selected' : '' }}>Hold</option>
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
                                        <th class="table_header">Description</th>
                                        <th class="table_header table_header_100">Unit</th>  
                                        <th class="table_header table_header_100">Qty in Stock</th>   
                                        <th class="table_header table_header_100">Qty Order</th>
                                        <th class="table_header table_header_100">Qty Issue</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @php
                                $count = (isset($gin_wip_details))?count($gin_wip_details):1;
                               
                                for($i=1;$i<=$count; $i++)
                                { 
                                    
                                    if(isset($gin_wip_details))
                                    {
                                        $lineItem = $gin_wip_details[($i-1)];
                                        
                                    }                                    
                                    @endphp                               
                                    <tr id="row{{$i}}" class="rowData">
                                        <td>
                                            <button  type="button" class="btn btn-danger btn-sm" onclick="deleteRow('row{{$i}}');"><i class="zmdi zmdi-delete"></i></button>
                                        </td>
                                        <td>
                                            <!-- mir detail id -->
                                            <input type="hidden" name="mir_detail_id[]" value="{{ $lineItem->mir_detail_id ?? ''  }}">
                                            <input type="text" name="item[]" id="item{{$i}}" onkeydown="checkWarehouse(this.id)" onkeyup="autoFill(this.id, ItemURL, token)" onblur="getItemDetail({{$i}});" value="{{ $lineItem->prod_name ?? ''  }}" class="form-control autocomplete" required autocomplete="off">
                                            <input type="hidden" name="item_ID[]" id="item{{$i}}_ID" value="{{ $lineItem->prod_id ?? ''  }}" required>
                                        </td>
                                        <td>
                                            <textarea name="description[]" id="description{{$i}}"  class="form-control desc-textarea no-resize">{{ $lineItem->description ?? ''  }}</textarea>
                                        </td>
                                        <td>
                                            <!-- sale unit -->
                                            <input type="text" name="unit[]" id="unit{{$i}}" class="form-control" value="{{ $lineItem->unit ?? ''  }}">
                                            <!-- base unit -->
                                            <input type="hidden"  name="base_unit[]" value="{{ $lineItem->unit_name ?? ''  }}">
                                            <input type="hidden" name="operator_value[]" value="{{ $lineItem->operator_value ?? ''  }}">
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" name="qty_in_stock[]" id="qty_in_stock{{$i}}"  class="form-control qty" readonly>
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" name="qty_order[]" id="qty_order{{$i}}" value="{{ $lineItem->qty_order ?? ''  }}" class="form-control qty" required>
                                        </td>
                                        <td>
                                            <input type="number" step="0.01"  name="qty_issue[]" id="qty_issue{{$i}}" value="{{ $lineItem->qty_issue ?? ''  }}" class="form-control qty" required>
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
                                <textarea name="note" maxlength="120" rows="4" class="form-control no-resize"  placeholder="">{{ $gin->note ?? '' }}</textarea>
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

$('#mir_num').on('change',function(){
    checkWarehouse();
    if($('#warehouse').val()!=='')
    {
        $('.rowData').html('');
        rowId=0;
        var mir_num=$(this).val();
        var url= "{{ url('Inventory/mirDetail') }}";
        $.post(url,{warehouse_id:$('#warehouse').val(),mir_num:mir_num, _token:token},function(data){
            console.log(data);
            data.map(function(val,i){
                if(val.qty_order==val.qty_issue)
                {

                }
                else
                {
                    (val.stock ? remaining_stock=getNum(val.stock/val.operator_value,2) : remaining_stock=0)
                    rowId++;
                    var rw='<tr id="row'+rowId+'" class="rowData">'+
                        '<input type="hidden" name="inv_detail_ID[]" id="inv_detail'+rowId+'_ID" required value="'+val.inv_detail_id+'"></td>'+
                        '<td><button  type="button" class="btn btn-danger btn-sm" onclick="deleteRow(\'row'+rowId+'\');"><i class="zmdi zmdi-delete"></i></button></td>'+
                        '<td>'+
                            '<input type="text" name="item[]" id="item'+rowId+'" class="form-control" onkeydown="checkWarehouse(this.id)" onkeyup="autoFill(this.id, ItemURL, token)" onblur="getItemDetail('+rowId+');" value="'+val.prod_name+'" required autocomplete="off">'+
                            '<input type="hidden" name="item_ID[]" id="item'+rowId+'_ID" required value="'+val.prod_id+'"></td>'+
                            '<input type="hidden" name="prod_coa_id" value="'+val.prod_coa_id+'">'+
                        '<td>'+
                        '   <textarea name="description[]" id="description'+rowId+'"  class="form-control desc-textarea no-resize">'+val.description+'</textarea>'+
                        '</td>'+
                        '<td>'+
                            //sale unit
                            '<input type="text"  name="unit[]" id="unit'+rowId+'" class="form-control" value="'+val.unit+'" readonly>'+
                            //base unit
                            '<input type="hidden"  name="base_unit[]" id="base_unit'+rowId+'" class="form-control" value="'+val.base_unit+'">'+
                            '<input type="hidden" name="operator_value[]" id="operator_value'+rowId+'" value="'+val.operator_value+'">'+
                        '</td>'+
                        '<td>'+
                            //qty in stock
                            '<input type="number" step="any" name="qty_in_stock[]" id="qty_in_stock'+rowId+'" value="'+remaining_stock+'"  class="form-control qty" readonly>'+
                        '</td>'+
                        '<td>'+
                            //qty order
                            '<input type="number" step="any" name="qty_order[]" id="qty_order'+rowId+'" value="'+(val.qty_order - val.qty_issue)+'" class="form-control qty" readonly>'+
                        '</td>'+
                        '<td>'+
                            //qty issue
                            '<input type="number" step="any" name="qty_issue[]" id="qty_issue'+rowId+'" onblur="checkQty('+rowId+')" class="form-control qty" required>'+
                            '<span class="text-danger" id="error_msg'+rowId+'"></span>'+

                            //mir detail id 
                            '<input type="hidden" name="mir_detail_id[]" value="'+val.id+'">'+
                        '</td>'+
                    '</tr>';
                    $('#voucher').append(rw);
                    $('#mir_date').html(val.date);  
                    $('#department_name').html(val.department_name); 
                    $('#department_id').val(val.department); 
                    $('option[value='+val.warehouse+']').prop("selected", true); 
                }
            }); 
        });
    }
   
});
//this check  issue is not greater to qty order
function checkQty(numt)
{
    var stock=getNum($('#qty_in_stock'+numt).val(),2);
     var qty_order=getNum($('#qty_order'+numt).val(),2);
     var qty_issue=getNum($('#qty_issue'+numt).val(),2);
        if(parseFloat(qty_issue) > parseFloat(qty_order) || parseFloat(qty_issue) > parseFloat(stock))
        {
            $('#error_msg'+numt).html('Qty Issue Must Be Less Then Qty And Stock');
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