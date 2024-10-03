@extends('layout.master')
@section('title', 'Project')
@section('parentPageTitle', 'Project')
@section('parent_title_icon', 'zmdi zmdi-home')
@section('page-style')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>

<link rel="stylesheet" href="{{asset('public/assets/plugins/dropify/css/dropify.min.css')}}"/>
<style>
.input-group-text {
    padding: 0 .75rem;
}

.amount{
    width: 150px;
    text-align: right;
}
.table td{
    padding: 0.10rem;            
}
.dropify
{
    width: 200px;
    height: 200px;
}
</style>
<script>
var customerURL = "{{ url('customerSearch') }}";
var userURL = "{{ url('userSearch') }}";
var token =  "{{ csrf_token()}}";
</script>
@stop
@section('content')

    <div class="row clearfix">
        <div class="card col-lg-12">
            <div class="header">
                <h2><strong>Project</strong> Details</h2>
            </div>
            @if(session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
            @endif
            <div class="body">
                <form method="post" action="{{url('Project/ProjectManagment/Add')}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $project->id ?? ''  }}">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="name">Project</label>
                            <div class="form-group">
                                <input type="text" name="project" class="form-control" value="{{ $project->name ?? ''  }}"  required>
                            </div>
                        </div>                        
                        <div class="col-md-6">
                            <label for="name">Select Parent Project</label>
                                <div class="form-group">
                                    <select name="parent_id" id="parent_id" class=" form-control show-tick ms select2">
                                        <option value="-1">Select Parent Project</option>
                                        @foreach($parent_projects as $parent)
                                        <option value="{{$parent->id}}" {{ ($parent->id == ( $project->parent_id ??'')) ? 'selected' : '' }}>{{$parent->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                        </div>                        
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label>Customer</label>
                            <input type="text" name="customer" id="customer" class="form-control" value="{{ $project->cust_name ?? ''}}"  placeholder="Customer" onkeyup="autoFill(this.id, customerURL, token)">
                            <input type="hidden" name="customer_ID" id="customer_ID" value="{{ $project->cust_coa_id ?? ''  }}" > 
                        </div>
                        <div class="col-md-6">
                            <label>Project Manager</label>
                            <div class="form-group">
                                <input type="text" name="assign" id="assign" class="form-control" value="{{ $project->project_manager_name ?? ''  }}" placeholder="Project Manager" onkeyup="autoFill(this.id,userURL, token)">
                                <input type="hidden" name="assign_ID" id="assign_ID" value="{{ $project->project_manager ?? ''  }}" >   
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">                        
                        <div class="col-md-4">
                            <label>Start Date</label>
                            <div class="form-group">
                                <input type="date" name="start_date" class="form-control" value="{{ $project->start_date ?? ''  }}"  required>
                            </div>
                        </div>     
                        <div class="col-md-4">
                            <label>End Date</label>
                            <div class="form-group">
                                <input type="date" name="end_date" class="form-control" value="{{ $project->end_date ?? ''  }}" required>
                            </div>
                        </div> 
                        <div class="col-md-4">
                            <label for="name">Category</label>
                                <div class="form-group">
                                    <select name="category" id="category" class=" form-control show-tick ms select2">
                                        <option value="-1">Select category</option>
                                        @foreach($project_category as $category)
                                        <option value="{{$category->description}}" {{ ($category->description == ( $project->category ?? '')) ? 'selected' : '' }}>{{$category->description}}</option>
                                        @endforeach
                                    </select>
                                </div>   
                        </div>                                          
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="fiscal_year">Description</label>
                            <div class="form-group" id="description">
                                <textarea name="description" maxlength="120" rows="4" class="form-control no-resize"  placeholder="">{{ $project->description ?? ''  }}</textarea>
                            </div>
                        </div> 
                        <div class="col-sm-6">
                            <label for="fiscal_year">Attachment</label>
                            <br>
                            @if($attachmentRecord ?? '')
                                <table>
                                @foreach($attachmentRecord as $attachment)
                                <tr>
                                    <td><button  type="button" class="btn btn-danger btn-sm" id="{{ $attachment->file }}" onclick="delattach(attachmentURL,this.id,token)"><i class="zmdi zmdi-delete"></i></button></td>
                                    <td><a target="_blank" href="{{asset('assets/attachments/'. $attachment->file)}}" download id="attachment">{{ $attachment->file }}</a></td>
                                </tr>
                                @endforeach
                                </table>
                            @endif
                            <input name="file" type="file" class="dropify"> 
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