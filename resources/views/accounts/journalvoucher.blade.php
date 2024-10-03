@extends('layout.master')
@section('title', 'Voucher')
@section('parentPageTitle', 'Accounts')
@section('parent_title_icon', 'zmdi zmdi-home')
@section('page-style')

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<link rel="stylesheet" href="{{asset('public/assets/plugins/dropify/css/dropify.min.css')}}"/>
<style>
    .amount{
        width: 150px;
        text-align: right;
    }
    .table td{
        padding: 0.10rem;
    }
    .dropify
    {
        width: 200px;
        height: 200px;
    }
</style>
<script lang="javascript/text">

var rowId=1;
var searchCoaURL = "{{ url('coaSearch')}}";
var CurrencyURL = "{{ url('getCurrencyRate') }}";
var projectURL = "{{ url('projectSearch') }}";
var token = "{{ csrf_token()}}";


function addRow()
{
       rowId++;
       var row ='<tr id="row'+rowId+'">'+
                    '<td>'+
                        '<button  type="button" class="btn btn-danger btn-sm" onclick="deleteRow(\'row'+rowId+'\');totalAmount()"><i class="zmdi zmdi-delete"></i></button>'+
                    '</td>'+

                    '<td>'+
                        '<input type="number" step="any" name="code[]" id="code" class="form-control" value="">'+
                    '</td>'+
                    '<td>'+
                        '<input type="text" name="name[]" id="name'+rowId+'" class="form-control autocomplete" onkeyup="autoFill(this.id, searchCoaURL, token)" value="" required>'+
                        '<input type="hidden" name="name_ID[]" id="name'+rowId+'_ID" required>'+
                    '</td>'+
                    '<td>'+
                        '<input type="text" name="description[]"  class="form-control" value="">'+
                    '</td>'+
                    '<td>'+
                        '<input type="number" name="debit[]" class="amount debit form-control"  step="any" value="0" onblur="totalAmount()" required>'+
                    '</td>'+
                    '<td>'+
                        '<input type="number" name="credit[]" class="amount credit form-control"  step="any" value="0" onblur="totalAmount()" required>'+
                    '</td>'+

//                    '<td>'+
//                        '<input type="text" name="cost_center[]" id="cost_center'+rowId+'" class="form-control" onkeyup="autoFill(this.id, projectURL, token)">'+
//                        '<input type="hidden" name="cost_center_ID[]" id="cost_center'+rowId+'_ID" required>'+
//                    '</td>'+
                '</tr>';
        $('#voucher').append(row);
}


$(document).ready(function(){
    $("form").submit(function(){

        if(totalAmount()==false)
        {
            alert('Total of Debit must be equal to sum of Credit');
            return false;
        }
    });
});





// // CurId is dropdown Id and rateId is Rate element id
// function getCurrencyRate(curId, rateId)
// {
//    curCode = $('#'+curId).val();
//    $.post("{{ url('getCurrencyRate') }}",{ code: curCode, _token : "{{ csrf_token()}}" }, function (data){
//        $('#'+rateId).val(data);
//    });

// }
function totalAmount()
{
    totalDebit = sumAll('debit');
    totalCredit = sumAll('credit');
    $('#total_debit').html('{{ session("symbol") }}'+totalDebit+'/-');
    $('#total_credit').html('{{ session("symbol") }}'+totalCredit+'/-');

    if(parseFloat(totalDebit)==parseFloat(totalCredit))
        return true;
    else
        return false;

}
////////////////////////////////////// following section will move to application Js class  for generic use ///////////////////////
function sumAll(tclass)
{
    var totalPrice = 0;
    $("."+tclass).each(function(i, td) {
        if($.isNumeric($(td).val()))
            totalPrice += parseFloat($(td).val());
    });
    return totalPrice.toFixed(2);
}



function deleteFileA(id, num)
{
    var x = confirm("Are you sure you want to delete?");
    if(x)
    {
        var url = '{{url("delete/")}}';
        deleteFile(url,id,token);
        $('#attRow'+num).html('');
     }
 }




//function autoFill(textId)
//{
//    $( "#"+textId ).autocomplete({
//
//        source: function(request, response)
//                {
//                    $.ajax({
//                    url: "{{url('coaSearch')}}",
//                    data: {
//                            name : request.term,
//                            _token : ''
//                     },
//                    dataType: "json",
//                    success: function( data ) {
//                        response( data );
//                     }
//                });
//            },
//        select: function (event, ui) {
//           // Set selection
//           $('#'+textId).val(ui.item.label); // display the selected text
//           $('#'+textId+'_ID').val(ui.item.value); // save selected id to input
//           return false;
//        }
//    });
//}
//function deleteRow(id)
//{
//    if(confirm('Do you want to delete?', 'OfDesk'))
//        $('#'+id).remove();
//}

/////////////////////////////////////////////// end //////////////////////////////////
</script>

@stop
<?php
//    echo "<pre>";
//    print_r($attachments);
//
//    die();

