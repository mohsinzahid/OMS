@extends('admin.master')

@section('content')
    <div class="container-fluid">


        <div class="row">
            <div class="col-lg-12">
                <div class="view-header">
                    <div class="pull-right text-right" style="line-height: 14px">
                    </div>
                    <div class="header-icon">
                        <i class="pe page-header-icon pe-7s-pen"></i>
                    </div>
                    <div class="header-title">
                        <h3>Add a Cash Payment</h3>
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
                        <h3>Payment Added Successfully.</h3>
                    @endif
                </div>
                <div class="panel panel-filled">
                    <div class="panel-body">
                        <form method="post" action="{{ url('/purchasepayment/postpayment') }}" autocomplete="off">
                            {{ csrf_field() }}


                            <div class="form-group">
                                <label for="InputQuantity">Vendor</label>
                                <select class="select2_demo_1 form-control" name="vendor_id" style="width: 100%" required>
                                    <option value="" selected disabled hidden>Choose here</option>
                                    @if(count($vendor)>0)
                                        @foreach($vendor as $vendors)
                                            <option value="{{$vendors->id}}">{{$vendors->id}} &emsp; {{$vendors->name}}</option>
                                        @endforeach
                                    @else
                                        <option>No vendor exist</option>
                                    @endif
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Date</label>
                                <input type="date" class="form-control" name="paiddate" required>
                            </div>

                            <div class="form-group">
                                <label for="Inputinvoiceno">Invoice no</label>
                                <input type="text" class="form-control" pattern="\d*" placeholder="0" name="invoiceno" required>
                            </div>

                            <div class="hidden form-group" id="chequeinfo1">
                                <label>Cheque No</label>
                                <input type="text" class="form-control" placeholder="xxxxxxxxxxxxx" name="chequeno"
                                       id="chequeno" pattern="\d*" disabled required>
                            </div>

                            <div class="hidden form-group" id="chequeinfo2">
                                <label>Cheque Date</label>
                                <input type="date" class="form-control" name="chequedate" id="chequedate" pattern="\d*"
                                       disabled required>
                            </div>

                            <div id="amountdiv">

                                <div class="form-group" id="amount">
                                    <label> <span class="hidden" id="chequename"> Cheque </span> Amount </label>
                                    <input type="text" class="form-control" pattern="[+-]?([0-9]+([.][0-9]*)?|[.][0-9]+)"
                                           id="chequeamount"  placeholder="0.00" name="amount" onkeyup="Calculate()"
                                           required>
                                </div>

                                <div class="hidden form-group col-sm-4" id="chequeinfo3">
                                    <label>Tax Amount</label>
                                    <input type="text" class="form-control" pattern="[+-]?([0-9]+([.][0-9]*)?|[.][0-9]+)"
                                           placeholder="0.00" name="taxamount" id="taxamount" onkeyup="Calculate()"
                                           disabled="disabled" required>
                                </div>

                                <div class="hidden form-group col-sm-4" id="chequeinfo3">
                                    <label>Total Amount</label>
                                    <input type="text" class="form-control" placeholder="0.00" name="totalamount"
                                           id="totalamount" disabled="disabled" readonly>
                                </div>
                            </div>

                            <hr>

                            <div class="row">
                                <div class="col-sm-4">
                                    <button type="submit" class="btn btn-primary" id="submit">Submit</button>
                                </div>

                                <div class="form-group col-sm-6" id="coc">
                                    <div class="radio radio-info radio-inline">
                                        <input type="radio" id="inlineRadio3" value="1" name="type" checked="">
                                        <label for="inlineRadio1"> Cash </label>
                                    </div>
                                    <div class="radio radio-inline">
                                        <input type="radio" id="inlineRadio4" value="0" name="type">
                                        <label for="inlineRadio2"> Cheque </label>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
            <div class="col-sm-3"></div>
        </div>
    </div>

    <script>

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
                $("#chequeinfo1,#chequeinfo2,#chequeinfo3,#chequename").removeClass("hidden");
                $("#chequeno,#chequedate,#taxamount").removeAttr("disabled");
                $("#amount").addClass( "col-sm-4" );
                $("#amountdiv").addClass( "row" );
            }
            else
            {
                $("#chequeinfo1,#chequeinfo2,#chequeinfo3,#chequename").addClass( "hidden" );
                $("#chequeno,#chequedate,#taxamount").attr("disabled", true);
                $("#amount").removeClass( "col-sm-4" );
                $("#amountdiv").removeClass( "row" );
            }
        });
    </script>
@endsection