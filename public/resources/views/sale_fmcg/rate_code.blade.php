@extends('layout.master')
@section('title', 'Rate Code')
@section('parentPageTitle','Sale')
@section('parent_title_icon', 'zmdi zmdi-home')
@section('page-style')

<?php  use App\Libraries\appLib; ?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<!--<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">-->
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<link rel="stylesheet" href="{{asset('public/assets/plugins/dropify/css/dropify.min.css')}}"/>
<style>
    .table td{
        padding: 0.10rem;            
    }
    .percent_field
    {
        width:80px;
        text-align: right;
    }
    .amount_field
    {
        width:120px;
        text-align: right;
    }
</style>
<script lang="javascript/text">
var token =  "{{ csrf_token()}}";
var rowId=1;
</script>
@stop
@section('content')

    <div class="row clearfix">
        <div class="card col-lg-12">
            <div class="body">
            <form method="post" action="{{url('Sales_Fmcg/RateCode/Add')}}"  id="igp_form">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{$rate_code->id  ?? ''}}">
                    <input type="hidden" name="number" value="{{$rate_code->number ??  $number}}">
                    <input type="hidden" name="month" value="{{$rate_code->month ?? ''}}">
                    <div class="row">
                        <div class="col-lg-4 col-md-4">
                            <label for="fiscal_year">Rate Code#</label><br>
                            <label for="fiscal_year">{{ $rate_code->month ?? '' }}-{{appLib::padingZero($rate_code->number  ?? '')}}</label>
                        </div>
                        <div class="col-lg-4 col-md-4">
                            <label for="date">Customer Category</label>
                            <div class="form-group">
                                <select name="category" class="form-control show-tick ms select2" data-placeholder="Select" required>
                                   <option  value="">Select Customer Category</option>
                                    @foreach($cust_categories as $category)
                                    <option value="{{$category->category}}" {{ ( $category->category == ( $rate_code->customer_category ??'')) ? 'selected' : '' }}>{{ $category->category}}</option>
                                    @endforeach
                                </select>
                            </div> 
                        </div>     
                    </div>        
                    <div class="row mt-1">
                        <div class="table-responsive">
                            <table id="voucher" class="table table-striped m-b-0">
                                <thead>
                                    <tr class="bg-purple">
                                        <th class="table_header ">Product</th>
                                        <th class="table_header ">Base Rate</th>
                                        <th class="table_header ">GST <input type="number" step="any"  id="gst" class="form-control qty bg-light ml-5" onblur="setValue(this.id);sumAll(this.id)"></th>
                                        <th class="table_header  mx-auto">FED <input type="number" step="any"  id="fed" class="form-control qty bg-light ml-5" onblur="setValue(this.id);sumAll(this.id)"></th>
                                        <th class="table_header">Others <input type="number" step="any" id="others" class="form-control qty bg-light ml-5" onblur="setValue(this.id);sumAll(this.id)"></th>
                                        <th class="table_header">Total</th>
                                        <th class="table_header">Price Inclusive Tax</th>
                                        <th class="table_header">Fix Margin <input type="number" step="any"  id="fix_margin" class="form-control qty bg-light ml-5" onblur="setValue(this.id);sumAll(this.id)"></th>
                                        <th class="table_header">L/U <input type="number" step="any"  id="lu" class="form-control qty bg-light ml-5" onblur="setValue(this.id);sumAll(this.id)"></th>
                                        <th class="table_header table_header_100">Freight <input type="number" step="any" id="freight" class="form-control qty bg-light ml-5" onblur="setValue(this.id);sumAll(this.id)"></th>
                                        <th class="table_header">Others <input type="number" step="any"  id="others_margin" class="form-control qty bg-light ml-5" onblur="setValue(this.id);sumAll(this.id)"></th>     
                                        <th class="table_header table_header_100">Total Margin</th>
                                        <th class="table_header table_header_100">Gross Rate</th>
                                        <th class="table_header table_header_100">Advance Payment%</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @php
                                $count = (isset($finishd_products))?count($finishd_products):1;
                               
                                for($i=1;$i<=$count; $i++)
                                { 
                                    
                                    if(isset($finishd_products))
                                    {
                                        $lineItem = $finishd_products[($i-1)];
                                        
                                    }                                    
                                    @endphp                               
                                    <tr id="row{{$i}}" class="rowData">
                                        <td>
                                            <!-- product -->
                                            <input type="text" name="item[]" value="{{ $lineItem->prod_name ?? ''  }}"   class="form-control prod_field" readonly>
                                            <input type="hidden" name="item_ID[]" id="item{{$i}}_ID" value="{{ $lineItem->prod_id ?? ''  }}">
                                        </td>
                                        <td>
                                            <!-- Base rate -->
                                            <input type="number" step="any" name="base_rate[]" id="base_rate{{$i}}" class="form-control qty" value="{{ $lineItem->base_rate ?? 0.00 }}" onblur="rowCalculation({{$i}})">
                                        </td>
                                        <td>
                                            <table>
                                                <tr> 
                                                     <!-- GST %-->
                                                    <td><input type="number" step="any" name="gst[]" id="gst{{$i}}" class="form-control percent_field gst" value="{{ $lineItem->gst_tax ?? 0.00 }}" onblur="rowCalculation({{$i}})"></td>
                                                    <!-- GST amount-->
                                                    <td><input type="number" step="any" name="gst_amount[]" id="gst_amount{{$i}}" class="form-control amount_field" value="{{ $lineItem->gst_tax_amount ?? 0.00 }}"  readonly></td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td>
                                            <table>
                                                <tr> 
                                                     <!-- FED %-->
                                                    <td><input type="number" step="any" name="fed[]" id="fed{{$i}}" class="form-control percent_field fed" value="{{ $lineItem->fed_tax ?? 0.00 }}" onblur="rowCalculation({{$i}})"></td>
                                                    <!-- FED amount-->
                                                    <td><input type="number" step="any" name="fed_amount[]" id="fed_amount{{$i}}" class="form-control amount_field" value="{{ $lineItem->fed_tax_amount ?? 0.00 }}" readonly></td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td>
                                            <table>
                                                <tr> 
                                                     <!-- Others %-->
                                                    <td><input type="number" step="any" name="others[]" id="others{{$i}}" class="form-control percent_field others" value="{{ $lineItem->others_tax ?? 0.00 }}" onblur="rowCalculation({{$i}})"></td>
                                                    <!-- others amount-->
                                                    <td><input type="number" step="any" name="others_amount[]" id="others_amount{{$i}}" class="form-control amount_field" value="{{ $lineItem->others_tax_amount ?? 0.00 }}" readonly></td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td>
                                            <!-- total -->
                                            <input type="number" step="any" name="total_tax_amount[]" id="total_tax_amount{{$i}}" class="form-control qty" value="{{ $lineItem->total_tax_amount ?? 0.00 }}" radonly>
                                        </td>
                                        <td>
                                            <!-- price inclusive tax -->
                                            <input type="number" step="any" name="price_inclusive_tax[]" id="price_inclusive_tax{{$i}}" class="form-control qty" value="{{ $lineItem->amount_after_tax ?? 0.00 }}" readonly>
                                        </td>
                                        <td>
                                            <table>
                                                <tr> 
                                                    <!-- fix margin -->
                                                    <td><input type="number" step="any" name="fix_margin[]" id="fix_margin{{$i}}" class="form-control  fix_margin percent_field" value="{{ $lineItem->fix_margin ?? 0.00 }}" onblur="rowCalculation({{$i}})"></td>
                                                    <!-- fix margin amount-->
                                                    <td><input type="number" step="any" name="fix_margin_amount[]" id="fix_margin_amount{{$i}}" class="form-control amount_field" value="{{ $lineItem->fix_margin_amount ?? 0.00 }}" readonly></td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td>
                                            <table>
                                                <tr> 
                                                    <!-- l/u -->
                                                    <td><input type="number" step="any" name="lu[]" id="lu{{$i}}" class="form-control percent_field  lu" value="{{ $lineItem->lu_margin ?? 0.00 }}" onblur="rowCalculation({{$i}})"></td>
                                                    <!-- fix margin amount-->
                                                    <td><input type="number" step="any" name="lu_amount[]" id="lu_amount{{$i}}" class="form-control amount_field" value="{{ $lineItem->lu_margin_amount ?? 0.00 }}" readonly></td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td>
                                            <table>
                                                <tr> 
                                                    <!-- freight -->
                                                    <td><input type="number" step="any" name="freight[]" id="freight{{$i}}" class="form-control percent_field freight" value="{{ $lineItem->freight ?? 0.00 }}" onblur="rowCalculation({{$i}})"></td>
                                                    <!-- freight amount-->
                                                    <td><input type="number" step="any" name="freight_amount[]" id="freight_amount{{$i}}" class="form-control amount_field" value="{{ $lineItem->freight_amount ?? 0.00 }}" readonly></td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td>
                                            <table>
                                                <tr> 
                                                    <!-- others margin -->
                                                    <td><input type="number" step="any" name="others_margin[]" id="others_margin{{$i}}" class="form-control percent_field others_margin" value="{{ $lineItem->others_margin ?? 0.00 }}" onblur="rowCalculation({{$i}})"></td>
                                                    <!-- others margin amount-->
                                                    <td><input type="number" step="any" name="others_margin_amount[]" id="others_margin_amount{{$i}}" class="form-control amount_field" value="{{ $lineItem->others_margin_amount ?? 0.00 }}" readonly></td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td>
                                            <!-- total margin -->
                                            <input type="number" step="any" name="total_margin[]" id="total_margin{{$i}}" class="form-control qty" value="{{ $lineItem->total_margins_amount ?? 0.00 }}" readonly>
                                        </td>
                                        <td>
                                            <!-- gross rate -->
                                            <input type="number" step="any" name="gross_rate[]" id="gross_rate{{$i}}" class="form-control qty" value="{{ $lineItem->gross_rate ?? 0.00 }}" readonly>
                                        </td>
                                        <td>
                                            <!-- advance Payment -->
                                            <input type="number" step="any" name="advance_payment[]" id="advance_payment{{$i}}" class="form-control qty" value="{{ $lineItem->advance_payment ?? 0.00 }}">
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
    function setValue(text)
    {
        console.log($('#'+text).val());
        $('.'+text).val($('#'+text).val());
    }

function sumAll(tclass)
{
    row=1;
    $("."+tclass).each(function(i, td) {
       $('#'+tclass+'_amount'+row).val($('#base_rate'+row).val() * $('.'+tclass).val() / 100);
       $('#total_tax_amount'+row).val(+getNum($('#gst_amount'+row).val()) + +getNum($('#fed_amount'+row).val()) + +getNum($('#others_amount'+row).val()));
       $('#price_inclusive_tax'+row).val( +getNum($('#total_tax_amount'+row).val()) + +getNum($('#base_rate'+row).val()));
       $('#total_margin'+row).val(+getNum($('#fix_margin_amount'+row).val()) + +getNum($('#lu_amount'+row).val()) + +getNum($('#others_margin_amount'+row).val()) + +getNum($('#freight_amount'+row).val())); 
       $('#gross_rate'+row).val(getNum($('#price_inclusive_tax'+row).val()) - getNum($('#total_margin'+row).val()));

       ++row;
    });    
       
}
function rowCalculation(num)
{
    $('#gst_amount'+num).val(percentVal($('#base_rate'+num).val(),$('#gst'+num).val()));
    $('#fed_amount'+num).val(percentVal($('#base_rate'+num).val(),$('#fed'+num).val()));
    $('#others_amount'+num).val(percentVal($('#base_rate'+num).val(),$('#others'+num).val()));
    $('#fix_margin_amount'+num).val(percentVal($('#base_rate'+num).val(),$('#fix_margin'+num).val()));
    $('#lu_amount'+num).val(percentVal($('#base_rate'+num).val(),$('#lu'+num).val()));
    $('#others_margin_amount'+num).val(percentVal($('#base_rate'+num).val(),$('#others_margin'+num).val()));
    $('#freight_amount'+num).val(percentVal($('#base_rate'+num).val(),$('#freight'+num).val()));
    $('#total_tax_amount'+num).val(+getNum($('#gst_amount'+num).val()) + +getNum($('#fed_amount'+num).val()) + +getNum($('#others_amount'+num).val()));
    $('#price_inclusive_tax'+num).val( +getNum($('#total_tax_amount'+num).val()) + +getNum($('#base_rate'+num).val()));
    $('#total_margin'+num).val(+getNum($('#fix_margin_amount'+num).val()) + +getNum($('#lu_amount'+num).val()) + +getNum($('#others_margin_amount'+num).val()) + +getNum($('#freight_amount'+num).val()));  
    $('#gross_rate'+num).val(getNum($('#price_inclusive_tax'+num).val()) - getNum($('#total_margin'+num).val()))
}
</script>
@stop