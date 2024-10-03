@extends('layout.master')
@section('title', 'Document Manager')
@section('parentPageTitle', 'All Documents')
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
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="card">
                <div class="body">
                    <a href="{{ url('Document/Create/Document') }}" class="btn btn-primary">Add New Document</a>
                    <div class="table-responsive">
                        @if (Session::has('update_msg'))
                            <div class="alert alert-warning">{{ Session::get('update_msg') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true" onclick="hideAlert()">&times;</span>
                                </button>
                            </div>
                        @endif
                        @if (Session::has('insert_msg'))
                            <div class="alert alert-success">{{ Session::get('insert_msg') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true" onclick="hideAlert()">&times;</span>
                                </button>
                            </div>
                        @endif
                        @if (Session::has('delete_msg'))
                            <div class="alert alert-danger">{{ Session::get('delete_msg') }}

                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true" onclick="hideAlert()">&times;</span>
                                </button>
                            </div>
                        @endif
                        <table class="table table-bordered table-striped table-hover" id="departments">
                            <thead>
                                <tr>
                                    <th>Actions</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Department</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($documents as $documents)
                                    <tr>
                                        <td class="action">
                                            <a class="btn btn-success btn-sm department_edit p-0 m-0"
                                                href="{{ url('Document/Create/Document', $documents->id) }}">
                                                <i class="zmdi zmdi-edit text-white px-2 py-1"></i>
                                            </a>
                                            <a class="btn btn-danger btn-sm department_delete p-0 m-0"
                                                onclick="return confirm('Are you sure to delete this??')"
                                                href="{{ url('Document/Delete/Document', $documents->id) }}">
                                                <i class="zmdi zmdi-delete text-white px-2 py-1"></i>
                                            </a>
                                            <a href="{{ url('Document/View/Document', $documents->id) }}"
                                                class="btn btn-danger print btn-sm p-0 m-0"><i
                                                    class="zmdi zmdi-receipt px-2 py-1"></i></a>

                                            @if ($documents->status == 'private')
                                                <button class="btn btn-danger btn-sm p-0 m-0"><a
                                                        href="{{ url('Document/Share', $documents->id) }}"
                                                        class="btn btn-danger print btn-sm p-0 m-0"><i
                                                            class="zmdi zmdi-share px-2 py-1"></i></a></button>
                                            @endif
                                        </td>
                                        <td class="column_size">{{ $documents->title }}</td>
                                        <td class="column_size">{{ $documents->description }}</td>
                                        <td class="column_size">{{ $documents->status }}</td>
                                        <td class="column_size">{{ $documents->department }}</td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('page-script')
    @include('datatable-list');
    <script>
        $('#departments').DataTable({
            dom: 'Bfrtip',
            buttons: [{
                    extend: 'pageLength',
                    className: 'btn cl mr-2 px-3 rounded'
                },
                {
                    extend: 'copy',
                    className: 'btn bg-dark mr-2 px-3 rounded',
                    title: 'Department List',
                    exportOptions: {
                        columns: [1] // Exclude columns with the class 'actions'
                    }
                },
                {
                    extend: 'csv',
                    className: 'btn btn-info mr-2 px-3 rounded',
                    title: 'Department List',
                    exportOptions: {
                        columns: [1] // Exclude columns with the class 'actions'
                    }
                },
                {
                    extend: 'pdf',
                    className: 'btn btn-danger mr-2 px-3 rounded',
                    title: 'Department List',
                    exportOptions: {
                        columns: [1] // Exclude columns with the class 'actions'
                    }
                },
                {
                    extend: 'excel',
                    className: 'btn btn-warning mr-2 px-3 rounded',
                    title: 'Department List',
                    exportOptions: {
                        columns: [1] // Exclude columns with the class 'actions'
                    }
                },
                {
                    extend: 'print',
                    className: 'btn btn-success mr-2 px-3 rounded',
                    title: 'Department List',
                    exportOptions: {
                        columns: [1] // Exclude columns with the class 'actions'
                    }
                },
                {
                    extend: 'colvis',
                    className: 'visible btn rounded'
                },
            ],
            "bDestroy": true,
            "lengthMenu": [
                [100, 200, 500, -1],
                [100, 200, 500, "All"]
            ],

        });
    </script>
@stop
