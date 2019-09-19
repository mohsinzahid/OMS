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
                        <h3>Add a new Waste</h3>
                    </div>
                </div>
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3"></div>
            <div class="col-sm-6">
                <div class="header-title">
                    @if($msg)
                        <h3>Waste Added Successfully.</h3>
                    @endif
                </div>
                <div class="panel panel-filled">
                    <div class="panel-body">
                        <form method="post" action="{{ url('/wastage/postwaste') }}" autocomplete="off">
                            {{ csrf_field() }}

                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label for="Inputdate">Approved Date</label>
                                    <input type="date" class="form-control" id="Inputdate" name="approveddate" required>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="Approvedby">Approved by</label>
                                    <input type="text" class="form-control" id="Approvedby" placeholder="Name"
                                           name="approvedby" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-sm-6">
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
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="InputQuantity">Quantity</label>
                                    <input type="text" class="form-control" id="InputQuantity" pattern="\d*" value="0" placeholder="0" name="quantity" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="Inputremarks">Remarks</label>
                                <input type="text" class="form-control" id="Inputremarks"
                                       placeholder="Remarks" name="remarks" required>
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
