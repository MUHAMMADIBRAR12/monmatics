@extends('layout.master')
@section('title', 'User List')
@section('parentPageTitle', 'Admin')
@section('page-style')
    <link rel="stylesheet" href="{{ asset('public/assets/plugins/footable-bootstrap/css/footable.bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/assets/plugins/footable-bootstrap/css/footable.standalone.min.css') }}" />
@stop
@section('content')
    <div class="row clearfix">
        <div class="col-lg-12">
            <div class="card">
                <a href="{{ url('Admin/Users/Create') }}" class="btn btn-primary" style="align:right">
                    <b><i class="zmdi zmdi-plus"></i>Add New User</b>
                </a>
                <div class="table-responsive contact">
                    <table class="table table-hover mb-0 c_list c_table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>User Name</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($userList as $user)
                                <tr>
                                    <td>
                                        @if ($user->profile)
                                            <img src="{{ url('display/' . $user->id) }}" class="avatar w30" alt="">
                                        @endif
                                        <p class="c_name">{{ $user->firstName }} {{ $user->lastName }}</p>
                                    </td>
                                    <td>
                                        <span class="email"><a href="javascript:void(0);"
                                                title="">{{ $user->email }}</a></span>
                                    </td>
                                    <!--<td>{{-- $user->company_name --}}</td>-->
                                    <td>{{ $user->role }}</td>
                                    <td>
                                        @if ($user->status == 1)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-warning">Suspendd</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a class="btn btn-primary btn-sm"
                                            href="{{ url('Admin/Users/Create/' . $user->id) }}">
                                            <i class="zmdi zmdi-edit"></i>
                                        </a>
                                        <a class="btn btn-danger btn-sm del" data-toggle="modal" data-target="#modalCenter">
                                            <input type="hidden" id="userId" value="{{ $user->id }}">
                                            <i class="zmdi zmdi-delete text-white"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal for delete confirmation -->
    <div class="modal fade" id="modalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Are You Sure to Delete The Role</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-secondary" data-dismiss="modal">No</a>
                    <a class="btn btn-primary model-delete" href="">Yes</a>
                </div>
            </div>
        </div>
    </div>
@stop
@section('page-script')
    <script src="{{ asset('public/assets/bundles/footable.bundle.js') }}"></script>
    <script src="{{ asset('public/assets/js/pages/tables/footable.js') }}"></script>
    <script>
        $(document).ready(function() {
            $(document).on('click', '.del', function() {
                var id = $(this).find('#userId').val();
                $(".model-delete").attr("href", "Remove/" + id);
            });
        });
    </script>


@stop
