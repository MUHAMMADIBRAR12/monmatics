@extends('layout.master')
@section('title', 'Project View')
@section('parentPageTitle', 'Project')
@section('parent_title_icon', 'zmdi zmdi-home')
@section('page-style')
    <?php use App\Libraries\appLib; ?>

    <style>
        .input-group-text {
            padding: 0 .75rem;
        }

        .table td {
            padding: 0.10rem;
        }

        .dropify {
            width: 200px;
            height: 200px;
        }

        .users_list li {
            display: inline-block;
            width: 130px;
        }

        .searchopen {
            float: right;
            cursor: pointer;
        }

        .searchopen i {
            font-size: 20px
        }
    </style>

    <script lang="javascript/text">
        var contactURL = "{{ url('contactsSearch') }}";
        var url = "{{ url('') }}";
        var userURL = "{{ url('userSearch') }}";
        var token = "{{ csrf_token() }}";
    </script>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link rel="stylesheet" href="{{ asset('public/assets/plugins/dropify/css/dropify.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/assets/css/list.css') }}">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@stop
@section('content')

    <div class="row clearfix">
        <div class="card col-lg-12">
            @if (session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
            @endif
            @if (session()->has('delete_message'))
                <div class="alert alert-danger">
                    {{ session()->get('delete_message') }}
                </div>
            @endif
            <div class="header">
                <h2><strong>{{ $project->name ?? '' }}</strong> Details</h2>
            </div>
            <div class="body">
                <input type="hidden" name="id" value="{{ $product->id ?? '' }}">
                <ul class="nav nav-tabs p-0 mb-3">
                    <li class="nav-item"><a class="tabSection home nav-link active" data-toggle="tab" href="#home">General
                            Detail</a></li>
                    <li class="nav-item"><a class="tabSection team nav-link" data-toggle="tab" href="#team">Team</a></li>
                    <li class="nav-item"><a class="tabSection tasks nav-link" data-toggle="tab" href="#tasks">Tasks</a>
                    </li>
                    <li class="nav-item"><a class="tabSection tickets nav-link" data-toggle="tab"
                            href="#tickets">Tickets</a></li>
                    <li class="nav-item"><a class="tabSection attachments nav-link" data-toggle="tab"
                            href="#attachments">Attachments</a></li>
                </ul>

                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane in active" id="home">
                        <div class="row">
                            <div class="col-md-2">
                                <label for="name">Parent</label>
                                <div class="form-group ">
                                    <p class="text-primary">{{ $project->parent_id ?? '--' }}</p>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label for="name">Company Name</label>
                                <div class="form-group ">
                                    <p class="text-primary">{{ $project->company_name ?? '' }}</p>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label for="sku">start Date</label>
                                <div class="form-group">
                                    <p class="text-primary">{{ $project->start_date ?? '' }}</p>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label for="sku">End Date</label>
                                <div class="form-group">
                                    <p class="text-primary">{{ $project->end_date ?? '' }}</p>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label for="sku">Category</label>
                                <div class="form-group">
                                    <p class="text-primary">{{ $project->category ?? '' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-2">
                                <label for="name">Description</label>
                                <div class="form-group ">
                                    <p class="text-primary">{{ $project->description ?? '' }}</p>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label for="name">Project Manager</label>
                                @php
                                    $ProjectManager = \App\Models\User::find($project->project_manager);
                                @endphp
                                <div class="form-group ">
                                    <p class="text-primary">{{ $ProjectManager->name ?? 'Unassigned' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <table class=" table-responsive align-center">
                                <tr>
                                    <td><button class="btn btn-primary"
                                            onclick="window.location.href = '{{ url('Project/ProjectManagment/Create/' . $project->id ?? '') }}';"><i
                                                class="zmdi zmdi-edit"></i> Edit</button></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="team">
                        <div class="row">
                            <div class="col-md-12">
                                <div id="teams">
                                    <ul class="list-group">
                                        @foreach ($team as $member)
                                            <li class="list-group-item">{{ $member->user_name }} </li>
                                        @endforeach
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <table class=" table-responsive align-center">
                                <tr>

                                </tr>
                            </table>

                            <div class="row">
                                <div class="modal-body">
                                    <ul class="users_list">

                                        <form action="{{ url('Project/TeamSave') }}" method="POST">
                                            @csrf

                                            @foreach ($users as $user)
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="checkbox" name="user_id[]"
                                                        id="inlineCheckbox{{ $user->id }}" value="{{ $user->id }}"
                                                        {{ in_array($user->id, $checkedUserIds) ? 'checked' : '' }}>
                                                    <label class="form-check-label"
                                                        for="inlineCheckbox{{ $user->id }}">{{ $user->user_name }}</label>
                                                </div>
                                            @endforeach


                                            <input type="hidden" name="prj_id" id="prj_id"
                                                value="{{ $project->id ?? '' }}"> <br> <br>
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </form>


                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="tasks">
                        <div class="row">
                            <div class="col-md-12">
                                <a href="{{ url('Crm/Tasks', $project->id) }}" class="btn btn-primary">New Task</a>
                                <div>
                                    <table class="table table-striped m-b-0">
                                        <thead>
                                            <tr>
                                                <th>Edit</th>
                                                <th>Subject</th>
                                                <th>Start Date</th>
                                                <th>Priority</th>
                                                <th>Status</th>
                                                <th>Assigned To</th>
                                            </tr>
                                        </thead>
                                        <tbody id="teamTable">
                                            @foreach ($tasks as $task)
                                                <tr>
                                                    <td><button class="btn btn-primary btn-sm"
                                                            onclick="window.location.href = '{{ url('Crm/Tasks/' . $task->id) }}';"><i
                                                                class="zmdi zmdi-edit"></i></button> </td>
                                                    <td>{{ $task->subject }}</td>
                                                    <td>{{ $task->start_date }}</td>
                                                    <td>{{ $task->priority }}</td>
                                                    <td>{{ $task->status }}</td>
                                                    <td>{{ $task->user_name }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div role="tabpanel" class="tab-pane" id="attachments">

                        <a href="#" class="btn btn-primary" id="showAttachmentForm">Add Attachments</a>



                        <div class="form">
                            <form id="attachmentForm" style="display: none;"
                                action="{{ url('Project/AttachmentSave') }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="text" class="form-control" name="title" placeholder="Attachment Name">
                                <br>
                                <div class="col-lg-2 col-md-6">

                                    <label for="fiscal_year">Attachment</label>
                                    <input type="hidden" value="{{ $id ?? '' }}" name="id">

                                    <br>
                                    <input name="file" type="file" class="dropify">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>

                            </form>
                        </div>
                        <div class="row">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($attachmentRecord as $attachmentRecord)
                                        @if ($attachment)
                                            <tr>
                                                @php
                                                    $i = 1;
                                                @endphp
                                                <th scope="row">{{ $loop->iteration }}</th>
                                                <td>{{ $attachmentRecord->title ?? '' }}</td>
                                                <td>
                                                    <a href="{{ url('download/' . $attachmentRecord->id) }}" download><i
                                                            class="zmdi zmdi-download text-success"
                                                            style="font-size: 22px"></i></a>

                                                    <a href="{{ url('Project/AttachmentDelete', $attachmentRecord->id) }}"
                                                        onclick="return confirm('Are you sure to delete this??')"><i
                                                            class="zmdi zmdi-delete text-danger"
                                                            style="font-size: 22px"></i></a>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>


                        </div>
                    </div>

                    <div role="tabpanel" class="tab-pane" id="tickets">
                        <div class="row">
                            <div class="table-responsive">
                                <span class="searchopen"><i class="zmdi zmdi-menu"
                                        onclick="openSearch()"></i></span>
                                <form action="" id="searchforme" style="display: none;">
                                    <div class="row clearfix">
                                        <div class="col-md-1 col-sm-12">
                                            <div class="form-group">
                                                <label>Ticket ID</label>
                                                <input type="text" name="ticket_id" id="account"
                                                    value="{{ request('ticket_id') }}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-12 pl-1">
                                            <div class="form-group">
                                                <label>Email</label>
                                                <input type="text" name="email" id="email" class="form-control"
                                                    value="{{ request('email') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-sm-12 pl-1">
                                            <div class="form-group">
                                                <label>From</label>
                                                <div class="input-group">
                                                    <input type="date" id="from_date" name="from_date"
                                                        class="form-control" value="{{ request('from_date') }}">
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-2 col-sm-12 pl-1">
                                            <div class="form-group">
                                                <label>To</label>
                                                <div class="input-group">
                                                    <input type="date" id="to_date" name="to_date"
                                                        class="form-control" value="{{ request('to_date') }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-sm-12 pl-1">
                                            <div class="form-group">
                                                <label>Category</label>
                                                <select class="form-control show-tick ms select2"
                                                    data-placeholder="Select" id="category" name="category" required>
                                                    <option selected disabled>Select Category</option>
                                                    @foreach ($categories as $category)
                                                        <option id="category"
                                                            value=" {{ $category->description ?? '' }}"
                                                            {{ request('category') == $category->description ? 'selected' : '' }}>
                                                            {{ $category->description ?? '' }} </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-sm-12 pl-1">
                                            <div class="form-group">
                                                <label>Priority</label>
                                                <select class="form-control show-tick ms select2"
                                                    data-placeholder="Select" id="priority" name="priority" required>
                                                    <option selected disabled>Select
                                                        Priority</option>
                                                    @foreach ($priorities as $priority)
                                                        <option id="priority" value="{{ $priority->description ?? '' }}"
                                                            {{ request('priority') == $priority->description ? 'selected' : '' }}>
                                                            {{ $priority->description ?? '' }} </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-sm-12 pl-1">
                                            <div class="form-group">
                                                <label>Status</label>
                                                <select class="form-control show-tick ms select2"
                                                    data-placeholder="Select" id="status" name="status" required>
                                                    <option selected disabled>Select Status</option>
                                                    @foreach ($statuses as $status)
                                                        <option id="status" value="{{ $status->description ?? '' }}"
                                                            {{ request('status') == $status->description ? 'selected' : '' }}>
                                                            {{ $status->description ?? '' }} </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-sm-12 pl-1">
                                            <div class="form-group">
                                                <label>Departments</label>
                                                <select class="form-control show-tick ms select2"
                                                    data-placeholder="Select" id="department" name="department" required>
                                                    <option selected disabled>Select Department</option>
                                                    @foreach ($departments as $department)
                                                        <option id="department" value="  {{ $department->name ?? '' }}"
                                                            {{ request('department') == $department->name ? 'selected' : '' }}>
                                                            {{ $department->name ?? '' }} </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="">Assign To</label>
                                            <input type="text" name="assign" id="assign" class="form-control"
                                                value="{{ request('assign') }}" placeholder="Assign To"
                                                onkeyup="autoFill(this.id, userURL, token)">

                                            <input type="hidden" name="assign_ID" id="assign_ID"
                                                value="{{ request('assign_ID') }}">
                                        </div>

                                        <div class="col-md-2"> <br> <br>
                                            <input type="checkbox" name="is_billable" id="is_billable"> Billable
                                        </div>

                                        <div class="col-md-2">
                                            <button class="btn btn-primary "style="height: 30px;margin-top: 30px;" id="formSubmitButton">Search</button>
                                            <button class="btn btn-primary " style=" height: 30px; margin-top: 30px;"
                                                onclick="clearForm()">Clear</button>
                                        </div>
                                    </div>

                                </form>

                                <script>
                                    function clearForm() {
                                        document.getElementById("searchforme").reset();
                                        document.getElementById("account").value = "";
                                        document.getElementById("email").value = "";
                                        document.getElementById("from_date").value = "";
                                        document.getElementById("to_date").value = "";
                                        document.getElementById("assign").value = "";
                                        document.getElementById("assign_ID").value = "";
                                        var selectElement = document.getElementById("category");
                                        selectElement.selectedIndex = 0;
                                        var selectElement = document.getElementById("department");
                                        selectElement.selectedIndex = 0;
                                        var selectElement = document.getElementById("status");
                                        selectElement.selectedIndex = 0;
                                        var selectElement = document.getElementById("priority");
                                        selectElement.selectedIndex = 0;
                                        var selectElement = document.getElementById("related_to");
                                        selectElement.selectedIndex = 0;
                                        document.getElementById("searchForm").value = " ";

                                    }
                                </script>
                                <form action="{{ url('Tmg/Ticket/BulkUpdate') }}" method="post" id="searchForm">
                                    @csrf
                                    <table id="transactionList"
                                        class="table table-bordered table-striped table-hover js-exportable dataTable "
                                        style="width:100%">
                                        <a href="{{ url('Tmg/Ticket', $project->id) }}"
                                            class="btn btn-primary size-sm">New
                                            Ticket</a>
                                        <thead>
                                            <th><input type="checkbox" id="selectAll"></th>
                                            <th>Edit</th>
                                            <th>View</th>
                                            <th>Delete</th>
                                            <th>Ticket No.</th>
                                            <th>Date</th>
                                            <th style="text-align:center;">Subject</th>
                                            <th style="text-align:center;">Priority</th>
                                            <th style="text-align:center;">Assign To</th>
                                            <th style="text-align:center;">Email</th>
                                            <th style="text-align:center;">Status</th>
                                            <th style="text-align:center;">Category</th>
                                            <th style="text-align:center;">Department</th>

                                        </thead>
                                        <tbody>

                                            @foreach ($tickets as $ticket)
                                                <tr>
                                                    @php
                                                        $user = \App\Models\User::find($ticket->assign_to);
                                                    @endphp
                                                    <td><input type="checkbox" name="ids[]"
                                                            value="{{ $ticket->id }}" class="singleCheck"></td>
                                                    <td><button class="btn btn-success btn-sm p-0 m-0"><a
                                                                onclick="UpdateUrl('{{ url('Tmg/Ticket/' . $ticket->id) }}')"
                                                                href="{{ url('Tmg/Ticket/' . $ticket->id) }}"><i
                                                                    class="zmdi zmdi-edit px-2 py-1"></i></a></button>
                                                    </td>
                                                    <td><button class="btn btn-danger btn-sm p-0 m-0"><a
                                                                onclick="UpdateUrl('{{ url('Tmg/View/' . $ticket->id) }}')"
                                                                href="{{ url('Tmg/View/' . $ticket->id) }}"
                                                                class="btn btn-danger print btn-sm p-0 m-0"><i
                                                                    class="zmdi zmdi-receipt px-2 py-1"></i></a></button>
                                                    </td>
                                                    <td><button class="btn btn-danger btn-sm p-0 m-0"><a
                                                                onclick="UpdateUrl('{{ url('Tmg/Ticket/Delete/' . $ticket->id) }}')"
                                                                href="{{ url('Tmg/Ticket/Delete/' . $ticket->id) }}"
                                                                class="btn btn-danger print btn-sm p-0 m-0"><i
                                                                    class="zmdi zmdi-delete px-2 py-1"></i></a></button>
                                                    </td>
                                                    <td>{{ $ticket->number ?? '' }} </td>
                                                    <td>{{ $ticket->created_at ?? '' }} </td>
                                                    <td>{{ $ticket->subject ?? '' }} </td>
                                                    <td>{{ $ticket->priority ?? '' }} </td>
                                                    <td>{{ $user->firstName ?? '' }} {{ $user->lastName ?? '' }}</td>
                                                    <td>{{ $ticket->email ?? '' }} </td>
                                                    <td>{{ $ticket->status ?? '' }} </td>
                                                    <td>{{ $ticket->category ?? '' }} </td>
                                                    <td>{{ $ticket->department ?? '' }} </td>

                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="mainn mr-3" style="display: flex">
                                        <div style="width: 15%">
                                            <select class="form-control show-tick ms select2" data-placeholder="Select"
                                                id="status" name="status" required>
                                                <option selected disabled>Select Status</option>
                                                @foreach ($statuses as $status)
                                                    <option id="status" value="{{ $status->description }}"
                                                        {{ request('status') == $status->description ? 'selected' : '' }}>
                                                        {{ $status->description }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <button class="btn btn-danger" onclick="searchUpdate()"
                                                id="searchUpdate">Update</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function() {
                $("#showAttachmentForm").click(function() {
                    $("#attachmentForm").toggle();

                });

            });
        </script>
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script src="{{ asset('public/assets/js/sw.js') }}"></script>
        <script>
            function openSearch() {
                $('#searchforme').slideToggle();
            }
        </script>
        <script>
            function searchUpdate() {
                $('#searchForm').submit();
            }

            function UpdateUrl(url) {


                event.preventDefault();
                window.location.href = url;
            }

            const selectAll = document.getElementById('selectAll');

            selectAll.addEventListener("click", function() {
                const singleCheckboxes = document.querySelectorAll('.singleCheck');

                singleCheckboxes.forEach(checkbox => checkbox.checked = selectAll.checked);
            });
        </script>
        {{-- <script>
           const section = document.querySelector(".tabSection.active");

           alert(section);
           section.addEventListener('click',function(){

            alert('main');
           });


        </script> --}}
        {{-- <script>
            console.log(localStorage.getItem('activeSectionClass'))
            document.addEventListener("click", function() {
                // Get the currently active section
                var activeSection = document.querySelector('.tabSection.active');

                // Check if an active section is found
                if (activeSection) {
                    var activeClass = activeSection.classList[1];
                    // localStorage.setItem('activeSectionClass',activeClass);

                } else {
                    console.log("No active section found.");
                }
            });
        </script> --}}

        {{-- <script>
            document.addEventListener("DOMContentLoaded", function() {

                var storedActiveClass = localStorage.getItem('activeSectionClass');

                var storedActiveSection = document.querySelector('.tabSection.' + storedActiveClass);

                var storedURL = localStorage.getItem('currentPageURL');
                if (storedURL && storedURL === window.location.href) {
                    storedActiveSection.classList.add('active');
                    const panel = document.getElementById(storedActiveSection.classList[1]).classList.add('active');
                }else{

                    const home = document.querySelector('.home');
                    const homeId = document.querySelector('#home');

                    home.classList.add('active');
                    homeId.classList.add('active');

                }


                localStorage.setItem('currentPageURL', window.location.href);

                document.addEventListener("click", function() {
                    var activeSection = document.querySelector('.tabSection.active');

                    if (activeSection) {
                        var activeClass = activeSection.classList[1];
                        localStorage.setItem('activeSectionClass', activeClass);
                    } else {
                        console.log("No active section found.");
                    }
                });
            });
        </script> --}}

        <script>
            $(document).ready(function () {
                $('#formSubmitButton').on('click', function (e) {
                    e.preventDefault();
                    // alert('demo');
                    var ticketId = $('#account').val();
                    var email = $('#email').val();
                    var from_date = $('#from_date').val();
                    var to_date = $('#to_date').val();
                    var category = $('#category').val();
                    var priority = $('#priority').val();
                    var status = $('#status').val();
                    var assign_ID = $('#assign_ID').val();
                    var department = $('#department').val();
                    var related_ID = $('.related_ID').val();
                    var is_billable = $('#is_billable').prop('checked') ? 'on' : '';
                    var prj_id = $('#prj_id').val();


                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: 'post',
                        url: '{{ route('search.ticket.project') }}',
                        data: {
                            ticket_id: ticketId,
                            email: email,
                            from_date: from_date,
                            to_date: to_date,
                            priority: priority,
                            status: status,
                            assign_ID: assign_ID,
                            department: department,
                            related_ID: related_ID,
                            is_billable: is_billable,
                            prj_id: prj_id,

                        },
                        success: function (data) {
                            var body = $('#transactionList tbody');
                            body.empty();
                            data.forEach(function (singleData) {
                                var row = '<tr>' +
                                    '<td><input type="checkbox" name="ids[]" value="' + singleData.id + '" class="bulkselect"></td>' +
                                    '<td><button class="btn btn-success btn-sm p-0 m-0"><a href="{{ url('Tmg/Ticket/') }}/' + singleData.id + '" onclick="UpdateUrl(\'{{ url('Tmg/Ticket/') }}/' + singleData.id + '\')"><i class="zmdi zmdi-edit px-2 py-1"></i></a></button></td>' +
                                    '<td><button class="btn btn-danger btn-sm p-0 m-0"><a href="{{ url('Tmg/View/') }}/' + singleData.id + '" onclick="UpdateUrl(\'{{ url('Tmg/View/') }}/' + singleData.id + '\')" class="btn btn-danger print btn-sm p-0 m-0"><i class="zmdi zmdi-receipt px-2 py-1"></i></a></button></td>' +
                                    '<td><button class="btn btn-danger btn-sm p-0 m-0"><a href="{{ url('Tmg/Ticket/Delete/') }}/' + singleData.id + '" onclick="UpdateUrl(\'{{ url('Tmg/Ticket/Delete/') }}/' + singleData.id + '\')" class="btn btn-danger print btn-sm p-0 m-0"><i class="zmdi zmdi-delete px-2 py-1"></i></a></button></td>' +
                                    '<td>' + (singleData.number ?? '') + '</td>' +
                                    '<td>' + (singleData.created_at ?? '') + '</td>' +
                                    '<td>' + (singleData.subject ?? '') + '</td>' +
                                    '<td>' + (singleData.priority ?? '') + '</td>' +
                                    '<td>' + (singleData.assigned_user_first_name + singleData.assigned_user_last_name ?? '') + '</td>' +
                                    '<td>' + (singleData.email ?? '') + '</td>' +
                                    '<td>' + (singleData.status ?? '') + '</td>' +
                                    '<td>' + (singleData.category ?? '') + '</td>' +
                                    '<td>' + (singleData.department ?? '') + '</td>' +
                                    '</tr>';

                                body.append(row);
                            });
                        },
                    });
                });
            });
        </script>


    @stop


    @section('page-script')
        <script src="{{ asset('public/assets/plugins/dropify/js/dropify.min.js') }}"></script>
        <script src="{{ asset('public/assets/js/pages/forms/dropify.js') }}"></script>
    @stop
