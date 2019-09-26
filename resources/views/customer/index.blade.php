@extends('admin.master')

<link rel="stylesheet" href="{{url('vendor/datatables/datatables.min.css')}}"/>


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
                        <h3>Customer List</h3>
                    </div>
                </div>
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-filled">
                    <div class="panel-heading">
                        Customer
                    </div>
                    <div class="panel-body">
                        {{--<p>
                            The Buttons library for DataTables provides a framework with common options and API that can be used with DataTables, but is also very extensible, recognising that you will likely want to use buttons which perform an action unique to your applications.
                        </p>--}}
                        <div class="table-responsive">

                            <table id="tableExample4" class="table table-striped table-hover table-bordered table-condensed">
                                <thead>
                                <tr>
                                    <th>Customer ID</th>
                                    <th>Name</th>
                                    <th>Short name</th>
                                    <th>Address</th>
                                    <th>Sale Tax No</th>
                                    <th>NTN No</th>
                                    <th>Credit limit</th>
                                    <th>Phone No</th>
                                    <th>Mobile No</th>
                                    <th>Contact name</th>
                                    <th>Email</th>
                                    <th>City</th>
                                    <th>Country</th>
                                    <th>Status</th>
                                    <th style="text-align: center"><span class="pe-7s-tools" style="color: #ffc771 ; font-size:20px !important; "></span></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($customer as $customers)
                                    {{--@if($customers->status === 1)--}}
                                        <tr>
                                            <td>{{$customers->id}}</td>
                                            <td>{{$customers->name}}</td>
                                            <td>{{$customers->short_name}}</td>
                                            <td>{{$customers->address}}</td>
                                            <td>{{$customers->sales_tax_no}}</td>
                                            <td>{{$customers->ntn_no}}</td>
                                            <td>{{$customers->credit_limit}}</td>
                                            <td>{{$customers->phone}}</td>
                                            <td>{{$customers->mobile}}</td>
                                            <td>{{$customers->contact_name}}</td>
                                            <td>{{$customers->email}}</td>
                                            <td>{{$customers->city}}</td>
                                            <td>{{$customers->country}}</td>
                                            @if($customers->status === 1)
                                                <td> Active </td>
                                            @else
                                                <td> Hide </td>
                                            @endif
                                            <td>
                                                <a href="/customer/{{$customers->id}}/edit"> <span style="font-size: 17px !important;" class="pe-7s-note"></span></a> &nbsp;
                                                {{--<a href="/vendor/{{$vendor->id}}/delete"><span class="pe-7s-close" style="font-size: 20px !important;"></span></a>--}}

                                            </td>

                                        </tr>
                                    {{--@endif--}}
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="{{url('vendor/datatables/datatables.min.js')}}"></script>

    <script>
        $(document).ready(function () {

            $('#tableExample4').DataTable({
                dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
                "lengthMenu": [[25, 50, -1], [25, 50, "All"]],
                buttons: [
                    {
                        extend: 'copy', className: 'btn-sm',
                        exportOptions: {columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12 ,13]}
                    },
                    {
                        extend: 'csv', title: "Zahid Scan", className: 'btn-sm',
                        exportOptions: {columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12 ,13]}
                    },
                    {
                        extend: 'pdf', title: "Zahid Scan", className: 'btn-sm',
                        exportOptions: {columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12,13]}
                    },
                    {
                        extend: 'print', title: "Zahid Scan", className: 'btn-sm',
                        exportOptions: {columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12,13]}
                    }
                ],
                "ordering": false
            });
        })
    </script>

@endsection