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
                        <h3>Add a Petty Cash Payment</h3>
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
                        <h3>Petty Cash Added Successfully.</h3>
                    @endif
                </div>
                <div class="panel panel-filled">
                    <div class="panel-body">
                        <form method="post" action="{{ url('/petty-cash/post-payment') }}" autocomplete="off">
                            {{ csrf_field() }}

                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label for="Inputdate">Date</label>
                                    <input type="date" class="form-control" id="Inputdate" name="date" required>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="Inputamount">Amount</label>
                                    <input type="text" class="form-control" min="0" id="Inputamount" name="amount" pattern="\d*"
                                           value="0" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <label for="remarks">Remarks</label>
                                    <input type="text" class="form-control" id="Remarks" placeholder="remarks"
                                           name="remarks" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-warning">Submit</button>

                        </form>
                    </div>
                </div>

            </div>
            <div class="col-sm-3"></div>
        </div>
    </div>
@endsection
