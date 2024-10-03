@extends('layout.master')
@section('title', 'Blog')
@section('parentPageTitle', 'Blog')

@section('page-style')
<link rel="stylesheet" href="{{ asset('public/assets/plugins/dropify/css/dropify.min.css') }}" />
<link rel="stylesheet" href="{{ asset('public/assets/plugins/footable-bootstrap/css/footable.bootstrap.min.css') }}" />
<link rel="stylesheet" href="{{ asset('public/assets/plugins/footable-bootstrap/css/footable.standalone.min.css') }}" />
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<link rel="stylesheet" href="{{asset('public/assets/plugins/dropify/css/dropify.min.css')}}" />
<!-- <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet"> -->
<style>
    ul {
        list-style-type: none;
    }
</style>

<style>
    .dropify {
        width: 200px;
        height: 200px;
    }

    th.project .project {
        display: block;
    }
</style>
@stop
@section('content')

<div class="row clearfix">
    <div class="card col-lg-12">
        <div class="header">
            <div class="row">
                <div class="col-md-9">
                    <h2><strong>Post Form</strong></h2>
                </div>
                <div class="col-md-3">

                    <a href="{{ url('role_management')}}" class="btn btn-primary float-right"><i class="zmdi zmdi-arrow-left"></i></a>
                </div>
            </div>
        </div>
        @if(session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
        @endif
        <div class="body">
            <form method="post" action="{{ isset($posts) ? url('blog/post/update/'.$posts->id) : url('blog/post/save') }}" enctype="multipart/form-data">

                @csrf
                <input type="hidden" name="id" value="{{ $posts->id ?? '' }}">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="role">Title</label>
                            <input type="text" name="title" value="{{$posts->title ?? ''}}" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="category">Category</label>
                            <select name="category" class="form-control show-tick ms select2" data-placeholder="Select">
                                <option value="">--Select Category--</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->category }}" {{ isset($posts) && $posts->category == $category->category ? 'selected' : '' }}>
                                        {{ $category->category }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" class="form-control show-tick ms select2" data-placeholder="Select">
                                <option value="">--Select Status--</option>
                                <option value="Publish" {{ isset($posts) && $posts->status == 'Publish' ? 'selected' : '' }}>Publish</option>
                                <option value="Draft" {{ isset($posts) && $posts->status == 'Draft' ? 'selected' : '' }}>Draft</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                
             
                   
              
                
                <div class="col-sm-12 pr-0">
                    <label for="fiscal_year">Attachment</label>
                    
                    
                    @if ($posts)
                                <img src="{{ url('display/' . $posts->id) }}"
                                     class="rounded-circle shadow " alt="post-image" style="max-width: 100px; float:right;">
                             @else
                                 <p>No image available for this post.</p>
                             @endif
                    <input name="file" type="file" class="dropify">
                </div>
              
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="role">Description</label>
                            <!-- <textarea id="summernote" name="bodytext"></textarea> -->
                            <textarea name="bodytext">{{ isset($posts) ? $posts->description : '' }}</textarea>

                        </div>
                    </div>

                </div>
{{-- 
                <div class="col-md-6">
                    
                    @if(isset($posts) && !empty($posts->image))
                    <img src="{{ asset('storage/app/public/images/' . $posts->image) }}" alt="Post Image" style="max-width: 200px; border-radius: 10px;">
                    @endif
                    <input type="file" name="image">
                </div> --}}



                
                <div class="row form-group" id="h2">
                    <div class="col-md-12 mt-3" style="text-align: center; width: 35%;height: 105%;">
                        <input type="submit" value="Save" class="btn btn-primary py-2 px-4 text-white">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@stop
@section('page-script')
<script src="{{ asset('public/assets/plugins/dropify/js/dropify.min.js') }}"></script>
<script src="{{asset('public/assets/plugins/dropify/js/dropify.min.js')}}"></script>
<script src="{{asset('public/assets/js/pages/forms/dropify.js')}}"></script>
<script src="{{asset('public/assets/js/sw.js')}}"></script>
<script src="{{asset('public/assets/bundles/footable.bundle.js')}}"></script>
<script src="{{asset('public/assets/js/pages/tables/footable.js')}}"></script>
<script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
<script>
    $(document).ready(function() {
        CKEDITOR.replace('bodytext');
    });
</script>
@stop