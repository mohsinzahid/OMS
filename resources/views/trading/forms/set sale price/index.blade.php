@extends('admin.master')

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
                        <h3>Sales price</h3>
                    </div>
                </div>
                <hr>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-3">
                <label>Customer</label>
                {{--<div class="col-sm-11">--}}
                <select class="form-control" name="customerid" id="callfunc"  style="width: 100% !important;" required>
                    <option value="" selected disabled hidden>Choose here</option>
                    @if(count($customer)>0)
                        @foreach($customer as $customers)
                            <option value="{{$customers->id}}" >{{$customers->id}} &emsp; {{$customers->name}}</option>
                        @endforeach
                    @else
                        <option>No Customer exist</option>
                    @endif
                </select>
            </div>
        </div>
        <br>
        <div class="row hidden" id="main">
            <div class="col-md-6">
                <div class="panel panel-filled">
                    <div class="panel-heading">
                        Sale price of items
                    </div>
                    <div class="panel-body">
                        <p>A list of all sale price which are assigned to selected customer rather than default prices.</p>

                        <div class="table-responsive">
                            <table  class="table table-hover table-striped table-bordered table-condensed">
                                <thead>
                                    <tr>
                                        <th>Size</th>
                                        <th>Sale price</th>
                                        <th style="text-align: center"><span class="pe-7s-config" style="color: #ffc771 ; font-size:20px !important; "></span></th>
                                    </tr>
                                </thead>
                                <tbody id="addrow">

                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="header-title">
                </div>
                <div class="panel panel-filled">
                    <div class="panel-body">
                        <form method="post" action="{{ url('/customer/saleprice/add') }}" autocomplete="off">
                            {{ csrf_field() }}

                            <div class="form-group">
                                <label for="InputSize">Size</label>
                                <select class="select2_demo_1 form-control" id="sizes" name="sizeid" style="width: 100%" required>
                                    <option value="" selected disabled hidden>Choose here</option>
                                </select>
                            </div>


                            <div class="form-group">
                                <label for="InputName">Sale price</label>
                                <input type="text" class="form-control" id="Inputsale" pattern="[+-]?([0-9]+([.][0-9]*)?|[.][0-9]+)"  placeholder="0.00" name="saleprice" required>
                            </div>

                            <input type="hidden" id="customer_id" name="customer_id" value="">
                            <button type="submit" class="btn btn-default">Submit</button>

                        </form>
                    </div>
                </div>

            </div>
            {{--<div class="col-sm-3"></div>--}}
        </div>
    </div>

    <script>
            $("#callfunc").on('change', function () {
            var id = $(this).val();
                $('#customer_id').val(id);

            $("#main").removeClass("hidden");

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
            url: '/customer/saleprice/ajaxupdate',
            data: {id: id},

            success: function (response) {
                $("#addrow").empty();
                $("#sizes").empty();

                // console.log(response);

                for(var i=0 ; i< response['size']['length']; i++)
                {
                    var htmlToAppend = '<option value="'+response["size"][i]["id"]+'">'+response["size"][i]["size"]+'</option>\n';

                    $('#sizes').append(htmlToAppend);
                }

                for( i=0 ; i< response['rates']['length']; i++)
                {
                    var htmlToAppend = '<tr>\n'+
                        '                   <td>'+response['rates'][i]['size']+'</td>\n'+
                        '                   <td>'+response['rates'][i]['saleprice']+'</td>\n'+
                        '                   <td><a href="/customer/saleprice/'+response['rates'][i]['id'] +'/delete"><span style="font-size: 17px !important;" class="pe-7s-trash"></span></a></td>\n'+
                        '                   </tr>\n';

                    $('#addrow').append(htmlToAppend);
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