?>
@section('content')

    <div class="row clearfix">
        <div class="card col-lg-12">
            <div class="header">
                <h2><strong>{{ $info['title'] }}</strong> Voucher</h2>
            </div>
            @if(session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
            @endif
            @if(session()->has('error'))
            <div class="alert alert-danger">
                {{ session()->get('error') }}
            </div>
        @endif
            <div class="body">
                <form method="post" action="{{url('Accounts/journalVoucherSave')}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $transmain->id ?? ''  }}">
                    <input type="hidden" name="type" value="{{ $info['type'] ?? ''  }}">
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <label for="date">Date</label>
                            <div class="form-group">
                                @php $toDate = date('m/d/Y'); @endphp
                                <input type="date" name="date" id='vdate'  class="form-control" value="{{ $transmain->date ?? date("Y-m-d")  }}"  required>
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-6 multicurrency">
                            <label for="fiscal_year">Currency</label>
                            <div class="form-group">
                                <select name="cur_id" id="cur_id" class="form-control show-tick ms select2" data-placeholder="Select" onchange="getCurrencyRate(this.id, 'rate');">
                                    <option value="">--Select--</option>
                                    @foreach($currencies as $cur)
                                        <option {{ ( $cur->code == ($cashAccountDetail->cur_id ??'')) ? 'selected' : '' }} value="{{ $cur->id }}">{{  $cur->code  }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 multicurrency">
                            <label for="rate">Rate</label>
                            <div class="form-group">
                                <input type="number" step="any" name="rate" id='rate'   class="form-control" value="{{ number_format($cashAccountDetail->cur_rate ?? '1',2)   }}" size='9' maxlength="9" required style="text-align: end;">
                            </div>
                        </div>


<!--                        <div class="col-lg-2 col-md-6">
                            <label for="code">Voucher No.</label>
                            <div class="form-group">
                                {{$transmain->tmnumber ?? '' }}
                            </div>
                        </div>-->

                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <label for="fiscal_year">Total Debit</label>
                            <label id="total_debit"></label>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label for="fiscal_year">Total Credit</label>
                            <label id="total_credit"></label>
                        </div>

                    </div>
                    <div class="row">
                        <div class="table-responsive">
                            <table id="voucher" class="table table-striped m-b-0">
                                <thead>
                                    <tr class="bg-purple">
                                        <th><button type="button" class="btn btn-primary" style="align:right" onclick="addRow();" >+</button></th>
                                        <th>A/c Code</th>
                                        <th>Account Name</th>
                                        <th>Narration</th>
                                        <th>Debit</th>
                                        <th>Credit</th>
                                       <!-- <th>Cost Center</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                @php
                                $count = (isset($transDetail))?count($transDetail):1;
                                for($i=1;$i<=$count; $i++)
                                {
                                    if(isset($transDetail))
                                    {
                                        $trans = $transDetail[($i-1)];
                                        $debit = $trans->debit;
                                        $credit = $trans->credit;
                                        $currency = $trans->cur_id;
                                        $rate = $trans->cur_rate;
                                    }
                                @endphp
                                    <tr id="row{{$i}}">
                                        <td><button  type="button" class="btn btn-danger btn-sm" onclick="deleteRow('row{{$i}}');totalAmount();"><i class="zmdi zmdi-delete"></i></button></td>

                                        <td class="account_code"><input type="number" name="code[]" id="code" class="form-control" value="{{ $trans->code ?? ''  }}"></td>

                                        <td class="autocomplete"><input type="text" name="name[]" id="name{{$i}}" onkeyup="autoFill(this.id, searchCoaURL, token)" value="{{ $trans->name ?? ''  }}" class="form-control autocomplete" required>
                                            <input type="hidden" name="name_ID[]" id="name{{$i}}_ID" value="{{ $trans->coa_id ?? ''  }}" required>
                                        </td>
                                        <td class=""><input type="text" name="description[]"  class="form-control" value="{{ $trans->description ?? '' }}"></td>
                                        <td><input type="number" name="debit[]" class="form-control amount debit" step="any" value="{{ $debit ?? 0  }}" onblur="totalAmount();" required></td>
                                        <td><input type="number" name="credit[]" class="form-control amount credit" step="any" value="{{ $credit ?? 0  }}" onblur="totalAmount();" required></td>
                                        <!--
                                        <td>

                                            <input type="text" name="cost_center[]" id="cost_center{{$i}}" class="form-control" onkeyup="autoFill(this.id, projectURL, token)">
                                            <input type="hidden" name="cost_center_ID[]" id="cost_center{{$i}}_ID">
                                        </td>
                                        -->
                                    </tr>
                                @php } @endphp
                                </tbody>
                                <script>

                                    rowId = {{$i}};
                                    // Set Currency
                                    $('#cur_id').val("{{ $currency ?? '' }}");
                                    $('#rate').val("{{ $rate ?? 1 }}");
                                    totalAmount();
                                </script>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <label for="fiscal_year">Note</label>
                            <div class="form-group" id="note">
                                <textarea name="note" maxlength="120" rows="4" class="form-control no-resize"  placeholder="">{{ $transmain->note ?? '' }}</textarea>
                            </div>
                        </div>
                        <div class="col-sm-6 pr-0">
                            <label for="fiscal_year">Attachment</label>
                            <br>
                            @if($attachmentRecord ?? '')
                                <table>
                                    @php $i=0 ; @endphp
                                @foreach($attachmentRecord as $attachment)
                                    @php $i++ ; @endphp
                                <tr id='attRow{{ $i}}'>
                                    <td>
                                        <button  type="button" class="btn btn-danger btn-sm" onclick="deleteFileA('{{$transmain->id}}', {{ $i}})"><i class="zmdi zmdi-delete"></i></button>
                                    </td>
                                    <td>
                                        <a href="{{url('download/'. $transmain->id)}}" download>{{ $attachment->file }}</a>
                                    </td>
                                </tr>
                                @endforeach
                                </table>
                            @endif
                            <input  name="file" type="file" class="dropify">
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="ml-auto">
                            <input type="checkbox" name="print" id="print" class="ml-2">
                            <label>Print</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="ml-auto">
                            <button class="btn btn-raised btn-primary waves-effect" type="submit">Save</button>
<!--                            <button class="btn btn-raised btn-primary waves-effect" type="submit">Save & New</button>-->
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
@stop
