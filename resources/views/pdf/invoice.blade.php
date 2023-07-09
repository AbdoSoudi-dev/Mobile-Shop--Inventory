<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.png">
    <title>Grand Phone | {{ $invoice['client']['name'] }}</title>
    <style>
        @page { margin: 50px 10px; }
        body { margin: 50px 10px; }

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
        .bg-dark{
            background-color: black !important;
        }
        .text-light{
            color: #fff !important;
        }
        tr td{
            text-align: center;
        }

        @import url('https://fonts.googleapis.com/css?family=Forum');

        .d-inline-block{
            display: inline-block;
        }

        .w-50{
            width: 50%;
        }

    </style>
</head>
<body style="font-family: Forum,Arial ; position: relative" dir="rtl">

<div style="position: absolute; top: 30px; left: 5px">
    <img style="height: auto; width: 100px; margin:0 0 8px 8px" src="images/logo.jpg" />
    <h5 style="margin: 0 0 20px 25px"> Grand Phone Store </h5>
</div>

<table width="40%" style="font-size: 12px; text-align: right !important; margin: 10px" cellspacing="2" cellpadding="2">
    <tbody>
    <tr>
        <td>
            فاتورة رقم :
        </td>
        <td>
            {{ ($invoice['id']) }}
        </td>
    </tr>
    <tr>
        <td>
            التاريخ :
        </td>
        <td>
            {{ ($invoice['created_at']->format("Y-m-d")) }}
        </td>
    </tr>
    <tr>
        <td>
            الوقت :
        </td>
        <td>
            {{ ($invoice['created_at']->format("h:i")) }} {{ $invoice['created_at']->format("A") == "AM" ? "صباحًا" : "مساءًا" }}
        </td>
    </tr>
    <tr>
        <td>
            اسم العميل :
        </td>
        <td>
            {{ $invoice['client']['name'] }}
        </td>
    </tr>
    @if($invoice['client']['mobile_number'])
        <tr>
            <td>
                رقم الهاتف :
            </td>
            <td>
                {{ $invoice['client']['mobile_number'] }}
            </td>
        </tr>
    @endif
    </tbody>
</table>


<table style="margin-top: 40px; font-size: 14px" width="100%" cellspacing="2" cellpadding="2">
    <thead>
    <tr class="text-light text-center bg-dark">
        <th style="padding: 10px" class="text-light">#</th>
        <th class="text-light">المنتج</th>
        @if($serial_no)
            <th class="text-light"> السيريال </th>
        @endif
        @if($warranty_period)
            <th class="text-light">  الضمان حتى </th>
        @endif
        <th class="text-light">السعر</th>
        <th class="text-light">الكمية</th>
        <th class="text-light">الاجمالي</th>
    </tr>
    </thead>

    <tbody>
    @php($total = 0)
    @foreach($invoice['sales_invoices'] as $key => $inv)
        @php($total += $inv['quantity'] * $inv['price'])
        <tr class="text-center">
            <td style="padding: 10px"> {{ $key+1 }} </td>
            <td> {{ $inv['product']['barcode']['category']['title'] . " - " . $inv['product']['barcode']['title'] }} </td>
            @if($serial_no)
                <td> {{ $inv['product']['barcode']['serial_no'] }} </td>
            @endif
            @if($warranty_period)
                <td> {{ $inv['created_at']->addDays($inv['warranty_period'])->format("Y-m-d") }} </td>
            @endif
            <td> {{ $inv['price'] }} </td>
            <td> {{ $inv['quantity'] }} </td>
            <td> {{ $inv['quantity'] * $inv['price'] }} </td>
        </tr>
    @endforeach
    </tbody>

    <tfoot>
    <tr class="text-light text-center bg-dark">
        <td style="padding: 10px" class="text-light" colspan="{{ $serial_no ? ( $warranty_period ? 5 : 4 ) : 3 }}"><b>الاجمالي</b></td>
        <td class="text-light" colspan="2"> <b>{{ $total }}</b> </td>
    </tr>
    </tfoot>
</table>



<table style="margin-top: 30px; font-size: 15px; font-weight: bolder" width="100%" cellspacing="2" cellpadding="2">
    <tbody>
    <tr class="text-left">
        <td class="text-left">
            <b>ملحوظة: المنتجات ذات ضمان لا تُستبدل بدون فاتورة وتُستبدل فقط مرة واحدة، بشرط عدم وجود كسر او قطع بالمنتج وتكون داخل فترة الضمان الموضح بالأعلى.</b>
        </td>
    </tr>
    </tbody>
</table>


<table style="margin-top: 20px; position: absolute; bottom:0; font-size: 15px;" width="100%" cellspacing="2" cellpadding="2">
    <tbody>
    <tr class="text-left">
        <td class="text-left">
            <b>.Thank you, Hope to see you again</b>

        </td>
    </tr>
    </tbody>
</table>

</body>
</html>
