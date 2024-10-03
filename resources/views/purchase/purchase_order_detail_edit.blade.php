@extends('layout.master')
@section('title', 'Purchase Expenses Details Edit')
@section('parentPageTitle', 'Purchase')
@section('page-style')

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>

<link rel="stylesheet" href="{{asset('public/assets/plugins/dropify/css/dropify.min.css')}}" />
<style>
    .input-group-text {
        padding: 0 .75rem;
    }

    .amount {
        width: 150px;
        text-align: right;
    }

    .table td {
        padding: 0.10rem;
    }

    .dropify {
        width: 200px;
        height: 200px;
    }
</style>
<script>
    var customerURL = "{{ url('customerSearch') }}";
    var userURL = "{{ url('userSearch') }}";
    var token = "{{ csrf_token()}}";
</script>
@stop
@section('content')

<div class="row clearfix">
    <div class="card col-lg-12">
        <div class="header">
            <h2><strong>Purchase</strong> Details</h2>
        </div>
        @if(session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
        @endif
        <div class="body">
            <form method="post" action="" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="hidden" name="id" value="{{ $project->id ?? ''  }}">
                <div class="row col-12">
                    <div class="col-md-4">
                        <label for="name">Purchase Order No</label>
                        <div class="form-group">
                        <p>-</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="name">Vendor Reference</label>
                        <div class="form-group">
                            <input type="text" name="project" class="form-control" value="" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        
                        <div class="form-group">
                            <label for="role">Date</label>
                            <input type="date" name="camp_date" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label>Vendor</label>
                        <input type="text" name="vendor" id="vendor" class="form-control" value="" placeholder="Vendor" onkeyup="autoFill(this.id, customerURL, token)" required>
                        <input type="hidden" name="customer_ID" id="customer_ID" value="">
                    </div>
                    <div class="col-md-6">
                    
                        <label for="name">Vendor Reference</label>
                        <div class="form-group">
                            <input type="text" name="project" class="form-control" value="" required>
                        </div>
                    </div>
                       
                 
                </div>
                <div class="row mt-3">
                    <div class="col-lg-2 col-md-6 multicurrency">
                        <label for="fiscal_year">Currency</label>
                        <div class="form-group">
                            <select name="cur_id" id="cur_id" class="form-control show-tick ms select2" data-placeholder="Select" onchange="getCurrencyRate(this.id, 'rate', CurrencyURL, token);" required>
                                <option value="">--Select--</option>
                               
                                <option >USD</option>
                             
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-6 multicurrency">
                        <label for="rate">Rate</label>
                        <div class="form-group">
                            <input type="number" step="any" name="cur_rate" id='cur_rate' class="form-control" value="1" size='9' maxlength="9" required>
                        </div>
                    </div>
                    <div class="col-md-2"></div>
                    <div class="col-md-4">
                        <label for="name">Amount</label>
                        <div class="form-group">
                            <input type="number" name="amount" class="form-control" value="" required>
                        </div>
                    </div>
                </div>
              

                <div class="row">
                    <div class="col-md-12 mx-auto">
                        <button class="btn btn-raised btn-primary waves-effect" type="submit">Update</button>
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