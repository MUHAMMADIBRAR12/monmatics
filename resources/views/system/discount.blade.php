@extends('layout.master')
@section('title', 'Discount')
@section('parentPageTitle', 'System')
@section('parent_title_icon', 'zmdi zmdi-home')
@section('page-style')
<style>
.input-group-text {
    padding: 0 .75rem;
}

.amount{
    width: 150px;
    text-align: right;
}
/* The switch - the box around the slider */
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

</style>
@stop
@section('content')
    <div class="row clearfix">
        <div class="card col-lg-12">
            <div class="header">
                <h2><strong>Discount</strong> Details</h2>
            </div>
            @if(session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
            @endif
            <div class="body">
                <form method="post" action="{{url('Admin/Discounts/Add')}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $discount->id ?? ''  }}">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="name">Name</label>
                            
                            @if(isset($discount) && is_object($discount) && property_exists($discount, 'editable') && $discount->editable == 0)
                            <br>
                            <label>{{ $discount->name ?? ''  }}</label>
                            @else
                            <div class="form-group">
                                <input type="autofill" name="name" class="form-control" value="{{ $discount->name ?? ''  }}"  required>
                            </div>
                            @endif
                        </div>                        
                        <div class="col-md-3">
                            <label for="coa_id">Main Account</label>
                            <div class="form-group">
                                <select name="coa_id" class="form-control show-tick ms select2" data-placeholder="Select" required>
                                    <option value="">Select Account</option> 
                                    @foreach($coaAccount as $account)
                                        <option  {{ ( $account->id == ($discount->coa_id ?? '')) ? 'selected' : '' }}  value="{{ $account->id}}">{{ $account->name}}</option> 
                                    @endforeach
                                </select>
                            </div>
                        </div>      
                        <div class="col-md-3">
                          
                            <label for="type">Category</label>
                            <div class="form-group">
                                <select name="category" class="form-control show-tick ms select2" data-placeholder="Select" required>
                                    <option value="">Select Type</option> 
                                    <option value="Amount" {{ ($discount->category ?? '') == 'Amount' ? 'selected' : '' }}>Amount</option>
                                    <option value="Rate" {{ ($discount->category ?? '') == 'Rate' ? 'selected' : '' }}>Rate</option>
                                </select>
                            </div>

                            <!-- Amount Input Field -->
                            <div class="col-md-3" id="amountField" style="display: {{ ($discount->category ?? '') == 'Amount' ? 'block' : 'none' }}">
                                <label for="rate">Amount</label>
                                <div class="form-group">
                                    <input type="number" name="amount" class="form-control amount" step="any" value="{{ $discount->rate ?? '' }}">
                                </div>
                            </div>

                            <!-- Rate Input Field -->
                            <div class="col-md-3" id="rateField" style="display: {{ ($discount->category ?? '') == 'Rate' ? 'block' : 'none' }}">
                                <label for="rate">Rate <small>is in %</small></label>
                                <div class="form-group">
                                    <input type="number" name="rate_percentage" class="form-control amount" step="any" value="{{ $discount->rate ?? '' }}">
                                </div>
                            </div>
                            </div>


                    
                        <div class="col-md-3">
                            <label for="type">Type</label>
                            <div class="form-group">
                                <select name="type" class="form-control show-tick ms select2" data-placeholder="Select" required>
                                    <option value="">Select Type</option> 
                                    <option value="Sale" {{ ($discount->type ?? '') == 'Sale' ? 'selected' : ''}}>Sale</option>
                                <option value="Purchase" {{ ($discount->type ?? '') == 'Purchase' ? 'selected' : ''}}>Purchase</option>
                                </select>
                            </div>
                        </div> 
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label for="status">Active Status</label>
                            <div class="form-group">                                
                                <label class="switch">
                                    <input type="checkbox" name="status" value='1' {{ ($discount->status ?? '')?'checked':'' }}>
                                    <span class="slider round"></span>
                                  </label>
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
@stop

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- JavaScript Code -->
<!-- Your existing HTML content -->

<!-- JavaScript Code -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    var selectedCategory = '{{ $discount->category ?? '' }}';

    function toggleFields(selectedOption) {
        if (selectedOption === 'Amount') {
            $('#amountField').show();
            $('#rateField').hide();
        } else if (selectedOption === 'Rate') {
            $('#amountField').hide();
            $('#rateField').show();
        } else {
            $('#amountField').hide();
            $('#rateField').hide();
        }
    }

    $('select[name="category"]').on('change', function() {
        var selectedOption = $(this).val();
        toggleFields(selectedOption);
    });

    // Initially set the fields based on the pre-selected category
    toggleFields(selectedCategory);
});
</script>



