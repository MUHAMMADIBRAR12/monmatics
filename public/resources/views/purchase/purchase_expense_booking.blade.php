@extends('layout.master')
@section('title', 'Purchase Expenses Details')
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
    var vendorURL= "{{ url('vendorCoaSearch') }}";
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
            <form method="post" action="{{url('Purchase/PurchaseExpenses/saveInvoice')}}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="hidden" name="peId" value="{{ $peId }}">
                <input type="hidden" name="id" value="{{ $purchaseInvoice->id ?? '' }}">                
                <input type="hidden" name="trm_id" value="{{ $purchaseInvoice->trm_id ?? '' }}">     
                <input type="hidden" name="poCoaId" value="{{ $poCoaId }}">     
                
                <div class="row col-12">
                    <div class="col-md-4">
                        <label for="name">Purchase Order No</label>
                        <div class="form-group">
                            <p>{{$poNumber}}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="name">Vendor Reference</label>
                        <div class="form-group">
                            <input type="text" name="vendor_reference" class="form-control" value="{{ $purchaseInvoice->vendor_reference ?? '' }}" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="role">Date</label>
                            <input type="date" name="date"  value="{{ $purchaseInvoice->date ?? '' }}" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label>Vendor</label>
                        <input type="text" name="vendor" id="vendor" class="form-control" value="{{ $purchaseInvoice->name ?? '' }}" placeholder="Vendor" onkeyup="autoFill(this.id, vendorURL, token)" required>
                        <input type="hidden" name="vendor_ID" id="vendor_ID" value="{{$purchaseInvoice->ven_coa_id ?? '' }}">
                    </div>
                    <div class="col-md-6">
                        <label for="fax">Description</label>
                        <div class="form-group">
                            <div class="form-group">
                                <textarea name="description" rows="4" class="form-control no-resize" placeholder="Description">{{ $purchaseInvoice->note ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-lg-2 col-md-6 multicurrency">
                        <label for="fiscal_year">Currency</label>
                        <div class="form-group">
                            <select name="cur_id" id="cur_id" class="form-control show-tick ms select2" data-placeholder="Select" onchange="getCurrencyRate(this.id, 'rate', CurrencyURL, token);" required>
                                <option value="">--Select--</option>
                                @foreach($currencies as $cur)
                                    <option {{ ( $cur->id == ($purchaseInvoice->cur_id ?? '')) ? 'selected' : '' }} value="{{ $cur->id }}">{{  $cur->code  }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-6 multicurrency">
                        <label for="rate">Rate</label>
                        <div class="form-group">
                            <input type="number" step="any" name="cur_rate" id='cur_rate' value="{{$purchaseInvoice->cur_rate ?? ''}}" class="form-control" value="1" size='9' maxlength="9" required>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <label for="name">Amount</label>
                        <div class="form-group">
                            <input type="number" step="any" name="amount" class="form-control" value="{{$purchaseInvoice->total_inv_amount ?? ''}}" required>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-12 mx-auto">
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