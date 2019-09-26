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
                        <h3>Purchase Detail List</h3>
                    </div>
                </div>
                <hr>
            </div>
        </div>
        <div class="row"  id="adddate">
{{--
            <input type="hidden" id="authid" value="{{Auth::user()->type_id}}">
--}}
            <div class="col-sm-3">
                <label>Vendor</label>
                {{--<div class="col-sm-11">--}}
                    <select class="form-control" name="vendor_id"  id="callfunc" style="width: 100% !important;" required>
                        <option value="" selected disabled hidden>Choose here</option>
                        @if(count($vendor)>0)
                            @foreach($vendor as $vendors)
                                <option value="{{$vendors->id}}" >{{$vendors->name}}</option>
                            @endforeach
                        @else
                            <option>No Vendor exist</option>
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
                        Purchases
                    </div>
                    <div class="panel-body">
                        {{--<p>
                            The Buttons library for DataTables provides a framework with common options and API that can be used with DataTables, but is also very extensible, recognising that you will likely want to use buttons which perform an action unique to your applications.
                        </p>--}}

{{--                        <div class="row">

                        </div>--}}
                        <div class="table-responsive">

                            <table id="tableExample4" class="table table-striped table-hover table-bordered table-condensed">
                                <thead>
                                <tr>
                                    <th style="color: #ffc771 ">Purchase Order No</th>
                                    <th style="color: #ffc771 ">Name</th>
                                    <th style="color: #ffc771 ">Date</th>
                                    <th style="color: #ffc771 ">Invoice No</th>
                                    <th style="color: #ffc771 ">Added At</th>
                                    <th style="color: #ffc771 ">Size</th>
                                    <th style="color: #ffc771 ">Quantity</th>
                                    <th style="color: #ffc771 ">Cost Price</th>
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
                    {extend: 'copy',className: 'btn-sm',  exportOptions: {columns: [ 0, 1, 2, 3, 4, 5 ,6 ,7 ]}},
                    {extend: 'csv',title: "Zahid Scan", className: 'btn-sm',  exportOptions: {columns: [ 0, 1, 2, 3, 4, 5 ,6 ,7 ]}},
                    {extend: 'pdf', title: "Zahid Scan", className: 'btn-sm',  exportOptions: {columns: [ 0, 1, 2, 3, 4, 5 ,6 ,7 ]}},
                    {extend: 'print',title: "Zahid Scan" , className: 'btn-sm',exportOptions: {columns: [ 0, 1, 2, 3, 4, 5 ,6 ,7 ]}}
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
                    url: '/supplier-ledger-detail/ajaxupdate',
                    data: {id: id, start: start, end: end},

                    success: function (response) {
                        console.log(response);

                        $('#tableExample4').DataTable().clear().draw();

                        for(key in response)
                        {
                            $("#tableExample4").DataTable().row.add([
                                response[key]['purchaseinventory_id'],name,response[key]["dateofpurchase"],
                                response[key]["invoice_no"], response[key]["added_at"],response[key]["size"],
                                response[key]["quantity"], response[key]["costprice"]
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
