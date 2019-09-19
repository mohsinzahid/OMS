@extends('admin.master')

@section('content')
    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-12">
                <div class="view-header">
                    {{--<div class="pull-right text-right" style="line-height: 14px">--}}
                    {{--</div>--}}
                    <div class="header-icon">
                        <i class="pe page-header-icon pe-7s-note2"></i>
                    </div>
                    <div>
                        <h3>Add a new Vendor</h3>
                    </div>
                </div>
                <hr>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="header-title">
                    @if($msg)
                        <h4>Vendor Added Successfully</h4>
                        @endif
                </div>
                <div class="panel panel-filled">
                    <div class="panel-body">
                        <form method="post" action="{{ url('/vendor/postvendor') }}" autocomplete="off">
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
                                    <input type="text" class="form-control" placeholder="NTN no" name="ntnno">
                                </div>

                                <div class="form-group col-sm-6">
                                    <label>Credit limit</label>
                                    <input type="text" class="form-control" pattern="[+-]?([0-9]+([.][0-9]*)?|[.][0-9]+)" placeholder="Uper Credit limit" name="creditlimit">
                                </div>


                                <div class="form-group col-sm-6">
                                    <label for="InputMobile">Mobile Number </label>
                                    <input type="text" class="form-control" placeholder="eg:03221234567" name="mobile">
                                </div>

                                <div class="form-group col-sm-6">
                                    <label for="InputPhone">Phone Number </label>
                                    <input type="text" class="form-control" placeholder="eg:04231234567" name="phone">
                                </div>

                                <div class="form-group col-sm-6">
                                    <label> Contact Name</label>
                                    <input type="text" class="form-control" placeholder="Person Name" name="contactname" required>
                                </div>

                                <div class="form-group col-sm-6">
                                    <label>Email </label>
                                    <input type="email" class="form-control" placeholder="abc@abc.com" name="email">
                                </div>

                                <div class="form-group col-sm-6">
                                    <label>City</label>
                                    <input type="text" class="form-control" placeholder="City" name="city">
                                </div>

                                <div class="form-group col-sm-6">
                                    <label>Country</label>
                                    <input type="text" class="form-control" placeholder="Country" name="country">
                                </div>

                                <div class="form-group col-sm-6">
                                    <label>Opening Balance</label>
                                    <input type="text" class="form-control" value="0" name="prevbalance">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-sm-2">
                                    <button type="submit" class="btn btn-default">Submit</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>



@endsection
