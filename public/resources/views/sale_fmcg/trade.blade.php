@extends('layout.master')
@section('title', 'Trade Offer')
@section('parentPageTitle','Sales')
@section('parent_title_icon', 'zmdi zmdi-home')
@php use App\Libraries\appLib; @endphp
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
var ItemURL = "{{ url('SaleItemSearch') }}";
var CustomerURL = "{{ url('customerSearch') }}";
var locationURL = "{{ url('locationSearch') }}";
var token =  "{{ csrf_token()}}";
</script>
@stop

@section('content')
    <div class="row clearfix">
        <div class="card col-lg-12">
            <div class="header">
                <h2><strong>Trade</strong> Offer</h2>
            </div>
            <div class="body">
                <form method="post" action="{{url('Sales_Fmcg/TradeOffer/Add')}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $trade->id ?? ''  }}">
                    <div class="row">
                        <div class="col-sm-4 col-md-3">
                            <label>Trade Offer No.</label><br>
                            {{$trade->month ?? ''}}-{{appLib::padingZero($trade->number  ?? '')}}
                        </div> 
                        <div class="col-sm-4 col-md-3">
                            <label>From Date</label>
                            <input type="date" name="from_date" class="form-control" value="{{ $trade->from_date ?? ''  }}" required>
                        </div> 
                        <div class="col-sm-4 col-md-3">
                            <label>To Date</label>
                            <input type="date" name="to_date" class="form-control" value="{{ $trade->to_date ?? ''  }}" required>
                        </div>  
                        <div class="col-md-3">
                            <label for="fiscal_year">Importance</label>
                            <div class="form-group">
                                <select name="importance" class="form-control show-tick ms select2" data-placeholder="Select">
                                   <option  value="">Select Importance</option>
                                    <option  value="High" {{ (( $trade->importance ??'') == 'High') ? 'selected' : '' }}>High</option>
                                    <option  value="Normal" {{ (( $trade->importance ??'') == 'Normal') ? 'selected' : '' }}>Normal</option>
                                </select>
                            </div>   
                        </div>                                           
                    </div>
                    <div class="row mt-2">                        
                        <div class="col-sm-3 col-md-3">
                            <label>Customer Category</label>
                            <div class="form-group">
                                <select name="customer_category" class="form-control show-tick ms select2" data-placeholder="Select">
                                   <option  value="">Select Category</option>
                                    @foreach($customer_categories as $category)
                                    <option value="{{$category->category}}" {{ ( $category->category == ( $trade->customer_category ??'')) ? 'selected' : '' }}>{{$category->category}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>     
                        <div class="col-sm-3 col-md-3">
                            <label>Customer</label>
                            <div class="form-group">
                                <input type="text" name="customer" id="customer"  value="{{ $trade->cust_name ?? ''  }}" class="form-control" onkeyup="autoFill(this.id,CustomerURL,token)">
                                <input type="hidden" name="customer_ID" id="customer_ID" value="{{ $trade->cust_id ?? ''  }}" >   
                            </div>
                        </div>  
                        <div class="col-sm-3 col-md-3">
                            <label>Town</label>
                            <div class="form-group">
                                <input type="text" name="location" id="location"  value="{{ $trade->location_name ?? ''  }}" class="form-control" onkeyup="autoFill(this.id,locationURL,token)">
                                <input type="hidden" name="location_ID" id="location_ID" value="{{ $trade->town ?? ''  }}" >   
                            </div>
                        </div> 
                    </div>
                    <div class="row">
                        <div class="col-sm-3 col-md-3">
                            <label>Type</label>
                            <div class="form-group">
                                <select name="type" id="type" class="form-control show-tick ms select2" data-placeholder="Select"  required>
                                    <option  value="">Select Type</option>
                                    <option value="Quantity" {{ (( $trade->type ??'') == 'Quantity') ? 'selected' : '' }}>Quantity</option>
                                    <option value="Value" {{ (( $trade->type ??'') == 'Value') ? 'selected' : '' }}>Value</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3" id="apply_on">
                            <label>Apply On</label>
                            <div class="form-group">
                                <select name="apply_on" class="form-control show-tick ms select2" >
                                    <option  value="">Select Apply On</option>
                                    <option value="gross_amount" {{(($trade->apply_on ??'')=='gross_amount') ? 'selected' : '' }}>Gross Amount</option>
                                    <option value="net_amount" {{(($trade->apply_on ??'')=='net_amount') ? 'selected' : '' }}>Net Amount</option>
                                </select>
                            </div>   
                        </div>
                        <div class="col-sm-3 col-md-2">
                            <div class="form-group">
                                <label>From</label>
                                <input type="number" step="any" name="from" class="form-control" value="{{ $trade->from_qty ?? ''  }}" required>
                            </div>
                        </div>
                        <div class="col-sm-3 col-md-2">
                            <div class="form-group">
                                <label>To</label>
                                <input type="number" step="any" name="to" class="form-control" value="{{ $trade->to_qty ?? ''  }}" required>
                            </div>
                        </div>
                        <div class="col-sm-3 col-md-2">
                            <div class="form-group">
                                <label>Repeat On</label>
                                <input type="number" step="any" name="repeat_on" class="form-control" value="{{ $trade->repeat_on ?? ''  }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <label>Free Sku</label>
                            <div class="form-group">
                                <input type="text"  id="item1" onkeyup="autoFill(this.id, ItemURL, token)"  value="{{ $trade->prod_name ?? ''  }}" class="form-control">
                                <input type="hidden" name="free_prod_id" id="item1_ID" value="{{ $trade->free_prod_id ?? ''  }}">
                            </div>  
                        </div>
                        <div class="col-sm-3">
                            <label>Free Qty(Selling Unit)</label>
                            <div class="form-group">
                                <input type="text" name="free_qty" class="form-control" value="{{ $trade->free_qty ?? ''  }}">
                            </div>  
                        </div>
                        <div class="col-sm-3 col-md-2">
                            <label>Discount %</label>
                            <div class="form-group">
                                <input type="number" step="any" name="discount" class="form-control qty" value="{{ $trade->disc_percent ?? ''  }}">
                            </div>
                        </div>  
                        <div class="col-sm-3 col-md-3">
                            <label>Discount Amount</label>
                            <div class="form-group">
                                <input type="text" name="discount_amount" class="form-control" value="{{ $trade->disc_amount ?? ''  }}" >
                            </div>
                        </div>  

                    </div>
                    <div class="row">
                        <div class="col-sm-8 col-md-8">
                            <div class="form-group">
                                <label>Special Instruction</label>
                                <input type="text" name="note" class="form-control" value="{{ $trade->note ?? ''  }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3 col-md-5">
                            <div class="form-group">
                                <select  id="item_list" class="form-control ct show-tick ms select2 mt-2" size="20">
                                @foreach( $products as  $product)
                                    <option  value="{{$product->id}}" class="pro" style="{{($product->id == ( $item->prod_id ??'')) ? 'display:none' : '' }}">{{$product->name}}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <button id="single-select" type="button" class="btn p-2 circle">></button>
                            <button id="all-select" type="button" class="btn p-2">>></button>
                            <button id="all-deselect" type="button" class="btn p-2"><<</button>
                            <button id="single-deselect" type="button" class="btn p-2"><</button>
                        </div>
                        <div class="col-sm-3 col-md-5">
                            <div class="form-group">
                                <select name="item_list[]" id="final_item_list" class="form-control ct show-tick ms select2 mt-2" size="20" multiple required>
                                @if(isset($item_list))
                                @foreach($item_list as $item)
                                    <option selected value="{{$item->prod_id}}" class="final_pro" >{{$item->name}}</option>
                                @endforeach  
                                @endif 
                                </select>
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

//apply on visible if type is value
$('#type').on('change',function(){
    if($(this).val()==='Value')
    {
        $('#apply_on').show();
    }
    else
    {
        $('#apply_on').hide();
    }
});

$('#single-select').on('click',function(){
    let item_id=$("#item_list :selected").val();
    console.log(item_id);
    let item_name=$("#item_list :selected").text();
    if(item_id !==undefined)
    {
        let option='<option  selected="selected"  value="'+item_id+'" class="final_pro">'+item_name+'</option>';
        $('#final_item_list').append(option);
        $("#item_list :selected").remove();  
    }
    
});
$('#single-deselect').on('click',function(){
    let item_id=$("#final_item_list :selected").val();
    let item_name=$("#final_item_list :selected").text();
    if(item_id !==undefined)
    {
        let option='<option    value="'+item_id+'">'+item_name+'</option>';
        $('#item_list').append(option);
        $("#final_item_list :selected").remove();  
    }
    
});
$('#all-select').on('click',function(){
    $(".pro").each(function(i, td) {
        option='<option  selected="selected"  value="'+$(td).val()+'" class="final_pro">'+$(td).text()+'</option>';
        $('#final_item_list').append(option);
    });  
    $('#item_list').html('');
});
$('#all-deselect').on('click',function(){
    $(".final_pro").each(function(i, td) {
        option='<option  value="'+$(td).val()+'" class="pro">'+$(td).text()+'</option>';
        $('#item_list').append(option);
    });  
    $('#final_item_list').html('');
});

</script>
@stop