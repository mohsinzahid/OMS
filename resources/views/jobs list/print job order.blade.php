
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />

    <title>Zahid Scan And CTP</title>

    <link rel='stylesheet' type='text/css' href='{{url('css/style.css')}}' />
    <link rel='stylesheet' type='text/css' href='{{url('css/print.css')}}' media="print" />
    <script type='text/javascript' src='{{url('js/jquery-1.3.2.min.js')}}'></script>
    <script type='text/javascript' src='{{url('js/example.js')}}'></script>

</head>

<body>
<div id="page-wrap">
    {{--<h2 style="text-align: center">Zahid Scan And CTP</h2>--}}

    <div id="header">INVOICE</div>

    {{--<div id="identity">--}}
        {{--<textarea id="address">Chris Coyier--}}
{{--123 Appleseed Street--}}
{{--Appleville, WI 53719--}}

{{--Phone: (555) 555-5555</textarea>--}}

        {{--<div id="logo" style="text-align: center">--}}
            {{--<img src="{{url('images/logo.png')}}"  alt="" class="img-responsive" style="height: 30%; width: 30%; float: right">--}}
        {{--</div>--}}

    {{--</div>--}}

    <div style="clear:both"></div>

    <div id="customer">

        <table id="meta" style="float: left;">
            <tr>
                <td class="meta-head">Customer Name</td>
                <td>{{$walkincustomer->name}}</td>
            </tr>
            <tr>

                <td class="meta-head">Mobile Number</td>
                <td>{{$walkincustomer->mobile}}</td>
            </tr>
            <tr>
                <td class="meta-head">Email</td>
                <td>{{$walkincustomer->email}}</td>
            </tr>

        </table>

        <table id="meta">
            <tr>
                <td class="meta-head">Date</td>
                <td>{{$inventory->dateofsale}}</td>
            </tr>
            <tr>
                <td class="meta-head">CGN</td>
                <td>{{$inventory->id}}</td>
            </tr>
            <tr>
                <td class="meta-head">Invoice #</td>
                <td>{{$inventory->invoiceno}}</td>
            </tr>
        </table>

    </div>
    <input type="hidden" name="productItemCount" value="{{$len}}" id="counter">
    <input type="hidden" name="walkid" value="{{$walk->id}}" id="walk">
    <input type="hidden" name="saleid" value="{{$saleinventoryid}}">
    <input type="hidden" name="oldcustid" value="{{$inventory->customer_id}}">

    <table id="items">

        <tr>
            <th>Size</th>
            <th>File Name</th>
            <th>Color</th>
            <th>Set</th>
            <th>Price</th>
        </tr>
        @if(sizeof($item)>0)
            @for($i =0; $i < sizeof($item) ;$i++)
                <tr class="item-row" style="text-align: center; border: 1px solid">
                    <td class="item-name" style="border: 1px solid">
                        {{$item[$i]->size}}
                    </td>
                    <td class="description" style="border: 1px solid">
                        {{$item[$i]->description}}
                    </td>
                    <td style="border: 1px solid">
                            {{$item[$i]->color}}
                    </td>
                    <input type="hidden" id="color{{$i}}" value="{{$item[$i]->color}}">
                    <td style="border: 1px solid">
                        {{$item[$i]->set}}
                    </td>
                    <input type="hidden" id="set{{$i}}" value="{{$item[$i]->set}}">
                    <input type="hidden" id="saleprice{{$i}}" value="{{$item[$i]->saleprice}}">
                    <td style="border: 1px solid">
                        <div id="answer{{$i}}"></div>
                    </td>
                    <input type="hidden" id="answer{{$i}}">
                </tr>
            @endfor
        @endif

        <tr>
            <td colspan="2" class="blank"> </td>
            <td class="total-line">Total</td>
            <td colspan="2" class="total-value"><div id="total"></div></td>
        </tr>
        <tr>
            <td colspan="2" class="blank"> </td>
            <td  class="total-line balance">Advance</td>
            <td colspan="2" class="total-value balance"></td>
        </tr>
        <tr>
            <td colspan="2" class="blank"> </td>
            <td  class="total-line">Amount Paid</td>
            <td colspan="2" class="total-value">{{$inventory->paidamount}}</td>
        </tr>

    </table>

    <div id="terms">
        <h5>Terms</h5>
        <div>NET 30 Days. Finance Charge of 1.5% will be made on unpaid balances after 30 days.</div>
    </div>

