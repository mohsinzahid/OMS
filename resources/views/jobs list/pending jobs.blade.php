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
                        <h3>Pending Jobs</h3>
                    </div>
                </div>
                <hr>
            </div>
        </div>
        <div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header text-center">
                        <h4 class="modal-title">CGN</h4>
                        <small>Computer Generated Number.</small>
                    </div>
                    <div class="modal-body" id="modalin" style="text-align: center">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>


        <div class="row" id="main">
            <div class="col-lg-12">
                <div class="panel panel-filled">
                    <div class="panel-heading">
                        Items
                    </div>
                    <div class="panel-body">
                        <p>A list of all sale records which are pending.</p>
                        <div class="table-responsive">
                            <table id="tableExample4"  class="table table-hover table-bordered table-condensed">
                                <thead>
                                <tr>
                                    <th style="color: #ffc771 ">Type</th>
                                    <th style="color: #ffc771 ">Name</th>
                                    <th style="color: #ffc771 ">Date</th>
                                    <th style="color: #ffc771 ">Invoice No</th>
                                    <th style="color: #ffc771 ">Employee</th>
                                    <th style="color: #ffc771 ">Added At</th>
                                    <th style="color: #ffc771 ">Size</th>
                                    <th style="color: #ffc771 ">Description</th>
                                    <th style="color: #ffc771 ">Set</th>
                                    <th style="color: #ffc771 ">Color</th>
                                    <th style="color: #ffc771 ">Status</th>
{{--                                    <th style="text-align: center"><span class="pe-7s-config" style="color: #ffc771 ; font-size:20px !important; "></span></th>--}}
                                    <th style="text-align: center"><span class="pe-7s-print" style="color: #ffc771 ; font-size:20px !important; "></span></th>
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
        function data () {

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
                url: '/jobs-list/get-pending-jobs',

                success: function (response) {
                    $('#tableExample4').DataTable().clear().draw();

                    // console.log(response);
                    var previd = -1;
                    for(key in response)
                    {
                        if(response[key]['saleinventory_id'] !== previd)
                        {
                            printbutton = '<button class="btn btn-w-md btn-success" style="color: #31b887; !important" ' +
                                'data-toggle="modal" id="submit'+response[key]['saleinventory_id']+'" data-target="#myModal1' +response[key]['saleinventory_id']+ '">Done</button>\n'+
                                ' <div class="modal fade" id="myModal1' +response[key]['saleinventory_id']+ '" tabindex="-1" ' +
                                'role="dialog" aria-hidden="true" style="display: none;">\n' +
                                '                                    <div class="modal-dialog modal-lg">\n' +
                                '                                        <div class="modal-content">\n' +
                                '                                            <div class="modal-header text-center">\n' +
                                '                                                <h4 class="modal-title">Change Status</h4>\n' +
                                '                                                <small>Update Record after Manipulation of Job.</small>\n' +
                                '                                            </div>\n' +
                                '                                            <div class="modal-body">\n' +
                                '                                                <p><strong>Are You Sure you want to ' +
                                'change Status of Prnting!</strong>   After Clicking on <strong>Save Changes</strong> ' +
                                'you will be no longer able to edit this record and stock will updated\n' +
                                'So,make sure you have properly mnipulate the job after that click on it!\n' +
                                '                                                   </p>\n' +
                                '                                            </div>\n' +
                                '                                            <div class="modal-footer">\n' +
                                '                                                <button type="button" class="btn btn-default" ' +
                                                                                        'data-dismiss="modal">Close</button>\n' +
                                '                                                <button type="button" class="btn btn-accent" ' +
                                'value="' + response[key]['saleinventory_id'] + '" id="print" data-dismiss="modal" >Save changes</button>\n' +
                                '                                            </div>\n' +
                                '                                        </div>\n' +
                                '                                    </div>\n' +
                                '                                </div>\n';
                            $("#tableExample4").DataTable().row.add([
                                response[key]["type"],response[key]["name"],response[key]["date"],
                                response[key]['invoice_no'], response[key]["created_by"], response[key]["added_at"],
                                response[key]["size"], response[key]["description"], response[key]["set"],
                                response[key]["color"], response[key]['status'],printbutton
                            ]).draw();
                            previd = response[key]['saleinventory_id'];

                        }
                        else
                        {
                            $("#tableExample4").DataTable().row.add([
                                response[key]["type"],response[key]["name"],response[key]["date"],
                                response[key]['invoice_no'], response[key]["created_by"], response[key]["added_at"],
                                response[key]["size"], response[key]["description"], response[key]["set"],
                                response[key]["color"], '','']).draw();
                        }
                    }

                    $("#tableExample4").DataTable().page('last').draw('page');
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(JSON.stringify(jqXHR));
                    console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                }
            });
        }

        $(document).ready(function ()
        {
            data();
            $('#tableExample4').DataTable({
                "dom": "<'row'<'col-sm-6'l><'col-sm-6'f>>t<'row'<'col-sm-6'i><'col-sm-6'p>>",
                "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
                "iDisplayLength": 10,
                "ordering": false
            });

        });

        $("#tableExample4").on('click','#print', function ()
        {
            var idvalue = $(this).val();
            console.log(idvalue);

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
                url: '/job-list/ajax-update-job-status',
                data: {id: idvalue},

                success: function (response) {
               /*     $("#modalin").empty();
                    var html  ='<p><strong>'+idvalue+'</strong></p>';
                    $("#modalin").append(html);

                    $("#myModal2").modal('show');*/
                    toastr.success('Print Status Updated successfully');
                    $("#submit"+idvalue).attr("disabled",true);

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


        });

        setInterval(function()
        {
           data();
        }, 120000);
    </script>

@endsection
