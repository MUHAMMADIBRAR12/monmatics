@extends('layout.master')
@section('title', 'Share Document')
@section('parentPageTitle', 'Documents')
@section('page-style')
    <?php use App\Libraries\appLib; ?>
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.dataTables.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/v/dt/dt-1.10.23/b-1.6.5/b-colvis-1.6.5/datatables.min.css" />
    <link rel="stylesheet" href="{{ asset('public/assets/css/sw.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/css/list.css') }}">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <style>
        input[type=checkbox] {
            margin-left: -14px;
        }
    </style>
@stop
@section('content')
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="card">
                <h2><strong>Users</strong></h2>
                @if (Session::has('insert_msg'))
                <div class="alert alert-success">{{ Session::get('insert_msg') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true" onclick="hideAlert()">&times;</span>
                    </button>
                </div>
            @endif
                <div class="body">

                    <p>Selected User Will see your this private document</p>

                    <form action="{{ url('Document/Share/Save',$document->id) }}" method="post">
                        @csrf
                        <input type="hidden" value="{{ $document->id }}" name="doc_id">
                        <div class="col">
                            <div class="row">
                                @foreach ($users as $user)
                                    <div class="col-md-3 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="user_id[]" {{ in_array($user->id, $checkUsers) ? 'checked' : '' }} value="{{ $user->id }}" id="flexCheckDefault">
                                            <label class="form-check-label" for="flexCheckDefault">
                                                {{ $user->firstName }} {{ $user->lastName }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <button class="btn btn-primary">Submit</button>
                    </form>

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