</div>
<br>
<hr>
<div id="page-wrap">
    {{--<h2 style="text-align: center">Zahid Scan And CTP</h2>--}}

    <div id="header">INVOICE</div>

    {{--<div id="identity">--}}
    {{--<textarea id="address">Chris Coyier--}}
    {{--123 Appleseed Street--}}
    {{--Appleville, WI 53719--}}

    {{--Phone: (555) 555-5555</textarea>--}}

    {{--<div id="logo" style="text-align: center">--}}
    {{--<img src="{{url('images/logo.png')}}"  alt="" class="img-responsive" style="height: 30%; width: 30%; float: right">--}}
    {{--</div>--}}

    {{--</div>--}}

    <div style="clear:both"></div>

    <div id="customer">

        <table id="meta" style="float: left;">
            <tr>
                <td class="meta-head">Customer Name</td>
                <td>{{$walkincustomer->name}}</td>
            </tr>
            <tr>

                <td class="meta-head">Mobile Number</td>
                <td>{{$walkincustomer->mobile}}</td>
            </tr>
            <tr>
                <td class="meta-head">Email</td>
                <td>{{$walkincustomer->email}}</td>
            </tr>

        </table>

        <table id="meta">
            <tr>
                <td class="meta-head">Date</td>
                <td>{{$inventory->dateofsale}}</td>
            </tr>
            <tr>
                <td class="meta-head">CGN</td>
                <td>{{$inventory->id}}</td>
            </tr>
            <tr>
                <td class="meta-head">Invoice #</td>
                <td>{{$inventory->invoiceno}}</td>
            </tr>
        </table>

    </div>
    <input type="hidden" name="productItemCount" value="{{$len}}" id="counter">
    <input type="hidden" name="walkid" value="{{$walk->id}}" id="walk">
    <input type="hidden" name="saleid" value="{{$saleinventoryid}}">
    <input type="hidden" name="oldcustid" value="{{$inventory->customer_id}}">

    <table id="items">

        <tr>
            <th>Size</th>
            <th>File Name</th>
            <th>Color</th>
            <th>Set</th>
            <th>Price</th>
        </tr>
        @if(sizeof($item)>0)
            @for($i =0; $i < sizeof($item) ;$i++)
                <tr class="item-row" style="text-align: center; border: 1px solid">
                    <td class="item-name" style="border: 1px solid">
                        {{$item[$i]->size}}
                    </td>
                    <td class="description" style="border: 1px solid">
                        {{$item[$i]->description}}
                    </td>
                    <td style="border: 1px solid">
                        {{$item[$i]->color}}
                    </td>
                    <input type="hidden" id="color{{$i}}" value="{{$item[$i]->color}}">
                    <td style="border: 1px solid">
                        {{$item[$i]->set}}
                    </td>
                    <input type="hidden" id="set{{$i}}" value="{{$item[$i]->set}}">
                    <input type="hidden" id="saleprice{{$i}}" value="{{$item[$i]->saleprice}}">
                    <td style="border: 1px solid">
                        <div id="answer{{$i}}"></div>
                    </td>
                    <input type="hidden" id="answer{{$i}}">
                </tr>
            @endfor
        @endif

        <tr>
            <td colspan="2" class="blank"> </td>
            <td class="total-line">Total</td>
            <td colspan="2" class="total-value"><div id="total"></div></td>
        </tr>
        <tr>
            <td colspan="2" class="blank"> </td>
            <td  class="total-line balance">Advance</td>
            <td colspan="2" class="total-value balance"></td>
        </tr>
        <tr>
            <td colspan="2" class="blank"> </td>
            <td  class="total-line">Amount Paid</td>
            <td colspan="2" class="total-value">{{$inventory->paidamount}}</td>
        </tr>

    </table>

    <div id="terms">
        <h5>Terms</h5>
        <div>NET 30 Days. Finance Charge of 1.5% will be made on unpaid balances after 30 days.</div>
    </div>

</div>
</body>

</html>

<script>
    var walkid;
    $(document).ready(function () {
        var Count = $('#counter').val();
        for (var i = 0; i < Count; i++) {
            Calculate(i);
        }
        tot();
        walkid =$("#walk").val();
        window.print();
    });

    function Calculate(locate)
    {
        var set = document.getElementById('set'+locate).value;
        var color = document.getElementById('color'+locate).value;
        var saleprice = document.getElementById('saleprice'+locate).value;
        var amount = (saleprice * (set * color));
        $('#answer'+locate).append(amount);
        document.getElementById('answer'+locate).value=parseFloat(amount);
    }

    function tot() {
        var counts = $('#counter').val();
        var totalamount =0;
        for(var i = 0 ; i<counts ; i++)
        {
            var val = document.getElementById('answer'+i).value;
            totalamount = parseFloat(totalamount) + parseFloat(val);
        }

        $('#total').append(totalamount);
    }

</script>