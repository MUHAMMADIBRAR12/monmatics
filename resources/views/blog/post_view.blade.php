@extends('layout.master')
@section('title', 'Blog List')
@section('parentPageTitle', 'Blog')
@section('page-style')
<link rel="stylesheet" href="{{asset('public/assets/plugins/footable-bootstrap/css/footable.bootstrap.min.css')}}" />
<link rel="stylesheet" href="{{asset('public/assets/plugins/footable-bootstrap/css/footable.standalone.min.css')}}" />
@stop
@section('content')

<style>
    body {
        font-family: Arial, sans-serif;
    }

    .post-container {
        background-color: #d1c2c2;
        padding: 20px;
        border-radius: 10px;
        margin-top: 30px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .post-image img {
        width: 100%;
        border-radius: 10px 10px 0 0;
    }

    .post-title {
        font-size: 24px;
        font-weight: bold;
        margin-top: 10px;
        padding: 10px 0;
        border-bottom: 1px solid #ddd;
    }

    .post-meta {
        font-weight: bold;
        color: #777;
    }

    .post-category, .post-date {
        margin-top: 10px;
    }

    .post-description {
        margin-top: 20px;
        line-height: 1.6;
        background-color: white;
        padding: 20px;
        border-radius: 0 0 10px 10px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
</style>

<div class="container post-container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div >
                    @if ($post)
                    <img class="post-image" src="{{ url('display/' . $post->id) }}"
                         class="rounded-circle shadow " alt="post-image" >
                 @else
                     <p>No image available for this post.</p>
                 @endif
                </div>
                <div class="card-header post-title">{{ $post->title }}</div>
                <div class="card-body">
                    <div class="post-details">
                        <div class="post-category"><span class="post-meta">Created By:</span> {{ $user->name }}</div>
                        <div class="post-category"><span class="post-meta">Category:</span> {{ $post->category }}</div>
                        <div class="post-date"><span class="post-meta">Published:</span> {{ $post->created_at }}</div>
                        <div class="post-description">{!! $post->description !!}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@stop
@section('page-script')
<script src="{{asset('public/assets/bundles/footable.bundle.js')}}"></script>
<script src="{{asset('public/assets/js/pages/tables/footable.js')}}"></script>
<script>
    $(document).ready(function() {
        $(document).on('click', '.del', function() {
            var id = $(this).find('#userId').val();
            $(".model-delete").attr("href", "removepost/" + id);
        });
    });
</script>
@stop