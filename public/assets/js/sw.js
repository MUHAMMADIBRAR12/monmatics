/* 
 * The app core rights are with Solutions Wave. 
 * For further help you can contact with info@solutionswave.com
 * All content of project are copyright with Solutions Wave.
 
 */
/////////////// note //////////
//
//      var searchCoaURL = "{{ url('coaSearch')}}";
//      var CurrencyURL = "{{ url('getCurrencyRate') }}";
//      var token = "{{ csrf_token()}}";
//
//////////

function parseString(value)
{
    return  value.replace(/\'/g, "'/");
    
}


function delattach(url,attachment,token)        // old delete attachment, may be removed later. 
{
    $.post(url,{ attachment: attachment, _token : token }, function (data){
        $('#attachment').html('');
    });
}

function deleteFile(url,id,token)   // This function is created after attachmenet saved to sys_attachment. 
{
    $.post(url,{ id: id, _token : token }, function(data){
        $('#attachment').html('');
    });
}


function autoFill(textId, url, token)
{
    
    
    $( "#"+textId ).autocomplete({
 
        source: function(request, response) 
            {
                  
                $.post(url,{ name: $( "#"+textId ).val(), _token : token }, function (data){
                    response( data );     
                }, "json");
                
            },
        select: function (event, ui) {
           // Set selection
           $('#'+textId).val(ui.item.label); // display the selected text
           $('#'+textId+'_ID').val(ui.item.value); // save selected id to input
           return false;
        }
    });
   
}

//customer detail
function getCustomerDetail(id,url,token)
{
    coa_id = $('#'+id+'_ID').val();
    $.post(url,{coa_id:coa_id, _token:token}, function (data){
        $('#cust_phone_label').html(data.phone);    
        $('#cust_phone').val(data.phone);    
        $('#cust_address_label').html(data.address);    
        $('#cust_address').val(data.address);    
        $('#cust_location_label').html(data.location);    
        $('#cust_location').val(data.location); 
        if(data.stn=='0' || data.stn==null )
            $('#cust_stn').val(1);
        else
            $('#cust_stn').val(0);
    });
}
// Delete row from grid
function deleteRow(id)
{
    if(confirm('Do you want to delete?', 'OfDesk'))
        $('#'+id).remove();
}

// CurId is dropdown Id and rateId is Rate element id
function getCurrencyRate(curId, rateId, url, token)
{
    curCode = $('#'+curId).val();
    $.post(url,{ code: curCode, _token : token }, function (data){
        $('#'+rateId).val(data);        
    });

}


function getTaxRate(url, taxName, token)
{
    $.post(url,{ taxName: taxName, _token : token }, function (data){
       return getNum(data);
    });
    
}

function showBalance(value)
{
    if(value < 0)
       var  showBalance='('+currencyPatrn(value)+')';
    else
      var   showBalance=currencyPatrn(value);
    return  showBalance;
}

/////////////////////////////////////// Maths.js //////////////////////////////////////////

// Send text object class
// return total sum
function sumAll(tclass)
{
    var totalPrice = 0;
    $("."+tclass).each(function(i, td) {
        if($.isNumeric($(td).val()))
            totalPrice += parseFloat($(td).val());
    });    
    return totalPrice.toFixed(2);    
}


function getNum(val, decimal=2)
{
  // alert(val);
    if(decimal)
        val=parseFloat(val).toFixed(decimal);
    else
        val = parseFloat(val);
     
    return val;
}

function getPercent(val, totalVal)
{
    
}

function setEmpty(val)
{   
    console.log(typeof val);
    if(typeof val=='object')
        return '';
    else
        return val;
}

function removeNull(val)
{
    if(val==0)
        return val
    
    
    
    if(val)
        return val
    else
        return '0'
}

function percentVal (percentage, totalVal, decimal=null)
{

    percentAmount = getNum(percentage)*getNum(totalVal)/getNum(100);
    if(decimal)
        percentAmount.toFixed(decimal);
    
    return percentAmount
}

// input: Qty & Rate
// output:  Amount
function QtyRateAmount(qty, rate, object=null)
{
   amount = getNum(qty)*getNum(rate);
   if(object)
       $('#'+object).val(amount);
   else
       return amount;
}

// input: Qtyid & Rateid
// output:  Amount
function QtyRateAmountbyId(qtyId, rateId, object=null)
{
    
    var amount = getNum($('#'+qtyId).val())*getNum($('#'+rateId).val());
   if(object)
       $('#'+object).val(amount);
   else
       return amount;
}

function checkWarehouse(textId=null)
{
    let warehouse=$('#warehouse').val();
    if(warehouse ==='')
    {
        alert('please Select warehouse first');
       /* var content='<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">'+
        '<div class="modal-dialog" role="document">'+
          '<div class="modal-content">'+
            '<div class="modal-header">'+
             '<h5 class="modal-title" id="exampleModalLabel">Modal title</h5>'+
              '<button type="button" class="close" data-dismiss="modal" aria-label="Close">'+
                '<span aria-hidden="true">&times;</span>'+
              '</button>'+
            '</div>'+
            '<div class="modal-body">'+
            '</div>'+
           '<div class="modal-footer">'+
              '<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>'+
              '<button type="button" class="btn btn-primary">Save changes</button>'+
            '</div>'+
          '</div>'+
        '</div>'+
     '</div>';
         $('#body').html(content); */
         $("option:selected").prop("selected", false)
        $( "#"+textId ).val('');
        
    }
}

// Related to search box
    $('#related_to').on('change',function(){
        var related_to=$(this).val();
        $('.related').attr("id",related_to);
        $('.related_ID').attr("id",`${related_to}_ID`);
        related_to_url=`${related_to}Search`;
        console.log(related_to);
    });


// subtract value
function subNumber(num1, num2, returnId=null)
{

    var SubVal;
    SubVal = getNum(num1)-getNum(num2);
    if(returnId)
        $('#'+returnId).val(getNum(SubVal)) ;
    else    
        return getNum(SubVal);

  
   
}

// this function check data is exist in db or not 
function checkDataExist(url,token,text)
{
    check_value=$('#'+text).val();
    $.post(url,{check_value :check_value, _token : token }, function (data){
        if(data!="")
        {
            $('#error_msg').html(data);
            $('.save').hide(); 
        }
        $('#'+text).on('keydown', function(e) {
            if( e.which == 8 || e.which == 46 )
            {
                
                $('#error_msg').html(''); 
                $('.save').show(); 
            }
        });    
        
    });
}

function currencyPatrn(num)
{
    if(num < 0)
    {
        num *=-1;
    }
    var currency = new Intl.NumberFormat('en-US', { 
    style: 'currency',
    currency:'USD',
    currencyDisplay: 'narrowSymbol',
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
    }).format(num);
    //return currency;
    return num;
    
}
//for curency
// Jquery Dependency

$("input[data-type='currency']").on({
    keyup: function() {
      formatCurrency($(this));
    },
    blur: function() { 
      formatCurrency($(this), "blur");
    }
});


function formatNumber(n) {
  // format number 1000000 to 1,234,567
  return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
}


function formatCurrency(input, blur) {
  // appends $ to value, validates decimal side
  // and puts cursor back in right position.
  
  // get input value
  var input_val = input.val();
  
  // don't validate empty input
  if (input_val === "") { return; }
  
  // original length
  var original_len = input_val.length;

  // initial caret position 
  var caret_pos = input.prop("selectionStart");
    
  // check for decimal
  if (input_val.indexOf(".") >= 0) {

    // get position of first decimal
    // this prevents multiple decimals from
    // being entered
    var decimal_pos = input_val.indexOf(".");

    // split number by decimal point
    var left_side = input_val.substring(0, decimal_pos);
    var right_side = input_val.substring(decimal_pos);

    // add commas to left side of number
    left_side = formatNumber(left_side);

    // validate right side
    right_side = formatNumber(right_side);
    
    // On blur make sure 2 numbers after decimal
    if (blur === "blur") {
      right_side += "00";
    }
    
    // Limit decimal to only 2 digits
    right_side = right_side.substring(0, 4);

    // join number by .
    input_val = "$" + left_side + "." + right_side;

  } else {
    // no decimal entered
    // add commas to number
    // remove all non-digits
    input_val = formatNumber(input_val);
    input_val = "$" + input_val;
    
    // final formatting
    if (blur === "blur") {
      input_val += ".00";
    }
  }
  
  // send updated string to input
  input.val(input_val);

  // put caret back in the right position
  var updated_len = input_val.length;
  caret_pos = updated_len - original_len + caret_pos;
  input[0].setSelectionRange(caret_pos, caret_pos);
}



$('input').attr('autocomplete','off');

function leftPad(value, length=4) { 
    return ('0'.repeat(length) + value).slice(-length); 
}

/* currency format

var format = function(num){
	var str = num.toString().replace("Rs", ""), parts = false, output = [], i = 1, formatted = null;
	if(str.indexOf(".") > 0) {
		parts = str.split(".");
		str = parts[0];
	}
	str = str.split("").reverse();
	for(var j = 0, len = str.length; j < len; j++) {
		if(str[j] != ",") {
			output.push(str[j]);
			if(i%3 == 0 && j < (len - 1)) {
				output.push(",");
			}
			i++;
		}
	}
	formatted = output.reverse().join("");
	return("Rs" + formatted + ((parts) ? "." + parts[1].substr(0, 5) : ""));
};
$(function(){
    $("#currency").keyup(function(e){
        $(this).val(format($(this).val()));
    });
});
$('#currency').blur(function(){
    var text = $(this).val();
    var number = Number(text .replace(/[^0-9.-]+/g,""));
    console.log(number);
})
*/