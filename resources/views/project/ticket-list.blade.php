@extends('layout.master')
@section('title', 'Taskboard')
@section('parentPageTitle', 'Project')
@section('content')
<div class="row clearfix">
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card state_w1">
            <div class="body d-flex justify-content-between">
                <div>
                    <h5>2,365</h5>
                    <span>Total Tickets</span>
                </div>
                <div class="sparkline" data-type="bar" data-width="97%" data-height="55px" data-bar-Width="3" data-bar-Spacing="5" data-bar-Color="#FFC107">5,2,3,7,6,4,8,1</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card state_w1">
            <div class="body d-flex justify-content-between">
                <div>
                    <h5>365</h5>
                    <span>Pending</span>
                </div>
                <div class="sparkline" data-type="bar" data-width="97%" data-height="55px" data-bar-Width="3" data-bar-Spacing="5" data-bar-Color="#46b6fe">8,2,6,5,1,4,4,3</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card state_w1">
            <div class="body d-flex justify-content-between">
                <div>
                    <h5>65</h5>
                    <span>Responded</span>
                </div>
                <div class="sparkline" data-type="bar" data-width="97%" data-height="55px" data-bar-Width="3" data-bar-Spacing="5" data-bar-Color="#ee2558">4,4,3,9,2,1,5,7</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card state_w1">
            <div class="body d-flex justify-content-between">
                <div>
                    <h5>2,055</h5>
                    <span>Resolve</span>
                </div>
                <div class="sparkline" data-type="bar" data-width="97%" data-height="55px" data-bar-Width="3" data-bar-Spacing="5" data-bar-Color="#04BE5B">7,5,3,8,4,6,2,9</div>
            </div>
        </div>
    </div>
</div>
<div class="row clearfix">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="card project_list">
            <div class="table-responsive">
                <table class="table table-hover c_table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>Title</th>
                            <th>Created by</th>
                            <th>Date</th>
                            <th>Agent</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>A2586</strong></td>
                            <td><a href="ticket-detail.html" title="">Lucid Side Menu Open OnClick</a></td>
                            <td>Lucid Admin</td>
                            <td>Tim Hank</td>
                            <td>02 Jan 2019</td>
                            <td>Maryam</td>
                            <td><span class="badge badge-warning">In Progress</span></td>
                        </tr>
                        <tr>
                            <td><strong>A4578</strong></td>
                            <td><a href="ticket-detail.html" title="">Update chart library</a></td>
                            <td>Alpino Bootstrap</td>
                            <td>Tim Hank</td>
                            <td>04 Jan 2019</td>
                            <td>Hossein</td>
                            <td><span class="badge badge-warning">In Progress</span></td>
                        </tr>
                        <tr>
                            <td><strong>A6523</strong></td>
                            <td><a href="ticket-detail.html" title="">Mega Menu Open OnClick</a></td>
                            <td>Hexabit Admin</td>
                            <td>Gary Camara</td>
                            <td>09 Jan 2019</td>
                            <td>Maryam</td>
                            <td><span class="badge badge-info">Opened</span></td>
                        </tr>
                        <tr>
                            <td><strong>A9514</strong></td>
                            <td><a href="ticket-detail.html" title="">Nexa Theme Side Menu Open OnClick</a></td>
                            <td>Nexa Template</td>
                            <td>Tim Hank</td>
                            <td>12 Jan 2019</td>
                            <td>Hossein</td>
                            <td><span class="badge badge-info">Opened</span></td>
                        </tr>
                        <tr>
                            <td><strong>A2548</strong></td>
                            <td><a href="ticket-detail.html" title="">Update Angular version</a></td>
                            <td>Lucid Admin</td>
                            <td>Fidel Tonn</td>
                            <td>22 Jan 2019</td>
                            <td>Frank</td>
                            <td><span class="badge badge-danger">Closed</span></td>
                        </tr>
                        <tr>
                            <td><strong>A1346</strong></td>
                            <td><a href="ticket-detail.html" title="">Add new hospital</a></td>
                            <td>Lucid Hospital</td>
                            <td>Fidel Tonn</td>
                            <td>13 Jan 2019</td>
                            <td>Hossein</td>
                            <td><span class="badge badge-danger">Closed</span></td>
                        </tr>
                        <tr>
                            <td><strong>A7845</strong></td>
                            <td><a href="ticket-detail.html" title="">Update latest bootstrap version</a></td>
                            <td>Compass Dashboard</td>
                            <td>Tim Hank</td>
                            <td>07 Jan 2019</td>
                            <td>Frank</td>
                            <td><span class="badge badge-warning">In Progress</span></td>
                        </tr>
                        <tr>
                            <td><strong>A2586</strong></td>
                            <td><a href="ticket-detail.html" title="">Add new extra page</a></td>
                            <td>Lucid Admin</td>
                            <td>Tim Hank</td>
                            <td>02 Jan 2019</td>
                            <td>Maryam</td>
                            <td><span class="badge badge-warning">In Progress</span></td>
                        </tr>
                        <tr>
                            <td><strong>A4578</strong></td>
                            <td><a href="ticket-detail.html" title="">Update chart library</a></td>
                            <td>Alpino Bootstrap</td>
                            <td>Tim Hank</td>
                            <td>04 Jan 2019</td>
                            <td>Hossein</td>
                            <td><span class="badge badge-warning">In Progress</span></td>
                        </tr>
                        <tr>
                            <td><strong>A6523</strong></td>
                            <td><a href="ticket-detail.html" title="">Mega Menu Open OnClick</a></td>
                            <td>Hexabit Admin</td>
                            <td>Gary Camara</td>
                            <td>09 Jan 2019</td>
                            <td>Maryam</td>
                            <td><span class="badge badge-info">Opened</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <ul class="pagination pagination-primary mt-4">
                <li class="page-item active"><a class="page-link" href="javascript:void(0);">1</a></li>
                <li class="page-item"><a class="page-link" href="javascript:void(0);">2</a></li>
                <li class="page-item"><a class="page-link" href="javascript:void(0);">3</a></li>
                <li class="page-item"><a class="page-link" href="javascript:void(0);">4</a></li>
                <li class="page-item"><a class="page-link" href="javascript:void(0);">5</a></li>
            </ul>
        </div>
    </div>
</div>
@stop
@section('page-script')

<script src="{{asset('assets/bundles/sparkline.bundle.js')}}"></script>
<script src="{{asset('assets/js/pages/charts/sparkline.js')}}"></script>
@stop
