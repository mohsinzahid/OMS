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
                        <h3>Statements</h3>
                    </div>
                </div>
                <hr>
            </div>
        </div>
        <div class="row"  id="adddate">
            <div class="col-sm-3"><input type="hidden" name="walkid" value="{{$walk->id}}" id="walk">
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
            </div>

            <div class="col-sm-4"></div>
            <div class="form-group col-sm-2" id="hiddenfrom" style="border-left: 1px solid darkslategrey;">
                <label >From</label>
                <input type="date" class="form-control"  id="start" required>
            </div>
            <div class="form-group col-sm-2 " id="hiddento">
                <label>To</label>
                <input type="date" class="form-control" disabled  id="end" required>
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
                        <p>A list of all sale records which are Printed.</p>

                        <div class="table-responsive">
                            <table id="tableExample4"  class="table table-hover table-bordered">
                                <thead>
                                <tr>
                                    <th style="color: #ffc771 ">Job Order No</th>
                                    <th style="color: #ffc771 ">Name</th>
                                    <th style="color: #ffc771 ">Date</th>
                                    <th style="color: #ffc771 ">Invoice No</th>
                                    <th style="color: #ffc771 ">Employee</th>
                                    <th style="color: #ffc771 ">Added At</th>
                                    <th style="color: #ffc771 ">Size</th>
                                    <th style="color: #ffc771 ">Description</th>
                                    <th style="color: #ffc771 ">Set</th>
                                    <th style="color: #ffc771 ">Color</th>
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
        var id;
        var name;

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
                url: '/reports/customerLedgerDetail/ajaxupdate',
                data: {id: id,start: start, end: end},

                success: function (response) {

                    console.log(response);
                    // $("#addrow").empty();
                    $('#tableExample4').DataTable({
                        dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
                        "lengthMenu": [ [25, 50, -1], [25, 50, "All"] ],
                        buttons: [
                            {extend: 'copy',className: 'btn-sm',  exportOptions: {columns: [1, 2, 3, 6 , 7, 8, 9]}},
                            {extend: 'csv',title: "Zahid Scan", message:'This Statement is printed under the authority of administration', className: 'btn-sm',  exportOptions: {columns: [1, 2, 3, 6 , 7, 8, 9]}},
                            {extend: 'pdf', title: "Zahid Scan", message:'This Statement is printed under the authority of administration', className: 'btn-sm',  exportOptions: {columns: [1, 2, 3, 6 , 7, 8, 9]}},
                            {extend: 'print',title: "Zahid Scan", message:'This Statement is printed under the authority of administration', className: 'btn-sm',exportOptions: {columns: [1, 2, 3, 6 , 7, 8, 9]}}
                        ],
                        "ordering": false,
                        "iDisplayLength": 25,
                        "bDestroy": true
                    });

                    $('#tableExample4').DataTable().clear().draw();

                    for(key in response)
                    {
                        $("#tableExample4").DataTable().row.add([
                            response[key]['saleinventory_id'],name,response[key]["dateofsale"], response[key]["invoiceno"],
                            response[key]["name"], response[key]["added_at"], response[key]["size"], response[key]["description"],
                            response[key]["set"], response[key]["color"]
                        ]).draw();
                    }

                    $("#tableExample4").DataTable().page('last').draw('page');
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
    </script>
@endsection
