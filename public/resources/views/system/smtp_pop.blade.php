@extends('layout.master')
@section('title', 'Setting')
@section('parentPageTitle', 'SMTP')
@section('page-style')
    <link rel="stylesheet" href="{{ asset('public/assets/plugins/footable-bootstrap/css/footable.bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/assets/plugins/footable-bootstrap/css/footable.standalone.min.css') }}" />
    <script lang="javascript/text">
        var token = "{{ csrf_token() }}";
    </script>
    <style>
        #cust_category_filter,
        label {
            float: inline-end;
        }

        .form-label {
            float: unset
        }
    </style>
@stop
@section('content')
    <div class="row clearfix">
        <div class="col-lg-12">
            <div class="card col-lg-12">
                <div class="table-responsive contact">
                    <div class="body">
                        @if (session()->has('error'))
                            <div class="alert alert-danger">
                                {{ session()->get('error') }}
                            </div>
                        @endif
                        @if (session()->has('message'))
                            <div class="alert alert-success">
                                {{ session()->get('message') }}
                            </div>
                        @endif
                        <p><a class="btn btn-primary" style="float: inline-end"
                                href="{{ url('Admin/Modules/List') }}">back</a>
                        <form>
                            <input type="hidden" name="id" id="credentialId" value="{{ $credential->id ?? '' }}">
                            @csrf
                            <div class="row">
                                <div class="mb-3 col-md-3">
                                    <label class="form-label">Host</label>
                                    <input type="text" class="form-control value" name="imap_host" placeholder="Host"
                                        id="imap_host" value="{{ $credential->imap_host ?? '' }}">
                                </div>
                                <div class="mb-3  col-md-2">
                                    <label class="form-label">Port</label>
                                    <input type="text" class="form-control value" name="imap_port" placeholder="Port"
                                        id="imap_port" value="{{ $credential->imap_port ?? '' }}">
                                </div>
                                <div class="mb-3  col-md-2">
                                    <label class="form-label">Protocol</label>
                                    <select name="imap_protocol" class="form-control show-tick ms select2"
                                        data-placeholder="Select" id="imap_protocol" required>
                                        <option selected>Select Protocol</option>
                                        <option {{ $credential && $credential->imap_protocol === 'imap' ? 'selected' : '' }}
                                            value="imap">Imap</option>
                                        <option {{ $credential && $credential->imap_protocol === 'pop' ? 'selected' : '' }}
                                            value="pop">Pop</option>

                                    </select>
                                </div>
                                <div class=" col-md-2">
                                    <label class="form-label">Encryption</label>

                                    <select name="imap_encryption" class="form-control show-tick ms select2"
                                        data-placeholder="Select" id="imap_encryption" required>
                                        <option selected>Select Encryption</option>
                                        <option
                                            {{ $credential && $credential->imap_encryption == 'ssl' ? 'selected' : '' }}
                                            value="ssl">ssl</option>
                                        <option
                                            {{ $credential && $credential->imap_encryption == 'tls' ? 'selected' : '' }}
                                            value="tls">tls</option>
                                        <option
                                            {{ $credential && $credential->imap_encryption == 'auto' ? 'selected' : '' }}value="auto">
                                            auto</option>
                                    </select>
                                </div>

                                <div class="mb-3  col-md-3">
                                    <label class="form-label">Username</label>
                                    <input type="text" class="form-control value" name="imap_username"
                                        placeholder="Username" id="imap_username"
                                        value="{{ $credential->imap_username ?? '' }}">
                                </div>
                                <div class="mb-3  col-md-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" class="form-control value" name="imap_password"
                                        placeholder="Password" id="imap_password"
                                        value="{{ $credential->imap_password ?? '' }}">
                                </div>
                                <div class="mb-3  col-md-3">
                                    <label class="form-label">Department</label>
                                    <select class="form-control show-tick ms select2" data-placeholder="Select"
                                        id="department" name="department" required>
                                        <option selected>Select Department</option>
                                        @foreach ($departments as $department)
                                            <option id="department" value="{{ $department->name }}">
                                                {{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Add this line for debugging -->
                                {{-- {{ dd($credential->message ?? '') }} --}}

                                <div class="dom col-md-3">
                                    <label class="form-label">Select to delete Mails or Read Mails</label> <br>
                                    <select name="message" class="form-control show-tick ms select2"
                                        data-placeholder="Select" id="message" required>
                                        <option selected>Select..</option>
                                        <option
                                            {{ $credential && $credential->message == 'delete' ? 'selected' : '' }}
                                            value="delete">Delete</option>
                                        <option
                                            {{ $credential && $credential->message == 'read' ? 'selected' : '' }}
                                            value="read">Read</option>
                                    </select>
                                </div>




                            </div>

                            <button class="btn btn-primary" id="submit">Submit </button>
                        </form>
                        <table class="table table-bordered table-striped table-hover" id="cust_category">
                            <thead>
                                <tr>
                                    <th>Host</th>
                                    <th>Port</th>
                                    <th>Protocol</th>
                                    <th>Encryption</th>
                                    <th>Username</th>
                                    <th>Password</th>
                                    <th>Status</th>
                                    <th>Department</th>
                                    <th>Message </th>
                                    <th>Activate</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($credentials as $credential)
                                    <tr id="rsm{{ $credential->id ?? ''}}">

                                        <td class="column_size">{{ $credential->imap_host ?? '' }}</td>
                                        <td class="column_size">{{ $credential->imap_port ?? '' }}</td>
                                        <td class="column_size">{{ $credential->imap_protocol ?? '' }}</td>
                                        <td class="column_size">{{ $credential->imap_encryption ?? '' }}</td>
                                        <td class="column_size">{{ $credential->imap_username ?? '' }}</td>
                                        <td class="column_size">{{ $credential->imap_password ?? '' }}</td>
                                        <td class="column_size">{{ $credential->status ?? '' }}</td>
                                        <td class="column_size">{{ $credential->department ?? '' }}</td>
                                        <td class="column_size">{{ $credential->message ?? '' }}</td>

                                        <td>
                                            {{-- javascript:void(0); --}}
                                            <a href="{{ url('Admin/Smtp/Setting/Active', $credential->id) }}"
                                                class="btn btn-primary"
                                                style="margin: 0; padding: 2px;width: 38px;">Active</a>
                                            <a href="{{ url('Admin/Smtp/Setting/DeActive', $credential->id) }}"
                                                class="btn btn-danger" id="deactive" data-id="{{ $credential->id }}"
                                                style="margin: 0; padding: 2px;width: 38px;">Deactive</a>
                                            <a href="{{ url('Admin/Smtp/Test', $credential->id) }}"
                                                class="btn btn-success" id="deactive" data-id="{{ $credential->id }}"
                                                style="margin: 0; padding: 2px;width: 42px; height:33px">Test</a>
                                        </td>



                                        <td class="action">
                                            <a class="btn btn-success btn-sm"
                                                href="{{ url('Admin/Smtp/Setting', $credential->id) }}">
                                                <i class="zmdi zmdi-edit"></i>
                                            </a>
                                            <a class="btn btn-danger btn-sm del" data-toggle="modal"
                                                data-target="#modalCenter{{ $credential->id ?? '' }}">
                                                <i class="zmdi zmdi-delete text-white"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <div class="modal fade" id="modalCenter{{ $credential->id ?? '' }}" tabindex="-1"
                                        data-credential-id="{{ $credential->id ?? '' }}" role="dialog"
                                        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLongTitle">Are You Sure to
                                                        Delete</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-footer">
                                                    <a class="btn btn-secondary" data-dismiss="modal">No</a>
                                                    <a class="btn btn-primary model-delete"
                                                        href="{{ url('Admin/Smtp/Setting/Delete', $credential->id) }}">Yes</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach


                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal for delete confirmation -->
    @stop
    @section('page-script')
        @include('datatable-list');
        <script>
            $('#cust_category').DataTable({
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'pageLength',
                        className: 'btn cl mr-2 px-3 rounded'
                    },
                    {
                        extend: 'copy',
                        className: 'btn bg-dark mr-2 px-3 rounded',
                        title: 'Customer Categories',
                        exportOptions: {
                            columns: [1] // Exclude columns with the class 'actions'
                        }
                    },
                    {
                        extend: 'csv',
                        className: 'btn btn-info mr-2 px-3 rounded',
                        title: 'Customer Categories',
                        exportOptions: {
                            columns: [1] // Exclude columns with the class 'actions'
                        }
                    },
                    {
                        extend: 'pdf',
                        className: 'btn btn-danger mr-2 px-3 rounded',
                        title: 'Customer Categories',
                        exportOptions: {
                            columns: [1] // Exclude columns with the class 'actions'
                        }
                    },
                    {
                        extend: 'excel',
                        className: 'btn btn-warning mr-2 px-3 rounded',
                        title: 'Customer Categories',
                        exportOptions: {
                            columns: [1] // Exclude columns with the class 'actions'
                        }
                    },
                    {
                        extend: 'print',
                        className: 'btn btn-success mr-2 px-3 rounded',
                        title: 'Customer Categories',
                        exportOptions: {
                            columns: [1] // Exclude columns with the class 'actions'
                        }
                    },
                    {
                        extend: 'colvis',
                        className: 'visible btn rounded'
                    }
                ],
                "bDestroy": true,
                "lengthMenu": [
                    [100, 200, 500, -1],
                    [100, 200, 500, "All"]
                ],
                // DataTable configuration...
            });
        </script>
        <script>
            $(document).ready(function() {
                // Use event delegation for dynamic elements
                $('form').submit(function(e) {
                    e.preventDefault();

                    var credentialId = $('#credentialId').val();
                    var port = $('#imap_port').val();
                    var host = $('#imap_host').val();
                    var username = $('#imap_username').val();
                    var password = $('#imap_password').val();
                    var encryption = $('#imap_encryption').val();
                    var protocol = $('#imap_protocol').val();
                    var message = $('#message').val();
                    var department = $('#department').val();



                    $.ajax({
                        url: '{{ route('smtp.setting') }}',
                        type: 'post',
                        data: {
                            _token: '{{ csrf_token() }}',
                            credential_id: credentialId,
                            imap_port: port,
                            imap_host: host,
                            imap_username: username,
                            imap_password: password,
                            imap_encryption: encryption,
                            imap_protocol: protocol,
                            department: department,
                            message: message,



                        },
                        success: function(response) {
                            var action = credentialId ? 'Edit' : 'Add';

                            if (action === 'Add') {
                                // Add a new row for the added data
                                var newRow = '<tr id="rsm' + response.id + '"><td>' + response
                                    .host + '</td><td>' +
                                    response.port + '</td><td>' + response.protocol + '</td><td>' +
                                    response.encryption +
                                    '</td><td>' + response.username + '</td><td>' + response
                                    .password + '</td><td>' +
                                    response.status + '</td><td>' +
                                    response.department + '</td><td>' +
                                    response.message + '</td><td>' +
                                    '<a href="{{ url('Admin/Smtp/Setting/Active') }}/' + response
                                    .id +
                                    '" class="btn btn-primary" style="margin: 0; padding: 2px;width: 38px;">Active</a>' +
                                    '<a href="{{ url('Admin/Smtp/Setting/DeActive') }}/' + response
                                    .id +
                                    '" class="btn btn-danger" id="deactive" data-id="' + response
                                    .id +
                                    '" style="margin: 0; padding: 2px;width: 38px;">Deactive</a>' +
                                    '<a href="{{ url('Admin/Smtp/Test') }}/' + response.id +
                                    '" class="btn btn-success"' +
                                    ' id="deactive" data-id="' + response.id +
                                    '" style="margin: 0; padding: 2px;width: 42px; height:33px">Test</a>' +
                                    '</td><td class="action"><a class="btn btn-success btn-sm" href="{{ url('Admin/Smtp/Setting') }}/' +
                                    response.id + '"><i class="zmdi zmdi-edit"></i></a>' +
                                    '<a class="btn btn-danger btn-sm del" data-credential-id="' +
                                    response.id +
                                    '"><i class="zmdi zmdi-delete text-white"></i></a></td></tr>';

                                // Append the new row to the table
                                $('#cust_category tbody').append(newRow);
                                $(".value").val("");
                            } else if (action === 'Edit') {
                                // Update the existing row with the edited data
                                var editedRow = $('#rsm' + credentialId);
                                editedRow.find('td:eq(0)').text(response.host);
                                editedRow.find('td:eq(1)').text(response.port);
                                editedRow.find('td:eq(2)').text(response.protocol);
                                editedRow.find('td:eq(3)').text(response.encryption);
                                editedRow.find('td:eq(4)').text(response.username);
                                editedRow.find('td:eq(5)').text(response.password);
                                editedRow.find('td:eq(6)').text(response.status);
                                editedRow.find('td:eq(7)').text(response.department);
                                editedRow.find('td:eq(8)').text(response.message);


                                $(".value").val("");


                            }

                        },
                        error: function(error) {
                            // Handle errors here
                            console.log(error);
                        }
                    });

                    return false;
                });
            });
        </script>
        {{-- <script>
            $(document).ready(function() {
                $('#active').click(function(e) {
                    e.preventDefault();

                    var credentialId = $(this).data('id');
                    alert(credentialId);
                    $.ajax({
                        url: '{{ route('smtp.active') }}',
                        type: 'post',
                        data: 'activete',
                        success: function(response) {
                            var editedRow = $('#rsm' + credentialId);
                            editedRow.find('td:eq(6)').text(response.status);
                        }

                    })
                });
            });
        </script> --}}

    @stop
