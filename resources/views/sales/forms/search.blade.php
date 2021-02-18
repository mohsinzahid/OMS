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
            <div class="col-md-6">
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
                                        <th style="color: #ffc771">Created By</th>
                                        <th style="color: #ffc771">Entry date</th>
                                        <th style="color: #ffc771">Status</th>
                                    </tr>
                                </thead>
                                <tbody id="back">
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-filled">
                    <div class="panel-heading">
                        Credit Info
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table id="tableExample3" class="table table-striped table-hover table-bordered table-condensed">
                                <thead>
                                <tr>
                                    <th style="color: #ffc771">Job Order CGN</th>
                                    <th style="color: #ffc771">Sales Payment CGN</th>
                                    <th style="color: #ffc771">Adjustment CGN</th>
                                    <th style="color: #ffc771">Edit</th>
                                    <th style="color: #ffc771">Paid Date</th>
                                    <th style="color: #ffc771">Credit Amount</th>
                                    <th style="color: #ffc771">Adjustment Amount</th>
                                    <th style="color: #ffc771">General Ledger</th>
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
            let id = $('#invoiceno').val();

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
                url: '/job-order/ajaxSearchDetail',
                // url: '/job-order/ajaxsearch',
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
                    $('#tableExample3').DataTable().clear().draw();

                    let debitInfo = response['debitInfo'];
                    let creditInfo = response['creditInfo'];
                    let adjInfo = response['adjInfo'];
                    console.log(debitInfo);


                    for (let key in debitInfo) {

                        // $("#tableExample4").DataTable().row.add([
                        //     response[key]["id"],response[key]["type"],response[key]["name"], response[key]["date"],
                        //     response[key]["invoice_no"], response[key]["debit_amount"], response[key]["remarks"],
                        //     response[key]["created_by"], response[key]["added_at"]
                        // ]).draw();
                        $("#tableExample4").DataTable().row.add([
                            debitInfo[key]["id"],debitInfo[key]["type"],
                            debitInfo[key]["name"], debitInfo[key]["date"],
                            debitInfo[key]["invoice_no"], debitInfo[key]["debit_amount"],
                            debitInfo[key]["created_by"], debitInfo[key]["added_at"],debitInfo[key]["status"]
                        ]).draw();
                    }

                    for (let key in creditInfo) {
                        if(creditInfo[key]["sp_cgn"])
                        {
                            edit = '<a href="/cash-collection/'+creditInfo[key]["sp_cgn"]+'/edit" target="_blank" class="pe-7s-edit"></a>';
                            $("#tableExample3").DataTable().row.add([
                                creditInfo[key]["job_order_cgn"],creditInfo[key]["sp_cgn"],'',edit,creditInfo[key]["paid_date"],
                                creditInfo[key]["credit_amount"], '', 'Cash'
                            ]).draw();
                        }

                    }
                    for (let key in adjInfo) {
                        if(adjInfo[key]["ca_cgn"])
                        {
                            edit = '<a href="/customer-adjustment/'+adjInfo[key]["ca_cgn"]+'/edit" target="_blank" class="pe-7s-edit"></a>';
                            $("#tableExample3").DataTable().row.add([
                                adjInfo[key]["job_order_cgn"],'',adjInfo[key]["ca_cgn"],edit,adjInfo[key]["adj_date"],
                                '', adjInfo[key]["adj_amount"],
                                adjInfo[key]["gl"]
                            ]).draw();
                        }

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
