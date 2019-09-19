@extends('admin.master')

@section('content')
    <div class="container-fluid">


        <div class="row">
            <div class="col-lg-12">
                <div class="view-header">
                    <div class="pull-right text-right" style="line-height: 14px">
                        <div style="float: right"><a href="/wastage/{{$waste->id}}/delete"
                                                     class="btn btn-w-md btn-danger">Delete</a>
                        </div>
                    </div>
                    <div class="header-icon">
                        <i class="pe page-header-icon pe-7s-pen"></i>
                    </div>
                    <div class="header-title">
                        <h3>Edit Waste</h3>
                    </div>
                </div>
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3"></div>
            <div class="col-sm-6">
                <div class="header-title">
                </div>
                <div class="panel panel-filled">
                    <div class="panel-body">
                        <form method="post" action="{{ url('/wastage/updatewaste') }}" autocomplete="off">
                            {{ csrf_field() }}

                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label for="Inputdate">Approved Date</label>
                                    <input type="date" class="form-control" id="Inputdate" name="approveddate"
                                           value="{{$waste->approved_date}}" required>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="Approvedby">Approved by</label>
                                    <input type="text" class="form-control" id="Approvedby" placeholder="Name"
                                           name="approvedby" value="{{$waste->approved_by}}"  required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label for="InputQuantity">Size</label>
                                    <select class="select2_demo_1 form-control" name="size_id" style="width: 100%" required>
                                        <option value="" selected disabled hidden>Choose here</option>
                                        @if(count($size)>0)
                                            @foreach($size as $sizes)
                                                @if($sizes->id === $waste->size_id)
                                                    <option value="{{$sizes->id}}" selected>{{$sizes->size}}</option>
                                                @else
                                                    <option value="{{$sizes->id}}">{{$sizes->size}}</option>
                                                @endif
                                            @endforeach
                                        @else
                                            <option>No sizes exist</option>
                                        @endif
                                    </select>
                                </div>

                                <div class="form-group col-sm-6">
                                    <label for="InputQuantity">Quantity</label>
                                    <input type="text" class="form-control" id="InputQuantity" pattern="\d*"
                                           value="{{$waste->quantity}}" placeholder="0" name="quantity" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="Inputremarks">Remarks</label>
                                <input type="text" class="form-control" id="Inputremarks" placeholder="Description"
                                       value="{{$waste->remarks}}" name="remarks" required>
                            </div>
                            <div class="form-group hidden">
                                <input type="hidden" class="form-control" value="{{$waste->id}}" name="id">
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
