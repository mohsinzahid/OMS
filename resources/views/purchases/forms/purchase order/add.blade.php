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
                        <h3>Add a new Purchase Inventory</h3>
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
                        Inventory form
                    </div>
                    <div class="panel-body">
                        <form method="post" action="{{ url('/purchase/store') }}" autocomplete="off">
                            {{ csrf_field() }}

                            <input type="hidden" name="productItemCount" value="1" id="counter">
                            <input type="hidden" name="productItemArray" value="1" id="array">
                            <div class="row">
                                <div class="col-sm-4 form-group">
                                    <label>Supplier</label>
                                    <select class="form-control" name="vendor_id" required>
                                        <option value="" selected disabled hidden>Choose here</option>
                                        @if(count($vendor)>0)
                                            @foreach($vendor as $vendors)
                                                <option value="{{$vendors->id}}">{{$vendors->id}} &emsp; {{$vendors->name}}</option>
                                            @endforeach
                                        @else
                                            <option>No Vendor exist</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="col-sm-4"></div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label >Date</label>
                                        <input type="date" class="form-control"  name="dateofpurchase" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Invoice No</label>
                                        <input type="text" class="form-control" pattern="\d*" name="invoiceno" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Received By</label>
                                        <input type="text" class="form-control" placeholder="Enter name of receiver"
                                               name="receivedby" required>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div id="in">
                                <div class="row">
                                    <div class="form-group col-sm-3">
                                        <label>Size</label>
                                        <select class="form-control" name="data[size][1]" id="sizes" style="width: 100%" required>
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
                                        <label>Quantity</label>
                                        <input type="text" class="form-control"  name="data[quantity][1]" pattern="\d*"
                                               id="quantity1" onchange='Calculate(1);tot();'  required>
                                    </div>
                                    {{--<div class="form-group col-sm-3"><label>Description</label><input type="text" class="form-control" name="data[description][1]" required></div>--}}
                                    <div class="form-group col-sm-3">
                                        <label>Cost Price</label>
                                        <input type="text" class="form-control" name="data[costprice][1]" id="costprice1"
                                               pattern="[+-]?([0-9]+([.][0-9]*)?|[.][0-9]+)" placeholder="0.00"
                                               onchange='Calculate(1);tot()' required>
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <label>Amount</label>
                                        <input type="text" class="form-control"  name="data[amount][1]" id="answer1"
                                               placeholder="0.00" value="0" readonly>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="form-group col-sm-2">
                                    <button type="submit" class="btn btn-w-md btn-success">Submit</button>
                                </div>
                                <div class="col-sm-1">
                                    <label>Remarks</label>
                                </div>
                                <div class="form-group col-sm-4">
                                    <textarea class="form-control" rows="3" name="remarks" placeholder="Add Remarks"
                                              required>
                                    </textarea>
                                </div>
                                <div class="col-sm-2">
                                    <label style="float: right;">Total Amount</label>
                                </div>
                                <div class="form-group col-sm-2">
                                    <input type="text" class="form-control" id="total" placeholder="0.00" disabled>
                                </div>
                                <div class="col-sm-1">
                                    <span class="pe-7s-plus" style="font-size: 20px !important; float: right" onclick="addRow()"></span>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>


         function Calculate(locate)
         {
             var quantity = document.getElementById('quantity'+locate).value;
             var costprice = document.getElementById('costprice'+locate).value;
             var amount = costprice * quantity;
             document.getElementById('answer'+locate).value=parseFloat(amount);
         }



        //Append Row

        var array = [1];
         var counter=2;

        function addRow(){
            $('#counter').val( function(i, oldval) {
                return ++oldval;
            });
            var options = $('#sizes').html();
            array.push(counter);
            var htmlToAppend = '<div class="row" id="row'+counter+'">\n' +
                '                                    <div class="form-group col-sm-3">\n' +
                '                                        <select class="form-control" name="data[size]['+counter+']" id="sizes" style="width: 100%" required>\n'+ options+
                '                                        </select>\n' +
                '                                    </div>\n' +
                '                                    <div class="form-group col-sm-3">\n' +
                '                                        <input type="text" class="form-control"  name="data[quantity]['+counter+']" pattern="\\d*" id="quantity'+counter+'" onchange="Calculate('+counter+');tot()"  required>\n' +
                '                                    </div>\n' +
                // '                                    <div class="form-group col-sm-3">\n' +
                // '                                        <input type="text" class="form-control"  name="data[description]['+counter+']" required>\n' +
                // '                                    </div>\n' +
                '                                    <div class="form-group col-sm-3">\n' +
                '                                        <input type="text" class="form-control"  name="data[costprice]['+counter+']" id="costprice'+counter+'" pattern="[+-]?([0-9]+([.][0-9]*)?|[.][0-9]+)"  placeholder="0.00" onchange="Calculate('+counter+');tot()" required>\n' +
                '                                    </div>\n' +
                '                                    <div class="form-group col-sm-2">\n' +
                '                                        <input type="text" class="form-control"  name="data[amount]['+counter+']" id="answer'+counter+'" placeholder="0.00" value="0" readonly>\n' +
                '                                    </div>\n' +'<div class="col-sm-1" > <span class="pe-7s-close-circle"  style="font-size: 20px !important; float: left" onclick="deleteRow('+counter+');tot()"></span></div>'+
                '               </div>\n';

            $('#in').append(htmlToAppend);

            $('#array').val(array.join(','));

            counter++;
        }

        function deleteRow(nth){
            $('#counter').val( function(i, oldval) {
                return --oldval;
            });
            array.splice($.inArray(nth,array),1);
            $("#row"+nth).remove();
            $('#array').val(array.join(','))
        }

        function tot() {
            var array = $('#array').val();
            var counts = $('#counter').val();
            var totalamount =0;
            var num=0;
            for(var i = 0 ; i<counts ; i++)
            {
                var val = document.getElementById('answer'+array[num]).value;
                totalamount = parseFloat(totalamount) + parseFloat(val);
                num+=2;
            }
            document.getElementById('total').value=parseFloat(totalamount);
        }


    </script>
@endsection
