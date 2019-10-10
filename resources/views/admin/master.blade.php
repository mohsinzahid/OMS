
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    {{--<link href='http://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900' rel='stylesheet' type='text/css'>--}}

    <!-- Page title -->
    <title>Zahid Scan | Admin</title>

    <!-- Vendor styles -->
    {{--<link rel="stylesheet" href="{{url('vendor/fontawesome/css/font-awesome.css')}}"/>--}}
    <link rel="stylesheet" href="{{url('vendor/animate.css/animate.css')}}"/>
    <link rel="stylesheet" href="{{url('vendor/bootstrap/css/bootstrap.css')}}"/>
    <link rel="stylesheet" href="{{url('vendor/toastr/toastr.min.css')}}"/>
    {{--<link rel="stylesheet" href="{{url('vendor/datatables/datatables.min.css')}}"/>--}}
    <link rel="stylesheet" href="{{url('vendor/switchery/switchery.min.css')}}"/>


    <!-- App styles -->
    <link rel="stylesheet" href="{{url('styles/pe-icons/pe-icon-7-stroke.css')}}"/>
    <link rel="stylesheet" href="{{url('styles/pe-icons/helper.css')}}"/>
    <link rel="stylesheet" href="{{url('styles/stroke-icons/style.css')}}"/>
    <link rel="stylesheet" href="{{url('styles/style.css')}}">
    <link rel="stylesheet" href="{{url('vendor/select2/dist/css/select2.min.css')}}"/>

    <script src="{{url('vendor/jquery/dist/jquery.min.js')}}"></script>


</head>
<body>

