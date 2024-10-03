@extends('layout.master')
@section('title', 'Warehouse')
@section('parentPageTitle', 'Warehouse')
@section('parent_title_icon', 'zmdi zmdi-home')
@section('page-style')


    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/morrisjs/morris.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/jquery-spinner/css/bootstrap-spinner.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-select/css/bootstrap-select.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/nouislider/nouislider.min.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/plugins/dropify/css/dropify.min.css') }}" />
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
@stop

@section('content')

    <div class="row clearfix">
        <div class="card col-lg-12">
            <div class="header">
                <h2><strong>Warehouse</strong> Details</h2>
            </div>
            @if (session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
            @endif
            <div class="body">
                <form method="post" action="{{ url('Admin/Warehouse/Add') }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $warehouse->id ?? '' }}">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="name">Warehouse</label>
                            <div class="form-group">
                                <input type="text" name="warehouse" class="form-control"
                                    value="{{ $warehouse->name ?? '' }}" >
                                </div>
                                @error('warehouse')
                            <div class="alert alert-danger">{{$message}}</div>
                                
                            @enderror
                            </div>
                       
                        <div class="col-md-6">
                            <label for="name">Select Parent Warehouse</label>
                            <div class="form-group">
                                <select name="parent_id" id="paren_id" class=" form-control show-tick ms select2">
                                    <option value="-1">Select Parent Warehouse</option>
                                    @foreach ($parent_warehouses as $parent)
                                        <option value="{{ $parent->id }}"
                                            {{ $parent->id == ($warehouse->parent_id ?? '') ? 'selected' : '' }}>
                                            {{ $parent->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label>Phone</label>
                            <div class="form-group">
                                <input type="text" name="phone" class="form-control"
                                    value="{{ $warehouse->phone ?? '' }}" required>
                            </div>
                            @error('phone')
                            <div class="alert alert-danger">{{$message}}</div>
                                
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label>Location</label>
                            <div class="form-group">
                                <input type="text" name="location" class="form-control"
                                    value="{{ $warehouse->location ?? '' }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <label>Address</label>
                            <div class="form-group">
                                <input type="text" name="address" class="form-control"
                                    value="{{ $warehouse->address ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label>Status</label>
                            <div class="form-group">
                                <input type="radio" id="active" name="status" value="1"
                                    {{ ($warehouse->status ?? '') == 1 ? 'checked' : '' }}>
                                <label for="male">Active</label>
                                <input type="radio" id="inactive" name="status" value="0"
                                    {{ ($warehouse->status ?? '') == 0 ? 'checked' : '' }}>
                                <label for="female">InActive</label>
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
    <script src="{{ asset('assets/plugins/dropify/js/dropify.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/forms/dropify.js') }}"></script>
@stop
