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
                        <h3>Inventory Summary</h3>
                    </div>
                </div>
                <hr>
            </div>
        </div>
        <div class="row"  id="adddate">

            <div class="col-sm-3">

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
                        Inventory
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">

                            <table id="tableExample4" class="table table-striped table-hover table-bordered table-condensed">
                                <thead>
                                    <tr>
                                        <th style="color: #ffc771 ">CGN</th>
                                        <th style="color: #ffc771 ">Size</th>
                                        <th style="color: #ffc771 ">Opening Quantity <span style="font-size: 10px; color: whitesmoke"> Related to date</span></th>
                                        <th style="color: #ffc771 ">Net Quantity</th>
                                        <th style="color: #ffc771 ">Sale Price</th>
                                        <th style="color: #ffc771 ">Action</th>
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

            $("#calldate").click( function () {
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
                    url: '/inventory-summary-report/ajaxupdate',
                    data: {start: start, end: end},

                    success: function (response) {
                        console.log(response);
                        $('#tableExample4').DataTable({
                            dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
                            "lengthMenu": [ [25, 50, -1], [25, 50, "All"] ],
                            buttons: [
                                {extend: 'copy',className: 'btn-sm',  exportOptions: {columns: [ 0, 1, 2, 3, 4 ]}},
                                {extend: 'csv',title: "Zahid Scan", className: 'btn-sm',  exportOptions: {columns: [ 0, 1, 2, 3, 4]}},
                                {extend: 'pdf', title: "Zahid Scan", className: 'btn-sm',  exportOptions: {columns: [ 0, 1, 2, 3, 4]}},
                                {extend: 'print',title: "Zahid Scan" , className: 'btn-sm',exportOptions: {columns: [ 0, 1, 2, 3, 4]}}
                            ],
                            "ordering": false
                        });

                        $('#tableExample4').DataTable().clear().draw();

                        for(key in response)
                        {
                            edit = '<a href="/stock/'+key+'/edit"> <span style="font-size: 17px !important;" class="pe-7s-note"></span></a> &nbsp;';
                            $("#tableExample4").DataTable().row.add([
                                key,response[key]["size"],response[key]["opening"], response[key]["net"],
                                response[key]['saleprice'], edit
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
