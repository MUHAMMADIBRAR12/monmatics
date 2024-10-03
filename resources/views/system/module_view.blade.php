@extends('layout.master')
@section('title', 'Module View')
@section('parentPageTitle', 'Admin')
@section('page-style')
    <link rel="stylesheet" href="{{ asset('public/assets/plugins/footable-bootstrap/css/footable.bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/assets/plugins/footable-bootstrap/css/footable.standalone.min.css') }}" />
    <script lang="javascript/text">
        var token = "{{ csrf_token() }}";
    </script>
@stop
@section('content')
    <div class="row clearfix">
        <div class="col-lg-12">
            <div class="card col-lg-12">
                <div class="table-responsive contact">
                    <div class="body">
                        <a class="btn btn-primary" href="{{ url('Admin/Modules/List') }}">back</a>
                        <form method="post" action="{{ url('Admin/Modules/Save/') }}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-6 col-md-6">
                                    <label for="module">Modules</label><br>
                                    <input type="checkbox" class="form-check-input" name="parent_status" id="parent_status"
                                        @if ($module->status == 1) checked @endif>
                                    <input type="text" name="parentModule" value="{{ $module->module }}"
                                        class="form-control">
                                    <input type="hidden" name="parent_id" value="{{ $module->id }}">
                                </div>
                            </div><br>
                            <div class="row">
                                @php    $i=0;  @endphp
                                @foreach ($moduledetail as $mdetail)
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <input type="checkbox" class="form-check-input"
                                                name="status[{{ $i }}]" value="{{ $mdetail->id }}"
                                                @if ($mdetail->status == 1) checked @endif>
                                            <input type="text" name="childModule[]" value="{{ $mdetail->module }}"
                                                class="form-control">

                                            <input type="hidden" name="id[]" value="{{ $mdetail->id }}">

                                        </div>
                                    </div>

                                    @php    $i++;  @endphp
                                @endforeach
                                <div class="col-md-3">
                                    <input type="checkbox" class="form-check-input" name="status_new[]">
                                    <input type="text" name="childModule_new[]" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <input type="checkbox" class="form-check-input" name="status_new[]">
                                    <input type="text" name="childModule_new[]" class="form-control">
                                </div>
                            </div>
                            <div class="form-controll text-right">
                                <button type="submit" class="btn btn-primary btn-lg">Save</button>
                            </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal for delete confirmation -->
    @stop
