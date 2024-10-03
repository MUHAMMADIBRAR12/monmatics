@extends('layout.master')
@section('title', 'Edit Shift')
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
                <form action="{{ route('hcm.shift.update' , $shifts->first()->id) }}" method="POST" class="">
                    @csrf
                    @method('PUT')

                    <div class="col-12 row">
                        <div class="col-lg-4 mb-3">
                            <div class="col-12 mb-3">
                                <label class="form-check-label" for="dept_code">Shift Code</label>
                                <input
                                    class="form-control"
                                    name="shift_code"
                                    id="shift_code"
                                    value="{{ $shifts->first()->shift_code }}"
                                    type="text" placeholder="Enter Shift Code" />
                                @error('shift_code')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-check-label" for="name">Shift Name</label>
                                <input
                                    class="form-control"
                                    name="shift_name"
                                    id="shift_name"
                                    value="{{ $shifts->first()->shift_name }}"
                                    type="text" placeholder="Enter Shift Name" />
                                @error('shift_name')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-8 mb-3">
                            <table class="table table-hover table-responsive">
                                <thead>
                                <tr>
                                    <td>Day</td>
                                    <td>In Time</td>
                                    <td>Out Time</td>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day)
                                    <tr class="py-2">
                                        <td>
                                            <div class="form-check mt-2">
                                                <input class="form-check-input"
                                                       {{ $shifts->where('day', $day)->where('active', 1)->isNotEmpty() ? 'checked' : '' }}
                                                       type="checkbox" value="{{ $day }}" name="{{ strtolower($day) }}_shift"
                                                       id="{{ strtolower($day) }}_shift">
                                                <label class="form-check-label" for="{{ strtolower($day) }}_shift">
                                                    {{ ucfirst($day) }}
                                                </label>
                                            </div>
                                        </td>
                                        <td class="px-2">
                                            <input class="form-control form-control-sm" name="{{ strtolower($day) }}_start_time"
                                                   id="start_time_{{ strtolower($day) }}"
                                                   value="{{ $shifts->where('day', $day)->first()->start_time ?? '' }}"
                                                   type="time" placeholder="Enter Start Time"/>
                                        </td>
                                        <td class="px-2">
                                            <input class="form-control form-control-sm" name="{{ strtolower($day) }}_end_time"
                                                   id="end_time_{{ strtolower($day) }}"
                                                   value="{{ $shifts->where('day', $day)->first()->end_time ?? '' }}"
                                                   type="time" placeholder="Enter End Time"/>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>

                </form>

        </div>
    </div>

@endsection

@section('page-script')

@endsection

