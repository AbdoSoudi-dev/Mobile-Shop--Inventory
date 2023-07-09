@extends("page_layout")
@section("page_content")

    <style>
        .text-bold{
            font-weight: bold;
        }

    </style>

    <div class="row">

        <div class="col-12">

            <div class="card mt-5">

                @if (session()->has('message'))
                    <div class="bg-primary text-light text-bold py-2 px-5  text-center mx-auto" id="session_message">
                        {{ session()->get("message") }}
                    </div>
                @endif

                <div class="col-12 m-auto text-center">
                    <h4 class="col-12 text-bold">
                        سجل التعديل عن شهر:
                    </h4>
                    <input type="month" class="form-control col-md-6 col-12 btn btn-outline-info" onchange="change_month(event)" value="{{ $month }}">
                </div>

                <div class="card-body">
                    <h4 class="card-title">سجل تعديلات</h4>
                    <h6 class="card-subtitle">عدد: {{ count($productsLogs) }}</h6>
                    <div class="table-responsive m-t-40">
                        <table id="myTable" class="table table-bordered nowrap table-striped w-100">
                            <thead>
                                <tr class="text-center text-bold">
                                    <td>م</td>
                                    <td>القسم</td>
                                    <td>الماركة</td>
                                    <td>المنتج</td>
                                    <td>الشراء</td>
                                    <td>البيع</td>
                                    <td>السيريال</td>
                                    <td>ملاحظات</td>
                                    <td>الكمية</td>
                                    <td>المستخدم</td>
                                    <td>الوقت</td>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($productsLogs as $key => $products_log)
                                <tr class="text-center">
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $products_log['product']['barcode']['category']['title'] }}</td>
                                    <td>{{ $products_log['product']['barcode']['brand']['title'] }}</td>

                                    <td> {!! helpers::compareValues($products_log['title']) !!} </td>
                                    <td> {!! helpers::compareValues($products_log['price']) !!} </td>
                                    <td> {!! helpers::compareValues($products_log['selling_price']) !!} </td>
                                    <td> {!! helpers::compareValues($products_log['serial_no']) !!} </td>
                                    <td> {!! helpers::compareValues($products_log['notes']) !!} </td>
                                    <td> {!! helpers::compareValues($products_log['quantity']) !!} </td>

                                    <td>{{ $products_log['user']['name'] }}</td>
                                    <td>{{ $products_log['created_at']->format("Y-m-d h:i A") }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <script>
        $(document).ready(function () {
            $('.selectpicker').selectpicker();

            $('#myTable').DataTable({
                "scrollX": true,
            });
        })

        setTimeout(()=>{
            $("#session_message")?.remove();
        },5000);

        function change_month(event) {
            let month = event.currentTarget.value;
            window.location = `${month}`;
        }
    </script>
@endsection
