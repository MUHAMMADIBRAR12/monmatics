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
                            @if($discount->editable==0)
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
                            <label for="rate">Rate</label>
                            <div class="form-group"><input type="number" name="rate" class="form-control amount" step="any" value="{{ $discount->rate ?? ''  }}" required>
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