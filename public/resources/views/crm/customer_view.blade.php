@extends('layout.master')
@section('title', 'Customer Detail')
@section('parentPageTitle', 'Crm')
@section('parent_title_icon', 'zmdi zmdi-home')
@section('page-style')
    <style>
        .flip {
            padding: 5px;
            background-color: #f5fffa;
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

     
    .edit-icon {
        float:right;
        
}

                       
    </style>
@stop
@section('content')
    <div class="row clearfix">
        <div class="card col-lg-12">
            <div class="header">
                <h2><strong>{{ $customer->name }}</strong> Details</h2>
            </div>
            <div class="body">
                <ul class="nav nav-tabs p-0 mb-3">
                    <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#home">General</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#contacts">Contacts</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#notes">Notes</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#calls">Calls</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tasks">Tasks</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#attachments">Other</a></li>
                </ul>
                <div class="tab-content" style="overflow: scroll; height: 60vh;">

                    <div role="tabpanel" class="tab-pane in active" id="home">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="name">Name</label>
                                <div class="form-group ">
                                    <p class="text-primary">{{ $customer->name ?? '' }}</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="sku">Category</label>
                                <div class="form-group">
                                    <p class="text-primary">{{ $customer->category ?? '' }}</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="sku">Company Name</label>
                                <div class="form-group">
                                    <p class="text-primary">{{ $customer->company_name ?? '' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <label for="coa_id">Mobile No.</label>
                                <div class="form-group">
                                    <p class="text-primary">{{ $customer->phone ?? '' }}</p>
                                </div>
                            </div>
                            {{-- <div class="col-md-3">
                                <label for="category">Fax No.</label>
                                <div class="form-group">
                                    <p class="text-primary">{{ $customer->fax ?? '' }}</p>
                                </div>
                            </div> --}}
                            <div class="col-md-3">
                                <label for="type">Email</label>
                                <div class="form-group">
                                    <p class="text-primary">{{ $customer->email ?? '' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <label for="primary_unit">Location</label>
                                <div class="form-group">
                                    <p class="text-primary">{{ $customer->location ?? '' }}</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="secondary_unit">Address</label>
                                <div class="form-group">
                                    <p class="text-primary">{{ $customer->address ?? '' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-md-4">
                                <label for="purchase_description" class="nav-link"> Description</label>
                                <div class="form-group">
                                    <p class="text-primary">{{ $customer->note ?? '' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <table class=" table-responsive align-center">
                                <tr>
                                    <td><button class="btn btn-primary"
                                            onclick="window.location.href = '{{ url('Crm/Customers/Create/' . $customer->id ?? '') }}';"><i
                                                class="zmdi zmdi-edit"></i> | Edit</button></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div role="tabpanel" class="tab-pane mt-2 ml-2" id="contacts">
                        <div class="row">
                            <div class="col-md-9"></div>
                            <div class="col-md-2">
                                <a href="{{ url('Crm/Contacts/Create/1/customer/'.$customer->id) }}"
                                    class="btn btn-primary btn-sm float-right w-100">Add New</a>
                            </div>
                            <div class="col-md-1"></div>
                        </div>
                       
                        <div class="row">
                            @foreach ($customer_contacts as $contact)
                                <div class="col-md-4">
                                    <div class="card shadow-lg rounded position-relative">
                                        <a href="{{ url('Crm/Contacts/Create/' . $contact->id) }}" class="edit-icon btn btn-primary btn-sm" style=""><i class="zmdi zmdi-edit"></i></a>
                                        <ul style="list-style-type: none" class="p-4">
                                            <li class="d-flex">
                                                <strong>Name:</strong>
                                                <p class="text-primary">{{ $contact->contact_name }}</p>
                                            </li>
                                            <li class="d-flex">
                                                <strong>Phone:</strong>
                                                <p class="text-primary">{{ $contact->mobile }}</p>
                                            </li>
                                            <li class="d-flex">
                                                <strong>Email:</strong>
                                                <p class="text-primary">{{ $contact->email }}</p>
                                            </li>
                                            <li class="d-flex">
                                                <strong>Title:</strong>
                                                <p class="text-primary">{{ $contact->title }}</p>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        
                        
                    </div>

                    <div role="tabpanel" class="tab-pane" id="notes">
                        <div class="row">
                            @php
                                $type = 1;
                            @endphp
                            <div class="col-md-9"></div>
                            <div class="col-md-2">
                                <a href="{{ url('Crm/Notes/Create/1/customer/' . $customer->id) }}" class="btn btn-primary btn-sm float-right w-100">Add New</a>
                            </div>
                            <div class="col-md-1"></div>
                        </div>
                        @foreach ($customer_notes as $note)
                            <div class="row mb-12">
                                <div class="col-md-12">
                                    <a href="{{ url('Crm/Notes/Create/' .$note->id) }}" class="edit-icon btn btn-primary ml-auto"><i class="zmdi zmdi-edit"></i></a>
                                    <h6 class="font-weight-bold">Subject</h6>
                                    <p>{{ $note->subject }}</p>
                                </div>
                                <div class="col-md-12">
                                    <h6 class="font-weight-bold">Description</h6>
                                    <p>{{ $note->description }}</p>
                                </div>
                            </div>
                            <hr>
                        @endforeach
                    </div>
                    
                    <div role="tabpanel" class="tab-pane" id="calls">
                        <div class="row">
                            <div class="col-md-9"></div>
                            <div class="col-md-2">
                                <a href="{{ url('Crm/Calls/Create/1/customer/' .$customer->id) }}"
                                    class="btn btn-primary btn-sm float-right w-100">Add New</a>
                            </div>
                            <div class="col-md-1"></div>
                        </div>
                        @foreach ($customer_calls as $call)
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <a href="{{ url('Crm/Calls/Create/' .$call->id) }}" class="edit-icon btn btn-primary ml-auto"><i class="zmdi zmdi-edit"></i></a>

                                    <h6 class="font-weight-bold">Subject</h6>
                                    <p class="mb-3">{{ $call->subject }}</p>
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Start Date</label>
                                                <p class="text-primary">{{ $call->start_date ?? '' }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group ">
                                                <label for="">End Date</label>
                                                <p class="text-primary">{{ $call->end_date ?? '' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <h6 class="font-weight-bold">Description</h6>
                                    <p class="mb-3">{{ $call->description }}</p>
                                </div>
                            </div>
                            <hr>
                        @endforeach
                    </div>

                    <div role="tabpanel" class="tab-pane" id="tasks">
                        <div class="row">
                            <div class="col-md-9"></div>
                            <div class="col-md-2">
                                <a href="{{ url('Crm/Tasks/1/customer/' .$customer->id) }}" class="btn btn-primary btn-sm float-right w-100">Add
                                    New</a>
                            </div>
                            <div class="col-md-1"></div>
                        </div>
                        @foreach ($customer_tasks as $task)
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <a href="{{ url('Crm/Tasks/' .$task->id) }}" class="edit-icon btn btn-primary ml-auto"><i class="zmdi zmdi-edit"></i></a>

                                    <h6 class="font-weight-bold">Subject</h6>
                                    <p class="mb-3">{{ $task->subject }}</p>
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <div class="form-group ">
                                                <label for="">Start Date</label>
                                                <p class="text-primary">{{ $task->start_date ?? '' }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group ">
                                                <label for="">Start Date</label>
                                                <p class="text-primary">{{ $task->due_date ?? '' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <h6 class="font-weight-bold">Description</h6>
                                    <p class="mb-3">{{ $task->description }}</p>
                                </div>
                            </div>
                            <hr>
                        @endforeach
                    </div>

                    <div role="tabpanel" class="tab-pane" id="attachments">
                        <div class="card">
                            <div class="card-body">

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
                $('#panel2').hide();
                $('#panel3').hide();
            });
            $("#flip2").click(function() {
                $("#panel2").slideToggle("slow");
                $('#panel1').hide();
                $('#panel3').hide();
            });
            $("#flip3").click(function() {
                $("#panel3").slideToggle("slow");
                $('#panel1').hide();
                $('#panel2').hide();
            });
        });
    </script>
@stop
