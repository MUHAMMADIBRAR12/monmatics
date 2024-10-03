@extends('layout.master')
@section('title', 'Dashboard')
@section('parentPageTitle', 'Blog')
@section('page-style')
<link rel="stylesheet" href="{{asset('assets/plugins/jvectormap/jquery-jvectormap-2.0.3.css')}}"/>
<link rel="stylesheet" href="{{asset('assets/plugins/charts-c3/plugin.css')}}"/>
@stop
@section('content')
<style>
    .card {
        border-radius: 10px;
        padding: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s, box-shadow 0.3s;
        background-color: #d8dcdf
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
       
        
    }

    .body {
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        color: #fff;
    }

    .xl-blue {
        background-color: #3498db;
    }

    .xl-purple {
        background-color: #9b59b6;
    }

    .xl-green {
        background-color: #2ecc71;
    }

    .xl-pink {
        background-color: #e91e63;
    }

    .card a {
        text-decoration: none;
        color: #fff;
        
    }

    .card a:hover {
        color: #f0f0f0;
        
    }
</style>

<div class="row clearfix">
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <a href="{{url('blog/category')}}">
                <div class="body xl-blue">
                    <h4 class="mt-0 mb-0">{{ count($categories) }}</h4>
                    <p class="mb-0">Blog Categories</p>                        
                </div>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <a href="{{url('blog/post')}}">
                <div class="body xl-purple">
                    <h4 class="mt-0 mb-0">{{$posts->count()}}</h4>
                    <p class="mb-0">Blog Posts</p>                        
                </div>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="body xl-green">
                <h4 class="mt-0 mb-0">{{$activePosts->count()}}</h4>
                <p class="mb-0">Publish Posts</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="body xl-pink">
                <h4 class="mt-0 mb-0">{{$DeactivePosts->count()}}</h4>
                <p class="mb-0">Draft Posts</p>
            </div>
        </div>
    </div>
</div>
     
