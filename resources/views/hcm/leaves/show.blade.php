@extends('layout.master')
@section('title', 'Leave Detail')
@section('parentPageTitle', 'Hcm')

@section('page-style')
    <link rel="stylesheet" href="{{asset('public/assets/css/sw.css')}}">
    <link rel="stylesheet" href="{{asset('public/assets/css/list.css')}}">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
@endsection

@section('content')
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-body body">
                    <div class="">
                        <h5 class="font-bold">{{ $leave->leave_group }} <span class="text-muted">(For: {{ $leave->leave_for }})</span></h5>
                        <table class="table table-hover table-responsive ">
                            <thead>
                            <tr>
                                <th>Type</th>
                                <th>Count</th>
                                <th>Accural</th>
                                <th>Earned</th>
                                <th>Carry Forward</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($leaveTypes as $type)
                                <tr>
                                    <td>{{ $type->leave_type }}</td>
                                    <td>{{ $type->leave_count }}</td>
                                    <td>
                                        @if($type->leave_category == "Accural")
                                            <i class="text-success">Yes</i>
                                        @else
                                            <i class="text-danger">No</i>
                                        @endif
                                    </td>
                                    <td>
                                        @if($type->leave_category == "Earned")
                                            <i class="text-success">Yes</i>
                                        @else
                                            <i class="text-danger">NO</i>
                                        @endif
                                    </td>
                                    <td>
                                        @if($type->leave_category == "Carry_Forward")
                                            <i class="text-success">Yes</i>
                                        @else
                                            <i class="text-danger">NO</i>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('page-script')

@endsection

