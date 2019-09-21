@extends('admin.master')

@section('content')
    <div class="container-fluid">


        <div class="row">
            <div class="col-lg-12">
                <div class="view-header">
                    <div class="pull-right text-right" style="line-height: 14px">
                    </div>
                    <div class="header-icon">
                        <i class="pe page-header-icon pe-7s-id"></i>
                    </div>
                    <div class="header-title">
                        <h3>Add a new Customer</h3>
                    </div>
                    {{--<div style="float: right"><a href="#" class="btn btn-w-md btn-accent">Create Walk-In-Customer</a></div>--}}
                </div>
            </div>
        </div>
        <hr>

        <div class="row">
            <div class="col-sm-12">
                <div class="header-title">
                    @if($msg)
                        <h3>Customer Added Successfully <span class="pe-7s-smile" style="color: #f6a821"></span></h3>
                    @endif
                </div>
                <div class="panel panel-filled">
                    <div class="panel-body">

                            <form method="post" action="{{ url('customer/store') }}" autocomplete="off">
                                {{ csrf_field() }}
                                <div class="row">


                                    <div class="form-group col-sm-6">
                                        <label>Name</label>
                                        <input type="text" class="form-control" placeholder="Company Name" name="name" required>
                                    </div>

                                    <div class="form-group col-sm-6">
                                        <label>Short Name</label>
                                        <input type="text" class="form-control" placeholder="Short Name" name="shortname" required>
                                    </div>

                                    <div class="form-group col-sm-6">
                                        <label>Contact Address</label>
                                        <input type="text" class="form-control" placeholder="Address" name="address">
                                    </div>

                                    <div class="form-group col-sm-6">
                                        <label>Sales Tax No</label>
                                        <input type="text" class="form-control" placeholder="Sales Tax no" name="salestaxno">
                                    </div>

                                    <div class="form-group col-sm-6">
                                        <label>NTN No</label>
                                        <input type="text" class="form-control" placeholder="NTN Number" name="ntnno">
                                    </div>

                                    <div class="form-group col-sm-6">
                                        <label>Credit limit</label>
                                        <input type="text" class="form-control"
                                               pattern="[+-]?([0-9]+([.][0-9]*)?|[.][0-9]+)" placeholder="Uper Credit limit"
                                               name="creditlimit" required>
                                    </div>


                                    <div class="form-group col-sm-6">
                                        <label >Mobile Number </label>
                                        <input type="text" class="form-control" placeholder="eg:03221234567" name="mobile">
                                    </div>

                                    <div class="form-group col-sm-6">
                                        <label >Phone Number </label>
                                        <input type="text" class="form-control" placeholder="eg:04237234569" name="phone">
                                    </div>

                                    <div class="form-group col-sm-6">
                                        <label> Contact Name</label>
                                        <input type="text" class="form-control" placeholder="Person Name" name="contactname">
                                    </div>

                                    <div class="form-group col-sm-6">
                                        <label>Email </label>
                                        <input type="email" class="form-control" placeholder="abc@abc.com" name="email">
                                    </div>

                                    <div class="form-group col-sm-6">
                                        <label>City</label>
                                        <input type="text" class="form-control" placeholder="City" name="city" required>
                                    </div>

                                    <div class="form-group col-sm-6">
                                        <label>Country</label>
                                        <input type="text" class="form-control" placeholder="Country" name="country" required>
                                    </div>

                                    <div class="form-group col-sm-6">
                                        <label>Opening Balance</label>
                                        <input type="text" class="form-control" value="0" name="prevbalance">
                                    </div>

                                </div>

                                <hr>

                                <div class="row">
                                    <div class="form-group col-sm-2">
                                        <button type="submit" class="btn btn-default">Submit</button>
                                    </div>

                                    <div class="form-group col-sm-6">
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio1" value="1" name="status" checked="">
                                            <label for="inlineRadio1"> Active </label>
                                        </div>
                                        <div class="radio radio-inline">
                                            <input type="radio" id="inlineRadio2" value="0" name="status">
                                            <label for="inlineRadio2"> Hide </label>
                                        </div>
                                    </div>
                                </div>
                        </form>
                            </div>

                               {{-- @foreach($size as $sizes)
                                    <div class="form-group">
                                        <label> {{$sizes->size}}</label>
                                        <input type="text" class="form-control"  name="" pattern="[+-]?([0-9]+([.][0-9]*)?|[.][0-9]+)" value="{{$sizes->saleprice}}"  placeholder="0.00" required>
                                    </div>
                                @endforeach--}}

                            </div>


                    </div>
        </div>

    </div>


    {{--<script src="{{url('vendor/switchery/switchery.min.js')}}"></script>--}}

@endsection