<!-- Wrapper-->
<div class="wrapper">

    <!-- Header-->
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <div id="mobile-menu">
                    <div class="left-nav-toggle">
                        <a href="#">
                            <i class="pe-7s-menu"></i>
                        </a>
                    </div>
                </div>
                <a class="navbar-brand" href="#" style="letter-spacing: 1px !important; font-weight: bold !important;">
                    Zahid Scan
                    {{--<span></span>--}}
                </a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <div class="left-nav-toggle">
                    <a href="#">
                        <i class="pe-7s-menu"></i>
                    </a>
                </div>
                <form class="navbar-form navbar-left">
                    <input type="text" class="form-control" placeholder="Search data for analysis" style="width: 175px">
                </form>
                <ul class="nav navbar-nav navbar-right" >
                    <li>
                        <a href="#" data-toggle="dropdown"  aria-expanded="false" aria-haspopup="true" role="button" aria-haspopup="true" v-pre>
                           <span class="profile-address"> {{ Auth::user()->name }} </span> <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    Logout
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </li>
                    {{--<li class="profil-link" style="margin:9px auto">--}}
                        {{--<img src="images/logo.png"  class="img-circle" alt="">--}}
                    {{--</li>--}}
                </ul>
            </div>
        </div>
    </nav>
    <!-- End header-->

    <!-- Navigation-->
    <aside class="navigation">
        <nav>
            <ul class="nav luna-nav">
                {{--super admin--}}
                    <li class="nav-category">
                        Main
                    </li>

                    <li>
                        <a href="{{url('/dashboard')}}">Dashboard</a>
                    </li>
                    {{--Sales include forms and reports--}}
                    <li class="nav-category">
                        Sales
                    </li>

                    <li>
                        <a href="#salesforms" data-toggle="collapse" aria-expanded="false">
                            Forms<span class="sub-nav-icon"> <i class="stroke-arrow"></i> </span>
                        </a>
                        <ul id="salesforms" class="nav nav-second collapse">
                            <li><a href="{{url('/sales/forms/job-order')}}">Job Order (CTP)</a></li>
                            <li><a href="{{url('/sales/forms/cash-collection')}}">Cash Collection</a></li>
                            <li><a href="{{url('/sales/forms/customer-adjustment')}}">Customer Adjustment Form</a></li>
                            <li><a href="{{url('/job-order/search')}}">Search Job Order</a></li>
                            <li><a href="{{url('/sale/receipt')}}">Receipts Collection</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="#salesreports" data-toggle="collapse" aria-expanded="false">
                            Reports<span class="sub-nav-icon"> <i class="stroke-arrow"></i> </span>
                        </a>
                        <ul id="salesreports" class="nav nav-second collapse">
                            <li><a href="{{url('/sales/reports/customerLedgerReport')}}">Customer Ledger Report</a></li>
                            <li><a href="{{url('/sales/reports/walkCustomerLedger')}}">Walk Customer Ledger Report</a></li>
                            <li><a href="{{url('/reports/customerLedgerDetail')}}">Customer ledger Detail Report</a></li>
                            <li><a href="{{url('/customer/index')}}">Customer Summary Report</a></li>
                        </ul>
                    </li>

                    <li class="nav-category">
                        Purchases
                    </li>

                    <li>
                        <a href="#purchasesforms" data-toggle="collapse" aria-expanded="false">
                            Forms<span class="sub-nav-icon"> <i class="stroke-arrow"></i> </span>
                        </a>
                        <ul id="purchasesforms" class="nav nav-second collapse">
                            <li><a href="{{url('/purchase/add')}}">Purchase Order</a></li>
                            <li><a href="{{url('/cashpayment/add')}}">Cash Payments</a></li>
                            <li><a href="{{url('/supplier-adjustment/add')}}">Supplier Adjustment Form</a></li>

                        </ul>
                    </li>

                    <li>
                        <a href="#purchasesreports" data-toggle="collapse" aria-expanded="false">
                            Reports<span class="sub-nav-icon"> <i class="stroke-arrow"></i> </span>
                        </a>
                        <ul id="purchasesreports" class="nav nav-second collapse">
                            <li><a href="{{url('/purchases/supplier_ledger')}}">Supplier Ledger Report</a></li>
                            <li><a href="{{url('/purchase/supplier_ledger_detail')}}">Supplier Ledger Detail Report</a></li>
                            <li><a href="{{url('/vendor/index')}}">Supplier summary Report</a></li>

                        </ul>
                    </li>

                    <li class="nav-category">
                        Inventory
                    </li>

                    <li>
                        <a href="#inventoryforms" data-toggle="collapse" aria-expanded="false">
                            Forms<span class="sub-nav-icon"> <i class="stroke-arrow"></i> </span>
                        </a>
                        <ul id="inventoryforms" class="nav nav-second collapse">
                            <li><a href="{{url('/wastage/add')}}">Wastage</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="#inventoryreports" data-toggle="collapse" aria-expanded="false">
                            Reports<span class="sub-nav-icon"> <i class="stroke-arrow"></i> </span>
                        </a>
                        <ul id="inventoryreports" class="nav nav-second collapse">
                            <li><a href="{{url('/inventory/inventory-report')}}">Inventory Report</a></li>
                            <li><a href="{{url('/inventory/inventory-summary')}}">Inventory Summary Report</a></li>
                        </ul>
                    </li>

                    <li class="nav-category">
                        General Ledger
                    </li>

                    <li>
                        <a href="#generalledgerforms" data-toggle="collapse" aria-expanded="false">
                            Forms<span class="sub-nav-icon"> <i class="stroke-arrow"></i> </span>
                        </a>
                        <ul id="generalledgerforms" class="nav nav-second collapse">
                            <li><a href="{{url('/general-ledger-adjustment/add')}}">General Ledger Adjustment Form</a></li>
                            <li><a href="{{url('/petty-cash/add')}}">Petty Cash Payment</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="#generalledgerreports" data-toggle="collapse" aria-expanded="false">
                            Reports<span class="sub-nav-icon"> <i class="stroke-arrow"></i> </span>
                        </a>
                            <ul id="generalledgerreports" class="nav nav-second collapse">
                                <li><a href="{{url('/general-ledger/reports')}}">GL Reports</a></li>
                                <li><a href="{{url('/general-ledger/dailyReports')}}">Daily Report</a></li>
                            </ul>
                    </li>

                    <li class="nav-category">
                        Trading
                    </li>

                    <li>
                        <a href="#tradingforms" data-toggle="collapse" aria-expanded="false">
                            Forms<span class="sub-nav-icon"> <i class="stroke-arrow"></i> </span>
                        </a>
                        <ul id="tradingforms" class="nav nav-second collapse">
                            <li><a href="{{url('/supplier/add')}}"> Supplier Opening Form</a></li>
                            <li><a href="{{url('/customer/add')}}">Customer Opening Form</a></li>
                            <li><a href="{{url('/stock/add')}}">Inventory Opening Form</a></li>
                            <li><a href="{{url('/customer/saleprice')}}">Set Sale price</a></li>
                            {{--<li><a href="{{url('/stock/size')}}">Sizes</a></li>--}}
                        </ul>
                    </li>

                    <li class="nav-category">
                        Jobs
                    </li>
                    <li>
                        <a href="#jobs" data-toggle="collapse" aria-expanded="false">
                            Jobs list <span class="sub-nav-icon"> <i class="stroke-arrow"></i></span>
                        </a>
                        <ul id="jobs" class="nav nav-second collapse">
                            <li><a href="{{url('/jobs-list/pending-jobs')}}">Pending Jobs</a></li>
                            <li><a href="{{url('/jobs-list/printed-jobs')}}">Printed Jobs</a></li>
                            <li><a href="{{url('/jobs-list/submitted-jobs')}}">Show Jobs</a></li>
                        </ul>
                    </li>

                 @if(Auth::user()->type_id === 1)
                    <li class="nav-category">
                        Accounts
                    </li>

                    <li>
                        <a href="{{url('register')}}">
                            Add User
                        </a>
                    </li>
                    <li>
                        <a href="{{url('/waste/show')}}">Wastage Report</a>
                    </li>
                @endif

                    <li class="nav-category">
                        Edit
                    </li>
                    <li><a href="{{url('/form/edit')}}">Edit Selection Form</a></li>
                    <li><a href="{{url('/accounting/close-date')}}">Close Accounting Date</a></li>
            </ul>
        </nav>
    </aside>
    <!-- End navigation-->


    <!-- Main content-->
    <section class="content" >

        @yield('content')
    </section>
    <!-- End main content-->

