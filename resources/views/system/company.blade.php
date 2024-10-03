@extends('layout.master')
@section('title', 'Company')
@section('parentPageTitle', 'Solutions Wave')
@section('parent_title_icon', 'zmdi zmdi-home')
@section('page-style')
    <link rel="stylesheet" href="{{ asset('public/assets/plugins/dropify/css/dropify.min.css') }}" />
@stop
@section('content')
    <div class="row clearfix">
        <div class="card col-lg-12">
            <div class="header">
                <h2><strong>Company</strong> Information</h2>
            </div>
            @if (session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
            @endif
            <div class="body">
                <form method="post" action="{{ url('Admin/Company/Add') }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $company->id ?? '' }}">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="name">Company Name</label>
                            <div class="form-group">
                                <input type="text" name="name" class="form-control"
                                    value="{{ old('name', $company->name ?? '') }}" required>
                            </div>
                            @error('name')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="website">Website</label>
                            <div class="form-group">
                                <input type="text" name="website" class="form-control"
                                    value="{{ old('website', $company->website ?? '') }}" required>
                            </div>
                            @error('website')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="phone">Phone</label>
                            <div class="form-group">
                                <input type="text" name="phone" class="form-control"
                                    value="{{ old('phone', $company->phone ?? '') }}" required>
                            </div>
                            @error('phone')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="email">Email</label>
                            <div class="form-group">
                                <input type="text" name="email" class="form-control"
                                    value="{{ old('email', $company->email ?? '') }}" required>
                            </div>
                            @error('email')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="address">Address</label>
                            <div class="form-group">
                                <input type="text" name="address" class="form-control"
                                    value="{{ old('address', $company->address ?? '') }}" required>
                            </div>
                            @error('address')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="fax">Fax</label>
                            <div class="form-group">
                                <input type="text" name="fax" class="form-control"
                                    value="{{ old('fax', $company->fax ?? '') }}" required>
                            </div>
                            @error('fax')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="state">State</label>
                            <div class="form-group">
                                <input type="text" name="state" class="form-control"
                                    value="{{ old('state', $company->state ?? '') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="address_two">Address 2</label>
                            <div class="form-group">
                                <input type="text" name="address_two" class="form-control"
                                    value="{{ old('address_two', $company->address_two ?? '') }}" required>
                            </div>
                            @error('address_two')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="currency">Currency</label>
                            <div class="form-group">
                                <select name="currency" class="form-control show-tick ms select2" data-placeholder="Select"
                                    required>
                                    <option value="">-- Select Currency --</option>
                                    @foreach ($currencies as $currency)
                                        <option value="{{ $currency->code }}"
                                            {{ old('currency', isset($company) ? $company->currency : '') == $currency->code ? 'selected' : '' }}>
                                            {{ $currency->code }}
                                        </option>
                                    @endforeach
                                </select>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="country">Country</label>
                            <div class="form-group">
                                <select name="country" class="form-control show-tick ms select2" data-placeholder="Select"
                                    required>
                                    <option value="">-- Select Country --</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->name }}"
                                            {{ old('country', isset($company) ? $company->country : '') == $country->name ? 'selected' : '' }}>
                                            {{ $country->name }}
                                        </option>
                                    @endforeach
                                </select>

                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="tax_number">Tax Number</label>
                            <div class="form-group">
                                <input type="text" name="tax_number"
                                    value="{{ old('tax_number', $company->tax_number ?? '') }}" class="form-control">
                            </div>
                            @error('tax_number')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-2">
                            <div class="form-check mt-4">
                                <input class="form-check-input" name="multi_currency" type="checkbox"
                                    {{ $multiCurrencyChecked ? 'checked' : '' }}>
                                <label class="form-check-label" for="defaultCheck1">
                                    Multi Currency
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="fiscal_year">Fiscal Year</label>
                            <div class="form-group">
                                <select name="fiscal_year" class="form-control show-tick ms select2"
                                    data-placeholder="Select" required>
                                    <option value="">Select Month</option>
                                    @foreach ($months as $month)
                                        <option value="{{ $month->month }}"
                                            {{ old('fiscal_year', isset($company) ? $company->fiscal_year : '') == $month->month ? 'selected' : '' }}>
                                            {{ $month->month }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="attachment">Logo</label>
                            <div class="form-group">
                                @if (isset($attachment))
                                    <div class="form-group" id='attRow{{ $i }}'>
                                        <button type="button" class="btn btn-danger btn-sm attachment-btn"
                                            id="{{ $company->logo }}"
                                            onclick="deleteFileA('{{ $company->id }}', {{ $i }})"><i
                                                class="zmdi zmdi-delete"></i></button>
                                        <img src="{{ url('display/' . $attachment->id) }}">
                                    </div>
                                    <script>
                                        $("#logo:visible").hide()
                                    </script>
                                @endif
                                <input name="file" id="logo" type="file" class="dropify">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="fiscal_year">Currency Format</label>
                                    <div class="form-group">
                                        <select name="fiscal_year" class="form-control show-tick ms select2"
                                            data-placeholder="Select" required>
                                            <option value="">Select Format</option>
                                            @foreach ($months as $month)
                                                <option value="{{ $month->month }}"
                                                    {{ old('fiscal_year', isset($company) ? $company->fiscal_year : '') == $month->month ? 'selected' : '' }}>
                                                    {{ $month->month }}
                                                </option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="fiscal_year">Date Format</label>
                                    <div class="form-group">
                                        <select name="date_format" class="form-control show-tick ms select2"
                                            data-placeholder="Select" required>
                                            <option value="">Select Month</option>
                                                <option value="M-d-Y">Month-day-Year</option>
                                                <option value="d-M-Y">day-Month-Year</option>
                                                <option value="Y-m-d">Year-month-day</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- <div class="col-md-6">
                                        <label for="fiscal_year">&nbsp;</label>
                                        <div class="form-group">
                                            <select class="form-control show-tick ms select2" data-placeholder="Select"
                                                required>
                                                <option value="">Select Month</option>
                                                @foreach ($months as $month)
    <option value="{{ $month->month }}"
                                                        {{ $month->month == ($company->fiscal_year ?? '') ? 'selected' : '' }}>
                                                        {{ $month->month }}</option>
    @endforeach
                                            </select>
                                        </div>
                                    </div> -->
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
    <!-- Model For Delete -->
    <div class="modal fade" id="modalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-center">
                    <span class="text-danger" id="exampleModalLongTitle">Are You Want to Delete This Logo?</span>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <a class="btn btn-secondary" data-dismiss="modal">No</a>
                    <a class="btn btn-success model-delete attach-del" data-dismiss="modal">Yes</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Model For Delete -->
@stop
@section('page-script')
    <script src="{{ asset('public/assets/plugins/dropify/js/dropify.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/pages/forms/dropify.js') }}"></script>
    <script src="{{ asset('public/assets/js/sw.js') }}"></script>
    <script>
        var attachmentURL = "{{ url('companyAttachDelete') }}";
        var token = "{{ csrf_token() }}";
        $('document').ready(function() {
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
        });

        function deleteFileA(id, num) {
            var x = confirm("Are you sure you want to delete?");
            if (x) {
                var url = '{{ url('delete/') }}';
                deleteFile(url, id, token);
                $('#attRow' + num).html('');
                $("#logo:visible").show()
            }
        }
    </script>
@stop
