@extends('admin.master')
<link rel="stylesheet" href="{{url('vendor/fontawesome/css/font-awesome.css')}}"/>
<link rel="stylesheet" href="{{url('vendor/datatables/datatables.min.css')}}"/>

@section('content')
    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-12">
                <div class="view-header">
                    {{--                    <div class="pull-right text-right" style="line-height: 14px">
                                            <small>Table design<br>General<br> <span class="c-white">DataTables</span></small>
                                        </div>--}}
                    <div class="header-icon">
                        <i class="pe page-header-icon pe-7s-albums"></i>
                    </div>
                    <div class="header-title">
                        <h3>Customer Ledger</h3>
                    </div>
                </div>
                <hr>
            </div>
        </div>
        <div class="row"  id="adddate">
            <div class="col-sm-3">
                <input type="hidden" name="walkid" value="{{$walk->id}}" id="walk">

                <label>Customer</label>
                {{--<div class="col-sm-11">--}}
                <select class="form-control" name="customer_id"  id="callfunc" style="width: 100% !important;" required>
                    <option value="" selected disabled hidden>Choose here</option>
                    @if(count($customer)>0)
                        @foreach($customer as $customers)
                            <option value="{{$customers->id}}" >{{$customers->name}}</option>
                        @endforeach
                    @else
                        <option>No Customer exist</option>
                    @endif
                </select>
                {{--</div>--}}
                {{--<div class="col-sm-1"><i class="fa fa-check-circle-o" style="font-size: 25px !important;"></i></div>--}}

            </div>

            <div class="col-sm-4"></div>
            <div class="form-group col-sm-2" id="hiddenfrom" style="border-left: 1px solid darkslategrey;">
                <label >From</label>
                <input type="date" class="form-control"  id="start" required>
            </div>
            <div class="form-group col-sm-2" id="hiddento">
                <label>To</label>
                <input type="date" class="form-control" disabled id="end" required>
            </div>
            <div class="col-sm-1 hidden" id="calldate">
                <button class="btn btn-default"  style="margin-top: 25px">
                    <i class="fa fa-check-circle-o" style="font-size: 16px !important;"></i>
                </button>
            </div>

        </div>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-filled">
                    <div class="panel-heading">
                        Sales
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">

                            <table id="tableExample4" class="table table-striped table-hover table-bordered table-condensed">
                                <thead>
                                <tr>
                                    <th style="color: #ffc771">CGN</th>
                                    <th style="color: #ffc771">Date</th>
                                    <th style="color: #ffc771" >Form Type</th>
                                    <th style="color: #ffc771">Invoice No</th>
                                    <th style="color: #ffc771">Cheque No</th>
                                    <th style="color: #ffc771">Cheque Date</th>
                                    <th style="color: #ffc771">Debit Amount</th>
                                    <th style="color: #ffc771">Credit Amount</th>
                                    <th style="color: #ffc771">Balance</th>
                                    <th style="color: #ffc771">Remarks</th>
                                    <th style="text-align: center"><span class="pe-7s-user" style="color: #ffc771 ; font-size:20px !important; "></span></th>
                                    <th style="text-align: center"><span class="pe-7s-clock" style="color: #ffc771 ; font-size:20px !important; "></span></th>
                                    <th style="text-align: center"><span class="pe-7s-edit" style="color: #ffc771 ; font-size:20px !important; "></span></th>
                                </tr>
                                </thead>
                                <tbody id="back">
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
    <script src="{{url('vendor/datatables/datatables.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            var name = '';
            var id;

            $( "#calldate" ).click(function(){
                id = $("#callfunc option:selected").val();
                name = $("#callfunc option:selected").text();
                var start = $('#start').val();
                var end = $('#end').val();

                var walkid =$("#walk").val();

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
                    url: '/sales/reports/customerLedgerReport/ajaxupdate',
                    data: {id: id, walkid : walkid,start: start, end: end},

                    success: function (response) {
                        console.log(response);

                        $('#tableExample4').DataTable({
                            dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
                            "lengthMenu": [ [25, 50, -1], [25, 50, "All"] ],
                            buttons: [
                                {extend: 'copy' ,title: name,className: 'btn-sm',  exportOptions: {columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11 ]}},
                                {extend: 'csv',title: name, message:'This Customer Ledger is printed under the authority of administration',
                                    className: 'btn-sm',  exportOptions: {columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11 ]}},
                                {extend: 'pdf', title: name, message:'This Customer Ledger is printed under the authority of administration',
                                    className: 'btn-sm',  exportOptions: {columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11 ]}},
                                {extend: 'print',title: name, className: 'btn-sm', message:'This Customer Ledger is printed under the authority of administration',
                                    exportOptions: {columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11 ]}}
                            ],
                            "ordering": false,
                            "bDestroy": true
                        });
                        // console.log(response);
                        var openingbalance = response['openingbalance'];

                        delete response['openingbalance'];


                        $('#tableExample4').DataTable().clear().draw();
                        $("#tableExample4").DataTable().row.add([
                            "","Opening Balance", "", "", "", "", "", "", openingbalance,"", "", ""]).draw();

                        var totaldebit = 0;
                        var totalcredit = 0;
                        var balance = openingbalance;
                        for (key in response) {
                            balance = (balance + response[key]["debit_amount"]) - response[key]["credit_amount"];
                            totaldebit = totaldebit + response[key]["debit_amount"];
                            totalcredit = totalcredit + response[key]["credit_amount"];
                            edit = '<a href="/job-order/'+response[key]["id"]+'/edit" target="_blank" class="btn btn-w-md btn-success">edit</a>';

                            $("#tableExample4").DataTable().row.add([
                                response[key]["id"],response[key]["date"], response[key]["formtype"], response[key]["invoice_no"],
                                response[key]["cheque_no"], response[key]["cheque_date"], response[key]["debit_amount"],
                                response[key]["credit_amount"],balance, response[key]["remarks"], response[key]["created_by"],
                                response[key]["added_at"], edit
                            ]).draw();
                        }

                        $("#tableExample4").DataTable().row.add([
                            "","Net Total", "", "", "", "", totaldebit, totalcredit, balance,"", "", ""]).draw();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(JSON.stringify(jqXHR));
                        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                    }
                });
            });


            $("#hiddenfrom").on('change', function () {
                var min = $("start"). val();
                $("#end").attr("min",min);

                $("#end").removeAttr("disabled");
            });

            $("#hiddento").on('change', function ()
            {
                $("#calldate").removeClass("hidden");

            });


        });
    </script>
@endsection
