@extends('layout.master')
@section('title', 'Accounts')
@section('parentPageTitle', 'Chart of Accounts')
@section('page-style')
<link rel="stylesheet" href="{{ asset('public/assets/plugins/footable-bootstrap/css/footable.bootstrap.min.css') }}"/>
<link rel="stylesheet" href="{{ asset('public/assets/plugins/footable-bootstrap/css/footable.standalone.min.css') }}"/>
@stop
@section('content')
<style>
    .table td{
        padding: 0.10rem;
    }
</style>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="body">
                <div class="table-responsive">
                    <button class="btn btn-primary" style="align:right" onclick="window.location.href = '{{ url('Accounts/Coa') }}';" >New Account</button>
                    <table class="table table-striped m-b-0">
                        <thead>
                            <tr>
                                <th>&nbsp;</th>
                                <th>Account Code</th>
                                <th data-breakpoints="xs">Account Name</th>
                                <th data-breakpoints="xs">Category</th>                                
                                <th>Status</th>
                            </tr>
                        </thead>
                            <tbody>
                                <?php  $category = array(1=>"Assets",2=>"Liability",3=>"Income",4=>"Expenses"); ?>                                
                                @foreach($coaList as $coa)
                                <tr >
                                    <td>
                                         @if($coa->editable==1)
                                        <button class="btn btn-primary btn-sm" onclick="window.location.href = '{{ url('Accounts/Coa?Id='.$coa->id) }}';"><i class="zmdi zmdi-edit"></i></button>
                                        @endif
                                        @if($coa->trans_group==0)
                                        <button class="btn btn-danger btn-sm" onclick="window.location.href = '{{ url('Accounts/Coa?coaId='.$coa->id) }}';"><i class="zmdi zmdi-add" alt='Add New Account'>+</i></button>
                                        @endif
                                    </td>
                                    <td>{{ $coa->code }}</td>
                                    <td>{{ $coa->name }}</td>
                                    <td>{{ $category[$coa->category]  }}</td>
                                    <td><span class="tag tag-danger"> Active</span></td>
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
<script src="{{asset('public/assets/bundles/footable.bundle.js')}}"></script>
<script src="{{asset('public/assets/js/pages/tables/footable.js')}}"></script>
@stop