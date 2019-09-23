@extends('admin.master')

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
                        <h3>Add a new Job Order</h3>
                    </div>
                </div>
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="header-title">
                    {{--                    @if($msg)
                                            <h3>Stock Added Successfully.</h3>
                                        @endif--}}
                </div>
                <div class="panel panel-filled">
                    <div class="panel-heading">
                        Job Order
                    </div>
                    <div class="panel-body" id="outprint">
                        <form method="post" action="{{ url('/sales/forms/job-order/store') }}" autocomplete="off">
                            {{ csrf_field() }}

                            <input type="hidden" name="productItemCount" value="1" id="counter">
                            <input type="hidden" name="productItemArray" value="1" id="array">
                            <input type="hidden" name="walkid" value="{{$walk->id}}" id="walk">
                            {{--<input type="hidden" name="employee" value="{{Auth::user()->name}}">--}}

                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Customer</label>
                                        <select class="form-control" name="customer_id" id="callfunc" autofocus required>
                                            <option value="" selected disabled hidden>Choose here</option>
                                            @if(count($customer)>0)
                                                @foreach($customer as $customers)
                                                    <option value="{{$customers->id}}">{{$customers->name}} &emsp; {{$customers->id}} </option>
                                                @endforeach
                                            @else
                                                <option>No Customer exist</option>
                                            @endif
                                        </select>
                                    </div>
                                    <div class="hidden" id="walkinfo1">
                                        <label>Customer Name</label>
                                        <input type="text" class="form-control" name="walkname" id="custname" pattern="[A-Za-z ]{1,32}" disabled required>
                                    </div>
                                    <div class="hidden" id="walkinfo2">
                                        <label>Mobile No</label>
                                        <input type="text" class="form-control" name="walkmobile" id="custmob" pattern="\d*" disabled>
                                    </div>
                                    <div class="hidden" id="walkinfo3">
                                        <label>Email</label>
                                        <input type="email" class="form-control" placeholder="abc@abc.com" id="custemail" name="walkemail" disabled="disabled">
                                    </div>
                                </div>
                                <div class="col-sm-4"></div>
                                <div class="col-sm-4">
                                    <div class="form-group"><label >Date</label> <input type="date" class="form-control" pattern="^(1[0-2]|0[1-9])/(3[01]|[12][0-9]|0[1-9])/[0-9]{4}$"  name="dateofsale" required></div>
                                    <div class="form-group"><label >Invoice No</label> <input type="text" class="form-control" pattern="\d*"  name="invoiceno" required></div>
                                </div>
                            </div>

                            <hr>
                            <div id="in">
                                <div class="row">
                                    <div class="form-group col-sm-2">
                                        <label>Size</label>
                                        <select class="form-control" name="data[size][1]" id="sizes1"
                                                onchange='Calculate("1");tot()' style="width: 100%" required>
                                            <option value="" selected disabled hidden>Choose here</option>
                                            @if(count($size)>0)
                                                @foreach($size as $sizes)
                                                    <option value="{{$sizes->id}}">{{$sizes->size}}</option>
                                                @endforeach
                                            @else
                                                <option>No sizes exist</option>
                                            @endif
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-3">
                                        <label>File name</label>
                                        <input type="text" class="form-control" name="data[description][1]" required>
                                    </div>
                                    <div class="form-group col-sm-1">
                                        <label>Color</label>
                                        <select class="form-control" name="data[color][1]" id="color1" style="width: 100%"
                                                onchange='Calculate("1");tot()' required>
                                            <option value="0" selected disabled hidden>Choose here</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-1">
                                        <label >Set</label>
                                        <input type="text" class="form-control" pattern="\d*" id="set1"  name="data[set][1]"
                                               onchange='Calculate("1");tot()' required>
                                    </div>

                                    <div class="form-group col-sm-2">
                                        <label >saleprice</label>
                                        <input type="text" class="form-control" pattern="[+-]?([0-9]+([.][0-9]*)?|[.][0-9]+)"
                                               id="saleprice1" value="0" name="data[saleprice][1]"   readonly required>
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <label>Amount</label>
                                        <input type="text" class="form-control" id="answer1" value="0"
                                        placeholder="0.00" readonly>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="form-group col-sm-2">
                                    <button type="submit" class="btn btn-warning" {{--style="color: #f6a821"--}}>Submit</button>
                                </div>

                                <div class="checkbox checkbox-warning col-sm-1">
                                    <input id="checkbox5" type="checkbox" name="replace" class="styled" value="1">
                                    <label for="checkbox5">
                                       Replace
                                    </label>
                                </div>

                                <div class="form-group col-sm-2">
                                    <label style="float: right">Prepared By
                                        <span class="pe-7s-star" style="color: #f6a821"></span>
                                    </label>
                                </div>
                                <div class="form-group col-sm-2">
                                    <select class="form-control" name="employee" style="width: 100%" required>
                                        <option value="" selected disabled hidden>Choose here</option>
                                        @if(count($employee)>0)
                                            @foreach($employee as $employees)
                                                <option value="{{$employees->id}}">{{$employees->name}}</option>
                                            @endforeach
                                        @else
                                            <option>No employee exist</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group col-sm-2" style="text-align: right">
                                    <label>Total Amount</label>
                                </div>
                                <div class="form-group col-sm-2">
                                    <input type="text" class="form-control" name="totalamount" id="total" placeholder="0.00" readonly>
                                </div>
                                <div class="col-sm-1"></div>
                                <span class="pe-7s-plus"  style="font-size: 20px !important; float: right; color: #f6a821;" onclick="addRow()"></span>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>

        //Append Row
        var rates = [];

        var array = [1];
        var counter=2;
        var walkid;
        $(document).ready(function () {
             walkid =$("#walk").val();
        });

        function addRow(){
            $('#counter').val( function(i, oldval) {
                return ++oldval;
            });
            var options = $('#sizes1').html();
            array.push(counter);

            var htmlToAppend = '<div class="row" id="row'+counter+'">\n'+
                '                <div class="form-group col-sm-2">\n'+
                '                   <select class="form-control" name="data[size]['+counter+']" id="sizes'+counter+'" ' +
                '                           onchange=\'Calculate('+counter+');tot()\' style="width: 100%" required>\n'+options+
                '                   </select>\n'+
                '                </div>\n'+
                '                <div class="form-group col-sm-3">\n'+
                '                   <input type="text" class="form-control" name="data[description]['+counter+']" required>\n'+
                '                </div>\n'+
                '                <div class="form-group col-sm-1">\n'+
                '                   <select class="form-control" name="data[color]['+counter+']" id="color'+counter+'" ' +
                '                           style="width: 100%" onchange=\'Calculate('+counter+');tot()\' required>\n'+
                '                       <option value="0" selected disabled hidden>Choose here</option>\n'+
                '                       <option value="1">1</option>\n'+
                '                       <option value="2">2</option>\n'+
                '                       <option value="3">3</option>\n'+
                '                       <option value="4">4</option>\n'+
                '                       <option value="5">5</option>\n'+
                '                   </select>\n'+
                '                </div>\n'+
                '                <div class="form-group col-sm-1">\n'+
                '                    <input type="text" class="form-control" pattern="\\d*" id="set'+counter+'"  ' +
                '                           name="data[set]['+counter+']" onchange=\'Calculate('+counter+');tot()\'' +
                '                           required>\n'+
                '                </div>\n'+
                '               <div class="form-group col-sm-2">\n'+
                '                   <input type="text" class="form-control" id="saleprice'+counter+'" ' +
                '                           name=data[saleprice]['+counter+'] value="0" readonly required>\n'+
                '               </div>\n'+
                '               <div class="form-group col-sm-2">\n'+
                '                   <input type="text" class="form-control" placeholder="0.00" id="answer'+counter+'" ' +
                '                          value="0" readonly>\n'+
                '               </div>\n'+
                '               <div class="col-sm-1" >\n' +
                '                   <span class="pe-7s-close-circle"  style="font-size: 20px !important; float: left;\n' +
                '                       color: #f6a821" onclick="deleteRow('+counter+')">' +
                '                   </span>' +
                '               </div>'+
                '             </div>\n';

            $('#in').append(htmlToAppend);
            $('#array').val(array.join(','));
            counter++;
        }

        function Calculate(locate)
        {
            var id = $('#sizes'+locate).val();
            var set = document.getElementById('set'+locate).value;
            var color = document.getElementById('color'+locate).value;
            var saleprice = rates[id];
            var amount = (saleprice * (set * color));
            document.getElementById('saleprice'+locate).value=parseFloat(saleprice);
            document.getElementById('answer'+locate).value=parseFloat(amount);
        }

        function tot() {
            var counts = $('#counter').val();
            var totalamount =0;
            for(var i = 1 ; i<=counts ; i++)
            {
                var val = document.getElementById('answer'+i).value;
                totalamount = Math.round(parseFloat(totalamount) + parseFloat(val));
            }
            document.getElementById('total').value=parseFloat(totalamount);
        }

        function deleteRow(nth){
            $('#counter').val( function(i, oldval) {
                return --oldval;
            });
            array.splice($.inArray(nth,array),1);
            $("#row"+nth).remove();
            $('#array').val(array.join(','))
        }


        $("#callfunc").on('change', function () {
            var value = $(this).val();
            if(value === walkid)
            {
                $("#walkinfo1,#walkinfo2,#walkinfo3").removeClass("hidden");
                $("#custname,#custmob,#custemail").removeAttr("disabled");
            }
            else
            {
                $("#walkinfo1,#walkinfo2,#walkinfo3").addClass( "hidden" );
                $("#custname,#custmob,#custemail").attr("disabled", true);
            }

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
                url: '/joborder/ajax/get-saleprice',
                data: {id: value},

                success: function (response) {

                    console.log(response);
                    rates = [];
                    rates = response;
                    var counts = $('#counter').val();
                    for(var i = 1 ; i<=counts ; i++)
                    {
                        Calculate(i);
                    }
                    tot();

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

        $(document).on('keydown',function (e) {
            if(e.keyCode == 113) {
                addRow();
            }
        });


    </script>
@endsection
