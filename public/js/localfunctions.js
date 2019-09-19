function Calculate()
{
    var chequeamount = document.getElementById('chequeamount').value;
    var taxamount = document.getElementById('taxamount').value;
    var amount = parseFloat(chequeamount) + parseFloat(taxamount);
    document.getElementById('totalamount').value=parseFloat(amount);
}

$('input:radio[name=type]').change(function () {
    var value = $(this).val();
    console.log(value);
    if(value == 0)
    {
        $("#amount").addClass( "col-sm-4" );
        $("#amountdiv").addClass( "row" );
        $("#chequeinfo1,#chequeinfo2,#chequeinfo3,#chequename").removeClass("hidden");
        $("#chequeno,#chequedate,#taxamount").removeAttr("disabled");

    }
    else
    {
        $("#chequeinfo1,#chequeinfo2,#chequeinfo3,#chequename").addClass( "hidden" );
        $("#chequeno,#chequedate,#taxamount").attr("disabled", true);
        $("#amount").removeClass( "col-sm-4" );
        $("#amountdiv").removeClass( "row" );
    }
});

function vor () {
    var value = $('input:radio[name=vor]:checked').val();
    if(value === 'r')
    {

        $("#jon,#check").removeClass("hidden");
        $("#inlineRadio3").attr("checked");
        $("#confirmjobid").attr("onkeyup","confirmid()");
        $("#cvn,#coc").addClass( "hidden" );
        $("#jod").addClass( "col-sm-11" );
        $("#jor").addClass( "row" );

        // console.log($('input:radio[name=type]').val());
        if( $('input:radio[name=type]:checked').val() == 0)
        {
            $("#chequeinfo1,#chequeinfo2,#chequeinfo3,#chequename").addClass( "hidden" );
            $("#chequeno,#chequedate,#taxamount").attr("disabled", true);
            $("#amount").removeClass( "col-sm-4" );
            $("#amountdiv").removeClass( "row" );
        }
        $("#formtype").val("r");
        confirmid();
    }
    else
    {
        $("#jon,#check").addClass( "hidden" );
        $("#cvn,#coc").removeClass( "hidden" );
        $("#jod").removeClass( "col-sm-11" );
        $("#jor").removeClass( "row" );
        $("#submit").removeAttr("disabled");
        $("#confirmjobid").removeAttr("onkeyup");


        if( $('input:radio[name=type]:checked').val() == 0)
        {
            $("#chequeinfo1,#chequeinfo2,#chequeinfo3,#chequename").removeClass("hidden");
            $("#chequeno,#chequedate,#taxamount").removeAttr("disabled");
            $("#amount").addClass( "col-sm-4" );
            $("#amountdiv").addClass( "row" );
        }
        $("#formtype").val("v");
    }
}

function getjobids () {
    var id = $("#callfunc").val();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        statusCode: {
            500: function () {
                alert("Script exhausted");
            }
        },
        type: 'GET',
        url: '/customer-adjustment/ajax/get-invoice',
        data: {id: id},

        success: function (response) {

            console.log(response);
            jobids = ','+response[0]['jobid']+',';
            console.log(jobids);
            confirmid()
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(JSON.stringify(jqXHR));
            console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
        }
    });
}
 function confirmid () {
    var id =','+$("#confirmjobid").val()+',';
    if( jobids.search(id) !== -1) {
        $("#submit").removeAttr("disabled").removeClass("btn-default").addClass("btn-success");
        $(".pe-7s-check").css({ 'color': 'lightgreen' });
        // $(".submitbtn").removeClass("btn-default");
        // $(".submitbtn").addClass("btn-success");

    }
    else {
        $("#submit").attr("disabled", true).removeClass("btn-success").addClass("btn-default");
        $(".pe-7s-check").css({ 'color': '' });
        // $(".submitbtn").removeClass("btn-success");
        // $(".submitbtn").addClass("btn-default");
    }
}