@extends('layout.master')
@section('title', 'Add Child Route')
@section('parentPageTitle', 'Admin')
@section('page-style')
    <link rel="stylesheet" href="{{ asset('public/assets/plugins/footable-bootstrap/css/footable.bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/assets/plugins/footable-bootstrap/css/footable.standalone.min.css') }}" />
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.dataTables.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/v/dt/dt-1.10.23/b-1.6.5/b-colvis-1.6.5/datatables.min.css" />
    <script lang="javascript/text">
        var token = "{{ csrf_token() }}";
    </script>
@stop
@section('content')
    <div class="row clearfix">
        <div class="col-lg-12">
            <div class="card col-lg-12">
                <div class="table-responsive contact">
                    <div class="body">

                        @if($module->level === 3)
                        <a class="btn btn-primary" href="{{ url('Admin/RouteManagement') }}">back</a>
                        <form action="{{ url('Admin/ChildRouteManagement') }}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="module">Modules</label>
                                    <input type="text" name="updateChildModule" value="{{ $module->module }}" class="form-control"
                                        >
                                    <input type="hidden" name="parent_id" value="{{ $module->id }}">
                                    <input type="hidden" name="child_level" value="{{ $module->level ?? '' }}">
                                    <input type="hidden" name="backURL" value="{{ $backURL ?? '' }}">

                                </div>
                                <div class="col-md-6">
                                    <label for="module">Module Route</label>
                                    <input type="text" name="updateChildRoute" class="form-control" value="{{ $module->route }}"
                                        >
                                </div>
                            </div>

                            <button class=" btn btn-primary">Submit</button>
                        </form>
                        @else
                        <a class="btn btn-primary" href="{{ url('Admin/RouteManagement') }}">back</a>
                        <form method="post" action="{{ url('Admin/ChildRouteManagement') }}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="module">Modules</label>
                                    <input type="text" name="" value="{{ $module->module }}" class="form-control"
                                        readonly>
                                    <input type="hidden" name="parent_id" value="{{ $module->id }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="module">Module Route</label>
                                    <input type="text" name="" class="form-control" value="{{ $module->route }}"
                                        readonly>
                                </div>
                            </div>
                            <div class="row" id="row">
                                <span><i class="zmdi zmdi-plus-circle-o" style="float: inline-end;"
                                        id="addmore"></i></span>
                                <div class="col-md-6 col-md-6">
                                    <label for="module">Child Module</label>
                                    <input type="text" name="moduleName[]" class="form-control"
                                        placeholder="Child Module">
                                </div>
                                <div class="col-md-6">
                                    <label for="module">Child Module Route</label>
                                    <input type="text" name="moduleRoute[]" class="form-control"
                                        placeholder="Child Module Route">
                                </div>
                            </div>

                            <div class="form-controll text-right">
                                <button type="submit" class="btn btn-primary btn-lg">Save</button>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable js-exportable"
                                id="igp">
                                <thead>
                                    <tr>
                                        <th class="text-center">Action</th>
                                        <th class="text-center">Name</th>
                                        <th class="text-center">Route</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($moduledetail as $chModule)
                                    <tr>
                                        <td>    <button class="btn btn-success btn-sm p-0 m-0" onclick="window.location.href ='{{ url('Admin/ChildRouteManagement',$chModule->id) }}'"><i class="zmdi zmdi-edit px-2 py-1"></i></button></td>
                                        <td>{{ $chModule->module ?? '' }}</td>
                                        <td>{{ $chModule->route ?? '' }}</td>
                                    </tr>
                                    @endforeach


                                </tbody>
                            </table>
                        </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>

        <script src="{{ asset('public/assets/bundles/datatablescripts.bundle.js') }}"></script>
        <script src="{{ asset('public/assets/plugins/jquery-datatable/buttons/dataTables.buttons.min.js') }}"></script>
        <script src="{{ asset('public/assets/plugins/jquery-datatable/buttons/buttons.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('public/assets/plugins/jquery-datatable/buttons/buttons.html5.min.js') }}"></script>
        <script src="{{ asset('public/assets/plugins/jquery-datatable/buttons/buttons.print.min.js') }}"></script>
        <script src="{{ asset('public/assets/plugins/jquery-datatable/buttons/buttons.colVis.min.js') }}"></script>
        <script src="{{ asset('public/assets/plugins/jquery-datatable/buttons/buttons.pdfMake.min.js') }}"></script>
        <script src="{{ asset('public/assets/plugins/jquery-datatable/buttons/buttons.pdfMakeVfs.min.js') }}"></script>
        <script src="{{ asset('public/assets/plugins/jquery-datatable/buttons/buttons.excel.min.js') }}"></script>
        <script>
            $('#igp').DataTable({
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'pageLength',
                        className: 'btn cl mr-2 px-3 rounded'
                    },
                    {
                        extend: 'copy',
                        className: 'btn bg-dark mr-2 px-3 rounded',
                        title: 'Products'
                    },
                    {
                        extend: 'csv',
                        className: 'btn btn-info mr-2 px-3 rounded',
                        title: 'Products'
                    },
                    {
                        extend: 'pdf',
                        className: 'btn btn-danger mr-2 px-3 rounded',
                        title: 'Products'
                    },
                    {
                        extend: 'excel',
                        className: 'btn btn-warning mr-2 px-3 rounded',
                        title: 'Products'
                    },
                    {
                        extend: 'print',
                        className: 'btn btn-success mr-2 px-3 rounded',
                        title: 'Products'
                    },
                    {
                        extend: 'colvis',
                        className: 'visible btn rounded'
                    },

                ],
                "bDestroy": true,

            });
        </script>
        <script>
            $(document).ready(function() {
                $('#addmore').on('click', function() {
                    var row = `                            <div class="row" id="main">
                                <span><i class="zmdi zmdi-minus-circle-outline remove" style="float: inline-end;" ></i></span>
                                <div class="col-md-6 col-md-6">
                                    <label for="module">Child Module</label>
                                    <input type="text" name="moduleName[]" class="form-control" placeholder="Child Module" style="width:735px;">
                                </div>
                                <div class="col-md-6" >
                                    <label for="module">Child Module Route</label>
                                    <input type="text" name="moduleRoute[]" class="form-control" placeholder="Child Module Route" >
                                </div>
                            </div>`;
                    $('#row').append(row);

                    $('.remove').on('click', function() {
                        $(this).closest('div').remove();
                        // $('#main').remove();
                    });
                });
            });
        </script>

    @stop

