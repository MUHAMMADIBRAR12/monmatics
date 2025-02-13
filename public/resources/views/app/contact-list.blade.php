@extends('layout.master')
@section('title', 'Contacts List')
@section('parentPageTitle', 'Crm')
@section('page-style')
<link rel="stylesheet" href="{{asset('assets/plugins/footable-bootstrap/css/footable.bootstrap.min.css')}}" />
<link rel="stylesheet" href="{{asset('assets/plugins/footable-bootstrap/css/footable.standalone.min.css')}}" />
@stop
@section('content')
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="table-responsive contact">
                <table class="table table-hover mb-0 c_list c_table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>                                    
                            <th data-breakpoints="xs">Phone</th>
                            <th data-breakpoints="xs sm md">Email</th>
                            <th data-breakpoints="xs sm md">Address</th>
                            <th data-breakpoints="xs">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="checkbox">
                                    <input id="delete_2" type="checkbox">
                                    <label for="delete_2">&nbsp;</label>
                                </div>
                            </td>
                            <td>
                                <img src="{{asset('assets/images/xs/avatar1.jpg')}}" class="avatar w30" alt="">
                                <p class="c_name">John Smith</p>
                            </td>
                            <td>
                                <span class="phone"><i class="zmdi zmdi-whatsapp mr-2"></i>264-625-2583</span>
                            </td>
                            <td>
                                <span class="email"><a href="javascript:void(0);" title="">johnsmith@gmail.com</a></span>
                            </td>
                            <td>
                                <address><i class="zmdi zmdi-pin"></i>123 6th St. Melbourne, FL 32904</address>
                            </td>
                            <td>
                                <button class="btn btn-primary btn-sm"><i class="zmdi zmdi-edit"></i></button>
                                <button class="btn btn-danger btn-sm"><i class="zmdi zmdi-delete"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="checkbox">
                                    <input id="delete_3" type="checkbox">
                                    <label for="delete_3">&nbsp;</label>
                                </div>
                            </td>
                            <td>
                                <img src="{{asset('assets/images/xs/avatar3.jpg')}}" class="avatar w30" alt="">
                                <p class="c_name">Hossein Shams</p>
                            </td>
                            <td>
                                <span class="phone"><i class="zmdi zmdi-whatsapp mr-2"></i>264-625-5689</span>
                            </td>
                            <td>
                                <span class="email"><a href="javascript:void(0);" title="">hosseinshams@gmail.com</a></span>
                            </td>
                            <td>
                                <address><i class="zmdi zmdi-pin"></i>44 Shirley Ave. West Chicago, IL 60185</address>
                            </td>
                            <td>
                                <button class="btn btn-primary btn-sm"><i class="zmdi zmdi-edit"></i></button>
                                <button class="btn btn-danger btn-sm"><i class="zmdi zmdi-delete"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="checkbox">
                                    <input id="delete_4" type="checkbox">
                                    <label for="delete_4">&nbsp;</label>
                                </div>
                            </td>
                            <td>
                                <img src="{{asset('assets/images/xs/avatar4.jpg')}}" class="avatar w30" alt="">
                                <p class="c_name">Maryam Amiri</p>
                            </td>
                            <td>
                                <span class="phone"><i class="zmdi zmdi-whatsapp mr-2"></i>264-625-9513</span>
                            </td>
                            <td>
                                <span class="email"><a href="javascript:void(0);" title="">maryamamiri@gmail.com</a></span>
                            </td>
                            <td>
                                <address><i class="zmdi zmdi-pin"></i>123 6th St. Melbourne, FL 32904</address>
                            </td>
                            <td>
                                <button class="btn btn-primary btn-sm"><i class="zmdi zmdi-edit"></i></button>
                                <button class="btn btn-danger btn-sm"><i class="zmdi zmdi-delete"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="checkbox">
                                    <input id="delete_5" type="checkbox">
                                    <label for="delete_5">&nbsp;</label>
                                </div>
                            </td>
                            <td>
                                <img src="{{asset('assets/images/xs/avatar6.jpg')}}" class="avatar w30" alt="">
                                <p class="c_name">Tim Hank</p>
                            </td>
                            <td>
                                <span class="phone"><i class="zmdi zmdi-whatsapp mr-2"></i>264-625-1212</span>
                            </td>
                            <td>
                                <span class="email"><a href="javascript:void(0);" title="">timhank@gmail.com</a></span>
                            </td>
                            <td>
                                <address><i class="zmdi zmdi-pin"></i>70 Bowman St. South Windsor, CT 06074</address>
                            </td>
                            <td>
                                <button class="btn btn-primary btn-sm"><i class="zmdi zmdi-edit"></i></button>
                                <button class="btn btn-danger btn-sm"><i class="zmdi zmdi-delete"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="checkbox">
                                    <input id="delete_6" type="checkbox">
                                    <label for="delete_6">&nbsp;</label>
                                </div>
                            </td>
                            <td>
                                <img src="{{asset('assets/images/xs/avatar7.jpg')}}" class="avatar w30" alt="">
                                <p class="c_name">Fidel Tonn</p>
                            </td>
                            <td>
                                <span class="phone"><i class="zmdi zmdi-whatsapp mr-2"></i>264-625-2323</span>
                            </td>
                            <td>
                                <span class="email"><a href="javascript:void(0);" title="">fideltonn@gmail.com</a></span>
                            </td>
                            <td>
                                <address><i class="zmdi zmdi-pin"></i>514 S. Magnolia St. Orlando, FL 32806</address>
                            </td>
                            <td>
                                <button class="btn btn-primary btn-sm"><i class="zmdi zmdi-edit"></i></button>
                                <button class="btn btn-danger btn-sm"><i class="zmdi zmdi-delete"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="checkbox">
                                    <input id="delete_7" type="checkbox">
                                    <label for="delete_7">&nbsp;</label>
                                </div>
                            </td>
                            <td>
                                <img src="{{asset('assets/images/xs/avatar8.jpg')}}" class="avatar w30" alt="">
                                <p class="c_name">Gary Camara</p>
                            </td>
                            <td>
                                <span class="phone"><i class="zmdi zmdi-whatsapp mr-2"></i>264-625-1005</span>
                            </td>
                            <td>
                                <span class="email"><a href="javascript:void(0);" title="">garycamara@gmail.com</a></span>
                            </td>
                            <td>
                                <address><i class="zmdi zmdi-pin"></i>44 Shirley Ave. West Chicago, IL 60185</address>
                            </td>
                            <td>
                                <button class="btn btn-primary btn-sm"><i class="zmdi zmdi-edit"></i></button>
                                <button class="btn btn-danger btn-sm"><i class="zmdi zmdi-delete"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="checkbox">
                                    <input id="delete_8" type="checkbox">
                                    <label for="delete_8">&nbsp;</label>
                                </div>
                            </td>
                            <td>
                                <img src="{{asset('assets/images/xs/avatar9.jpg')}}" class="avatar w30" alt="">
                                <p class="c_name">Frank Camly</p>
                            </td>
                            <td>
                                <span class="phone"><i class="zmdi zmdi-whatsapp mr-2"></i>264-625-9999</span>
                            </td>
                            <td>
                                <span class="email"><a href="javascript:void(0);" title="">frankcamly@gmail.com</a></span>
                            </td>
                            <td>
                                <address><i class="zmdi zmdi-pin"></i>123 6th St. Melbourne, FL 32904</address>
                            </td>
                            <td>
                                <button class="btn btn-primary btn-sm"><i class="zmdi zmdi-edit"></i></button>
                                <button class="btn btn-danger btn-sm"><i class="zmdi zmdi-delete"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="checkbox">
                                    <input id="delete_9" type="checkbox">
                                    <label for="delete_9">&nbsp;</label>
                                </div>
                            </td>
                            <td>
                                <img src="{{asset('assets/images/xs/avatar10.jpg')}}" class="avatar w30" alt="">
                                <p class="c_name">Tim Hank</p>
                            </td>
                            <td>
                                <span class="phone"><i class="zmdi zmdi-whatsapp mr-2"></i>264-625-1212</span>
                            </td>
                            <td>
                                <span class="email"><a href="javascript:void(0);" title="">timhank@gmail.com</a></span>
                            </td>
                            <td>
                                <address><i class="zmdi zmdi-pin"></i>70 Bowman St. South Windsor, CT 06074</address>
                            </td>
                            <td>
                                <button class="btn btn-primary btn-sm"><i class="zmdi zmdi-edit"></i></button>
                                <button class="btn btn-danger btn-sm"><i class="zmdi zmdi-delete"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop
@section('page-script')
<script src="{{asset('assets/bundles/footable.bundle.js')}}"></script>
<script src="{{asset('assets/js/pages/tables/footable.js')}}"></script>
@stop