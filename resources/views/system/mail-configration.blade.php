@extends('layout.master')
@section('title', 'Setting')
@section('parentPageTitle', 'Mail Configration')
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
                            <input type="hidden" name="id" id="credentialId" value="{{ $Mail->id ?? ''}}">
                            @csrf
                            <div class="row">
                                <div class="mb-3 col-md-3">
                                    <label class="form-label">Host</label>
                                    <input type="text" class="form-control value" value="{{ $Mail->mail_host ?? '' }}" name="mail_host" placeholder="Host"
                                        id="mail_host">
                                </div>
                                <div class="mb-3  col-md-2">
                                    <label class="form-label">Port</label>
                                    <input type="text" class="form-control value" value="{{ $Mail->mail_port ?? '' }}" name="mail_port" placeholder="Port"
                                        id="mail_port">
                                </div>
                                <div class="mb-3  col-md-2">
                                    <label class="form-label">Transport</label>
                                    <select name="mail_transport" class="form-control value show-tick ms select2"
                                        data-placeholder="Select" id="mail_transport" required>
                                        <option selected>Sel..</option>
                                        <option value="smtp" {{ $Mail && $Mail->mail_transport == 'smtp' ? 'selected' : '' }}>SMTP</option>
                                        <option value="mailgun" {{ $Mail && $Mail->mail_transport == 'mailgun' ? 'selected' : '' }}>MAILGUN</option>
                                    </select>
                                </div>
                                <div class=" col-md-2">
                                    <label class="form-label">Encryption</label>

                                    <select name="mail_encryption" class="form-control value show-tick ms select2"
                                        data-placeholder="Select" id="mail_encryption" required>
                                        <option selected>Sel..</option>
                                        <option value="ssl" {{ $Mail && $Mail->mail_encryption == 'ssl' ? 'selected' : '' }}>ssl</option>
                                        <option value="tls" {{ $Mail && $Mail->mail_encryption == 'tls' ? 'selected' : '' }}>tls</option>
                                        <option value="auto" {{ $Mail && $Mail->mail_encryption == 'auto' ? 'selected' : '' }}>auto</option>
                                    </select>
                                </div>

                                <div class="mb-3  col-md-3">
                                    <label class="form-label">Username</label>
                                    <input type="text" class="form-control value" value="{{ $Mail->mail_username ?? '' }}" name="mail_username"
                                        placeholder="Username" id="mail_username">
                                </div>
                                <div class="mb-3  col-md-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" class="form-control value" value="{{ $Mail->mail_password ?? '' }}" name="mail_password"
                                        placeholder="Password" id="mail_password">
                                </div>
                                <div class="mb-3  col-md-3">
                                    <label class="form-label">Department</label>
                                    <select class="form-control value show-tick ms select2" data-placeholder="Select"
                                        id="department" name="department" required>
                                        <option selected>Select Department</option>
                                        @foreach ($departments as $department)
                                        <option id="department" value=" {{ $department->name ?? ''}}" {{ $department->name == ($Mail->department ?? '') ? 'selected' : ''}}> {{ $department->name ?? ''}} </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3  col-md-3">
                                    <label class="form-label">From Name</label>
                                    <input type="text" class="form-control value" value="{{ $Mail->from_name ?? '' }}" name="from_name"
                                        placeholder="From Name" id="from_name">
                                </div>
                            </div>

                            <button class="btn btn-primary" id="submit">Submit </button>
                        </form>
                        <table class="table table-bordered table-striped table-hover" id="cust_category">
                            <thead>
                                <tr>
                                    <th>Host</th>
                                    <th>Port</th>
                                    <th>Transport</th>
                                    <th>Encryption</th>
                                    <th>Username</th>
                                    <th>Password</th>
                                    <th>From Name</th>
                                    <th>Status</th>
                                    <th>Department</th>
                                    <th>Activate</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($Mails as $Mail)
                                <tr id="rsm{{ $Mail->id ?? '' }}">

                                    <td class="column_size">{{ $Mail->mail_host ?? '' }}</td>
                                    <td class="column_size">{{ $Mail->mail_port ?? '' }}</td>
                                    <td class="column_size">{{ $Mail->mail_transport  ?? '' }}</td>
                                    <td class="column_size">{{ $Mail->mail_encryption ?? '' }}</td>
                                    <td class="column_size">{{ $Mail->mail_username ?? '' }}</td>
                                    <td class="column_size">{{ $Mail->mail_password ?? '' }}</td>
                                    <td class="column_size">{{ $Mail->from_name ?? '' }}</td>
                                    <td class="column_size">{{ $Mail->status ?? '' }}</td>
                                    <td class="column_size">{{ $Mail->department ?? '' }}</td>

                                    <td>
                                        {{-- javascript:void(0); --}}
                                        <a href="{{ url('Admin/Mail/Setting/Active', $Mail->id) }}"
                                                class="btn btn-primary"
                                                style="margin: 0; padding: 2px;width: 38px;">Active</a>
                                            <a href="{{ url('Admin/Mail/Setting/DeActive', $Mail->id) }}"
                                                class="btn btn-danger" id="deactive" data-id="{{ $Mail->id }}"
                                                style="margin: 0; padding: 2px;width: 38px;">Deactive</a>
                                            <a href="{{ url('Admin/Mail/Test', $Mail->id) }}"
                                                class="btn btn-success" id="deactive" data-id="{{ $Mail->id }}"
                                        style="margin: 0; padding: 2px;width: 42px; height:33px">Test</a>
                                    </td>



                                    <td class="action">
                                        <a class="btn btn-success btn-sm"
                                                href="{{ url('Admin/Mail/Setting', $Mail->id) }}">
                                                <i class="zmdi zmdi-edit"></i>
                                            </a>
                                        <a class="btn btn-danger btn-sm del" data-toggle="modal"
                                            data-target="#modalCenter{{ $Mail->id ?? '' }}">
                                            <i class="zmdi zmdi-delete text-white"></i>
                                        </a>
                                    </td>
                                </tr>
                                <div class="modal fade" id="modalCenter{{ $Mail->id ?? '' }}" tabindex="-1"
                                    data-Mail-id="{{ $Mail->id ?? '' }}" role="dialog"
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
                                                <a class="btn btn-primary model-delete" href=" {{ url('Admin/Mail/Setting/Delete',$Mail->id) }}">Yes</a>
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
                    var port = $('#mail_port').val();
                    var host = $('#mail_host').val();
                    var username = $('#mail_username').val();
                    var password = $('#mail_password').val();
                    var encryption = $('#mail_encryption').val();
                    var transport = $('#mail_transport').val();
                    var department = $('#department').val();
                    var from_name = $('#from_name').val();




                    $.ajax({
                        url: '{{ route('mail.setting') }}',
                        type: 'post',
                        data: {
                            _token: '{{ csrf_token() }}',
                            id: credentialId,
                            mail_port: port,
                            mail_host: host,
                            mail_username: username,
                            mail_password: password,
                            mail_encryption: encryption,
                            mail_transport: transport,
                            department: department,
                            from_name: from_name,


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
                                    response.from_name + '</td><td>' +
                                    response.status + '</td><td>' +
                                    response.department + '</td><td>' +
                                    '<a href="{{ url('Admin/Mail/Setting/Active') }}/' + response
                                    .id +
                                    '" class="btn btn-primary" style="margin: 0; padding: 2px;width: 38px;">Active</a>' +
                                    '<a href="{{ url('Admin/Mail/Setting/DeActive') }}/' + response
                                    .id +
                                    '" class="btn btn-danger" id="deactive" data-id="' + response
                                    .id +
                                    '" style="margin: 0; padding: 2px;width: 38px;">Deactive</a>' +
                                    '<a href="{{ url('Admin/Mail/Test') }}/' + response.id +
                                    '" class="btn btn-success"' +
                                    ' id="deactive" data-id="' + response.id +
                                    '" style="margin: 0; padding: 2px;width: 42px; height:33px">Test</a>' +
                                    '</td><td class="action"><a class="btn btn-success btn-sm" href="{{ url('Admin/Mail/Setting') }}/' +
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
                                editedRow.find('td:eq(6)').text(response.from_name);
                                editedRow.find('td:eq(7)').text(response.status);
                                editedRow.find('td:eq(8)').text(response.department);



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
