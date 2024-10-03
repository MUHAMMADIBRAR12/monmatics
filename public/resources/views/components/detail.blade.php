<!-- opportunities -->
<div class="flip">
    Opportunities
    <i class="zmdi zmdi-hc-fw float-right" id="flip1" style="cursor:pointer"></i>
</div>
<div class="panel" id="panel1">
    <table class="table table-striped m-b-0">
        <thead>
            <tr>
                <th>Edit</th>
                <th>Opportunity Name</th>
                <th>Account Name</th>
                <th>Lead Type</th>
                <th>Sale stage</th>
                <th>Assigned User</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($opportunities as $opportunity)
                <tr>
                    <td><button class="btn btn-primary btn-sm"
                            onclick="window.location.href = '{{ url('Crm/Opportunities/' . $opportunity->id) }}';"><i
                                class="zmdi zmdi-edit"></i></button> </td>
                    <td>{{ $opportunity->name }}</td>
                    <td></td>
                    <td>{{ $opportunity->lead_type }}</td>
                    <td>{{ $opportunity->sale_stage }}</td>
                    <td>{{ $opportunity->user_name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<!-- Calls -->
<div class="flip">
    Calls
    <i class="zmdi zmdi-hc-fw float-right" id="flip2" style="cursor:pointer"></i>
</div>
<div class="panel" id="panel2">
    <table class="table table-striped m-b-0">
        <thead>
            <tr>
                <th>Edit</th>
                <th>Subject</th>
                <th>Start Date</th>
                <th>Related To</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($calls as $call)
                <tr>
                    <td> <button class="btn btn-primary btn-sm"
                            onclick="window.location.href = '{{ url('Crm/Call/' . $call->id) }}';"><i
                                class="zmdi zmdi-edit"></i></button>
                    </td>
                    <td>{{ $call->subject }}</td>
                    <td>{{ $call->start_date }}</td>
                    <td>{{ $call->related_to_type }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<!-- Task -->
<div class="flip">
    Task
    <i class="zmdi zmdi-hc-fw float-right" id="flip3" style="cursor:pointer"></i>
</div>
<div class="panel" id="panel3">
    <table class="table table-striped m-b-0">
        <thead>
            <tr>

                <th>Edit</th>
                <th>Subject</th>
                <th>Start Date</th>
                <th>Priority</th>
                <th>Status</th>
                <th>Assigned To</th>

            </tr>
        </thead>
        <tbody>
            @foreach ($tasks as $task)
                <tr>
                    <td><button class="btn btn-primary btn-sm"
                            onclick="window.location.href = '{{ url('Crm/Tasks/' . $task->id) }}';"><i
                                class="zmdi zmdi-edit"></i></button> </td>
                    <td>{{ $task->subject }}</td>
                    <td>{{ $task->start_date }}</td>
                    <td>{{ $task->priority }}</td>
                    <td>{{ $task->status }}</td>
                    <td>{{ $task->user_name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
