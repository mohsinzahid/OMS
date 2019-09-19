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
                        <h3>Edit Payment</h3>
                    </div>
                    <div style="float: right">
                        <a href="/purchasepayment/{{$payment->id}}/delete" class="btn btn-w-md btn-danger">Delete</a>
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
                        <form method="post" action="{{ url('/purchasepayment/update') }}" autocomplete="off">
                            {{ csrf_field() }}


                            <div class="form-group">
                                <label for="InputQuantity">Vendor</label>
                                <select class="select2_demo_1 form-control" name="vendor_id" style="width: 100%" required>
                                    <option value="" selected disabled hidden>Choose here</option>
                                    @if(count($vendor)>0)
                                        @foreach($vendor as $vendors)
                                            @if($vendors->id === $payment->vendor_id)
                                                <option selected value="{{$vendors->id}}">{{$vendors->name}}</option>
                                            @else
                                                <option value="{{$vendors->id}}">{{$vendors->name}}</option>
                                            @endif
                                        @endforeach
                                    @else
                                        <option>No sizes exist</option>
                                    @endif
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Date</label>
                                <input type="date" class="form-control" value="{{$payment->paiddate}}" name="paiddate" required>
                            </div>

                            <div class="form-group">
                                <label for="Inputinvoiceno">Invoice no</label>
                                <input type="text" class="form-control" pattern="\d*" value="{{$payment->invoiceno}}"
                                       placeholder="0" name="invoiceno" required>
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
                                           id="chequeamount"  placeholder="0.00" value="{{$payment->amount}}" name="amount" onchange="Calculate()"
                                           required>
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
                                    <button type="submit" class="btn btn-primary" id="submit">Submit</button>
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

    <script type="text/javascript" src="{{asset('js/localfunctions.js') }}"></script>


    <script>


        $(document).ready(function () {
            Calculate();
            if($('input:radio[name=type]:checked').val() == 0)
            {
                $("#chequeinfo1,#chequeinfo2,#chequeinfo3,#chequename").removeClass("hidden");
                $("#chequeno,#chequedate,#taxamount").removeAttr("disabled");
                $("#amount").addClass( "col-sm-4" );
                $("#amountdiv").addClass( "row" );
            }


        });
    </script>
@endsection