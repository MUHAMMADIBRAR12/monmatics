@extends('layout.master')
@section('title', 'Salary Detail')
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
                        <h5 class="font-bold">{{ $salary->salary_name }} <span class="text-muted">(For

                                @foreach($designations as $designation)
                                    @if($designation->id == $salary->salary_for)
                                        {{ $designation->designation }}
                                    @endif
                                @endforeach
                                                        )</span> Basic: <span class="text-primary">{{ $salary->basic_salary }}</span></h5>
                        <div class="row col-lg-12 gap-5">
                            <div class="col-lg-5">
                                <h5 class="rounded text-white p-1 " style="background-color: #0c7ce6;">Allowances</h5>

                                <table class="table table-hover table-responsive">
                                    <thead>
                                    <tr>
                                        <th>Allowance</th>
                                        <th>Amount</th>
                                        <th>Percentage</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($allowances as $allowance)
                                        <tr>
                                            <td>{{ $allowance->allowance_name }}</td>
                                            <td>
                                                @if($allowance->allowance_amount)
                                                    {{ $allowance->allowance_amount }}
                                                @endif
                                            </td>
                                            <td>
                                                @if($allowance->allowance_percentage)
                                                    {{ $allowance->allowance_percentage }}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                            </div>
                            <div class="col-lg-5">
                                <h5 class="rounded text-white p-1 " style="background-color: #0c7ce6;">Deduction</h5>

                                <table class="table table-hover table-responsive">
                                    <thead>
                                    <tr>
                                        <th>Deduction</th>
                                        <th>Amount</th>
                                        <th>Percentage</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($deductions as $deduction)
                                        <tr>
                                            <td>{{ $deduction->deduction_name }}</td>
                                            <td>
                                                @if($deduction->deduction_amount)
                                                    {{ $deduction->deduction_amount }}
                                                @endif
                                            </td>
                                            <td>
                                                @if($deduction->deduction_percentage)
                                                    {{ $deduction->deduction_percentage }}
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
        </div>
    </div>

@endsection

@section('page-script')

@endsection

