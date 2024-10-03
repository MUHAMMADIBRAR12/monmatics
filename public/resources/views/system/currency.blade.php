@extends('layout.master')
@section('title', 'CurrencyList')
@section('parentPageTitle', 'Currency')
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
                            <input type="hidden" name="id" id="recordId"
                                value="{{ $currency->id ?? '' }}">
                            @csrf
                            <div class="row">
                                <div class="mb-3 col-md-3">
                                    <label class="form-label">Name</label>
                                    <input type="text" class="form-control value" name="name" placeholder="Name"
                                        id="name" value="{{ $currency->name ?? '' }}" required autocomplete="off">
                                        @error('name')
                                        <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                </div>
                                <div class="mb-3  col-md-3">
                                    <label class="form-label">Short Form / Symbol</label>
                                    <input type="text" class="form-control value" name="shortform" placeholder="Short Form / Symbol"
                                        id="shortform" value="{{ $currency->symbol ?? '' }}" required autocomplete="off">
                                        @error('shortform')
                                        <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                </div>

                                <div class="mb-3  col-md-3">
                                    <label class="form-label">Price</label>
                                    <input type="text" class="form-control value" name="price" placeholder="Price"
                                        id="price" value="{{ $currency->rate ?? '' }}" required autocomplete="off">
                                        @error('price')
                                        <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                </div>

                                <div class="mb-3  col-md-3">
                                    <label class="form-label">Sub Unit</label>
                                    <input type="text" class="form-control value" name="sub_unit" placeholder="Sub Unit"
                                        id="sub_unit" value="{{ $currency->sub_unit ?? '' }}" autocomplete="off">
                                </div>

                            </div>

                            <button class="btn btn-primary" id="submit">Submit</button>
                        </form>
                        <table class="table table-bordered table-striped table-hover" id="cust_category">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Short Form / Symbol</th>
                                    <th>Price</th>
                                    <th>Sub Unit</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($currencies as $currency)
                                <tr id="rsm{{ $currency->id ?? '' }}">

                                    <td class="column_size">{{ $currency->name ?? 'Null' }}</td>
                                    <td class="column_size">{{ $currency->symbol ?? 'Null' }}</td>
                                    <td class="column_size">{{ $currency->rate ?? 'Null' }}</td>
                                    <td class="column_size">{{ $currency->sub_unit ?? 'Null' }}</td>

                                    <td class="action">
                                        <a class="btn btn-success btn-sm"
                                            href="{{ url('Admin/Currencies/List', $currency->id ?? '') }}">
                                            <i class="zmdi zmdi-edit"></i>
                                        </a>
                                        <a class="btn btn-danger btn-sm del" data-toggle="modal"
                                            data-target="#modalCenter{{ $currency->id ?? '' }}">
                                            <i class="zmdi zmdi-delete text-white"></i>
                                        </a>
                                    </td>
                                </tr>
                                <div class="modal fade" id="modalCenter{{ $currency->id ??  '' }}" tabindex="-1"
                                    data-currency-id="{{ $currency->id ?? '' }}" role="dialog"
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
                                                    href="{{ url('Admin/Currencies/Delete', $currency->id ?? '') }}">Yes</a>
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
                $('form').submit(function(e) {
                    e.preventDefault();

                    var recordId = $('#recordId').val();
                    var name = $('#name').val();
                    var price = $('#price').val();
                    var sub_unit = $('#sub_unit').val();
                    var shortform = $('#shortform').val();

                    $.ajax({
                        url: '{{ route('Admin/Currencies/Store') }}',
                        type: 'post',
                        data: {
                            _token: '{{ csrf_token() }}',
                            recordId: recordId,
                            name: name,
                            price: price,
                            sub_unit: sub_unit,
                            shortform: shortform,
                        },
                        success: function(response) {
                            var action = recordId  ? 'Edit' : 'Add';

                            if (action === 'Add') {
                                // Add a new row for the added data
                                var newRow = '<tr id="rsm' + response.id + '"><td>' + response
                                    .name + '</td><td>' +
                                    response.symbol + '</td><td>' + response.rate + '</td><td>' +
                                    response.sub_unit +
                                    '</td><td class="action"><a class="btn btn-success btn-sm" href="{{ url('Admin/Currencies/List') }}/' +
                                    response.id + '"><i class="zmdi zmdi-edit"></i></a>' +
                                    '<a class="btn btn-danger btn-sm del" data-credential-id="' +
                                    response.id +
                                    '"><i class="zmdi zmdi-delete text-white"></i></a></td></tr>';
                                $('#cust_category tbody').append(newRow);
                                $(".value").val("");
                            } else if (action === 'Edit') {
                                // Update the existing row with the edited data
                                var editedRow = $('#rsm' + recordId);
                                editedRow.find('td:eq(0)').text(response.name);
                                editedRow.find('td:eq(1)').text(response.symbol);
                                editedRow.find('td:eq(2)').text(response.rate);
                                editedRow.find('td:eq(3)').text(response.sub_unit);
                                $(".value").val("");
                            }
                        },
                        error: function(error) {
                            console.log(error);
                        }
                    });
                    return false;
                });
            });
        </script>

    @stop
