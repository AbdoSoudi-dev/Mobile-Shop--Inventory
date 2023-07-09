@extends("page_layout")
@section("page_content")

    <style>
        .cursor-pointer{
            cursor: pointer;
        }
        .text-bold{
            font-weight: bolder !important;
        }
        .bg-gray{
            background-color: #6c757d;
        }
    </style>


    {{--    <pre>--}}
    {{--        {{ print_r($sells) }}--}}
    {{--    </pre>--}}


    <div class="row">
        <div class="col-12">

            <div class="card">

                <div class="row">
                    <div class="col-12">

                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title text-info text-bold">أرباح مبيعات عن شهر</h4>
                                <div class="col-12 text-center">
                                    <input type="month" onchange="sell_month()" value="{{ $month }}" class="col-6 form-control m-auto date_month">
                                </div>

                                <div class="table-responsive m-t-40">
                                    <table class="table ">
                                        <thead class="thead-dark text-bold ">
                                        <tr class="text-center bg-dark text-light text-bold">
                                            <td>م</td>
                                            <td>م يوم</td>
                                            <td>بيان</td>
                                            <td>ملاحظات</td>
                                            <td>العميل</td>
                                            <td>المستخدم</td>
                                            <td>الساعة</td>
                                            <td>البيع</td>
                                            <td>ربح  يومي</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php($count = 0)
                                        @php($net_profit = 0)

                                        @foreach($sells as $key => $sells_value)
                                            @php($net_profit_daily = 0)
                                            <tr>
                                                <td colspan="9" class="text-center text-bold bg-info text-light">{{ $key }}</td>
                                            </tr>
                                            @foreach($sells_value as $key => $sell)
                                                @php($count++)
                                                <tr class="{{ $sell['removed'] == 1 ? "text-danger" : ($sell['refund'] == 1 ? "text-success" : "" ) }}">
                                                    <td>{{ $count }}</td>
                                                    <td>{{ $key+1 }}</td>
                                                    <td>
                                                        <div class="col-12 row justify-content-center">
                                                            @php($profit = 0)
                                                            @foreach($sell['sales_invoices'] as $sales_inv)
                                                                @php($profit += $sales_inv['price'] - $sales_inv['product']['price'])
                                                                <div class="col-12">
                                                                    {{ $sales_inv['product']['barcode']['title'] }}
                                                                </div>
                                                                <div class="col-12 bordered">
                                                                    الكمية: {{ $sales_inv['quantity'] . " * " . $sales_inv['price'] . " - " . $sales_inv['product']['price'] }}
                                                                </div>
                                                            @endforeach
                                                            @php($net_profit += ($sell['removed'] == 1 || $sell['refund'] == 1 ? 0 : $profit))
                                                            @php($net_profit_daily += ($sell['removed'] == 1 || $sell['refund'] == 1 ? 0 : $profit) )
                                                        </div>
                                                    </td>
                                                    <td>{{ $sell['notes'] }}</td>
                                                    <td>{{ $sell['client']['name'] ?? "" }}</td>
                                                    <td>{{ $sell['user']['name']}}</td>
                                                    <td>{{ $sell['created_at']->format("h:i A") }}</td>
                                                    <td>{{ floor($sell['debit']) != 0 ? $sell['debit'] : $sell['unpaid_debit'] }}</td>
                                                    <td>{{ $sell['removed'] == 1 ? "محذوف" : ($sell['refund'] == 1 ? "مرتجع" : $profit) }}</td>
                                                </tr>
                                            @endforeach
                                            <tr class="text-center text-bold bg-gray text-light mb-2">
                                                <td colspan="7">اجمالي اليومي</td>
                                                <td colspan="2">{{ number_format($net_profit_daily,2) }}</td>
                                            </tr>
                                            <tr class="text-center text-bold bg-dark text-light mb-2">
                                                <td colspan="7">ترصيد الارباح</td>
                                                <td colspan="2">{{ number_format($net_profit,2) }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>

                                        <tfoot class="bg-dark text-light text-bold text-center">
                                        <tr>
                                            <td colspan="7">مجموع أرباح مبيعات شهر
                                                {{ \Carbon\Carbon::parse($month)->format("F") }}
                                                لسنة
                                                {{ \Carbon\Carbon::parse($month)->format("Y") }}
                                            </td>
                                            <td colspan="2">{{ number_format($net_profit,2) }}</td>
                                        </tr>
                                        </tfoot>
                                    </table>

                                </div>
                            </div>
                        </div>


                    </div>
                </div>

            </div>


        </div>
    </div>


    <script>
        $(function () {
            $('#myTable').DataTable({
                "scrollX": true,
            });
        });

        setTimeout(()=>{
            $("#session_message")?.remove();
        },5000);


        function sell_month() {
            let month = $(".date_month").val();
            location.href = `${month}`;
        }
    </script>
@endsection
