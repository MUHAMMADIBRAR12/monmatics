@extends('layout.master')
@section('title', 'D.O Tracking')
@section('parentPageTitle','Sales')
@section('parent_title_icon', 'zmdi zmdi-home')
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
                <h2><strong>D.O</strong> Tracking</h2>
            </div>
            @if(session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
            @endif
            <div class="body">
                <form method="post" action="{{url('Sales_Fmcg/DoTracking/Add')}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $do_tracking->id ?? ''  }}">
                    <div class="row">
                        <div class="col-sm-3 col-md-3">
                            <label>D.O No</label>
                            <div class="form-group">
                                <select name="do_num" id="do_num" class="form-control show-tick ms select2" data-placeholder="Select">
                                   <option  value="">Select Delivery Order</option>
                                   @foreach($do_numbers as $do_number)
                                   <option  value="{{$do_number->id}}" {{($do_number->id == ($do_tracking->id ??'')) ? 'selected' : '' }}>{{$do_number->do_num}}</option>
                                   @endforeach
                                </select>
                            </div>  
                        </div> 
                        <div class="col-sm-3 col-md-3">
                            <label> Date</label>
                            <input type="date" name="date" class="form-control" value="{{$do_tracking->date ?? date('Y-m-d')  }}" required>
                        </div>
                        <div class="col-sm-3 col-md-3">
                            <label>Customer</label><br>
                            <label id="customer" style="color:#bababa">{{$do_tracking->cust_name ?? '' }}</label>
                            <input type="hidden" name="customer_id" id="customer_id" value="{{$do_tracking->cust_id ?? '' }}">
                        </div>
                        <div class="col-sm-3 col-md-3">
                            <label>Address</label><br>
                            <label id="cust_address_label" style="color:#bababa">{{$do_tracking->address ?? '' }}</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="table-responsive">
                            <table id="voucher" class="table table-striped m-b-0">
                                <thead>
                                    <tr class="bg-purple">
                                        <th>Product</th>
                                        <th class="qty">Qty Issue</th>
                                        <th class="table_header table_header_100">Qty Delivered</th>
                                        <th class="table_header table_header_100">Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @php
                                $count = (isset($do_tracking_detail))?count($do_tracking_detail):1;
                               
                                for($i=1;$i<=$count; $i++)
                                { 
                                    
                                    if(isset($do_tracking_detail))
                                    {
                                        $lineItem = $do_tracking_detail[($i-1)];
                                    }                                    
                                    @endphp                               
                                    <tr id="row{{$i}}" class="rowData">
                                        <td>
                                            <!-- Product -->
                                            <input type="text" name="item[]" id="item{{$i}}"   onkeyup="autoFill(this.id, ItemURL, token)" onblur="getItemDetail({{$i}});" value="{{ $lineItem->prod_name ?? ''  }}" class="form-control" required>
                                            <input type="hidden" name="item_ID[]" id="item{{$i}}_ID" value="{{ $lineItem->prod_id ?? ''  }}" >
                                        </td>
                                        <td>
                                            <!-- qty issue -->
                                            <input type="number" step="any"  name="qty_issue[]" id="qty_issue{{$i}}" value="{{ $lineItem->qty_approved ?? ''  }}" class="form-control"   readonly >
                                        </td>
                                        <td>
                                            <!-- qty delivered -->
                                            <input type="number" step="any"  name="qty_delivered[]" id="qty_delivered{{$i}}" value="{{ $lineItem->qty_delivered ?? ''  }}" class="form-control qty">
                                            
                                        </td>
                                        <td>
                                            <!-- Remarks -->
                                            <input type="text"  name="remark[]" id="remark{{$i}}"  value="{{ $lineItem->remarks ?? ''  }}" class="form-control">
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
                            <label>Veh No</label>
                            <input type="text" name="veh_no" id="veh_no" class="form-control" value="{{ $do_tracking->veh_no?? ''  }}" placeholder="Veh No.">
                        </div> 
                        <div class="col-sm-3 col-md-3">
                            <label>Bilty No</label>
                            <input type="text" name="bilty_no" id="bilty_no" class="form-control" value="{{$do_tracking->bilty_no ?? ''  }}" placeholder="Bulty No.">
                        </div> 
                        <div class="col-sm-3 col-md-3">
                            <label>Delivery Name</label>
                            <input type="text" name="delivery_name" id="delivery_name" class="form-control" value="{{$do_tracking->delivery_name ?? ''  }}" placeholder="Delivery Name">
                        </div>
                        <div class="col-sm-3 col-md-3">
                            <label>Delivery Confirmation By</label>
                            <input type="text" name="delivery_cnfrm_by" class="form-control" value="{{$do_tracking->delivery_confirm_by ?? ''  }}" placeholder="Delivery Confirmation By">
                        </div>
                    </div>
                    <div class="row mt-2">                        
                        <div class="col-sm-3 col-md-3">
                            <label>Delivery Date</label>
                            <input type="date" name="delivery_date" class="form-control" value="{{$do_tracking->delivery_date ?? ''  }}" placeholder="Delivery Date">
                        </div> 
                        <div class="col-sm-3 col-md-3">
                            <label>Freight</label>
                            <input type="text" name="freight" class="form-control" value="{{ $do_tracking->freight ?? ''  }}" placeholder="Freight">
                        </div>
                        <div class="col-sm-3 col-md-3">
                            <label>Transit Loss</label>
                            <input type="text" name="transit_loss" class="form-control" value="{{$do_tracking->transit_loss ?? ''  }}" placeholder="Transit Loss">
                        </div>
                        <div class="col-sm-3 col-md-3">
                            <label>Other Amount</label>
                            <input type="text" name="other_amount" class="form-control" value="{{$do_tracking->other_amount ?? ''  }}" placeholder="Other Amount">
                        </div>   
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="">Remarks</label>
                            <textarea name="remarks" id="remarks" class="form-control">{{$do_tracking->remarks ?? ''  }}</textarea>
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
$(document).ready(function(){
$('#do_num').on('change',function(){
    $('.rowData').html('');
     rowId=0;
    var do_id=$(this).val();
    var url= "{{ url('Sales_Fmcg/DeliveryOrderDetail') }}";
    $.post(url,{do_id:do_id, _token:token},function(data){
        console.log(data);
        data.map(function(val,i){
            rowId++;
              var rw='<tr id="row'+rowId+'" class="rowData">'+
                '<td>'+
                    //do detail id
                    '<input type="hidden" name="do_detail_id[]" value="'+val.do_detail_id+'">'+
                    //product
                    '<label class="prod_field">'+val.prod_name+'</label>'+
                    '<input type="hidden" name="item_ID[]" id="item'+rowId+'_ID" value="'+val.prod_id+'">'+
                '</td>'+
                '<td>'+
                    //qty issue
                    '<label class="qty">'+val.qty_delivery+'</label>'+
                    '<input type="hidden"  name="qty_issue[]" id="qty_issue'+rowId+'"  value="'+val.qty_delivery+'">'+
                '</td>'+
                '<td>'+
                    //qty delivered
                    '<input type="number" step="any" name="qty_delivered[]" id="qty_delivered'+rowId+'" class="form-control qty" >'+
                '</td>'+
                '<td>'+
                    //remarks
                    '<input type="text"  name="remark[]" id="remark'+rowId+'" class="form-control" value="'+val.remarks_delivered+'">'+
                '</td>'+
             '</tr>';
            $('#voucher').append(rw);
            $('#customer').html(val.cust_name);
            $('#customer_id').val(val.cust_id); 
            $('#cust_address_label').html(val.cust_address);
            $('#veh_no').val(val.veh_no); 
            $('#bilty_no').val(val.bilty_no); 
            $('#delivery_name').val(val.delivery_name);
            $('#remarks').val(val.remarks);  
        }); 
        
    });
   
});
});
</script>
@stop