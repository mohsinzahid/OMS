@extends('admin.master')

@section('content')
    <div class="container-fluid">



        <div class="row">
            <div class="col-lg-12">
                <div class="view-header">
                    {{--                    <div class="pull-right text-right" style="line-height: 14px">
                                            <small>Table design<br>General<br> <span class="c-white">DataTables</span></small>
                                        </div>--}}
                    <div class="header-icon">
                        <i class="pe page-header-icon pe-7s-albums"></i>
                    </div>
                    <div class="header-title">
                        <h3>Waste List</h3>
                    </div>
                </div>
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-filled">
                    <div class="panel-heading">
                        Waste
                    </div>
                    <div class="panel-body">
                        {{--<p>
                            The Buttons library for DataTables provides a framework with common options and API that can be used with DataTables, but is also very extensible, recognising that you will likely want to use buttons which perform an action unique to your applications.
                        </p>--}}
                        <div class="table-responsive">

                            <table id="tableExample3" class="table table-striped table-hover">
                                <thead>
                                <tr>
                                    <th>Size</th>
                                    <th>Quantity</th>
                                    <th>Description</th>
                                    <th>Added at</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($waste as $wastes)
                                    <tr>
                                        <td>{{$wastes->size}}</td>
                                        <td>{{$wastes->quantity}}</td>
                                        <td>{{$wastes->description}}</td>
                                        <td>{{$wastes->datentime}}</td>
                                        <td>
                                            <a href="/waste/{{$wastes->id}}/edit"> <span style="font-size: 17px !important;" class="pe-7s-note"></span></a> &nbsp
                                            <a href="/waste/{{$wastes->id}}/delete"><span class="pe-7s-close" style="font-size: 20px !important;"></span></a>

                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection