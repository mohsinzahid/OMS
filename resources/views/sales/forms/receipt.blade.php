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
                            <select class="form-control"style="width: 100%" required>
                                <option value="jo" selected> JO &emsp; Job Order(CTP)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="Inputinvoiceno">
                                Invoice No
                            </label>
                            <input type="text" class="form-control" id="invoiceno" pattern="\d*"
                                   placeholder="0" autofocus required>
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
                                    <th style="color: #ffc771">Paid Amount</th>
                                    <th style="color: #ffc771">Discount</th>
                                    <th style="color: #ffc771">Created By</th>
                                    <th style="color: #ffc771">Entry date</th>
                                    <th style="color: #ffc771">Status</th>
                                    <th style="color: #ffc771" class="pe-7s-config"></th>
                                    <th style="color: #ffc771" class="hidden"></th>
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

                    unique_id = 1;

                    for (key in response) {

                        job_id ='<div id="jobid'+unique_id+'">'+response[key]["id"]+'</div>';
                        date = '<input type="date" class="form-control"\n' + 'value=""  ' +
                            'name="date" id="date'+unique_id+'" required>';
                        debitamount = '<input value="'+response[key]["debit_amount"]+'" type="text" class="form-control"' +
                            ' id="debitamount'+unique_id+'" disabled>';
                        paidamount = '<input type="text" class="form-control" placeholder="0.00" id="paidamount'+unique_id+'" ' +
                            'name="paidamount" onkeyup=calculateDiscount('+unique_id+')>';
                        if (response[key]["status"] === 'paid')
                        {
                            button = '<button type="button" style="color: #ffc771" class="btn btn-warning btn-sm" ' +
                                'onclick="collect('+unique_id+')" id="submit'+unique_id+'" disabled>Collect</button>';
                        }
                        else{
                            button = '<button type="button" style="color: #ffc771" class="btn btn-warning btn-sm" ' +
                                'onclick="collect('+unique_id+')" id="submit'+unique_id+'">Collect</button>';
                        }

                        customer_id = '<input value="'+response[key]["customer_id"]+'" id="cust_id'+unique_id+'"' +
                            ' class="hidden" required>';

                        discount = '<input value="" type="text" class="form-control" id="discount'+unique_id+'" disabled' +
                            ' required>';

                        $("#tableExample4").DataTable().row.add([
                           job_id ,response[key]["type"],response[key]["name"], date,
                            response[key]["invoice_no"],debitamount ,paidamount,discount, response[key]["created_by"],
                            response[key]["added_at"],response[key]["status"], button, customer_id
                        ]).draw();
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(JSON.stringify(jqXHR));
                    console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                }
            });
        });
        $(function () {
            $('[autofocus]').focus()
        });

        function collect(id) {
            var paidamount =$("#paidamount"+id).val();
            var date =$("#date"+id).val();
            if(paidamount && date) {
                $("#submit"+id).attr("disabled", true);
                var customer_id = $("#cust_id" + id).val();
                var jobid = $("#jobid" + id).text();
                var discount = $("#discount"+ id).val();
                console.log(discount);

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
                    type: 'POST',
                    url: '/job-order/ajaxcollect',
                    data: {customerid: customer_id, date: date, job_order_no: jobid, amount: paidamount, discount:discount},

                    success: function (response) {
                        console.log(response);
                        if (response === 1) {
                            toastr.success('Receipt Collected successfully');
                        }
                    },
                    error: function (XMLHttpRequest, jqXHR, textStatus, errorThrown) {
                        if (XMLHttpRequest.readyState == 0) {
                            // Network error (i.e. connection refused, access denied due to CORS, etc.)
                            toastr.error('Network Connection Refused');
                            $("#submit"+id).removeAttr("disabled");
                        }
                        console.log(JSON.stringify(jqXHR));
                        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                    }
                });
            }
            else{
                toastr.warning('Please Enter Paid Amount');
            }
        }

        function calculateDiscount(id)
        {
            var debitamount = $('#debitamount'+id).val();
            var paidamount = $('#paidamount'+id).val();
            $('#discount'+id).val(debitamount - paidamount);
        }

    </script>

@endsection