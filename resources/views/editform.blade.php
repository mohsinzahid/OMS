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
                        <h3>Edit Selection Form</h3>
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
                        <form method="post" action="{{ url('/form/edit/redirect') }}" autocomplete="off">
                            {{ csrf_field() }}

                            <div class="form-group">
                                <label for="formtype">Forms type</label>
                                <select class="select2_demo_1 form-control" name="formid" id="callfunc" style="width: 100%"
                                        {{--onchange="getjobids()"--}} required>
                                    <option value="" selected disabled hidden>Choose here</option>
                                    <option value="jo"> JO &emsp; Job Order(CTP)</option>
                                    <option value="cc"> CC &emsp; Cash Collection</option>
                                    <option value="cadj"> CADJ &emsp; Customer Adjustment</option>
                                    <option value="po"> PO &emsp; Purchase Order</option>
                                    <option value="cp">CP &emsp; Cash Payment</option>
                                    <option value="sadj">SADJ &emsp; Supplier Adjustment</option>
                                    <option value="wa">WA &emsp; Wastage</option>
                                    <option value="gladj">GLADJ &emsp; General Ledger Adjustment</option>
                                    <option value="pc">PC &emsp; Petty Cash</option>
                                    <option value="so">SO &emsp; Supplier Opening</option>
                                    <option value="co">CO &emsp; Customer Opening</option>
                                    <option value="io">IO &emsp; Inventory Opening</option>
                                </select>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-11">
                                    <label for="Inputinvoiceno">
                                        CGN <small>(System Generated Unique ID)</small>
                                    </label>
                                    <input type="text" class="form-control" id="confirmjobid" pattern="\d*"
                                           placeholder="0" name="cgn" onkeyup="confirmjobids()" required>
                                </div>
                                <div class=" col-sm-1" id="check">
                                    <label></label>
                                    <span class="pe-7s-check" style="font-size: 25px;margin-top: 10px;"></span>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary" id="submit" disabled>Submit</button>

                        </form>
                    </div>
                </div>

            </div>
            <div class="col-sm-3"></div>
        </div>
    </div>

    <script>
        var jobids;
        var table;

        function confirmjobids () {
            var jobid =$("#confirmjobid").val();


            var id = $("#callfunc").val();
            if(id === 'jo')
            {
                table = 'saleinventory';
            }
            else if(id === 'cc')
            {
                table = 'salepayment';
            }
            else if(id === 'cadj')
            {
                table = 'customeradjustment';
            }
            else if(id === 'po')
            {
                table = 'purchaseinventory';
            }
            else if(id === 'cp')
            {
                table = 'purchasepayment';
            }
            else if(id === 'sadj')
            {
                table = 'supplieradjustment';
            }
            else if(id === 'cc')
            {
                table = 'salepayment';
            }
            else if(id === 'wa')
            {
                table = 'waste';
            }
            else if(id === 'cc')
            {
                table = 'salepayment';
            }
            else if(id === 'gladj')
            {
                table = 'gladjustment';
            }
            else if(id === 'cc')
            {
                table = 'salepayment';
            }
            else if(id === 'pc')
            {
                table = 'pettycashpayment';
            }
            else if(id === 'so')
            {
                table = 'vendor';
            }
            else if(id === 'co')
            {
                table = 'customers';
            }
            else if(id === 'io')
            {
                table = 'stock';
            }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                statusCode: {
                    500: function () {
                        alert("Script exhausted");
                    }
                },
                type: 'GET',
                url: '/edit-form/ajax/get-id',
                data: {table: table, jobid: jobid},

                success: function (response) {
                    /*jobids = ','+response[0]['jobid']+',';
                    console.log(jobids);
                    confirmid();*/
                    console.log(response);
                    if(response) {
                        $("#submit").removeAttr("disabled").removeClass("btn-default").addClass("btn-success");
                        $(".pe-7s-check").css({ 'color': 'lightgreen' });
                    }
                    else {
                        $("#submit").attr("disabled", true).removeClass("btn-success").addClass("btn-default");
                        $(".pe-7s-check").css({ 'color': '' });
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(JSON.stringify(jqXHR));
                    console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                }
            });
        }
        /*function confirmid () {
            var id =','+$("#confirmjobid").val()+',';
            if( jobids.search(id) !== -1) {
                $("#submit").removeAttr("disabled").removeClass("btn-default").addClass("btn-success");
                $(".pe-7s-check").css({ 'color': 'lightgreen' });
            }
            else {
                $("#submit").attr("disabled", true).removeClass("btn-success").addClass("btn-default");
                $(".pe-7s-check").css({ 'color': '' });
            }
        }*/
    </script>

@endsection