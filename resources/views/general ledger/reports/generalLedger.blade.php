@extends('admin.master')
<link rel="stylesheet" href="{{url('vendor/fontawesome/css/font-awesome.css')}}"/>
<link rel="stylesheet" href="{{url('vendor/datatables/datatables.min.css')}}"/>
@section('content')
    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-12">
                <div class="view-header">
                    <div class="header-icon">
                        <i class="pe page-header-icon pe-7s-albums"></i>
                    </div>
                    <div class="header-title">
                        <h3>General Ledger Report</h3>
                    </div>
                </div>
                <hr>
            </div>
        </div>
        <div class="row"  id="adddate">
            <div class="col-sm-3">
                <label>Vendor</label>
                <select class="form-control" name="gl" id="callfunc" style="width: 100%" required>
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

            <div class="col-sm-4"></div>
           <div class="form-group col-sm-2" id="hiddenfrom" style="border-left: 1px solid darkslategrey;">
                   <label >From</label>
                   <input type="date" class="form-control"  id="start" required>
           </div>
           <div class="form-group col-sm-2" id="hiddento">
                  <label>To</label>
                  <input type="date" class="form-control" disabled  id="end" required>
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
                        general ledger
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">

                            <table id="tableExample4" class="table table-striped table-hover table-bordered table-condensed">
                                <thead>
                                <tr>
                                    <th>CGN</th>
                                    <th>Date</th>
                                    <th>Form Type</th>
                                    <th>Customer Name</th>
                                    <th>Customer Type</th>
                                    <th>Reference No</th>
                                    <th>Cheque No</th>
                                    <th>Cheque Date</th>
                                    <th>Debit Amount</th>
                                    <th>Credit amount</th>
                                    <th>Net Balance</th>
                                    <th>Remarks</th>
                                    <th>Entry Date</th>
                                    <th>Created by</th>
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

            $('#tableExample4').DataTable({
                dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
                "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
                buttons: [
                    {extend: 'copy',className: 'btn-sm',
                        exportOptions: {columns: [ 0, 1, 2, 3, 4, 5 ,6 ,7 ,8 ,9 ,10 ,11 ]}},
                    {extend: 'csv',title: "Zahid Scan", className: 'btn-sm',
                        exportOptions: {columns: [ 0, 1, 2, 3, 4, 5 ,6 ,7 ,8 ,9 ,10 ,11 ]}},
                    {extend: 'pdf', title: "Zahid Scan", className: 'btn-sm',
                        exportOptions: {columns: [ 0, 1, 2, 3, 4, 5 ,6 ,7 ,8 ,9 ,10 ,11 ]}},
                    {extend: 'print',title: "Zahid Scan" , className: 'btn-sm',
                        exportOptions: {columns: [ 0, 1, 2, 3, 4, 5 ,6 ,7 ,8 ,9 ,10 ,11 ]}}
                ],
                "ordering": false
            });

            $("#calldate").click( function () {
                 id = $("#callfunc option:selected").val();
                name = $("#callfunc option:selected").text();
                var start = $('#start').val();
                var end = $('#end').val();

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
                    url: '/general-ledger-reports/ajaxupdate',
                    data: {id: id, start: start, end: end},

                    success: function (response) {
                        console.log(response['original']);
                        var openingbalance = response['original']['openingbalance'];

                        delete response['original']['openingbalance'];
                        $('#tableExample4').DataTable().clear().draw();
                        $("#tableExample4").DataTable().row.add([
                            "","Opening Balance", "", "", "", "","","", "", "", openingbalance,"", "", ""]).draw();

                        var balance = openingbalance;
                        var totaldebit = 0;
                        var totalcredit = 0;

                        for(key in response['original'])
                        {
                            balance = (balance + response['original'][key]["debit_amount"]) - response['original'][key]["credit_amount"];
                            totaldebit = totaldebit + response['original'][key]["debit_amount"];
                            totalcredit = totalcredit + response['original'][key]["credit_amount"];
                            $("#tableExample4").DataTable().row.add([
                                response['original'][key]['id'],response['original'][key]["date"],
                                response['original'][key]["formtype"],response['original'][key]["customer_name"],
                                response['original'][key]["customer_type"],response['original'][key]["invoice_no"],
                                response['original'][key]['cheque_no'],response['original'][key]['cheque_date'],
                                response['original'][key]['debit_amount'], response['original'][key]['credit_amount'],
                                balance,response['original'][key]["remarks"], response['original'][key]["added_at"],
                                response['original'][key]["created_by"]
                            ]).draw();
                        }
                        $("#tableExample4").DataTable().row.add([
                            "","Net Total", "", "", "","","", "", totaldebit, totalcredit, balance,"", "", ""]).draw();
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
