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
                        <h3>Edit a Purchase Order</h3>
                    </div>
                    <div style="float: right"><a href="/purchase-order/{{$inventory->id}}/delete" class="btn btn-w-md btn-danger">Delete</a></div>
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
                        <form method="post" action="{{ url('/purchase-order/update') }}" autocomplete="off">
                            {{ csrf_field() }}

                            <input type="hidden" name="productItemCount" value="{{$itemlength}}" id="counter">
                            <input type="hidden" name="productItemArray" value="1" id="array">
                            <input type="hidden" name="productItemOldCount" value="{{$itemlength}}" id="oldcounter">
                            <input type="hidden" name="productItemOldTotal" value="{{$itemlength}}">
                            <input type="hidden" name="deleteproductItemID" value="" id="deleteid">
                            <div class="row">
                                <div class="col-sm-4 form-group">
                                    <label>Supplier</label>
                                    <select class="form-control" name="vendor_id" required>
                                        <option value="" selected disabled hidden>Choose here</option>
                                        @if(count($vendor)>0)
                                            @foreach($vendor as $vendors)
                                                @if($inventory-> vendor_id === $vendors->id)
                                                    <option selected value="{{$vendors->id}}">{{$vendors->name}}</option>
                                                @else
                                                    <option value="{{$vendors->id}}">{{$vendors->name}}</option>
                                                @endif
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
                                        <input type="date" class="form-control"  name="dateofpurchase"
                                               value="{{$inventory->dateofpurchase}}" required>
                                    </div>
                                    <div class="form-group">
                                        <label >Invoice No</label>
                                        <input type="text" class="form-control" pattern="\d*"
                                               value="{{$inventory->invoice_no}}" name="invoiceno" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Received By</label>
                                        <input type="text" class="form-control" placeholder="Enter name of receiver"
                                               name="receivedby" value="{{$inventory->received_by}}" required>
                                    </div>
                                </div>
                                 <input type="hidden" value="{{$inventory->id}}" name="inventoryid">
                            </div>
                            <hr>
                            <div id="in">
                                @if($itemlength>0)
                                    @for($i =0; $i < $itemlength ;$i++)
                                        <div class="row" id="row{{$i+1}}">
                                            <div class="form-group col-sm-3">
                                                @if($i === 0)
                                                    <label>Size</label>
                                                @endif
                                                <select class="form-control" name="data[size][{{$i+1}}]" id="sizes" style="width: 100%" required>
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
                                            <div class="form-group col-sm-3">
                                                @if($i === 0)
                                                    <label>Quantity</label>
                                                @endif
                                                <input type="text" class="form-control"  name="data[quantity][{{$i+1}}]" pattern="\d*" id="quantity{{$i+1}}" value="{{$item[$i]->quantity}}" onchange='Calculate({{$i+1}});tot();'  required>
                                            </div>
                                            <div class="form-group col-sm-3">
                                                @if($i === 0)
                                                    <label>Cost Price</label>
                                                @endif
                                                <input type="text" class="form-control"  name="data[costprice][{{$i+1}}]" id="costprice{{$i+1}}" pattern="[+-]?([0-9]+([.][0-9]*)?|[.][0-9]+)"  placeholder="0.00" value="{{$item[$i]->costprice}}" onchange='Calculate($i+1);tot()' required>
                                            </div>
                                            <div class="form-group col-sm-2">
                                                @if($i === 0)
                                                    <label>Amount</label>
                                                @endif
                                                <input type="text" class="form-control"  name="data[amount][{{$i+1}}]" id="answer{{$i+1}}" placeholder="0.00" value="0" readonly>
                                            </div>
                                            <input type="hidden" value="{{$item[$i]->id}}" name="data[itemid][{{$i+1}}]">
                                            @if($i !== 0)
                                                <div class="col-sm-1">
                                                    <span class="pe-7s-close-circle"  style="font-size: 20px !important; float: left" onclick="deleteRow({{$i+1}});tot();oldCounter({{$item[$i]->id}})"></span>
                                                </div>
                                            @endif
                                        </div>
                                    @endfor
                                @endif
                            </div>
                            <hr>
                            <div class="row">
                                <div class="form-group col-sm-2">
                                    <button type="submit" class="btn btn-default">Submit</button>
                                </div>
                                <div class="col-sm-1"><label>Remarks</label></div>
                                <div class="form-group col-sm-4">
                                    <textarea class="form-control" rows="3" name="remarks" placeholder="Add Remarks"
                                              required>{{$inventory->remarks}}
                                    </textarea>
                                </div>
                                <div class="col-sm-2"> <label>Total Amount</label></div>
                                <div class="form-group col-sm-2">
                                    <input type="text" class="form-control" id="total"
                                           placeholder="0.00" name="totalamount" readonly>
                                </div>
                                <div class="col-sm-1">
                                    <span class="pe-7s-plus"  style="font-size: 20px !important; float: right ; color: #ffc771" onclick="addRow()"></span>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var array =[];
        var counter;
        $(document).ready(function(){
            var Count = $('#counter').val();
            counter =$('#counter').val();
            counter++;
            for (var i = 1; i <= Count; i++) {
                array.push(i);
                $('#array').val(array.join(','));
                Calculate(i);
                /*var quantity = document.getElementById('quantity'+ i).value;
                var costprice = document.getElementById('costprice'+ i).value;
                var amount = costprice * quantity;
                document.getElementById('answer'+ i).value=parseFloat(amount);*/
            }
            tot();
        });



        function Calculate(locate)
        {
            var quantity = document.getElementById('quantity'+locate).value;
            var costprice = document.getElementById('costprice'+locate).value;
            var amount = costprice * quantity;
            document.getElementById('answer'+locate).value=parseFloat(amount);
        }



        //Append Row



        function addRow(){
            $('#counter').val( function(i, oldval) {
                return ++oldval;
            });
            var options = $('#sizes').html();
            array.push(counter);
            var htmlToAppend = '<div class="row" id="row'+counter+'">\n' +
                '                                    <div class="form-group col-sm-3">\n' +
                '                                        <select class="form-control" name="data[size]['+counter+']" ' +
                '                                                id="sizes" style="width: 100%" required>\n'+ options+
                '                                        </select>\n' +
                '                                    </div>\n' +
                '                                    <div class="form-group col-sm-3">\n' +
                '                                        <input type="text" class="form-control"  ' +
                '                                         name="data[quantity]['+counter+']" pattern="\\d*" ' +
                '                                         id="quantity'+counter+'" onchange="Calculate('+counter+');tot()"' +
                '                                         required>\n' +
                '                                    </div>\n' +
                '                                    <div class="form-group col-sm-3">\n' +
                '                                        <input type="text" class="form-control"  ' +
                '                                       name="data[costprice]['+counter+']" id="costprice'+counter+'" ' +
                '                                       pattern="[+-]?([0-9]+([.][0-9]*)?|[.][0-9]+)"  placeholder="0.00"' +
                '                                       onchange="Calculate('+counter+');tot()" required>\n' +
                '                                    </div>\n' +
                '                                    <div class="form-group col-sm-2">\n' +
                '                                        <input type="text" class="form-control"  ' +
                '                                           name="data[amount]['+counter+']" id="answer'+counter+'" ' +
                '                                           placeholder="0.00" value="0" readonly>\n' +
                '                                    </div>\n' +'<div class="col-sm-1" > <span class="pe-7s-close-circle"' +
                '                                           style="font-size: 20px !important; float: left" ' +
                '                                           onclick="deleteRow('+counter+');tot()"></span></div>'+
                '                                   </div>\n';

            $('#in').append(htmlToAppend);

            $('#array').val(array.join(','));
            counter++;
        }

        function deleteRow(nth){
            $('#counter').val( function(i, oldval) {
                return --oldval;
            });
            array.splice($.inArray(nth,array),1);
            $('#row'+nth).remove();
            $('#array').val(array.join(','))
        }

        function tot() {
            // var array = $('#array').val();
            var counts = $('#counter').val();
            console.log("in tot function count = "+counts);
            var totalamount =0;
            // var num=0;
            for(var i = 0 ; i<counts ; i++)
            {
                var val = document.getElementById('answer'+array[i]).value;

                totalamount = parseFloat(totalamount) + parseFloat(val);
                // num+=2;
            }
            document.getElementById('total').value=parseFloat(totalamount);
        }

        var del=[];
        function oldCounter(id)
        {
            $('#oldcounter').val( function(i, oldval) {
                return --oldval;
            });

            del.push(id);
            $('#deleteid').val(del.join(','));
        }


    </script>
@endsection
