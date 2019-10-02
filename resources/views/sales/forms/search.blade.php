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
                        <i class="pe page-header-icon pe-7s-pen"></i>
                    </div>
                    <div class="header-title">
                        <h3>Search Job Order</h3>
                    </div>
                </div>
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3"></div>
            <div class="col-sm-6">
                <div class="header-title">
                </div>
                <div class="panel panel-filled">
                    <div class="panel-body">
                            <div class="form-group">
                                <label for="formtype">Forms type</label>
                                <select class="select2_demo_1 form-control" name="formid" id="callfunc" style="width: 100%"
                                        onchange="getjobids()" required>
                                    <option value="" selected disabled hidden>Choose here</option>
                                    <option value="jo" selected> JO &emsp; Job Order(CTP)</option>
                                </select>
                            </div>
                                <div class="form-group">
                                    <label for="Inputinvoiceno">
                                        Invoice No
                                    </label>
                                    <input type="text" class="form-control" id="invoiceno" pattern="\d*"
                                           placeholder="0" required>
                                </div>
                            <button class="btn btn-primary" id="search" >Search</button>
                    </div>
                </div>

            </div>
            <div class="col-sm-3"></div>
        </div>

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
                                        <th style="color: #ffc771">Type</th>
                                        <th style="color: #ffc771">Name</th>
                                        <th style="color: #ffc771">Date</th>
                                        <th style="color: #ffc771">Invoice No</th>
                                        <th style="color: #ffc771">Debit Amount</th>
                                        <th style="color: #ffc771">Remarks</th>
                                        <th style="color: #ffc771">Created By</th>
                                        <th style="color: #ffc771">Entry date</th>
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

        $( "#search" ).click(function(){
            var id = $('#invoiceno').val();

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
                url: '/job-order/ajaxsearch',
                data: {id: id},

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


                    $('#tableExample4').DataTable().clear().draw();

                    for (key in response) {

                        $("#tableExample4").DataTable().row.add([
                            response[key]["id"],response[key]["type"],response[key]["name"], response[key]["date"],
                            response[key]["invoice_no"], response[key]["debit_amount"], response[key]["remarks"],
                            response[key]["created_by"], response[key]["added_at"]
                        ]).draw();
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(JSON.stringify(jqXHR));
                    console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                }
            });
        });
    </script>

@endsection