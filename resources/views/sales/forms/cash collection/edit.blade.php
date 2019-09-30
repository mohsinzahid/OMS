@extends('admin.master')

@section('content')
    <div class="container-fluid">


        <div class="row">
            <div class="col-lg-12">
                <div class="view-header">
                    <div class="pull-right text-right" style="line-height: 14px">
                        @if ($payment -> job_order_no)
                            <div class="radio radio-info radio-inline">
                                <input type="radio" id="inlineRadio1" onchange='vor()' value="v" name="vor">
                                <label for="inlineRadio1"> Voucher </label>
                            </div>
                            <div class="radio radio-inline">
                                <input type="radio" id="inlineRadio2" value="r" onchange='vor()' name="vor" checked="">
                                <label for="inlineRadio2"> Receipt </label>
                            </div>
                        @else
                            <div class="radio radio-info radio-inline">
                                <input type="radio" id="inlineRadio1" value="v" onchange='vor()' name="vor" checked="">
                                <label for="inlineRadio1"> Voucher </label>
                            </div>
                            <div class="radio radio-inline">
                                <input type="radio" id="inlineRadio2" value="r" onchange='vor()' name="vor">
                                <label for="inlineRadio2"> Receipt </label>
                            </div>
                        @endif

                    </div>
                    <div class="header-icon">
                        <i class="pe page-header-icon pe-7s-pen"></i>
                    </div>
                    <div class="header-title">
                        <h3>Edit Payment</h3>
                    </div>
                    <div style="float: right">
                        <a href="/cash-collection/{{$payment->id}}/delete" class="btn btn-w-md btn-danger">Delete</a>
                    </div>
                </div>
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3"></div>
            <div class="col-sm-6">
                <div class="header-title">
                    @if($msg)
                        <h3>Good Job!</h3>
                    @endif
                </div>
                <div class="panel panel-filled">
                    <div class="panel-body">
                        <form method="post" action="{{ url('/cash-collection/updatepayment') }}" autocomplete="off">
                            {{ csrf_field() }}

                            @if ($payment -> job_order_no)
                                <input name="formtype" value="r" id="formtype" class="hidden">
                            @else
                                <input name="formtype" value="v" id="formtype" class="hidden">
                            @endif


                            <div class="form-group">
                                <label>Customer</label>
                                <select class="select2_demo_2 form-control" name="customerid" autofocus
                                        id="callfunc" style="width: 100%" required>
                                    <option value="" selected disabled hidden>Choose here</option>
                                    @if(count($customer)>0)
                                        @foreach($customer as $customers)
                                            @if($customers->id === $payment->customer_id)
                                                <option selected value="{{$customers->id}}">{{$customers->id}} &emsp; {{$customers->name}}</option>
                                            @else
                                                <option value="{{$customers->id}}">{{$customers->id}} &emsp; {{$customers->name}}</option>
                                            @endif
                                        @endforeach
                                    @else
                                        <option>No Customer exist</option>
                                    @endif
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Date</label>
                                <input type="date" class="form-control" value="{{$payment->date}}" name="paiddate" required>
                            </div>

                            <div id="jor">
                                <div class="form-group" id="jod">
                                    <label for="Inputinvoiceno" id="cvn">Cash Voucher No</label>
                                    <label for="Inputinvoiceno" class="hidden" id="jon">Job Order No (System Generated)</label>
                                    @if($payment->invoiceno)
                                        <input type="text" class="form-control" id="confirmjobid" pattern="\d*"
                                               value="{{$payment->invoiceno}}" placeholder="0" name="invoiceno"
                                               required>
                                    @else
                                        <input type="text" class="form-control" id="confirmjobid" pattern="\d*"
                                               value="{{$payment->job_order_no}}"
                                               placeholder="0" name="invoiceno" required>
                                    @endif
                                </div>
                                <div class=" hidden col-sm-1" id="check">
                                    <label></label>
                                    <span class="pe-7s-check" style="font-size: 25px;margin-top: 10px;"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="payeename"> Payer Name </label>
                                <input type="text" class="form-control" placeholder="name" value="{{$payment->payee_name}}"
                                       name="payeename" required>
                            </div>

                            @if($chequeinfo)

                                <div class="form-group" id="chequeinfo1">
                                    <label>Cheque No</label>
                                    <input type="text" class="form-control" placeholder="xxxxxxxxxxxxx" name="chequeno"
                                           id="chequeno" pattern="\d*" value="{{$chequeinfo->cheque_no}}" required>
                                </div>

                                <div class="form-group" id="chequeinfo2">
                                    <label>Cheque Date</label>
                                    <input type="date" value="{{$chequeinfo->cheque_date}}" class="form-control" name="chequedate" id="chequedate"
                                           pattern="\d*" required>
                                </div>
                            @else
                                <div class=" hidden form-group" id="chequeinfo1">
                                    <label>Cheque No</label>
                                    <input type="text" class="form-control" placeholder="xxxxxxxxxxxxx" name="chequeno"
                                           id="chequeno" pattern="\d*" disabled="disabled" required>
                                </div>

                                <div class="hidden form-group" id="chequeinfo2">
                                    <label>Cheque Date</label>
                                    <input type="date" class="form-control" name="chequedate" id="chequedate"
                                           pattern="\d*" disabled required>
                                </div>
                            @endif
                            <div id="amountdiv">

                                <div class="form-group" id="amount">
                                    <label> <span class="hidden" id="chequename"> Cheque </span> Amount </label>
                                    <input type="text" class="form-control" pattern="[+-]?([0-9]+([.][0-9]*)?|[.][0-9]+)"
                                           id="chequeamount"  placeholder="0.00" value="{{$payment->amount}}" name="amount"
                                           onchange="Calculate()" required>
                                </div>
                                @if($chequeinfo)
                                    <div class="form-group col-sm-4" id="chequeinfo3">
                                        <label>Tax Amount</label>
                                        <input type="text" class="form-control" pattern="[+-]?([0-9]+([.][0-9]*)?|[.][0-9]+)"
                                               placeholder="0.00" value="{{$chequeinfo->tax_amount}}" name="taxamount" id="taxamount"
                                               onchange="Calculate()" required>
                                    </div>

                                    <div class="form-group col-sm-4" id="chequeinfo3">
                                        <label>Total Amount</label>
                                        <input type="text" class="form-control" placeholder="0.00" name="totalamount"
                                               value=0 id="totalamount" disabled="disabled"
                                               readonly>
                                    </div>
                                @else
                                    <div class="hidden form-group col-sm-4" id="chequeinfo3">
                                        <label>Tax Amount</label>
                                        <input type="text" class="form-control" pattern="[+-]?([0-9]+([.][0-9]*)?|[.][0-9]+)"
                                               placeholder="0.00" value=0 name="taxamount" id="taxamount"
                                               onchange="Calculate()" disabled="disabled" required>
                                    </div>

                                    <div class="hidden form-group col-sm-4" id="chequeinfo3">
                                        <label>Total Amount</label>
                                        <input type="text" class="form-control" placeholder="0.00" name="totalamount"
                                               value=0 id="totalamount" disabled="disabled"
                                               readonly>
                                    </div>
                                @endif


                            </div>

                            <input type="hidden" value="{{$payment->id}}" name="id">

                            <hr>

                            <div class="row">
                                <div class="col-sm-2">
                                    <button type="submit" class="btn btn-default" id="submit">Submit</button>
                                </div>

                                @if($payment->type == 'cash')

                                    <div class="form-group col-sm-6" id="coc">
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio3" value="1" name="type" checked="">
                                            <label for="inlineRadio3"> Cash </label>
                                        </div>
                                        <div class="radio radio-inline">
                                            <input type="radio" id="inlineRadio4" value="0" name="type">
                                            <label for="inlineRadio4"> Cheque </label>
                                        </div>
                                    </div>
                                @else
                                    <div class="form-group col-sm-6" id="coc">
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio3" value="1" name="type" >
                                            <label for="inlineRadio3"> Cash </label>
                                        </div>
                                        <div class="radio radio-inline">
                                            <input type="radio" id="inlineRadio4" value="0" name="type" checked="">
                                            <label for="inlineRadio4"> Cheque </label>
                                        </div>
                                    </div>
                                @endif
                            </div>

                        </form>
                    </div>
                </div>

            </div>
            <div class="col-sm-3"></div>
        </div>
    </div>
    <script>

        $(document).ready(function () {
                Calculate();

            if($('input:radio[name=type]:checked').val() == 0)
                {
                    $("#amount").addClass( "col-sm-4" );
                    $("#amountdiv").addClass( "row" );
                    $("#chequename").removeClass("hidden");
                }
            setTimeout(vor,1000);

            $(".select2_demo_2").select2({
                placeholder: "Select a customer",
                allowClear: true
            });
        });

        $("#callfunc").on('change', function () {
            var id = $(this).val();
            walkid = $("#walk").val();
            var optionvalue = $('input:radio[name=vor]:checked').val();
            if ((id === walkid) && (optionvalue !== 'r')) {
                $("#inlineRadio1").removeAttr("checked");
                $("#inlineRadio2").prop("checked", true);
                vor();
            }

            if(optionvalue === 'r')
            {
                confirmid();
            }
        });

        function confirmid () {
            var customer_id = $("#callfunc").val();
            var jobid =$("#confirmjobid").val();

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
                url: '/cash-collection/ajax/get-invoice',
                data: {id: customer_id, jobid: jobid},

                success: function (response) {

                    var value = $('input:radio[name=vor]:checked').val();
                    if(value === 'r') {
                        if(response) {
                            $("#submit").removeAttr("disabled").removeClass("btn-default").addClass("btn-success");
                            $(".pe-7s-check").css({ 'color': 'lightgreen' });
                        }
                        else {
                            $("#submit").attr("disabled", true).removeClass("btn-success").addClass("btn-default");
                            $(".pe-7s-check").css({ 'color': '' });
                        }
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) { if (XMLHttpRequest.readyState == 0) {
                    // Network error (i.e. connection refused, access denied due to CORS, etc.)
                    toastr.error('Network Connection Refused');
                }
                }
            });
        }

        function Calculate()
        {
            var chequeamount = document.getElementById('chequeamount').value;
            var taxamount = document.getElementById('taxamount').value;
            var amount = parseFloat(chequeamount) + parseFloat(taxamount);
            document.getElementById('totalamount').value=parseFloat(amount);
        }

        $('input:radio[name=type]').change(function () {
            var value = $(this).val();
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
                $("#inlineRadio3").prop("checked", true);
                $("#confirmjobid").attr("onkeyup","confirmid()");
                $("#cvn,#coc").addClass( "hidden" );
                $("#jod").addClass( "col-sm-11" );
                $("#jor").addClass( "row" );

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

        $(function () {
            $('[autofocus]').focus()
        });
    </script>
@endsection