<!DOCTYPE html>
<html lang="en">
<head>
    <title>Zahid Scan</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--===============================================================================================-->
    <link rel="icon" type="image/png" href="{{url('images/icons/favicon.ico')}}"/>
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{url('vendor/bootstrap/css/bootstrap.min.css')}}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{url('fonts/font-awesome-4.7.0/css/font-awesome.min.css')}}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{url('fonts/iconic/css/material-design-iconic-font.min.css')}}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{url('vendor/animate/animate.css')}}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{url('vendor/select2/select2.min.css')}}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{url('css/util.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('css/main.css')}}">
    <!--===============================================================================================-->

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-128559613-2"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-128559613-2');
    </script>


</head>
<body>

<div class="bg-g1 size1 flex-w flex-col-c-sb p-l-15 p-r-15 p-t-15 p-b-60 respon1" >
    <span class=" txt-center"><img src="images/logo.png"  alt="" class="img-responsive" style="height: 50%; width: 50%"></span>
    <div class="flex-col-c p-t-50 p-b-50">
        <h3 class="l1-txt1 txt-center p-b-10">
            Coming Soon
        </h3>

        <p class="txt-center l1-txt2 p-b-60">
            Our website is under construction
        </p>

        <div class="flex-w flex-c cd100 p-b-82" id="time">
            <div class="flex-col-c-m size2 how-countdown">
                <span class="l1-txt3 p-b-9 days" id="day"></span>
                <span class="s1-txt1">Days</span>
            </div>

            <div class="flex-col-c-m size2 how-countdown">
                <span class="l1-txt3 p-b-9 hours" id="hour"></span>
                <span class="s1-txt1">Hours</span>
            </div>

            <div class="flex-col-c-m size2 how-countdown">
                <span class="l1-txt3 p-b-9 minutes" id="min"></span>
                <span class="s1-txt1">Minutes</span>
            </div>

            <div class="flex-col-c-m size2 how-countdown">
                <span class="l1-txt3 p-b-9 seconds" id="sec"></span>
                <span class="s1-txt1">Seconds</span>
            </div>
        </div>

        {{--<button class="flex-c-m s1-txt2 size3 how-btn"  data-toggle="modal" data-target="#subscribe">--}}
            {{--Follow us for update now!--}}
        {{--</button>--}}
    </div>

    <span class="s1-txt3 txt-center">
			Copyright@ 2018 Developed by Mohsin Zahid
		</span>

</div>

<!-- Modal Login -->
{{--<div class="modal fade" id="subscribe" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document" data-dismiss="modal">
        <div class="modal-subscribe where1-parent bg0 bor2 size4 p-t-54 p-b-100 p-l-15 p-r-15">
            <button class="btn-close-modal how-btn2 fs-26 where1 trans-04">
                <i class="zmdi zmdi-close"></i>
            </button>

            <div class="wsize1 m-lr-auto">
                <h3 class="m1-txt1 txt-center p-b-36">
                    <span class="bor1 p-b-6">Subscribe</span>
                </h3>

                <p class="m1-txt2 txt-center p-b-40">
                    Follow us for update now!
                </p>

                <form class="contact100-form validate-form">
                    <div class="wrap-input100 m-b-10 validate-input" data-validate = "Name is required">
                        <input class="s1-txt4 placeholder0 input100" type="text" name="name" placeholder="Name">
                        <span class="focus-input100"></span>
                    </div>

                    <div class="wrap-input100 m-b-20 validate-input" data-validate = "Email is required: ex@abc.xyz">
                        <input class="s1-txt4 placeholder0 input100" type="text" name="email" placeholder="Email">
                        <span class="focus-input100"></span>
                    </div>

                    <div class="w-full">
                        <button class="flex-c-m s1-txt2 size5 how-btn1 trans-04">
                            Get Updates
                        </button>
                    </div>
                </form>

                <p class="s1-txt5 txt-center wsize2 m-lr-auto p-t-20">
                    And don’t worry, we hate spam too! You can unsubcribe at anytime.
                </p>
            </div>
        </div>

    </div>
</div>--}}



<!--===============================================================================================-->
<script src="{{url('vendor/jquery/jquery-3.2.1.min.js')}}"></script>
<!--===============================================================================================-->
<script src="{{url('vendor/bootstrap/js/popper.js')}}"></script>
<script src="{{url('vendor/bootstrap/js/bootstrap.min.js')}}"></script>
<!--===============================================================================================-->
<script src="{{url('vendor/select2/select2.min.js')}}"></script>
<!--===============================================================================================-->
<script src="{{url('vendor/countdowntime/moment.min.js')}}"></script>
<script src="{{url('vendor/countdowntime/moment-timezone.min.js')}}"></script>
<script src="{{url('vendor/countdowntime/moment-timezone-with-data.min.js')}}"></script>
<script src="{{url('vendor/countdowntime/countdowntime.js')}}"></script>
{{--<script>--}}
    {{--$('.cd100').countdown100({--}}
        {{--// Set Endtime here--}}
        {{--// Endtime must be > current time--}}
        {{--endtimeYear: 0,--}}
        {{--endtimeMonth: 0,--}}
        {{--endtimeDate: 10,--}}
        {{--endtimeHours: 18,--}}
        {{--endtimeMinutes: 0,--}}
        {{--endtimeSeconds: 0,--}}
        {{--timeZone: ""--}}
        {{--// ex:  timeZone: "America/New_York", can be empty--}}
        {{--// go to " http://momentjs.com/timezone/ " to get timezone--}}
    {{--});--}}
{{--</script>--}}

<script>

    // Set the date we're counting down to
    var countDownDate = new Date("nov 30, 2018 24:00:00").getTime();

    // Update the count down every 1 second
    var x = setInterval(function() {

        // Get todays date and time
        var now = new Date().getTime();

        // Find the distance between now and the count down date
        var distance = countDownDate - now;

        // Time calculations for days, hours, minutes and seconds
        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        // Display the result in the element with id="demo"
        document.getElementById("day").innerHTML = days;
        document.getElementById("hour").innerHTML = hours;
        document.getElementById("min").innerHTML = minutes;
        document.getElementById("sec").innerHTML = seconds;

        /*// If the count down is finished, write some text
        if (distance < 0) {
            clearInterval(x);
            document.getElementById("demo").innerHTML = "EXPIRED";
        }*/
    }, 1000);

    /*$('.cd100').countdown100({
        // Set Endtime here
        // Endtime must be > current time
        endtimeYear: 0,
        endtimeMonth: 0,
        endtimeDate: days,
        endtimeHours: hours,
        endtimeMinutes: minutes,
        endtimeSeconds: seconds,
        timeZone: ""
        // ex:  timeZone: "America/New_York", can be empty
        // go to " http://momentjs.com/timezone/ " to get timezone
    });*/
</script>
<!--===============================================================================================-->
<script src="{{url('vendor/tilt/tilt.jquery.min.js')}}"></script>
<script >
    $('.js-tilt').tilt({
        scale: 1.1
    })
</script>
<!--===============================================================================================-->
<script src="{{url('js/main.js')}}"></script>

</body>
</html>