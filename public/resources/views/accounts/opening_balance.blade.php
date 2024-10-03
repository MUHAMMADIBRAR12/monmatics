@extends('layout.master')
@section('title', 'Opening Balance')
@section('parentPageTitle','Accounts')
@section('parent_title_icon', 'zmdi zmdi-home')
@section('page-style')
<?php  use App\Libraries\appLib; ?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<!--<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">-->
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<link rel="stylesheet" href="{{asset('public/assets/plugins/dropify/css/dropify.min.css')}}"/>
@stop
@section('content')
    <div class="row clearfix">
        <div class="card col-lg-12">
            <div class="body">
            <form method="post" action="{{url('Accounts/OpeningBalance/Add')}}" autocomplete="off">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-lg-3">
                            <label>Group Accounts</label>
                            <select name="group_account" id="group_account" class="form-control show-tick ms select2" data-placeholder="Select" required>  
                            <option>Select Group Account</option>
                            <option value="-1">All</option>
                            @foreach($accounts as $account)
                            <option value="{{$account->id}}">{{$account->name}}</option>
                            @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>Total Debit</label>
                            <br>
                            <label id="total_debit"></label>
                        </div>
                        <div class="col-md-2">
                            <label>Total Credit</label>
                            <br>
                            <label id="total_credit"></label>
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="table-responsive">
                            <table id="opening_balance_table" class="table table-striped m-b-0">
                                <thead>
                                    <tr class="bg-purple">
                                        <th>Sr.</th>
                                        <th >Accounts</th>
                                        <th class="table_header" style="display:none">Rate</th>
                                        <th class="table_header" style="display:none">Currency</th>
                                        <th>Debit</th>
                                        <th>Credit</th>
                                       
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>  
                            </table>
                        </div>
                    </div>
                    <div class="row">                        
                        <div class="ml-auto">
                            <button class="btn btn-raised btn-primary waves-effect" type="submit" id="submit-btn">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
@section('page-script')
<script src="{{asset('public/assets/plugins/dropify/js/dropify.min.js')}}"></script>
<script src="{{asset('public/assets/js/pages/forms/dropify.js')}}"></script>
<script src="{{asset('public/assets/js/sw.js')}}"></script>
<script>
$(document).ready(function(){
    $('#group_account').on('change',function(){
        $('.rowData').html('');
        var id=$(this).val();
        var url = "{{ url('Accounts/getOpeningBalance')}}";
        var token =  "{{ csrf_token()}}";
        $.post(url,{coa_id:id, _token:token},function(data){
            console.log(data);
            list(data);
        });
       
    });
});

function list(data)
{  
    var i=1;
    //console.log(data);
    //console.log(data[1]);
    //console.log(data[0]['name']);
    data.map(function(row){
        if(Array.isArray(row))
        {
            list(row);
        }
    
        else
        {
            // if(i ==0)
            // {
            // i =1;
            // }
           /* if(row.debit == null)
            {
                num=0;
            }
            else
            {
                num=row.debit;
            } */
            var row ='<tr id="row" class="rowData">'+
                        '<td>'+i+'</td>'+
                        '<td>'+row.name+'<input type="hidden" name="coa_id[]" value="'+row.coa_id+'">'+
                        '</td>'+
                        '<td> <input type="number" name="debit[]" class="form-control qty debit" value="'+row.debit+'" onblur="total()"></td>'+
                        '<td> <input type="number" name="credit[]" class="form-control qty credit" value="'+row.credit+'" onblur="total()"></td>'+
                    '</tr>';
            $('#opening_balance_table').append(row);
            $('#total_debit').html(sumAll('debit'));
            $('#total_credit').html(sumAll('credit'));
            i++;
        }
    });
}
function total()
{
    $('#total_debit').html(sumAll('debit'));
    $('#total_credit').html(sumAll('credit'));
}

</script>
@stop