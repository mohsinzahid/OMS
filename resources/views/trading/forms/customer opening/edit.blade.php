@extends('admin.master')

@section('content')
    <div class="container-fluid">


        <div class="row">
            <div class="col-lg-12">
                <div class="view-header">
                    <div class="pull-right text-right" style="line-height: 14px">
                        <div style="float: right"><a href="/supplier/{{$result->id}}/delete"
                                                     class="btn btn-w-md btn-danger">Delete</a>
                        </div>
                    </div>
                    <div class="header-icon">
                        <i class="pe page-header-icon pe-7s-pen"></i>
                    </div>
                    <div class="header-title">
                        <h3>Edit a Customer</h3>
                    </div>
                </div>
                <hr>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="header-title">
                </div>
                <div class="panel panel-filled">
                    <div class="panel-body">

                        <form method="post" action="{{ url('customer/update') }}" autocomplete="off">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label>Name</label>
                                    <input type="text" class="form-control" placeholder="Company Name" name="name" value="{{$customer->name}}" required>
                                </div>

                                <div class="form-group col-sm-6">
                                    <label>Short Name</label>
                                    <input type="text" class="form-control" placeholder="Short Name" name="shortname" value="{{$customer->short_name}}"required>
                                </div>

                                <div class="form-group col-sm-6">
                                    <label>Contact Address</label>
                                    <input type="text" class="form-control" placeholder="Address" name="address" value="{{$customer->address}}">
                                </div>

                                <div class="form-group col-sm-6">
                                    <label>Sales Tax No</label>
                                    <input type="text" class="form-control" placeholder="Sales Tax no" name="salestaxno" value="{{$customer->sales_tax_no}}">
                                </div>

                                <div class="form-group col-sm-6">
                                    <label>NTN No</label>
                                    <input type="text" class="form-control" placeholder="NTN Number" name="ntnno" value="{{$customer->ntn_no}}" required>
                                </div>

                                <div class="form-group col-sm-6">
                                    <label>Credit limit</label>
                                    <input type="text" class="form-control" pattern="[+-]?([0-9]+([.][0-9]*)?|[.][0-9]+)" placeholder="Uper Credit limit" name="creditlimit" value="{{$customer->credit_limit}}" required>
                                </div>


                                <div class="form-group col-sm-6">
                                    <label >Mobile Number </label>
                                    <input type="text" class="form-control" placeholder="eg:03221234567" name="mobile" value="{{$customer->mobile}}">
                                </div>

                                <div class="form-group col-sm-6">
                                    <label >Phone Number </label>
                                    <input type="text" class="form-control" placeholder="eg:04237234569" name="phone" value="{{$customer->phone}}">
                                </div>

                                <div class="form-group col-sm-6">
                                    <label> Contact Name</label>
                                    <input type="text" class="form-control" placeholder="Person Name" name="contactname" value="{{$customer->contact_name}}" required>
                                </div>

                                <div class="form-group col-sm-6">
                                    <label>Email </label>
                                    <input type="email" class="form-control" placeholder="abc@abc.com" name="email" value="{{$customer->email}}">
                                </div>

                                <div class="form-group col-sm-6">
                                    <label>City</label>
                                    <input type="text" class="form-control" placeholder="City" name="city" value="{{$customer->city}}">
                                </div>

                                <div class="form-group col-sm-6">
                                    <label>Country</label>
                                    <input type="text" class="form-control" placeholder="Country" name="country" value="{{$customer->country}}">
                                </div>

                                <div class="form-group col-sm-6">
                                    <label>Previous Balance</label>
                                    <input type="text" class="form-control" value="{{$customer->prevbalance}}" name="prevbalance" required>
                                </div>

                            </div>
                            <br>
                            <input type="hidden" value="{{$customer->id}}" name="id">

                            <div class="row">
                                <div class="form-group col-sm-2">
                                    <button type="submit" class="btn btn-default">Submit</button>
                                </div>
                                <div class="form-group col-sm-6">
                                    @if($customer->status === 1)
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio1" value="1" name="status" checked="">
                                            <label for="inlineRadio1"> Active </label>
                                        </div>
                                        <div class="radio radio-inline">
                                            <input type="radio" id="inlineRadio2" value="0" name="status">
                                            <label for="inlineRadio2"> Hide </label>
                                        </div>
                                    @else
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio1" value="1" name="status">
                                            <label for="inlineRadio1"> Active </label>
                                        </div>
                                        <div class="radio radio-inline">
                                            <input type="radio" id="inlineRadio2" value="0" name="status" checked="">
                                            <label for="inlineRadio2"> Hide </label>
                                        </div>
                                    @endif
                                </div>
                            </div>

                        </form>
                    </div>
                </div>


            </div>
        </div>

    </div>


    <script src="{{url('vendor/switchery/switchery.min.js')}}"></script>
@endsection
