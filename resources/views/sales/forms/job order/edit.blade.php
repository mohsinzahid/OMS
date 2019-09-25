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
                        <h3>Edit a Sale Inventory</h3>
                    </div>
                    <div style="float: right">
                        <a href="/customer/sale/{{$inventory->id}}/delete" class="btn btn-w-md btn-danger">Delete</a>
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
                    <div class="panel-body">
                        <form method="post" action="{{ url('/job-order/update') }}" autocomplete="off">
                            {{ csrf_field() }}

                            <input type="hidden" name="productItemCount" value="{{$len}}" id="counter">
                            <input type="hidden" name="walkid" value="{{$walk->id}}" id="walk">
                            <input type="hidden" name="saleid" value="{{$saleinventoryid}}">
                            <input type="hidden" name="oldcustid" value="{{$inventory->customer_id}}">

                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Customer</label>
                                        <select class="form-control" name="customer_id" id="callfunc" onchange='getrates();'
                                                required>
                                            <option value="" selected disabled hidden>Choose here</option>
                                            @if(count($customer)>0)
                                                @foreach($customer as $customers)
                                                    @if($customers->id === $inventory->customer_id)
                                                        <option value="{{$customers->id}}" selected>{{$customers->id}} &emsp; {{$customers->name}}</option>
                                                    @else
                                                        <option value="{{$customers->id}}">{{$customers->id}} &emsp; {{$customers->name}}</option>
                                                    @endif
                                                @endforeach
                                            @else
                                                <option>No Customer exist</option>
                                            @endif
                                        </select>
                                    </div>
                                    @if($inventory->customer_id === $walk->id)
                                        <div id="walkinfo1">
                                            <label>Customer Name</label>
                                            <input type="text" class="form-control" name="walkname" id="custname" pattern="[A-Za-z ]{1,32}" value="{{$walkincustomer->name}}" required>
                                        </div>
                                        <div id="walkinfo2">
                                            <label>Mobile No</label>
                                            <input type="text" class="form-control" name="walkmobile" id="custmob" pattern="\d*" value="{{$walkincustomer->mobile}}">
                                        </div>
                                        <div id="walkinfo3">
                                            <label>Email</label>
                                            <input type="email" class="form-control" placeholder="abc@abc.com" id="custemail" name="walkemail" value="{{$walkincustomer->email}}">
                                        </div>
                                    @else
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
                                    @endif
                                </div>
                                <div class="col-sm-4"></div>
                                <div class="col-sm-4">
                                    <div class="form-group"><label >Date</label> <input type="date" class="form-control"
                                        value="{{$inventory->dateofsale}}"  name="dateofsale" required></div>
                                    <div class="form-group"><label >Invoice No</label> <input type="text"
                                          class="form-control" pattern="\d*"  value="{{$inventory->invoiceno}}"
                                            name="invoiceno" required></div>
                                </div>
                            </div>
                            <hr>
                            <div id="in">
                                @if(sizeof($item)>0)
                                    @for($i =0; $i < sizeof($item) ;$i++ ){{--//for priting lable on the top--}}
                                        <div class="row">
                                            <div class="form-group col-sm-2">
                                                @if($i===0)
                                                    <label>Size</label>
                                                @endif
                                                <select class="form-control" name="data[size][{{$i}}]" id="sizes{{$i}}"
                                                        style="width: 100%" onchange='Calculate("{{$i}}");tot()' required>
                                                    <option value="" selected disabled hidden>Choose here</option>
                                                    @if(count($size)>0)
                                                        @foreach($size as $sizes)
                                                            @if($item[$i]->size_id === $sizes->id)
                                                                <option selected value="{{$sizes->id}}">{{$sizes->size}}</option>
                                                            @else
                                                                <option value="{{$sizes->id}}">{{$sizes->size}}</option>
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        <option>No sizes exist</option>
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="form-group col-sm-4">
                                                @if($i===0)
                                                    <label>File name</label>
                                                @endif
                                                <input type="text" class="form-control" value="{{$item[$i]->description}}" name="data[description][{{$i}}]" required>
                                            </div>
                                            <div class="form-group col-sm-1">
                                                @if($i===0)
                                                    <label>Color</label>
                                                @endif
                                                <input class="form-control" name="data[color][{{$i}}]"
                                                       onchange='Calculate("{{$i}}");tot()' id="color{{$i}}"
                                                       style="width: 100%" value="{{$item[$i]->color}}" required>

                                            </div>

                                            <div class="form-group col-sm-1">
                                                @if($i===0)
                                                    <label >Set</label>
                                                @endif
                                                <input type="text" class="form-control" pattern="\d*" id="set{{$i}}"
                                                       name="data[set][{{$i}}]" value="{{$item[$i]->set}}"
                                                       onchange='Calculate("{{$i}}");tot()' required>
                                            </div>

                                            <div class="form-group col-sm-2">
                                                @if($i===0)
                                                    <label >saleprice</label>
                                                @endif
                                                <input type="text" class="form-control"
                                                       pattern="[+-]?([0-9]+([.][0-9]*)?|[.][0-9]+)" id="saleprice{{$i}}"
                                                       name="data[saleprice][{{$i}}]" value="{{$item[$i]->saleprice}}"
                                                       onchange='Calculate({{$i}});tot()' required>

                                            </div>
                                            <div class="form-group col-sm-2">
                                                @if($i===0)
                                                    <label>Amount</label>
                                                @endif
                                                <input type="text" class="form-control"  name="data[amount][{{$i}}]"
                                                       id="answer{{$i}}" placeholder="0.00" value="0" readonly>

                                            </div>
                                            <input type="hidden" value="{{$item[$i]->id}}" name="data[itemid][{{$i}}]">

                                        </div>
                                    @endfor
                                @endif
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-2"></div>
                                <div class="col-sm-4"></div>
                                <div class="col-sm-1"></div>
                                <div class="col-sm-1"></div>
                                <div class="col-sm-2" style="text-align: center"> <label>Total Amount</label></div>
                                <div class="form-group col-sm-2">
                                    <input type="text" class="form-control" name="total_amount" id="total" placeholder="0.00" readonly>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-2">
                                </div>
                                <div class="col-sm-4"></div>
                                <div class="col-sm-1"></div>
                                <div class="col-sm-1"></div>
                                <div class="col-sm-2"></div>
                                <div class="col-sm-2"></div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-2">
                                    <button type="submit" class="btn btn-default" style="color: #f6a821">Submit</button>
                                </div>
                                <div class="form-group col-sm-2">
                                    <label>Prepared By <span class="pe-7s-star" style="color: #f6a821"></span></label>
                                    <select class="form-control" name="employee_id" style="width: 100%" required>
                                        <option value="" selected disabled hidden>Choose here</option>
                                        @if(count($employee)>0)
                                            @foreach($employee as $employees)
                                                @if($employees->id === $inventory->employee_id)
                                                    <option value="{{$employees->id}}" selected>{{$employees->name}}</option>
                                                @else
                                                    <option value="{{$employees->id}}">{{$employees->name}}</option>
                                                @endif
                                            @endforeach
                                        @else
                                            <option>No employee exist</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var walkid;
        var rates = [];
        $(document).ready(function () {
            // var Count = $('#counter').val();
            walkid =$("#walk").val();
            getrates();
   /*         for (var i = 0; i < Count; i++) {
                Calculate(i);
            }
            tot();*/

        });

        function Calculate(locate)
        {
            var id = $('#sizes'+locate).val();
            var set = document.getElementById('set'+locate).value;
            var color = document.getElementById('color'+locate).value;
            // var saleprice = document.getElementById('saleprice'+locate).value;
            var saleprice = rates[id];
            var amount = (saleprice * (set * color));
            document.getElementById('saleprice'+locate).value=parseFloat(saleprice);
            document.getElementById('answer'+locate).value=parseFloat(amount);
        }

        function tot() {
            var counts = $('#counter').val();
            var totalamount =0;
            for(var i = 0 ; i<counts ; i++)
            {
                var val = document.getElementById('answer'+i).value;
                // totalamount = parseFloat(totalamount) + parseFloat(val);
                totalamount = Math.round(parseFloat(totalamount) + parseFloat(val));
            }
            /*$("#totalpaid").attr({
                "max" : totalamount        // substitute your own
            });*/
            document.getElementById('total').value=parseFloat(totalamount);
        }

        function getrates () {
            var value = $('#callfunc').val();
            console.log(value);
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
                    for(var i = 0 ; i<counts ; i++)
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
        }

    </script>
@endsection