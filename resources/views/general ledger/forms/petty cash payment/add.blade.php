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
                        <form method="post" action="{{ url('/petty-cash/post-payment') }}" id="form" autocomplete="off">
                            {{ csrf_field() }}

                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label for="Inputdate">Date</label>
                                    <input type="date" class="form-control" id="date" name="date" required autofocus>
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

    <script>
        $("form").submit(function(e){
            var date =$("#date").val();
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
                url: '/receipts/ajax-validate-period',
                data: {date: date},

                success: function (response) {
                    // console.log(response);
                    if (response === 1) {
                        //$("#submit").removeAttr("disabled").removeClass("btn-default").addClass("btn-success");
                        $('#form')[0].submit();
                    }
                    else
                    {
                        toastr.warning('Selected Date is in closed period.');
                        //$("#submit").attr("disabled", true).removeClass("btn-success").addClass("btn-default");
                    }
                },
                error: function (XMLHttpRequest, jqXHR, textStatus, errorThrown) {
                    if (XMLHttpRequest.readyState == 0) {
                        // Network error (i.e. connection refused, access denied due to CORS, etc.)
                        toastr.error('Network Connection Refused');
                    }
                    console.log(XMLHttpRequest);
                    console.log(JSON.stringify(jqXHR));
                    console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                }
            });
            e.preventDefault(e);
        })
    </script>
@endsection
