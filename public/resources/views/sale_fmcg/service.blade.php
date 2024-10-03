@extends('layout.master')
@section('title', 'Services')
@section('parentPageTitle', 'Sales')
@section('page-style')
<?php  use App\Libraries\appLib; ?>
<link rel="stylesheet" href="{{ asset('public/assets/plugins/footable-bootstrap/css/footable.bootstrap.min.css') }}"/>
<link rel="stylesheet" href="{{ asset('public/assets/plugins/footable-bootstrap/css/footable.standalone.min.css') }}"/>
@stop
@section('content')
<!-- Horizontal Layout -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="body">
                <form class="form-horizontal" action="{{url('Sales/Services/Add')}}" method="post">
                   {{ csrf_field() }}
                   @if(isset($service))
                    <input type="hidden" name="id" value="{{$service->id}}">
                    @endif
                    <div class="row clearfix">
                        <div class="col-lg-2 col-md-2 col-sm-4 form-control-label">
                            <label>Service Name</label>
                        </div>
                        <div class="col-lg-10 col-md-10 col-sm-8">
                            <div class="form-group">
                                <input type="text" id="service" name="service" class="form-control" placeholder="Service Name --" value="@if(isset($service)){{$service->name ?? ''}}@endif" required>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-2 col-md-2 col-sm-4 form-control-label">
                            <label>Unit</label>
                        </div>
            
                        <div class="col-lg-10 col-md-10 col-sm-8">
                            <div class="form-group">
                                <select name="unit" id="unit" class="form-control show-tick ms select2" data-placeholder="Select" required>
                                    <option value="">-- Select unit --</option>
                                    @foreach ($units as $unit)
                                  
                                    <option value="{{ $unit->id }}" {{ isset($service) && $unit->id == $service->primary_unit ? 'selected' : '' }} >
                                        {{ $unit->name ?? '' }}
                                    </option>
                                    @endforeach
                                </select>
                                
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                    <div class="col-lg-2 col-md-2 col-sm-2 form-control-label">
                            <label>Rate</label>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4">
                            <div class="form-group">
                                <input type="number" step="any" id="rate" name="rate" class="form-control" placeholder="Rate --" value="@if(isset($service)){{$service->sale_price}}@endif" required>
                                <div id="rate-validation-error" style="color: red; font-size: 12px"></div>
                            </div>
                        </div>
                         <div class="col-lg-2 col-md-2 col-sm-2 form-control-label">
                            <label>Tax</label>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4">
                            <div class="form-group">
                                <input type="number" value="{{isset($service->tax) ? $service->tax : ''}}" step="any" id="tax" name="tax" class="form-control" placeholder="Tax --"  required>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-sm-8 offset-sm-2">
                            <button type="submit" class="btn btn-raised btn-primary btn-round waves-effect">SAVE</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    var rateInput = document.getElementById('rate');
    var errorDiv = document.getElementById('rate-validation-error');

    rateInput.addEventListener('input', function() {
        var rate = this.value;

        // Remove leading zeros
        rate = rate.replace(/^0+/, '');

        // Restrict to 4 digits before and after the decimal point
        var parts = rate.split('.');
        var wholePart = parts[0];
        var decimalPart = parts[1] || '';

        if (wholePart.length > 4 || decimalPart.length > 4) {
            errorDiv.textContent = 'Rate should have a maximum of 4 digits before and after the decimal point.';
            rateInput.setCustomValidity('Rate should have a maximum of 4 digits before and after the decimal point.');
        } else {
            errorDiv.textContent = '';
            rateInput.setCustomValidity('');
        }
    });
</script>
@stop
@section('page-script')
<script src="{{asset('public/assets/bundles/footable.bundle.js')}}"></script>
<script src="{{asset('public/assets/js/pages/tables/footable.js')}}"></script>
@stop

