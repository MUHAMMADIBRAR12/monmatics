@extends('layout.master')
@section('title', 'Designation')
@section('parentPageTitle', 'HCM')
@section('page-style')
    <?php use App\Libraries\appLib; ?>
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.dataTables.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/v/dt/dt-1.10.23/b-1.6.5/b-colvis-1.6.5/datatables.min.css" />
    <link rel="stylesheet" href="{{ asset('public/assets/css/sw.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/css/list.css') }}">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
@stop
@section('content')
    <div class="row clearfix">
        <div class="col-lg-12">
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
            <div class="card">
                <div class="header">
                    Designation
                </div>
                <div class="body">
                    <form action="{{ url('HCM/Designation') }}" method="post">
                        <input type="hidden" name="id" value="{{ $desig->id ?? '' }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <label>Designation</label>
                                <input type="text" name="designation" class="form-control "
                                    value="{{ $desig->designation ?? '' }}" placeholder="Designation" required>
                            </div>
                            <div class="col-md-4">
                                <label>Code</label>
                                <input type="text" name="code" class="form-control " value="{{ $desig->code ?? '' }}"
                                    placeholder="Code" required>
                            </div>
                            <div class="col-md-4">
                                <label>Reporting To</label>
                                <select name="reporting_to" class=" form-control show-tick ms select2" required>
                                    <option value="-1" disabled selected>----Select----</option>
                                    @foreach ($Designations as $Designation)
                                        <option value="{{ $Designation->designation }}"
                                            {{ $Designation->designation == ($desig->reporting_to ?? '') ? 'selected' : '' }}>
                                            {{ $Designation->designation }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div> <br>
                        <button class="btn btn-primary">Submit</button>
                    </form>
                    <table class="table table-bordered table-striped table-hover" id="cust_category">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Designation</th>
                                <th>Code</th>
                                <th>Reporting To</th>
                                <th>Action</th>


                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($Designations as $Designation)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $Designation->designation ?? '' }}</td>
                                    <td>{{ $Designation->code ?? '' }}</td>
                                    <td>{{ $Designation->reporting_to ?? '' }}</td>
                                    <td> <a class="btn btn-success btn-sm"
                                            href="{{ url('HCM/Designation', $Designation->id) }}">
                                            <i class="zmdi zmdi-edit"></i>
                                        </a>
                                        <a class="btn btn-danger btn-sm del" data-toggle="modal"
                                            data-target="#modalCenter{{ $Designation->id ?? '' }}">
                                            <i class="zmdi zmdi-delete text-white"></i>
                                        </a>
                                    </td>
                                </tr>
                                <div class="modal fade" id="modalCenter{{ $Designation->id ?? '' }}" tabindex="-1"
                                    data-Mail-id="{{ $Designation->id ?? '' }}" role="dialog"
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
                                                    href=" {{ url('HCM/Designation/Delete', $Designation->id) }}">Yes</a>
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

@stop
