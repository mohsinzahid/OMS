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
                        <h3>Inventory</h3>
                    </div>
                </div>
                <hr>
            </div>
        </div>
        <div class="row"  id="adddate">

            <div class="col-sm-3">
                <label>Sizes</label>
                    <select class="form-control" name="vendor_id"  id="callfunc" style="width: 100% !important;" required>
                        <option value="" selected disabled hidden>Choose here</option>
                        @if(count($size)>0)
                            @foreach($size as $sizes)
                                <option value="{{$sizes->id}}" >{{$sizes->id}} &emsp; {{$sizes->size}}</option>
                            @endforeach
                        @else
                            <option>No Size Exist</option>
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

                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">

                            <table id="tableExample4" class="table table-striped table-hover table-bordered table-condensed">
                                <thead>
                                    <tr>
                                        <th>CGN</th>
                                        <th>Date</th>
                                        <th>Form Type</th>
                                        <th>Invoice No</th>
                                        <th>Debit Amount</th>
                                        <th>Credit amount</th>
                                        <th>Net Balance</th>
                                        <th>Remarks</th>
                                        <th>Added At</th>
                                        <th>Action by</th>
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
                    url: '/inventory-report/ajaxupdate',
                    data: {id: id, start: start, end: end},

                    success: function (response) {
                        // console.log(response);
                        $('#tableExample4').DataTable({
                            dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
                            "lengthMenu": [ [25, 50, -1], [25, 50, "All"] ],
                            buttons: [
                                {extend: 'copy',className: 'btn-sm',
                                    exportOptions: {columns: [ 0, 1, 2, 3, 4 ]}},
                                {extend: 'csv',title: "Zahid Scan", className: 'btn-sm',
                                    exportOptions: {columns: [ 0, 1, 2, 3, 4, 5 ,6 ,7 ,8 ,9 ]}},
                                {extend: 'pdf', title: "Zahid Scan", className: 'btn-sm',
                                    exportOptions: {columns: [ 0, 1, 2, 3, 4, 5 ,6 ,7 ,8 ,9 ]}},
                                {extend: 'print',title: "Zahid Scan" , className: 'btn-sm',
                                    exportOptions: {columns: [ 0, 1, 2, 3, 4, 5 ,6 ,7 ,8 ,9 ]}}
                            ],
                            "ordering": false,
                            "bDestroy": true

                        });
                        var openingbalance = response['openingbalance'];

                        delete response['openingbalance'];
                        $('#tableExample4').DataTable().clear().draw();
                        $("#tableExample4").DataTable().row.add([
                            "","Opening Balance", "", "", "", "", openingbalance,"", "", ""]).draw();

                        var balance = openingbalance;

                        for(key in response)
                        {
                            balance = (balance + parseInt(response[key]["debit_quantity"]) ) - parseInt(response[key]["credit_quantity"]) ;
                            $("#tableExample4").DataTable().row.add([
                                response[key]['id'],response[key]["date"],response[key]["formtype"],
                                response[key]["invoice_no"], response[key]['debit_quantity'],
                                response[key]['credit_quantity'], balance,response[key]["remarks"],
                                response[key]["added_at"], response[key]["rca_by"]
                            ]).draw();
                        }
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
