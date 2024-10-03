@extends('layout.master')
@section('title', 'Route List')
@section('parentPageTitle', 'Admin')
@section('page-style')
    <link rel="stylesheet" href="{{ asset('public/assets/plugins/footable-bootstrap/css/footable.bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/assets/plugins/footable-bootstrap/css/footable.standalone.min.css') }}" />
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.dataTables.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/v/dt/dt-1.10.23/b-1.6.5/b-colvis-1.6.5/datatables.min.css" />
@stop
@section('content')
    <div class="row clearfix">
        <div class="form-group col-md-6">
            <label>Module</label>
            <select name="module" id="module" class="form-control show-tick ms select2" data-placeholder="Select">
                <option value="0">Select Module</option>
                @foreach ($modules as $module)
                    <option value="{{ $module->id }}">{{ $module->module }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover dataTable js-exportable" id="igp">
            <thead>
                <tr>
                    <th class="text-center">Action</th>
                    <th class="text-center">Name</th>


                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
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
            $('#module').on('change', function() {
                var module = $('#module').val();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'post',
                    url: '{{ url('Child/Route/Fetch') }}',
                    data: {
                        module: module,
                    },
                    success: function(data) {
                        $('#igp tbody').empty();
                        data.forEach(function(singleData) {
                            var body = $('#igp tbody');
                            var row =
                                '<tr>' +
                                '<td class="p-0 text-center">' +
                                '<button class="btn btn-success btn-sm p-0 m-0" onclick="window.location.href = \'' +
                                '{{ url('Admin/ChildRouteManagement/') }}' + '/' + +  singleData.id +
                                '\';"><i class="zmdi zmdi-edit px-2 py-1"></i></button>' +
                                '</td>' +
                                '<td class="p-0 text-center">' + singleData.module +
                                '</td>' +
                                '</tr>';

                            body.append(row);
                        });
                    }

                });
            });
        });
    </script>
    <!-- Modal for delete confirmation -->
@stop
