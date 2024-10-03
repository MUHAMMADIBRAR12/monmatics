@extends('layout.master')
@section('title','Customer Discount')
@section('parentPageTitle','Sales FMCG')
@section('parent_title_icon', 'zmdi zmdi-home')
@section('page-style')
<?php  use App\Libraries\appLib; ?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="{{asset('assets/plugins/dropify/css/dropify.min.css')}}"/>
<script lang="javascript/text">
var CustomerURL = "{{ url('customerSearch') }}";
var token =  "{{ csrf_token()}}";
</script>
@stop
@section('content')

    <div class="row clearfix">
        <div class="card col-lg-12">
            <div class="body">
                <form method="post" action="{{url('Sales_Fmcg/CustomerDiscount/Add')}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{$discount->id ?? ''  }}">
                    <div class="row">
                        <div class="col-md-2">
                            <label>Discount No</label><br>
                            <label>{{$discount->month ?? ''}}-{{appLib::padingZero($discount->number  ?? '')}}</label>
                        </div>                        
                        <div class="col-md-4">
                            <label for="name">Customer</label>
                            <div class="form-group">
                                <input type="text" name="customer" id="customer" onkeyup="autoFill(this.id,CustomerURL,token)" value="{{$discount->cust_name ?? ''  }}" class="form-control" required>
                                <input type="hidden" name="customer_ID" id="customer_ID" value="{{ $discount->cust_id ?? ''  }}" required> 
                            </div>
                        </div> 
                        <div class="col-md-3">
                            <label for="name">Date From</label>
                            <div class="form-group">
                                <input type="date" name="date_from" id="date_from"  value="{{$discount->date_from ?? ''  }}" class="form-control" required>
                            </div>
                        </div> 
                        <div class="col-md-3">
                            <label for="name">Date To</label>
                            <div class="form-group">
                                <input type="date" name="date_to" id="date_to"  value="{{$discount->date_to ?? ''  }}" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">              
                        <div class="col-md-4">
                            <label>Discount Rs On Per Qty</label>
                            <div class="form-group">
                                <input type="number" step="any" name="discount_per_qty" id="discount_per_qty" class="form-control qty" value="{{$discount->qty_discount ?? ''  }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label>Discount % On Gross Amount</label>
                            <div class="form-group">
                                <input type="number" step="any" name="discount_gross_amount" id="discount_gross_amount" class="form-control qty" value="{{$discount->amount_discount_percent ?? ''  }}">
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
<script src="{{asset('assets/plugins/dropify/js/dropify.min.js')}}"></script>
<script src="{{asset('assets/js/pages/forms/dropify.js')}}"></script>
<script src="{{asset('public/assets/js/sw.js')}}"></script>
@stop