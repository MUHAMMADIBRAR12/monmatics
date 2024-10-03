@extends('layout.master')
@section('title', 'System')
@section('parentPageTitle', 'Tax')
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
                <h2><strong>Tax</strong> Details</h2>
            </div>
            @if(session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
            @endif
            <div class="body">
                <form method="post" action="{{url('Admin/Taxes/Add')}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $tax->id ?? ''  }}">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="name">Name</label>
                            @if(isset($tax) && $tax->editable==0)
                                <br>
                                <label>{{ $tax->name ?? ''  }}</label>
                                <input type="hidden" name="name"  value="{{ $tax->name ?? ''  }}">
                            @else
                            <div class="form-group">
                                <input type="autofill" name="name" class="form-control" value="{{ $tax->name ?? ''  }}"  required>
                            </div>
                            @endif
                        </div>                        
                        <div class="col-md-3">
                            <label for="coa_id">Main Account</label>
                            <div class="form-group">
                                <select name="coa_id" class="form-control show-tick ms select2" data-placeholder="Select" required>
                                    <option value="">Select Account</option> 
                                    @foreach($coaAccount as $account)
                                        <option  {{ ( $account->id == ($tax->coa_id ?? '')) ? 'selected' : '' }}  value="{{ $account->id}}">{{ $account->name}}</option> 
                                    @endforeach
                                </select>
                            </div>
                        </div>                        
                        <div class="col-md-3">
                            <label for="rate">Rate</label>
                            <div class="form-group"><input type="number" name="rate" class="form-control amount" step="any" value="{{ $tax->rate ?? ''  }}" required>
                            </div>
                            @if ($errors->has('rate'))
                                <div id="rate-validation-error" style="color: red; font-size: 12px">{{ $errors->first('rate') }}</div>
                            @else
                                <div id="rate-validation-error"></div>
                            @endif
                        </div> 
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label for="withheld">Withheld</label>
                            <div class="form-group">                                
                                <label class="switch">
                                    <input type="checkbox" name="withheld" value='1' {{ @($tax->withheld ?? '')?'checked':'' }}>
                                    <span class="slider round"></span>
                                  </label>
                            </div>
                        </div> 
                        <div class="col-md-3">
                            <label for="status">Active Status</label>
                            <div class="form-group">                                
                                <label class="switch">
                                    <input type="checkbox" name="status" value='1' {{ @($tax->status ?? '')?'checked':'' }}>
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

<script>
    const rateInput = document.getElementById('rate');
    const rateValidationError = document.getElementById('rate-validation-error');

    rateInput.addEventListener('input', function() {
        const rate = rateInput.value;
        const ratePattern = /^\d{0,10}(\.\d{0,2})?$/;
        
        if (!ratePattern.test(rate)) {
            rateValidationError.textContent = 'Rate should have a maximum of 10 digits before and 2 digits after the decimal point.';
        } else {
            rateValidationError.textContent = '';
        }
    });
</script>
@stop