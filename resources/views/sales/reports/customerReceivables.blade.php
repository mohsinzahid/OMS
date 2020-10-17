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
                        <h3>Customer Receivables</h3>
                    </div>
                </div>
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3">
            </div>
            <div class="col-sm-4"></div>
            <div class="form-group col-sm-2" id="date" style="border-left: 1px solid darkslategrey;">
                <label>Date</label>
                <input type="date" class="form-control"  id="end" required>
            </div>
            <div class="col-sm-1" id="calldate">
                <button class="btn btn-default"  style="margin-top: 25px">
                    <i class="fa fa-check-circle-o" style="font-size: 16px !important;"></i>
                </button>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10">
                <div class="panel panel-filled">
                    <div class="panel-heading">
                        Receivables
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table id="tableExample4" class="table table-striped table-hover table-bordered table-condensed">
                                <thead>
                                <tr>
                                    <th style="color: #ffc771">Customer</th>
                                    <th style="color: #ffc771">Last Bill Date</th>
                                    <th style="color: #ffc771">Last Payment Date</th>
                                    <th style="color: #ffc771">Last Payment Amount</th>
                                    <th style="color: #ffc771">Balance</th>
                                </tr>
                                </thead>
                                <tbody id="back">
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-1"></div>

        </div>
    </div>
    <script src="{{url('vendor/datatables/datatables.min.js')}}"></script>
    <script>
$(document).ready(function () {
    var name = '';
    var id;

    $( "#calldate" ).click(function(){
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
                            url: '/sales/reports/customerReceivable/ajaxupdate',
                            data: {end: end},

                            success: function (response) {
                            console.log(response);

                    $('#tableExample4').DataTable({
                                    dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
                                    "lengthMenu": [ [25, 50, -1], [25, 50, "All"] ],
                                    buttons: [
                                        {extend: 'copy' ,title: name,className: 'btn-sm',  exportOptions: {columns: [0, 1, 2, 3 ]}},
                                        {extend: 'csv',title: name, message:'This Customer Ledger is printed under the authority of administration',
                                            className: 'btn-sm',  exportOptions: {columns: [0, 1, 2, 3 ]}},
                                        {extend: 'pdf', title: name, message:'This Customer Ledger is printed under the authority of administration',
                                            className: 'btn-sm',  exportOptions: {columns: [0, 1, 2, 3]}},
                                        {extend: 'print',title: name, className: 'btn-sm', message:'This Customer Ledger is printed under the authority of administration',
                                            exportOptions: {columns: [0, 1, 2, 3]}}
                                    ],
                                    "ordering": false,
                                    "bDestroy": true
                                });
                                 // console.log(response);
                                $('#tableExample4').DataTable().clear().draw();

                                var totalBalance = 0;
                                for (key in response) {

                                    let lastPayment = '';
                                    let lastPaymentdate = '';
                                    let lastSale = '';
                                    totalBalance = totalBalance + response[key]["balance"];
                                    if(response[key]["lastPayment"]!= null)
                                    {
                                        lastPayment = response[key]["lastPayment"]["amount"] +
                                            response[key]["lastPayment"]["tax_amount"];
                                        lastPaymentdate = response[key]["lastPayment"]["date"];
                                        // console.log(lastPayment);
                                    }
                                    if(response[key]["lastSale"] != null)
                                    {
                                        lastSale = response[key]["lastSale"]["dateofsale"];
                                    }

                                    $("#tableExample4").DataTable().row.add([
                                        response[key]["name"], lastSale ,lastPaymentdate ,
                                        lastPayment , response[key]["balance"]
                                    ]).draw();
                                }
                                $("#tableExample4").DataTable().row.add([
                                    "Net Total", "","", "", totalBalance]).draw();
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                console.log(JSON.stringify(jqXHR));
                                console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                            }
                    });
            });
});
    </script>
@endsection