</div>
<!-- End wrapper-->

<!-- Vendor scripts -->
<script src="{{url('vendor/pacejs/pace.min.js')}}"></script>
<script src="{{url('vendor/toastr/toastr.min.js')}}"></script>
{{--<script src="{{url('vendor/sparkline/index.js')}}"></script>--}}
{{--<script src="{{url('vendor/flot/jquery.flot.min.js')}}"></script>--}}
{{--<script src="{{url('vendor/flot/jquery.flot.resize.min.js')}}"></script>--}}
{{--<script src="{{url('vendor/flot/jquery.flot.spline.js')}}"></script>--}}
<script src="{{url('vendor/bootstrap/js/bootstrap.min.js')}}"></script>
<script src="{{url('vendor/bootstrap3-typeahead/bootstrap3-typeahead.min.js')}}"></script>
<script src="{{url('vendor/select2/dist/js/select2.js')}}"></script>



<!-- App scripts -->
<script src="{{url('scripts/luna.js')}}"></script>

<script>
    $(document).ready(function () {
        toastr.options = {
            "debug": false,
            "newestOnTop": false,
            "positionClass": "toast-bottom-right",
            "closeButton": true,
            "progressBar": true
        };
    });
</script>

{{--<script src="{{url('vendor/datatables/datatables.min.js')}}"></script>--}}
</body>
</html>