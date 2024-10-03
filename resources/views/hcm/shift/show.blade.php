@extends('layout.master')
@section('title', 'Shift Detail')
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
                        <h5 class="font-bold">Shift Name: <span class="text-primary">{{ $shifts->first()->shift_name }}</span> <span class="text-muted">({{ $shifts->first()->shift_code }})</span></h5>
                        <table class="table table-hover table-responsive ">
                            <thead>
                            <tr>
                                <th>Day</th>
                                <th>Start Time</th>
                                <th>End Time</th>
{{--                                <th>Active</th>--}}
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($shifts as $shift)
                                <tr class="{{ $shift->active ? 'bg-success' : 'bg-danger' }}">
                                    <td>{{ ucfirst($shift->day) }}</td>
                                    <td>
                                        @if($shift->start_time)
                                            {{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }}
                                        @else

                                            {{ $shift->start_time }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($shift->end_time)
                                            {{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }}
                                        @else

                                            {{ $shift->end_time }}
                                        @endif
                                    </td>
{{--                                    <td>{{ $shift->active ? 'Yes' : 'No' }}</td>--}}
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

