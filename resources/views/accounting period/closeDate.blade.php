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
                        <h3>Closed Date</h3>
                    </div>
                </div>
                <hr>
            </div>
        </div>

        <div class="row">
        </div>
        <br>
        <div class="row" id="main">
            <div class="col-md-6">
                <div class="panel panel-filled">
                    <div class="panel-heading">
                        Closed Period
                    </div>
                    <div class="panel-body">
                        <p>All previous dates till are closed.</p>

                        <div class="table-responsive">
                            <table  class="table table-hover table-striped table-bordered table-condensed">
                                <thead>
                                <tr>
                                    <th>Closed Period <small>(yyyy-mm-dd)</small></th>
                                    <th>Job Order Closed Year <small>(yyyy-mm-dd)</small></th>
                                </tr>
                                </thead>
                                <tbody >
                                <tr>
                                    <td>{{$result->closed_date}}</td>
                                    <td>{{$result->job_order_closed_year}}</td>
                                </tr>

                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-filled">
                    <div class="panel-body">
                        <form method="post" action="{{ url('/accounting/close-date/update') }}" autocomplete="off">
                            {{ csrf_field() }}

                            <div class="form-group">
                                <label>Closed Date</label>
                                <input type="date" class="form-control" name="closeddate"
                                       required>
                            </div>

                            <button type="submit" class="btn btn-default">Submit</button>
                        </form>
                    </div>
                </div>
                <div class="panel panel-filled">
                    <div class="panel-body">
                        <form method="post" action="{{ url('/accounting/job-order-close-date/update') }}" autocomplete="off">
                            {{ csrf_field() }}

                            <div class="form-group">
                                <label>Job Order Closed Year</label>
                                <input type="date" class="form-control" name="jobOrderClosedDate"
                                       required>
                            </div>

                            <button type="submit" class="btn btn-default">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
