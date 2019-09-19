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
                        <h3>Add a new Inventory</h3>
                    </div>
                </div>
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3"></div>
            <div class="col-sm-6">
                <div class="header-title">
{{--                    @if($msg)
                        <h3>Stock Added Successfully.</h3>
                    @endif--}}
                </div>
                <div class="panel panel-filled">
                    <div class="panel-body">
                        <form method="post" action="{{ url('/stock/poststock') }}" autocomplete="off">
                            {{ csrf_field() }}


                            {{--<div class="form-group">
                                <label for="InputQuantity">Size</label>
                                <select class="select2_demo_1 form-control" name="id" style="width: 100%" required>
                                    <option value="" selected disabled hidden>Choose here</option>
                                @if(count($size)>0)
                                        @foreach($size as $sizes)
                                            <option value="{{$sizes->id}}">{{$sizes->size}}</option>
                                        @endforeach
                                    @else
                                        <option>No sizes exist</option>
                                    @endif
                                </select>
                            </div>--}}

                            <div class="form-group">
                                <label for="InputName">Size</label>
                                <input type="text" class="form-control" id="InputName" placeholder="eg : 123x123" name="size" required>
                            </div>

                            <div class="form-group">
                                <label for="InputQuantity">Quantity</label>
                                <input type="text" class="form-control" id="InputQuantity" pattern="\d*" value="0" placeholder="0" name="quantity" required>
                            </div>

                            <div class="form-group">
                                <label for="Inputprice">Sale Price </label>
                                <input type="text" class="form-control" id="Inputsale" value="1" pattern="[+-]?([0-9]+([.][0-9]*)?|[.][0-9]+)" name="saleprice" required>
                            </div>

                            <button type="submit" class="btn btn-default">Submit</button>


                        </form>
                    </div>
                </div>

            </div>
            <div class="col-sm-3"></div>
        </div>
    </div>
@endsection
        {{--<div class="row">

            <div class="row">
                <div class="header-title">
                </div>
                <div class="panel panel-filled">
                    <div class="panel-heading">
                        Stock Form
                    </div>
                    <div class="panel-body">
                        --}}{{--<p>Add <code>.form-inline</code> to your form (which doesn't have to be a <code>&lt;form&gt;</code>) for left-aligned and inline-block controls. <strong>This only applies to forms within viewports that are at least 768px wide.</strong></p>--}}{{--

                        <form class="form-inline" >
                                <div class="form-group col-sm-4">
                                        <select class="select2_demo_1 form-control" name="id" style="width: 60%">
                                            @if(count($size)>0)
                                                @foreach($size as $sizes)
                                                    <option value="{{$sizes->id}}">{{$sizes->size}}</option>
                                                @endforeach
                                            @else
                                                <option>No sizes exist</option>
                                            @endif
                                        </select>
                            </div>
                            <div class="form-group col-sm-4"><label for="exampleInputName2"></label>Quantity <input type="text" name="quantity" class="form-control" id="exampleInputName2" value="0" pattern="[0-9]" placeholder="0"></div>
                            <div class="form-group col-sm-4"><label for="exampleInputName2"></label> Sale Price <input type="number" name="SalePrice" class="form-control" id="exampleInputName2" value="0" placeholder="0.00" step="0.1"></div>
                            <div class="col-sm-6"><button type="submit" class="btn btn-default">Submit</button></div><div class="col-sm-6"></div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>--}}



{{--
<script src="{{url('vendor/jquery/dist/jquery.min.js')}}"></script>
<script src="{{url('vendor/select2/dist/js/select2.js')}}"></script>



<script>
    $(document).ready(function(){
        $(".select2_demo_1").select2();
        $(".select2_demo_2").select2({
            placeholder: "Select a state",
            allowClear: true
        });
        $(".select2_demo_3").select2();
    })
</script>

--}}
{{--<div class="col-sm-3"></div>
<div class="col-sm-6">
    <div class="header-title">
        @if($msg)
            <h3>Vendor Added Successfully. You want to add new?</h3>
        @endif
    </div>
    <div class="panel panel-filled">
        <div class="panel-body">
            <form method="post" action="{{ url('/vendor/postvendor') }}" autocomplete="off">
                {{ csrf_field() }}


                <div class="form-group">
                    <label for="InputName">Username</label>
                    <input type="text" class="form-control" id="InputName" placeholder="Name" pattern="[A-Za-z,]" name="name" required>
                </div>

                <div class="form-group">
                    <label for="Inputaddress">Address</label>
                    <input type="text" class="form-control" id="Inputaddress" placeholder="Address" name="address">
                </div>

                <div class="form-group">
                    <label for="Inputaccount">Account </label>
                    <input type="text" class="form-control" id="Inputaccount" pattern="[0-9]{16}"  placeholder="eg:00123456789012" name="account" required>
                </div>

                <div class="form-group">
                    <label for="InputPhone">Phone  Number </label>
                    <input type="text" class="form-control" id="InputPhone" pattern="[03]{2}[0-4]{1}[0-9]{1}[0-9]{7} " placeholder="eg:03221234567" name="phone" required>
                </div>

                <div class="form-group">
                    <label for="InputEmail1">Email </label>
                    <input type="email" class="form-control" id="InputEmail" placeholder="abc@abc.com" name="email" required>
                </div>

                <button type="submit" class="btn btn-default">Submit</button>


            </form>
        </div>
    </div>

</div>
<div class="col-sm-3"></div>
</div>
</div>--}}{{--




--}}
