@extends('admin.master')

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
                        <h3>Receipts</h3>
                    </div>
                </div>
                <hr>
            </div>
        </div>
        <div class="row"  id="adddate">
            <div class="col-sm-3">
                <input type="hidden" name="walkid" value="{{$walk->id}}" id="walk">

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
                {{--</div>--}}
                {{--<div class="col-sm-1"><i class="fa fa-check-circle-o" style="font-size: 25px !important;"></i></div>--}}

            </div>

            <div class="col-sm-4"></div>
            <div class="form-group col-sm-2" id="hiddenfrom" style="border-left: 1px solid darkslategrey;">
                <label >From</label>
                <input type="date" class="form-control"  id="start" required>
            </div>
            <div class="form-group col-sm-2 " id="hiddento">
                <label>To</label>
                <input type="date" class="form-control"  id="end" required>
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
                        Invoices
                    </div>
                    {{--<forms method="post" action="{{url('/sale/changeAmount')}}">--}}
                        <div class="panel-body">
                            <div class="table-responsive">

                                    {{ csrf_field() }}
                                    <table id="tableExample2" class="table table-hover table-bordered table-condensed">
                                        <thead>
                                        <tr>
                                            <th>CGN</th>
                                            <th>Name</th>
                                            <th>Date</th>
                                            <th>Invoice No</th>
                                            <th>Amount Due</th>
                                            <th>Amount Paid</th>
                                            <th style="text-align: center"><span class="pe-7s-tools" style="color: #ffc771 ; font-size:20px !important; "></span></th>
                                        </tr>
                                        </thead>
                                        <tbody id="back">
                                        </tbody>
                                    </table>
                                    <br>
                                    <input type="submit" class="btn btn-default hidden" style="color: #f6a821; float: right;" id="savebtn" value="Save">
                            </div>
                        </div>
                    {{--</forms>--}}
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            var name = '';
            var id;
            var arr=[];
            var arrlength;
            var checked_records = {};

            $( "#calldate" ).click(function(){
                arr=[];
                id = $("#callfunc option:selected").val();
                name = $("#callfunc option:selected").text();
                var start = $('#start').val();
                var end = $('#end').val();

                // $("#calldate,#hiddenfrom,#hiddento").removeClass("hidden");
                // $("#end").attr("disabled", true);
                // $("#calldate").addClass( "hidden" );

                var walkid =$("#walk").val();

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
                    url: '/sale/ajaxreceipt',
                    data: {id: id, walkid : walkid,start: start, end: end},

                    success: function (response) {

                        console.log(response);

                        /*var payment = response['payment'];

                        delete response['payment'];*/

                        var count = 0;

                        for (key in response)
                            count = count + 1;

                        for (var z = 0; z < count; z++) {
                            arr[z] ={"id": response[z]['id'] ,
                                "customer_id": response[z]['customer_id'],
                                "date": response[z]['dateofsale'],
                                "name": response[z]['name'],
                                "invoice_no": response[z]['invoiceno'],
                                "debit": response[z]['total_amount']
                                };
                        }

                        arrlength=count;

                        arr.sort(function(a, b){
                            var dateA=new Date(a.date), dateB=new Date(b.date);
                            return dateA-dateB //sort by date ascending
                        });

                        $('#tableExample2').DataTable().clear().draw();

                        for (var i = 0; i < count; i++)
                        {
                            // var inputfield ='<input name="amount['+i+']" type="text" class="forms-control" id="Inputamount'+arr[i]["id"]+'" pattern="[+-]?([0-9]+([.][0-9]*)?|[.][0-9]+)"  placeholder="0.00" name="paidamount" min="0" max="'+arr[i]["debit"]+'">';
                            var inputfield ='<input name="amount['+arr[i]["id"]+']" type="text" class="forms-control" id="Inputamount'+arr[i]["id"]+'" pattern="[+-]?([0-9]+([.][0-9]*)?|[.][0-9]+)"  placeholder="0.00" name="paidamount" min="0" max="'+arr[i]["debit"]+'">';
                            var editIcon = '<div class="checkbox checkbox-warning">\n' +
                                '                                    <input name="id['+i+']" value="'+arr[i]["id"]+'" id="checkbox5" type="checkbox" class="styled tosend" >\n' +
                                '                                    <label for="checkbox5">\n' +
                                '                                    </label>\n' +
                                '                                </div>';

                            var debit = '<div id="debit'+arr[i]["id"]+'">'+arr[i]["debit"]+'</div>';

                            $("#tableExample2").DataTable().row.add([
                                arr[i]["id"],arr[i]["name"], arr[i]["date"], arr[i]["invoice_no"], debit,inputfield,editIcon
                            ]).draw();
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(JSON.stringify(jqXHR));
                        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                    }
                });
            });

            // $( "#calldate" ).click(function()
            // {
            //
            //     var start = $('#start').val();
            //     var end = $('#end').val();
            //
            //     var filter=[];
            //
            //     var len=0;
            //
            //     for (var z = 0; z < arrlength; z++) {
            //         if(arr[z]['date'] >= start)
            //         {
            //             if(arr[z]['date'] <= end)
            //             {
            //                 filter[len]=arr[z];
            //                 len++;
            //             }
            //         }
            //     }
            //
            //     $('#tableExample2').DataTable().clear().draw();
            //
            //     for (var i = 0; i < len; i++)
            //     {
            //         var inputfield ='<input name="amount['+i+']" type="text" class="forms-control" id="Inputamount'+filter[i]["id"]+'" pattern="[+-]?([0-9]+([.][0-9]*)?|[.][0-9]+)"  placeholder="0.00" name="paidamount" max="'+filter[i]["debit"]+'">';
            //         var editIcon = '<div class="checkbox checkbox-warning">\n' +
            //             '                                    <input name="id['+i+']" value="'+filter[i]["id"]+'" id="checkbox5" type="checkbox" class="styled" >\n' +
            //             '                                    <label for="checkbox5">\n' +
            //             '                                    </label>\n' +
            //             '                                </div>';
            //
            //         var debit = '<div id="debit'+filter[i]["id"]+'">'+filter[i]["debit"]+'</div>';
            //
            //         $("#tableExample2").DataTable().row.add([
            //             filter[i]["id"] ,filter[i]["name"],filter[i]["date"], filter[i]["invoice_no"], debit, inputfield, editIcon
            //         ]).draw();
            //     }
            // });

            $("#hiddenfrom").on('change', function () {

                $("#end").removeAttr("disabled");
                /*}
                else
                {
                    $("#walkinfo1,#walkinfo2,#walkinfo3").addClass( "hidden" );
                    $("#custname,#custmob,#custemail").attr("disabled", true);
                }*/
            });

            $("#hiddento").on('change', function ()
            {
                $("#calldate").removeClass("hidden");

            });

            $('#tableExample2').on('click', '#checkbox5', function()
            {
                $("#savebtn").removeClass("hidden");

            });
            //
            // $('#back').children('tr').click(function(event)
            // {
            //     if (event.target.type !== 'checkbox') {
            //         $(':checkbox', this).trigger('click');
            //     }
            // });

            $('#tableExample2').on('click', '#checkbox5', function()
            {
                var checkid = $(this).val();
                var debit = $("#debit"+checkid).html();
                $("#Inputamount"+checkid).val(debit);
            });

            function get_records() {
                $('input.tosend:checkbox:checked').each(function () {
                    var checkid = $(this).val();
                    var update_amount = $("#Inputamount"+checkid).val();
                    checked_records[checkid] = update_amount;
                });
            }
            $( "#savebtn" ).click(function(){

                get_records();
                console.log(checked_records);

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
                    url: '/sale/changeAmount',
                    data: {amount: checked_records},

                    success: function (response) {
                        window.location.href = '/sale/receipt'
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(JSON.stringify(jqXHR));
                        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                    }
                });
            });


        });
    </script>
@endsection
