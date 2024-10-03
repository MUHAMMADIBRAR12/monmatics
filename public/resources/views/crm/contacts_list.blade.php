@extends('layout.master')
@section('title', 'Contacts List')
@section('parentPageTitle', 'Crm')
<?php use App\Libraries\appLib; ?>
@section('page-style')
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.23/b-1.6.5/b-colvis-1.6.5/datatables.min.css"/>
<link rel="stylesheet" href="{{asset('public/assets/css/sw.css')}}">
<link rel="stylesheet" href="{{asset('public/assets/css/list.css')}}">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>




@stop
@section('content')
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header">
            <button class="btn btn-primary" style="align:right" onclick="window.location.href = '{{ url('Crm/Contacts/Create')}}';" >New Contact</button>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" id="contacts">
                        <thead>
                            <tr>
                                <th class="px-1 py-0">Action</th>
                                <th>Name</th>                                    
                                <th>Office Phone</th>
                                <th>Email</th>
                                <th>Relatd To</th>
                                <th>User</th>
                                <th>Date Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($contactsList as $contact)
                            <tr>
                                <td class="action">
                                    <button class="btn btn-primary btn-sm p-0 m-0" onclick="window.location.href = '{{ url('Crm/Contacts/Views/'.$contact['id']) }}';"><i class="zmdi zmdi-view-day px-2 py-1"></i></button>                                        
                                </td>
                                <td class="column_size">
                                    {{$contact['name']}}
                                </td>
                                <td class="column_size" ><p style="float: right">{{$contact['office_No']}}</p></td>
                                <td class="column_size">{{$contact['email']}}</td>
                                <td class="column_size">{{$contact['account_Name']->name ?? ''}}</td>
                                <td class="column_size">{{$contact['user_name']}}</td>
                                <td class="column_size">{{appLib::setDateFormat($contact['created_at'])}}</td>
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
$('#contacts').DataTable( {
    dom: 'Bfrtip',
    buttons: [
        { extend: 'pageLength', className:'btn cl mr-2 px-3 rounded'},
        { extend: 'copy', className: 'btn bg-dark mr-2 px-3 rounded', title:'Products'},
        { extend: 'csv', className: 'btn btn-info mr-2 px-3 rounded', title:'Products'},
        { extend: 'pdf', className: 'btn btn-danger mr-2 px-3 rounded', title:'Products'},
        { extend: 'excel', className: 'btn btn-warning mr-2 px-3 rounded',title:'Products'},
        { extend: 'print', className: 'btn btn-success mr-2 px-3 rounded',title:'Products'},
        { extend: 'colvis', className:'visible btn rounded'},
        ],
    "bDestroy": true,   
    "lengthMenu": [[100, 200, 500, -1], [100, 200, 500, "All"]],  
}); 
</script>
@stop