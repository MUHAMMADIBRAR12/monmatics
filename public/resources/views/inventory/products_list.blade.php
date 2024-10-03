@extends('layout.master')
@section('title', 'Product List')
@section('parentPageTitle', 'Inventory')
@section('page-style')
    <?php use App\Libraries\appLib; ?>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.dataTables.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/v/dt/dt-1.10.23/b-1.6.5/b-colvis-1.6.5/datatables.min.css" />
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
@stop
@section('content')
    <div class="row clearfix">
        <div class="col-lg-12">
            <div class="card">
                <div class="header">
                    <button class="btn btn-primary" style="align:right"
                        onclick="window.location.href = '{{ url('Inventory/Product/Create') }}';">New Product</button>
                </div>
                <div class="body">

                    <table class="table table-bordered table-striped table-hover" id="product" style="width:100%">
                        <thead>
                            <tr>
                                <th class="px-1 py-0">Action</th>
                                <th class="px-1 py-0 text-center">Product</th>
                                <th class="px-1 py-0 text-center">Qty In Stock</th>
                                <th class="px-1 py-0 text-center">Code</th>
                                <th class="px-1 py-0 text-center">Sku</th>
                                <th class="px-1 py-0 text-center">Main Account</th>
                                <td class="px-1 py-0 text-center">Primary Unit</td>
                                <td class="px-1 py-0 text-center">Purchase Unit</td>
                                <td class="px-1 py-0 text-center">Sale Unit</td>
                                <th class="px-1 py-0 text-center">Category</th>
                                <th class="px-1 py-0 text-center">Type</th>
                                <th class="px-1 py-0 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products_list as $product)
                                <tr>
                                    <td class="p-0 text-center">
                                        <button class="btn btn-primary btn-sm p-0 m-0"
                                            onclick="window.location.href = '{{ url('Inventory/Product/Views/' . $product['id']) }}';"><i
                                                class="zmdi zmdi-view-day px-2 py-1"></i></button>
                                    </td>
                                    <td class="py-0 px-1 text-nowrap">{{ $product['name'] }}</td>
                                    <td class="py-0 px-1 text-nowrap">{{ $product['qty_in_stock'] ?? '0' }}</td>
                                    <td class="py-0 px-1 text-nowrap">{{ $product['code'] }}</td>
                                    <td class="py-0 px-1 text-nowrap">{{ $product['sku'] }}</td>
                                    <td class="py-0 px-1 text-nowrap">{{ $product['account_name'] }}</td>
                                    <td class="py-0 px-1 text-nowrap">{{ $product['primary_unit'] }}</td>
                                    <td class="py-0 px-1 text-nowrap">{{ $product['purchase_unit'] }}</td>
                                    <td class="py-0 px-1 text-nowrap">{{ $product['sale_unit'] }}</td>
                                    <td class="py-0 px-1 text-nowrap">{{ $product['category'] }}</td>
                                    <td class="py-0 px-1 text-nowrap">{{ $product['type'] }}</td>
                                    <td class="py-0 px-1 text-nowrap"><span class="tag tag-danger"> Active</span></td>
                                </tr>
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
        $('#product').DataTable({
            scrollY: '50vh',
            scrollX: true,
            scrollCollapse: true,
            dom: 'Bfrtip',
            buttons: [{
                    extend: 'pageLength',
                    className: 'btn cl mr-2 px-3 rounded'
                },
                {
                    extend: 'copy',
                    className: 'btn  bg-dark mr-2 px-3 rounded',
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
                    title: '@php echo "My Product \\n Good prodcs"; @endphp',
                    footer: true
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
                    extend: 'colvis'
                },
            ],
            "bDestroy": true,
            "lengthMenu": [
                [100, 200, 500, -1],
                [100, 200, 500, "All"]
            ],
            "columnDefs": [{
                className: "dt-right",
                "targets": [2, 3]
            }, ],



        });
    </script>
@stop
