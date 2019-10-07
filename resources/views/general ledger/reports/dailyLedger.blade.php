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
                        <h3>Submitted Jobs</h3>
                    </div>
                </div>
                <hr>
            </div>
        </div>
        <div class="row"  id="adddate">
            <div class="col-sm-3"></div>

            <div class="col-sm-4"></div>
            <div class="form-group col-sm-2" id="hiddenfrom" style="border-left: 1px solid darkslategrey;">
                <label >From</label>
                <input type="date" class="form-control"  id="start" required>
            </div>
            <div class="col-sm-1 hidden" id="calldate">
                <button class="btn btn-default"  style="margin-top: 25px">
                    <i class="fa fa-check-circle-o" style="font-size: 16px !important; color: #ffc771"></i>
                </button>
            </div>

        </div>
        <hr>
        <div class="row">
            <div class="col-lg-">
                <div class="panel panel-filled">
                    <div class="panel-heading">
                        Daily Submitted Records
                    </div>
                    <div class="panel-body">
                        <p>A list of all records submitted in JO, CC, CADJ, PCF.</p>

                        <div class="table-responsive">
                            <table  class="table table-hover table-striped table-bordered table-condensed">
                                <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>CGN</th>
                                    <th>Form Type</th>
                                    <th>Added At</th>
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
        $( "#calldate" ).click(function(){
            var start = $('#start').val();
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
                data: {start: start},
                url: '/general-ledger-reports/ajax-daily-ledger',

                success: function (response) {
                    console.log(response);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(JSON.stringify(jqXHR));
                    console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                }
            });
        });

        $("#hiddenfrom").on('change', function ()
        {
            var min = $(this).val();
            $("#hiddento").attr("min", min);
            $("#hiddento").removeClass("hidden");
        });

        $("#hiddento").on('change', function ()
        {
            $("#calldate").removeClass("hidden");

        });
    </script>
@endsection

