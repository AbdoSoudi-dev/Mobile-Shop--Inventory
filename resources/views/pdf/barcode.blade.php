<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.png">
    <title>Grand Phone | {{ $barcode['title'] }}</title>
    <style>
        @page { margin: 0; }
        body { margin: 0; }
        .d-flex{
            display: flex;
        }
        .justify-content-center{
            justify-content: center;
        }
        .col-3{
            width: 25%;
        }
        .text-center{
            text-align: center;
        }
        .text-left{
            text-align: left;
        }
        .mx-3{
            margin: 10px;
        }
        .m-auto{
            margin: auto;
        }

    </style>
</head>
<body style="font-family: Arial">

<div style="height: 90%; width: 95%; margin-bottom: 0">
    <div class="text-center" style="margin-bottom:0; font-size: 15px">{{ $barcode['brand']['title'] }} - {{ $barcode['title'] }}</div>
    <div class="text-center">
        <img src="data:image/png;base64,{{DNS1D::getBarcodePNG(abs($barcode['barcode']), 'C39',1,55,array(0,0,0), true)}}"
             width="95%"  alt="barcode" />
    </div>
</div>

{{--<div style="height: 13%; width: 80%;margin-bottom: -.60cm; opacity: 0">--}}
{{--    <h3 class="text-left" style="margin-bottom:5px; font-size: 18px">--}}
{{--        {{ $barcode['category']['title'] . " - " . ($barcode['brand']['id'] != 10 ? $barcode['brand']['title'] . " - " : "") . $barcode['title'] }}--}}
{{--    </h3>--}}
{{--    <br>--}}
{{--    <br>--}}
{{--    <br>--}}
{{--    <div class="text-left">--}}
{{--        <img src="data:image/png;base64,{{DNS1D::getBarcodePNG($barcode['barcode'], 'C39',1,55,array(0,0,0), true)}}"--}}
{{--             width="100%" alt="barcode" />--}}
{{--    </div>--}}
{{--</div>--}}
{{--<div style="height: 13%; width: 80%;margin-bottom: 1.5cm">--}}
{{--    <h6 class="text-left" style="margin-bottom:5px; font-size: 13px; direction: rtl">--}}
{{--        {{ $barcode['category']['title'] . " - " . ($barcode['brand']['id'] != 10 ? $barcode['brand']['title'] . " - " : "") . $barcode['title'] }}--}}
{{--    </h6>--}}
{{--    <div class="text-left">--}}
{{--        <img src="data:image/png;base64,{{DNS1D::getBarcodePNG($barcode['barcode'], 'C39',1,55,array(0,0,0), true)}}"--}}
{{--             width="100%" alt="barcode" />--}}
{{--    </div>--}}
{{--</div>--}}
{{--<div style="height: 15%; width: 80%;margin-bottom: 1.3cm">--}}
{{--    <h6 class="text-left" style="margin-bottom:5px; font-size: 13px; direction: rtl">--}}
{{--        {{ $barcode['category']['title'] . " - " . ($barcode['brand']['id'] != 10 ? $barcode['brand']['title'] . " - " : "") . $barcode['title'] }}--}}
{{--    </h6>--}}
{{--    <div class="text-left">--}}
{{--        <img src="data:image/png;base64,{{DNS1D::getBarcodePNG($barcode['barcode'], 'C39',1,55,array(0,0,0), true)}}"--}}
{{--             width="100%" alt="barcode" />--}}
{{--    </div>--}}
{{--</div>--}}
{{--<div style="height: 15%; width: 80%;margin-bottom: -1cm">--}}
{{--    <h6 class="text-left" style="margin-bottom:5px; font-size: 13px; direction: rtl">--}}
{{--        {{ $barcode['category']['title'] . " - " . ($barcode['brand']['id'] != 10 ? $barcode['brand']['title'] . " - " : "") . $barcode['title'] }}--}}
{{--    </h6>--}}
{{--    <div class="text-left">--}}
{{--        <img src="data:image/png;base64,{{DNS1D::getBarcodePNG($barcode['barcode'], 'C39',1,55,array(0,0,0), true)}}"--}}
{{--             width="100%" alt="barcode" />--}}
{{--    </div>--}}
{{--</div>--}}


</body>
</html>
