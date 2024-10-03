@extends('layout.master')
@section('title','Good Received Reports')
@section('parentPageTitle','Reports')
@section('page-style')
<?php  use App\Libraries\appLib; ?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.23/b-1.6.5/b-colvis-1.6.5/datatables.min.css"/>
<link rel="stylesheet" href="{{asset('public/assets/css/sw.css')}}">
<link rel="stylesheet" href="{{asset('public/assets/css/list.css')}}">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script>
var vendorURL= "{{ url('vendorSearch') }}";
var token =  "{{ csrf_token()}}";
</script>
@stop
@section('content')
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
              <div class="body">
              <form method="post" action="{{url('Reports/GoodReceived/tcpdf')}}">
              @csrf
                <div class="row">
                  <div class="col-lg-3 col-md-6">
                    <label for="">Vendor</label>
                    <input type="text" name="vendor" id="vendor" value="{{ $purchaseOrder->name ?? '' }}" placeholder="Vendor Name" class="form-control" onkeyup="autoFill(this.id, vendorURL, token)"  autocomplete="off"> 
                    <input type="hidden" name="vendor_ID" id="vendor_ID" value="{{ $purchaseOrder->v_id ?? '' }}" >
                  </div>
                  <div class="col-lg-3 col-md-6">
                    <label for="">From Date</label>
                    <input type="date" name="from_date" class="form-control" id="from_date">
                  </div>
                  <div class="col-lg-3 col-md-6">
                    <label for="">To Date</label>
                    <input type="date" name="to_date" class="form-control" id="to_date">
                  </div>
                  <div class="col-lg-3">
                    <br>
                    <button type="submit" id="button" class="btn btn-primary btn-sm mt-3">Generate</button>
                  </div>
                </div>
              </form>
              </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('page-script')
@include('datatable-list');
<script src="{{asset('public/assets/js/sw.js')}}"></script>
<script>
$(document).ready(function(){
      $("#button").click(function(){
        var ven_id=$('#vendor_ID').val();
        var from_date=$('#from_date').val();
        var to_date=$('#to_date').val();
        var url= "{{ url('Reports/GoodReceived/tcpdf') }}";
        $.post(url,{ ven_id: ven_id,from_date:from_date,to_date:to_date, _token:token},function(data){
        });
      });

});
// $(".save-data").click(function(){
//   $('#poNum').html('');
//    rowId=0;
//    var ven_id=$('#vendor_ID').val();
//    var url= "{{ url('Reports/GoodReceived/tcpdf') }}";
//    $.post(url,{ven_id:})
// });

</script>
@stop

