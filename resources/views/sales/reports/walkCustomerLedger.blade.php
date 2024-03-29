@extends('admin.master')

<link rel="stylesheet" href="{{url('vendor/fontawesome/css/font-awesome.css')}}"/>
<link rel="stylesheet" href="{{url('vendor/datatables/datatables.min.css')}}"/>

@section('content')
    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-12">
                <div class="view-header">
                    <div class="pull-right text-right" style="line-height: 14px">
                    </div>
                    <div class="header-icon">
                        <i class="pe page-header-icon pe-7s-note2"></i>
                    </div>
                    <div class="header-title">
                        <h3>Walking Customer Ledger</h3>
                    </div>
                </div>
                <hr>
            </div>
        </div>
        <div class="row"  id="adddate">
            <div class="col-sm-3">
                <select class="form-control" id="status" style="width: 100%" required>
                    <option value="paid">Paid</option>
                    <option value="unpaid" selected>Unpaid</option>
                    <option value="partial">Partial</option>
                    <option value="all">All</option>
                </select>
            </div>

            <div class="col-sm-4"></div>
            <div class="form-group col-sm-2" id="hiddenfrom" style="border-left: 1px solid darkslategrey;">
                <label >From</label>
                <input type="date" class="form-control"  id="start" required>
            </div>
            <div class="form-group col-sm-2 " id="hiddento">
                <label>Date</label>
                <input type="date" class="form-control"  id="end" required>
            </div>
            <div class="col-sm-1 hidden" id="calldate">
                <button class="btn btn-default"  style="margin-top: 25px">
                    <i class="fa fa-check-circle-o" style="font-size: 16px !important; color: #ffc771"></i>
                </button>
            </div>

        </div>
        <hr>
        <div class="row" id="main">
            <div class="col-lg-12">
                <div class="panel panel-filled">
                    <div class="panel-heading">
                        Items
                    </div>
                    <div class="panel-body">
                        <p>A list of all sale records which are Submitted.</p>

                        <div class="table-responsive">
                            <table id="tableExample4"  class="table table-hover table-bordered">
                                <thead>
                                <tr>
                                    <th style="color: #ffc771 ">CGN</th>
                                    <th style="color: #ffc771 ">Name</th>
                                    <th style="color: #ffc771 ">Date</th>
                                    <th style="color: #ffc771 ">Type</th>
                                    <th style="color: #ffc771 ">Invoice No</th>
                                    <th style="color: #ffc771 ">Employee</th>
                                    <th style="color: #ffc771 ">Added At</th>
                                    <th style="color: #ffc771 ">Amount</th>
                                    <th style="color: #ffc771 ">Status</th>
                                    <th style="text-align: center"><span class="pe-7s-edit" style="color: #ffc771 ; font-size:20px !important; "></span></th>
                                </tr>
                                </thead>
                                <tbody id="addrow">

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
            $('#tableExample4').DataTable({
                dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
                "lengthMenu": [ [25, 50, -1], [25, 50, "All"] ],
                buttons: [
                    {extend: 'copy',className: 'btn-sm',
                        exportOptions: {columns: [ 0, 1, 2, 3, 4, 5 ,6 ,7 ,8 ]}},
                    {extend: 'csv',title: "Zahid Scan", className: 'btn-sm',
                        exportOptions: {columns: [ 0, 1, 2, 3, 4, 5 ,6 ,7 ,8]}},
                    {extend: 'pdf', title: "Zahid Scan", className: 'btn-sm',
                        exportOptions: {columns: [ 0, 1, 2, 3, 4, 5 ,6 ,7 ,8]}},
                    {extend: 'print',title: "Zahid Scan" , className: 'btn-sm',
                        exportOptions: {columns: [ 0, 1, 2, 3, 4, 5 ,6 ,7 ,8]}}
                ],
                "ordering": false
            });
        });

        $( "#calldate" ).click(function(){
            let status = $("#status option:selected").val();
            var start = $('#start').val();
            var end = $('#end').val();
            $("#addrow").empty();
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
                data: {start:start, end: end, status:status},
                url: '/sales/reports/walkCustomerLedger/ajaxupdate',

                success: function (response) {
                    // console.log(response);
                    $('#tableExample4').DataTable().clear().draw();
                    let edit= '';

                    for(key in response)
                    {
                        edit = '<a href="/job-order/'+response[key]["id"]+'/edit" target="_blank" class="btn btn-w-md btn-success">edit</a>';
                            $("#tableExample4").DataTable().row.add([
                                response[key]["id"],response[key]["name"],response[key]["date"],
                                response[key]["type"], response[key]['invoice_no'], response[key]["created_by"],
                                response[key]["added_at"], response[key]["amount"], response[key]["status"], edit]).draw();
                    }


                    $("#tableExample4").DataTable().page('last').draw('page');
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(JSON.stringify(jqXHR));
                    console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                }
            });
        });

        // $("#hiddenfrom").on('change', function ()
        // {
        //     var min = $(this).val();
        //     $("#hiddento").attr("min", min);
        //     $("#hiddento").removeClass("hidden");
        // });

        $("#hiddento").on('change', function ()
        {
            $("#calldate").removeClass("hidden");

        });
    </script>
@endsection

