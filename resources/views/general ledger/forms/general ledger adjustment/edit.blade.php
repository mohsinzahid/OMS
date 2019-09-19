@extends('admin.master')

@section('content')
    <div class="container-fluid">


        <div class="row">
            <div class="col-lg-12">
                <div class="view-header">
                    <div class="pull-right text-right" style="line-height: 14px">
                        <div style="float: right"><a href="/general-ledger/{{$result->id}}/delete"
                                                     class="btn btn-w-md btn-danger">Delete</a>
                        </div>
                    </div>
                    <div class="header-icon">
                        <i class="pe page-header-icon pe-7s-pen"></i>
                    </div>
                    <div class="header-title">
                        <h3>Update a General Ledger Adjustment</h3>
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
                        <form method="post" action="{{ url('/general-ledger/update-adjustment') }}" autocomplete="off">
                            {{ csrf_field() }}


                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label>Debit GL</label>
                                    <select class="form-control" name="debitgl" style="width: 100%" required>
                                        <option value="" selected disabled hidden>Choose here</option>
                                        @if(count($gl)>0)
                                            @foreach($gl as $gls)
                                                @if($gls->id == $result->debit_gl)
                                                    <option value="{{$gls->id}}" selected>{{$gls->id}} &emsp;
                                                        {{$gls->name}}</option>
                                                @else
                                                    <option value="{{$gls->id}}">{{$gls->id}} &emsp;
                                                        {{$gls->name}}</option>
                                                @endif
                                            @endforeach
                                        @else
                                            <option>No General Ledger exist</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label>Credit GL </label>
                                    <select class="form-control" name="creditgl" style="width: 100%" required>
                                        <option value="" selected disabled hidden>Choose here</option>
                                        @if(count($gl)>0)
                                            @foreach($gl as $gls)
                                                @if($gls->id === $result->credit_gl)
                                                    <option value="{{$gls->id}}" selected>{{$gls->id}} &emsp;
                                                        {{$gls->name}}</option>
                                                @else
                                                    <option value="{{$gls->id}}">{{$gls->id}} &emsp;
                                                        {{$gls->name}}</option>
                                                @endif
                                            @endforeach
                                        @else
                                            <option>No General Ledger exist</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label for="Inputamount">Amount</label>
                                    <input type="text" class="form-control" min="0" id="Inputamount" name="amount"
                                           pattern="\d*" placeholder="0.00" value="{{$result->amount}}" required>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="Inputdate">Date</label>
                                    <input type="date" class="form-control" id="Inputdate" name="date"
                                           value="{{$result->date}}" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <label for="remarks">Remarks</label>
                                    <input type="text" class="form-control" id="Remarks" placeholder="remarks"
                                           value="{{$result->remarks}}" name="remarks" required>
                                </div>
                            </div>

                            <input type="hidden" class="form-control" value="{{$result->id}}" name="id">

                            <button type="submit" class="btn btn-warning">Submit</button>

                        </form>
                    </div>
                </div>

            </div>
            <div class="col-sm-3"></div>
        </div>
    </div>
@endsection
