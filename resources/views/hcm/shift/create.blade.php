@extends('layout.master')
@section('title', 'Create Shift')
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
                        <form action="{{ route('hcm.shifts.store') }}" method="POST" class="">
                            @csrf
                            <div class="col-12 row">
                                <div class="col-lg-4 mb-3">
                                    <div class="col-12 mb-3">
                                        <label class="form-check-label" for="dept_code">Shift Code</label>
                                        <input
                                            class="form-control"
                                            name="shift_code"
                                            id="shift_code"
                                            value="{{ old('shift_code') }}"
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
                                            value="{{ old('shift_name') }}"
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
                                        <tr class="py-2">
                                            <td>
                                                <div class="form-check mt-2">
                                                    <input class="form-check-input " type="checkbox" value="monday" name="monday_shift" id="monday_shift">
                                                    <label class="form-check-label" for="monday_shift">
                                                        Monday
                                                    </label>
                                                </div>
                                            </td>
                                            <td class="px-2">
                                                <input
                                                    class="form-control form-control-sm"
                                                    name="monday_start_time"
                                                    id="start_time"
                                                    value="{{ old('start_time') }}"
                                                    type="time" placeholder="Enter Start Time" />
                                            </td>
                                            <td class="px-2">
                                                <input
                                                    class="form-control form-control-sm"
                                                    name="monday_end_time"
                                                    id="end_time"
                                                    value="{{ old('end_time') }}"
                                                    type="time" placeholder="Enter End Time" />
                                            </td>
                                        </tr>
                                        <tr class="py-2">
                                            <td>
                                                <div class="form-check mt-2">
                                                    <input class="form-check-input " type="checkbox" value="tuesday" name="tuesday_shift" id="monday_shift">
                                                    <label class="form-check-label" for="monday_shift">
                                                        Tuesday
                                                    </label>
                                                </div>
                                            </td>
                                            <td class="px-2">
                                                <input
                                                    class="form-control form-control-sm"
                                                    name="tuesday_start_time"
                                                    id="start_time"
                                                    value="{{ old('start_time') }}"
                                                    type="time" placeholder="Enter Start Time" />
                                            </td>
                                            <td class="px-2">
                                                <input
                                                    class="form-control form-control-sm"
                                                    name="tuesday_end_time"
                                                    id="end_time"
                                                    value="{{ old('end_time') }}"
                                                    type="time" placeholder="Enter End Time" />
                                            </td>
                                        </tr>
                                        <tr class="py-2">
                                            <td>
                                                <div class="form-check mt-2">
                                                    <input class="form-check-input " type="checkbox" value="wednesday" name="wednesday_shift" id="monday_shift">
                                                    <label class="form-check-label" for="monday_shift">
                                                        Wednesday
                                                    </label>
                                                </div>
                                            </td>
                                            <td class="px-2">
                                                <input
                                                    class="form-control form-control-sm"
                                                    name="wednesday_start_time"
                                                    id="start_time"
                                                    value="{{ old('start_time') }}"
                                                    type="time" placeholder="Enter Start Time" />
                                            </td>
                                            <td class="px-2">
                                                <input
                                                    class="form-control form-control-sm"
                                                    name="wednesday_end_time"
                                                    id="end_time"
                                                    value="{{ old('end_time') }}"
                                                    type="time" placeholder="Enter End Time" />
                                            </td>
                                        </tr>
                                        <tr class="py-2">
                                            <td>
                                                <div class="form-check mt-2">
                                                    <input class="form-check-input " type="checkbox" value="thursday" name="thursday_shift" id="monday_shift">
                                                    <label class="form-check-label" for="monday_shift">
                                                        Thursday
                                                    </label>
                                                </div>
                                            </td>
                                            <td class="px-2">
                                                <input
                                                    class="form-control form-control-sm"
                                                    name="thursday_start_time"
                                                    id="start_time"
                                                    value="{{ old('start_time') }}"
                                                    type="time" placeholder="Enter Start Time" />
                                            </td>
                                            <td class="px-2">
                                                <input
                                                    class="form-control form-control-sm"
                                                    name="thursday_end_time"
                                                    id="end_time"
                                                    value="{{ old('end_time') }}"
                                                    type="time" placeholder="Enter End Time" />
                                            </td>
                                        </tr>
                                        <tr class="py-2">
                                            <td>
                                                <div class="form-check mt-2">
                                                    <input class="form-check-input " type="checkbox" value="friday" name="friday_shift" id="monday_shift">
                                                    <label class="form-check-label" for="monday_shift">
                                                        Friday
                                                    </label>
                                                </div>
                                            </td>
                                            <td class="px-2">
                                                <input
                                                    class="form-control form-control-sm"
                                                    name="friday_start_time"
                                                    id="start_time"
                                                    value="{{ old('start_time') }}"
                                                    type="time" placeholder="Enter Start Time" />
                                            </td>
                                            <td class="px-2">
                                                <input
                                                    class="form-control form-control-sm"
                                                    name="friday_end_time"
                                                    id="end_time"
                                                    value="{{ old('end_time') }}"
                                                    type="time" placeholder="Enter End Time" />
                                            </td>
                                        </tr>
                                        <tr class="py-2">
                                            <td>
                                                <div class="form-check mt-2">
                                                    <input class="form-check-input " type="checkbox" value="saturday" name="saturday_shift" id="monday_shift">
                                                    <label class="form-check-label" for="monday_shift">
                                                        Saturday
                                                    </label>
                                                </div>
                                            </td>
                                            <td class="px-2">
                                                <input
                                                    class="form-control form-control-sm"
                                                    name="saturday_start_time"
                                                    id="start_time"
                                                    value="{{ old('start_time') }}"
                                                    type="time" placeholder="Enter Start Time" />
                                            </td>
                                            <td class="px-2">
                                                <input
                                                    class="form-control form-control-sm"
                                                    name="saturday_end_time"
                                                    id="end_time"
                                                    value="{{ old('end_time') }}"
                                                    type="time" placeholder="Enter End Time" />
                                            </td>
                                        </tr>
                                        <tr class="py-2">
                                            <td>
                                                <div class="form-check mt-2">
                                                    <input class="form-check-input " type="checkbox" value="sunday" name="sunday_shift" id="monday_shift">
                                                    <label class="form-check-label" for="monday_shift">
                                                        Sunday
                                                    </label>
                                                </div>
                                            </td>
                                            <td class="px-2">
                                                <input
                                                    class="form-control form-control-sm"
                                                    name="sunday_start_time"
                                                    id="start_time"
                                                    value="{{ old('start_time') }}"
                                                    type="time" placeholder="Enter Start Time" />
                                            </td>
                                            <td class="px-2">
                                                <input
                                                    class="form-control form-control-sm"
                                                    name="sunday_end_time"
                                                    id="end_time"
                                                    value="{{ old('end_time') }}"
                                                    type="time" placeholder="Enter End Time" />
                                            </td>
                                        </tr>

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
        </div>
    </div>

@endsection

@section('page-script')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Function to add a new shift day and time
            function addNewShiftDayTime() {
                let shiftDayTime = document.querySelector('.shiftDayTime'); // Selecting the parent container
                let newShiftDayTime = shiftDayTime.cloneNode(true); // Cloning the first shift day and time element
                shiftDayTime.parentNode.appendChild(newShiftDayTime); // Appending the cloned element after the original one
            }

            // Event listener for the button
            document.getElementById('addNewShiftDayTime').addEventListener('click', addNewShiftDayTime);
        });
    </script>
@endsection

