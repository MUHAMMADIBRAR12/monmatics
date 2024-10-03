@extends('layout.master')
@section('title', 'Contact View')
@section('parentPageTitle', 'Crm')
@section('parent_title_icon', 'zmdi zmdi-home')
@section('page-style')
    <style>
        .panel,
        .flip {
            padding: 5px;
            text-align: center;
            background-color: #e5eecc;
            border: solid 1px #c3c3c3;
        }

        .panel {
            display: none;
        }

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
                <h2><strong>{{ $contact->contact_name ?? '' }}</strong> Details</h2>
            </div>
            <div class="body">
                <ul class="nav nav-tabs p-0 mb-3">
                    <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#home">General</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#attachments">Other</a></li>
                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane in active" id="home">
                        <div class="row">
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="name">Name</label>
                                        <div class="form-group ">
                                            <p class="text-primary">{{ $contact->contact_name ?? '' }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="sku">Title</label>
                                        <div class="form-group">
                                            <p class="text-primary">{{ $contact->title ?? '' }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="sku">Email</label>
                                        <div class="form-group">
                                            <p class="text-primary">{{ $contact->email ?? '' }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="coa_id">Primary Address</label>
                                        <div class="form-group">
                                            <p class="text-primary">{{ $contact->primary_address ?? '' }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="category">Primary City</label>
                                        <div class="form-group">
                                            <p class="text-primary">{{ $contact->primary_city ?? '' }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="type">Primary Country</label>
                                        <div class="form-group">
                                            <p class="text-primary">{{ $contact->primary_country ?? '' }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="primary_unit">Other Address</label>
                                        <div class="form-group">
                                            <p class="text-primary">{{ $contact->other_address ?? '' }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="secondary_unit">Other City</label>
                                        <div class="form-group">
                                            <p class="text-primary">{{ $contact->other_city ?? '' }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="reorder">Other country</label>
                                        <div class="form-group">
                                            <p class="text-primary">{{ $contact->other_country ?? '' }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="purchase_price">Phone No.</label>
                                        <div class="form-group">
                                            <p class="text-primary">{{ $contact->mobile ?? '' }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="sale_price">Office Phone No.</label>
                                        <div class="form-group">
                                            <p class="text-primary">{{ $contact->phone_office ?? '' }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="sale_price">Description</label>
                                        <div class="form-group">
                                            <p class="text-primary">{{ $contact->description ?? '' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <img src="{{ asset('public/assets/attachments/' . $contact->profile) }}"
                                    class="rounded-circle" alt="">
                            </div>
                        </div>
                        <div class="row">
                            <table class=" table-responsive align-center">
                                <tr>
                                    <td><button class="btn btn-primary"
                                            onclick="window.location.href = '{{ url('Crm/Contacts/Create/' . $contact->id) }}';"><i
                                                class="zmdi zmdi-edit"></i> | Edit</button></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="attachments">
                        <div class="row">
                            <div class="col-md-12">
                                <x-detail :name="$contact->contact_name" :id="$contact->id" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('public/assets/js/sw.js') }}"></script>
    <script>
        $(document).ready(function() {
            $("#flip1").click(function() {
                $("#panel1").slideToggle("slow");
            });
            $("#flip2").click(function() {
                $("#panel2").slideToggle("slow");
            });
            $("#flip3").click(function() {
                $("#panel3").slideToggle("slow");
            });
        });
    </script>
@stop
