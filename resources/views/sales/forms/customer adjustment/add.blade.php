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
                        <h3>Customer Adjustment Form</h3>
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
                                            <h3>Adjustment Added Successfully.</h3>
                                        @endif
                </div>
                <div class="panel panel-filled">
                    <div class="panel-body">
                        <form method="post" action="{{ url('/customer-adjustment/postpayment') }}" autocomplete="off">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="form-group col-sm-5">
                                    <label for="callfunc">Customer</label>
                                    <select class="select2_demo_2 form-control" name="customerid" id="callfunc"
                                            style="width: 100%" required>
                                        <option value="" selected disabled hidden>Choose here</option>
                                        @if(count($customer)>0)
                                            @foreach($customer as $customers)
                                                <option value="{{$customers->id}}">{{$customers->id}} &emsp;
                                                    {{$customers->name}}</option>
                                            @endforeach
                                        @else
                                            <option>No customer exist</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label>General Ledger </label>
                                    <select class="form-control" name="generalledger" style="width: 100%" required>
                                        <option value="" selected disabled hidden>Choose here</option>
                                        @if(count($gl)>0)
                                            @foreach($gl as $gls)
                                                <option value="{{$gls->id}}">{{$gls->id}} &emsp;
                                                    {{$gls->name}}</option>
                                            @endforeach
                                        @else
                                            <option>No General Ledger exist</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="col-sm-1"></div>
                            </div>

                            <div class="row">
                                <div class="form-group col-sm-5">
                                    <label>Date</label>
                                    <input type="date" class="form-control" name="paiddate" required>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="JobOrderno">Job Card ID (System generated)</label>
                                    <input type="text" class="form-control" id="confirmjobid" placeholder="0"
                                           name="joborderno" onchange="confirmid()" required>
                                </div>
                                <div class="form-group col-sm-1">
                                    <label></label>
                                    <span class="pe-7s-check" style="font-size: 25px;margin-top: 10px;"></span>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-sm-5">
                                <label> Amount </label>
                                <input type="text" class="form-control" pattern="[+-]?([0-9]+([.][0-9]*)?|[.][0-9]+)"
                                       placeholder="0.00" name="amount" required>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label>Remarks</label>
                                    <textarea class="form-control" rows="3" name="remarks" placeholder="Add Remarks"
                                              required></textarea>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="form-group col-sm-4">
                                    <button type="submit" id="submit" class="btn btn-w-md btn-default" disabled>
                                        Submit
                                    </button>
                                </div>

                                <div class="form-group col-sm-6">
                                    <div class="radio radio-info radio-inline">
                                        <input type="radio" id="inlineRadio1" value="debit" name="type" checked="">
                                        <label for="inlineRadio1"> Debit </label>
                                    </div>
                                    <div class="radio radio-inline">
                                        <input type="radio" id="inlineRadio2" value="credit" name="type">
                                        <label for="inlineRadio2"> Credit </label>
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

{{--    <script type="text/javascript" src="{{asset('js/localfunctions.js') }}"></script>--}}

    <script>
        $(document).ready(function () {
            $(".select2_demo_2").select2({
                placeholder: "Select a customer",
                allowClear: true
            });
        });
        function confirmid () {
            var custid = $("#callfunc").val();
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
                url: '/customer-adjustment/ajax/get-invoice',
                data: {custid: custid, jobid: jobid},

                success: function (response) {

                    /*console.log(response);
                    jobids = ','+response[0]['jobid']+',';
                    console.log(jobids);
                    confirmid()*/
                    console.log(response);
                    if((response === 1) || ((response === 2))) {
                        $("#submit").removeAttr("disabled").removeClass("btn-default").addClass("btn-success");
                        $(".pe-7s-check").css({ 'color': 'lightgreen' });
                        if(response === 2)
                        {
                            toastr.warning('Cash Collection Already submitted against this job id');
                        }
                    }
                    else {
                        $("#submit").attr("disabled", true).removeClass("btn-success").addClass("btn-default");
                        $(".pe-7s-check").css({ 'color': '' });
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) { if (XMLHttpRequest.readyState == 0) {
                    // Network error (i.e. connection refused, access denied due to CORS, etc.)
                    toastr.error('Network Connection lost');
                }
                    /*console.log(JSON.stringify(jqXHR));
                    console.log("AJAX error: " + textStatus + ' : ' + errorThrown);*/
                }
            });
        }
        /*function confirmid () {
            var id = ',' + $("#confirmjobid").val() + ',';
            if (jobids.search(id) !== -1) {
                $("#submit").removeAttr("disabled").removeClass("btn-default").addClass("btn-success");
                $(".pe-7s-check").css({'color': 'lightgreen'});
                // $(".submitbtn").removeClass("btn-default");
                // $(".submitbtn").addClass("btn-success");

            } else {
                $("#submit").attr("disabled", true).removeClass("btn-success").addClass("btn-default");
                $(".pe-7s-check").css({'color': ''});
                // $(".submitbtn").removeClass("btn-success");
                // $(".submitbtn").addClass("btn-default");
            }
        }*/
    </script>
@endsection