@extends('layout.master')
@section('title', 'Terms')
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
                <h2><strong>Term</strong> Details</h2>
            </div>
            @if(session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
            @endif
            <div class="body">
                <form method="post" action="{{url('Admin/Term/List/Add')}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $term->id ?? ''  }}">
                    <div class="row">                                               
                        
                        <div class="col-md-3">
                            <label for="category">Category</label>
                            <div class="form-group">
                                <select name="category" class="form-control show-tick ms select2" data-placeholder="Select" required>
                                    <option value="">Select Category</option> 
                                    @foreach($categories as $category)
                                        <option  {{ ( $category->category == ($term->category ?? '')) ? 'selected' : '' }}  value="{{ $category->category}}">{{ $category->category}}</option> 
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label for="terms">Description</label>
                            <div class="form-group" id="note">
                                <textarea name="terms" maxlength="425" rows="6" class="form-control no-resize"  placeholder="">{{ $term->terms ?? '' }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-2">                            
                            <label for="status">Active Status</label>
                            <div class="form-group">                                
                                <label class="switch">
                                    <input type="checkbox" name="status" value='1' {{ @($term->status ?? '')?'checked':'' }}>
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