{{-- <div class="row clearfix">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="header">
                <h2><strong>Popular</strong> Categories</h2>
                <ul class="header-dropdown">
                    <li class="dropdown"> <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <i class="zmdi zmdi-more"></i> </a>
                        <ul class="dropdown-menu dropdown-menu-right slideUp">
                            <li><a href="javascript:void(0);">Edit</a></li>
                            <li><a href="javascript:void(0);">Delete</a></li>
                            <li><a href="javascript:void(0);">Report</a></li>
                        </ul>
                    </li>
                    <li class="remove">
                        <a role="button" class="boxs-close"><i class="zmdi zmdi-close"></i></a>
                    </li>
                </ul>
            </div>
            <div class="body">
                <div id="chart-bar" style="height: 16rem"></div>
            </div>
        </div>
    </div>
</div>
<div class="row clearfix">
    <div class="col-sm-12">
        <div class="card">
            <div class="header">
                <h2><strong>Social</strong> Media</h2>
                <ul class="header-dropdown">
                    <li class="dropdown"> <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <i class="zmdi zmdi-more"></i> </a>
                        <ul class="dropdown-menu dropdown-menu-right slideUp">
                            <li><a href="javascript:void(0);">Edit</a></li>
                            <li><a href="javascript:void(0);">Delete</a></li>
                            <li><a href="javascript:void(0);">Report</a></li>
                        </ul>
                    </li>
                    <li class="remove">
                        <a role="button" class="boxs-close"><i class="zmdi zmdi-close"></i></a>
                    </li>
                </ul>
            </div>
            <div class="table-responsive social_media_table">
                <table class="table table-hover c_table">
                    <thead>
                        <tr>
                            <th>Media</th>
                            <th>Name</th>
                            <th>Like</th>
                            <th>Comments</th>
                            <th>Share</th>
                            <th>Members</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><span class="social_icon linkedin"><i class="zmdi zmdi-linkedin"></i></span>
                            </td>
                            <td><span class="list-name">Linked In</span>
                                <span class="text-muted">Florida, United States</span>
                            </td>
                            <td>19K</td>
                            <td>14K</td>
                            <td>10K</td>
                            <td>
                                <span class="badge badge-success">2341</span>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="social_icon twitter-table"><i class="zmdi zmdi-twitter"></i></span>
                            </td>
                            <td><span class="list-name">Twitter</span>
                                <span class="text-muted">Arkansas, United States</span>
                            </td>
                            <td>7K</td>
                            <td>11K</td>
                            <td>21K</td>
                            <td>
                                <span class="badge badge-success">952</span>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="social_icon facebook"><i class="zmdi zmdi-facebook"></i></span>
                            </td>
                            <td><span class="list-name">Facebook</span>
                                <span class="text-muted">Illunois, United States</span>
                            </td>
                            <td>15K</td>
                            <td>18K</td>
                            <td>8K</td>
                            <td>
                                <span class="badge badge-success">6127</span>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="social_icon google"><i class="zmdi zmdi-google-plus"></i></span>
                            </td>
                            <td><span class="list-name">Google Plus</span>
                                <span class="text-muted">Arizona, United States</span>
                            </td>
                            <td>15K</td>
                            <td>18K</td>
                            <td>154</td>
                            <td>
                                <span class="badge badge-success">325</span>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="social_icon youtube"><i class="zmdi zmdi-youtube-play"></i></span>
                            </td>
                            <td><span class="list-name">YouTube</span>
                                <span class="text-muted">Alaska, United States</span>
                            </td>
                            <td>15K</td>
                            <td>18K</td>
                            <td>200</td>
                            <td>
                                <span class="badge badge-success">160</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>            
</div>            
<div class="row clearfix">
    <div class="col-md-12">
        <div class="card">
            <div class="header">
                <h2><strong>Browser</strong> Usage</h2>
                <ul class="header-dropdown">
                    <li class="dropdown"> <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <i class="zmdi zmdi-more"></i> </a>
                        <ul class="dropdown-menu dropdown-menu-right slideUp">
                            <li><a href="javascript:void(0);">Edit</a></li>
                            <li><a href="javascript:void(0);">Delete</a></li>
                            <li><a href="javascript:void(0);">Report</a></li>
                        </ul>
                    </li>
                    <li class="remove">
                        <a role="button" class="boxs-close"><i class="zmdi zmdi-close"></i></a>
                    </li>
                </ul>
            </div>
            <div class="body">
                <div class="row clearfix">
                    <div class="col-lg-6 col-md-12">
                        <div id="chart-donut" style="height: 17rem"></div>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <div class="table-responsive">
                            <table class="table table-hover c_table mb-0">
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Chrome</td>
                                        <td>6985 <i class="zmdi zmdi-caret-up text-success"></i></td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Other</td>
                                        <td>2697 <i class="zmdi zmdi-caret-up text-success"></i></td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>Safari</td>
                                        <td>3597 <i class="zmdi zmdi-caret-down text-danger"></i></td>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td>Firefox</td>
                                        <td>2145 <i class="zmdi zmdi-caret-up text-success"></i></td>
                                    </tr>
                                    <tr>
                                        <td>5</td>
                                        <td>IE</td>
                                        <td>54 <i class="zmdi zmdi-caret-down text-danger"></i></td>
                                    </tr>                               
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>                    
</div>
<div class="row clearfix">
    <div class="col-lg-7 col-md-12">
        <div class="card visitors-map">
            <div class="header">
                <h2><strong>Visitors</strong> Statistics</h2>
                <ul class="header-dropdown">
                    <li class="dropdown"> <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <i class="zmdi zmdi-more"></i> </a>
                        <ul class="dropdown-menu dropdown-menu-right slideUp">
                            <li><a href="javascript:void(0);">Edit</a></li>
                            <li><a href="javascript:void(0);">Delete</a></li>
                            <li><a href="javascript:void(0);">Report</a></li>
                        </ul>
                    </li>
                    <li class="remove">
                        <a role="button" class="boxs-close"><i class="zmdi zmdi-close"></i></a>
                    </li>
                </ul>                        
            </div>
            <div class="body">
                <div id="world-map-markers" class="jvector-map"></div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover c_table theme-color mb-0">
                    <thead>
                        <tr>
                            <th>Contrary</th>
                            <th>2016</th>
                            <th>2019</th>
                            <th>Change</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>USA</td>
                            <td>2,009</td>
                            <td>3,591</td>
                            <td>7.01% <i class="zmdi zmdi-trending-up text-success"></i></td>
                        </tr>
                        <tr>
                            <td>India</td>
                            <td>1,129</td>
                            <td>1,361</td>
                            <td>3.01% <i class="zmdi zmdi-trending-up text-success"></i></td>
                        </tr>
                        <tr>
                            <td>Canada</td>
                            <td>2,009</td>
                            <td>2,901</td>
                            <td>9.01% <i class="zmdi zmdi-trending-up text-success"></i></td>
                        </tr>
                        <tr>
                            <td>Australia</td>
                            <td>954</td>
                            <td>901</td>
                            <td>5.71% <i class="zmdi zmdi-trending-down text-warning"></i></td>
                        </tr>
                        <tr>
                            <td>Other</td>
                            <td>4,236</td>
                            <td>4,591</td>
                            <td>9.15% <i class="zmdi zmdi-trending-up text-success"></i></td>
                        </tr>                                            											
                    </tbody>
                </table>                                    
            </div>
        </div>
    </div>
    <div class="col-lg-5 col-md-12">
        <div class="card">
            <div class="header">
                <h2><strong>USA</strong> Categories Statistics</h2>
                <ul class="header-dropdown">
                    <li class="dropdown"> <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <i class="zmdi zmdi-more"></i> </a>
                        <ul class="dropdown-menu dropdown-menu-right slideUp">
                            <li><a href="javascript:void(0);">Edit</a></li>
                            <li><a href="javascript:void(0);">Delete</a></li>
                            <li><a href="javascript:void(0);">Report</a></li>
                        </ul>
                    </li>
                    <li class="remove">
                        <a role="button" class="boxs-close"><i class="zmdi zmdi-close"></i></a>
                    </li>
                </ul>
            </div>
            <div class="body">
                <div id="usa" class="text-center" style="height: 400px"></div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover c_table theme-color mb-0">
                    <thead>
                        <tr>
                            <th>Categories</th>
                            <th>2016</th>
                            <th>2019</th>
                            <th>Change</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Web Design</td>
                            <td>2,009</td>
                            <td>3,591</td>
                            <td>7.01% <i class="zmdi zmdi-trending-up text-success"></i></td>
                        </tr>
                        <tr>
                            <td>Photography</td>
                            <td>1,129</td>
                            <td>1,361</td>
                            <td>3.01% <i class="zmdi zmdi-trending-up text-success"></i></td>
                        </tr>
                        <tr>
                            <td>Technology</td>
                            <td>2,009</td>
                            <td>2,901</td>
                            <td>9.01% <i class="zmdi zmdi-trending-up text-success"></i></td>
                        </tr>
                        <tr>
                            <td>Lifestyle</td>
                            <td>954</td>
                            <td>901</td>
                            <td>5.71% <i class="zmdi zmdi-trending-down text-warning"></i></td>
                        </tr>
                        <tr>
                            <td>Sports</td>
                            <td>4,236</td>
                            <td>4,591</td>
                            <td>9.15% <i class="zmdi zmdi-trending-up text-success"></i></td>
                        </tr>                                            											
                    </tbody>
                </table>                                    
            </div>
        </div>
    </div>
</div> --}}
@stop
@section('page-script')
<script src="{{asset('assets/bundles/jvectormap.bundle.js')}}"></script>
<script src="{{asset('assets/plugins/jvectormap/jquery-jvectormap-us-aea-en.js')}}"></script>
<script src="{{asset('assets/bundles/sparkline.bundle.js')}}"></script>
<script src="{{asset('assets/bundles/c3.bundle.js')}}"></script>
<script src="{{asset('assets/js/pages/blog/blog.js')}}"></script>
@stop