@extends('layout.master')
@section('title', 'Inventory')
@section('parentPageTitle', 'Products')
@section('parent_title_icon', 'zmdi zmdi-home')
@section('page-style')

    <link rel="stylesheet" href="{{ asset('public/assets/plugins/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/assets/plugins/morrisjs/morris.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/assets/plugins/jquery-spinner/css/bootstrap-spinner.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/assets/plugins/bootstrap-select/css/bootstrap-select.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/assets/plugins/nouislider/nouislider.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/assets/plugins/dropify/css/dropify.min.css') }}" />
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
                <h2><strong>Product</strong> Details</h2>
            </div>
            @if (session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="body">
                <form method="post" action="{{ url('Inventory/Product/Add') }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $product->id ?? '' }}">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="name">Name</label>
                            <div class="form-group">
                                <input type="autofill" name="name" class="form-control"
                                    value="{{ $product->name ?? old('name') }}" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label for="sku">Brand</label>
                            <div class="form-group">
                                <select name="brand" class="form-control show-tick ms select2" data-placeholder="Select">
                                    <option value="">--Select Brand--</option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->name }}"
                                            {{ $brand->name == ($product->brand ?? old('brand')) ? 'selected' : '' }}>
                                            {{ $brand->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="sku">SKU</label>
                            <div class="form-group">
                                <input type="autofill" name="sku" class="form-control"
                                    value="{{ $product->sku ?? old('sku') }}" >
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label for="sku">UPC/Code</label>
                            <div class="form-group">
                                <input type="autofill" name="code" class="form-control"
                                    value="{{ $product->code ?? old('code') }}" >
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label for="sku">Taxable</label>
                            <div class="form-group">
                                <select name="taxable" class="form-control show-tick ms select2" data-placeholder="Select">
                                    <option value="">--Select Tax--</option>
                                    @foreach ($taxables as $taxable)
                                        <option
                                            {{ $taxable->id == ($product->tax ?? old('taxable')) ? 'selected' : '' }}
                                            value="{{ $taxable->id }}">{{ $taxable->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label for="coa_id">Main Account</label>
                            <div class="form-group">
                                <select name="coa_id" class="form-control show-tick ms select2" data-placeholder="Select">
                                    <option value="">Select Account</option>
                                    @foreach ($coaAccount as $account)
                                        <option
                                            {{ $account->id == ($product->coa_id ?? old('coa_id')) ? 'selected' : '' }}
                                            value="{{ $account->id }}" selected>{{ $account->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="category">Category</label>
                            <div class="form-group">
                                <select name="category" class="form-control show-tick ms select2" data-placeholder="Select">
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $category)
                                        <option
                                            {{ $category->category == ($product->category ?? old('category')) ? 'selected' : '' }}
                                            value="{{ $category->category }}">{{ $category->category }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="type">Type</label>
                            <div class="form-group">
                                <select name="type" class="form-control show-tick ms select2" data-placeholder="Select">
                                    <option value="">Select Type</option>
                                    @foreach ($types as $type)
                                        <option {{ $type->type == ($product->type ?? old('type')) ? 'selected' : '' }}
                                            value="{{ $type->type }}">{{ $type->type }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="status">Status</label>
                            <div class="form-group">
                                <select name="status" class="form-control show-tick ms select2" data-placeholder="Select">
                                    <option value="">--Select Status--</option>
                                    <option {{ ($product->status ?? old('status')) == 'Active' ? 'selected' : '' }}
                                        value="Active">Active</option>
                                    <option {{ ($product->status ?? old('status')) == 'Suspended' ? 'selected' : '' }}
                                        value="Suspended">Suspended</option>
                                    <option {{ ($product->status ?? old('status')) == 'Closed' ? 'selected' : '' }}
                                        value="Closed">Closed</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label for="primary_unit">Primary Unit</label>
                            <div class="form-group">
                                <select name="primary_unit" id="primary_unit" class="form-control show-tick ms select2"
                                    data-placeholder="Select">
                                    <option value="">Select Unit</option>
                                    @foreach ($units as $unit)
                                        <option
                                            {{ $unit->id == ($product->primary_unit ?? old('primary_unit')) ? 'selected' : '' }}
                                            value="{{ $unit->id }}">{{ $unit->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="purchase_unit">Purchase Unit</label>
                            <div class="form-group">
                                <select name="purchase_unit" id="purchase_unit" class="form-control show-tick ms select2"
                                    data-placeholder="Select">
                                    <option value="">Select Unit</option>
                                    @foreach ($units as $unit)
                                        <option
                                            {{ $unit->id == ($product->purchase_unit ?? old('purchase_unit')) ? 'selected' : '' }}
                                            value="{{ $unit->id }}">{{ $unit->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="sale_unit">Sale Unit</label>
                            <div class="form-group">
                                <select name="sale_unit" id="sale_unit" class="form-control show-tick ms select2"
                                    data-placeholder="Select">
                                    <option value="">Select Unit</option>
                                    @foreach ($units as $unit)
                                        <option
                                            {{ $unit->id == ($product->sale_unit ?? old('sale_unit')) ? 'selected' : '' }}
                                            value="{{ $unit->id }}">{{ $unit->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="reorder">Reorder Level</label>
                            <div class="form-group">
                                <input type="autofill" name="reorder" class="form-control"
                                    value="{{ $product->reorder ?? old('reorder') }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label for="purchase_price">Purchase Price</label>
                            <div class="form-group"><input type="number" name="purchase_price"
                                    class="form-control amount" step="0.01"
                                    value="{{ $product->purchase_price ?? old('purchase_price') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="sale_price">Sale Price</label>
                            <div class="form-group"><input type="number" name="sale_price" class="form-control amount"
                                    step="0.01" value="{{ $product->sale_price ?? old('sale_price') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="packaging_detail">Packaging Detail</label>
                            <div class="form-group">
                                <select name="packaging_detail" class="form-control show-tick ms select2"
                                    data-placeholder="Select">
                                    <option value="">--Select Packaging Detail--</option>
                                    @foreach ($packaging_details as $packaging_detail)
                                        <option
                                            {{ $packaging_detail->description == ($product->packaging_detail ?? old('packaging_detail')) ? 'selected' : '' }}
                                            value="{{ $packaging_detail->description }}">
                                            {{ $packaging_detail->description }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label>Report Order</label>
                            <input type="number" step="any" name="report_order" class="form-control qty"
                                value="{{ $product->report_order ?? old('report_order') }}">

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="purchase_description">Purchase Description</label>
                            <div class="form-group">
                                <textarea name="purchase_description" rows="4" class="form-control no-resize"
                                    placeholder="Purchase Description">{{ $product->purchase_description ?? old('purchase_description') }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="sales_description">Sales Description</label>
                            <div class="form-group">
                                <textarea name="sales_description" rows="4" class="form-control no-resize" placeholder="Sales Description">{{ $product->sales_description ?? old('sales_description') }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="description">Description</label>
                            <div class="form-group">
                                <textarea name="description" rows="4" class="form-control no-resize" placeholder="General Description">{{ $product->description ?? old('description') }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        @for ($i = 0; $i <= 3; $i++)
                            <div class="col-md-3">
                                <label for="attachment">Attachment {{ $i + 1 }}</label>
                                <div class="form-group">
                                    @if ($attachments[$i] ?? '')
                                        <button type="button" class="btn btn-danger btn-sm attachment-btn"
                                            id="{{ $attachments[$i]->file }}" data-toggle="modal"
                                            data-target="#modalCenter"><i class="zmdi zmdi-delete"></i></button>
                                        <img src="{{ asset('public/assets/products/' . $attachments[$i]->file) }}">
                                    @else
                                        <input name="file[]" type="file" class="dropify">
                                    @endif
                                </div>
                            </div>
                        @endfor
                    </div>


                    <!-- Model For Delete -->
                    <div class="modal fade" id="modalCenter" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header d-flex justify-content-center">
                                    <span class="text-danger" id="exampleModalLongTitle">Are You Want to Delete This
                                        Attachment?</span>
                                </div>
                                <div class="modal-footer d-flex justify-content-center">
                                    <a class="btn btn-secondary" data-dismiss="modal">No</a>
                                    <a class="btn btn-success model-delete attach-del" data-dismiss="modal">Yes</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Model For Delete -->


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
    <script src="{{ asset('public/assets/plugins/dropify/js/dropify.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/pages/forms/dropify.js') }}"></script>
    <script>
        var unitsURL = "{{ url('unitsSearch') }}";
        var attachmentURL = "{{ url('productAttachDelete') }}";
        var token = "{{ csrf_token() }}";
        $('#primary_unit').on('change', function() {
            $('#purchase_unit').html('');
            $('#sale_unit').html('');
            $('#purchase_unit').append('<option>--Select Purchase Uint --</option>');
            $('#sale_unit').append('<option>--Select Sale Uint --</option>');
            var primary_unit = $(this).val();
            $.post(unitsURL, {
                primary_unit: primary_unit,
                _token: token
            }, function(data) {
                data.map(function(val, i) {
                    var option = '<option value="' + val.id + '">' + val.name + '</option>';
                    $('#purchase_unit').append(option);
                    $('#sale_unit').append(option);
                });
            });
        });
        $('.attachment-btn').click(function() {
            var img = $(this).attr('id');
            $('.attach-del').click(function() {
                $.post(attachmentURL, {
                    img: img,
                    _token: token
                }, function(data) {
                    location.reload(true);

                });
            });
        });
    </script>
@stop
