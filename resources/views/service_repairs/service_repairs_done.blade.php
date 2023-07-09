@extends("page_layout")
@section("page_content")
    <style>
        .cursor-pointer{
            cursor: pointer;
        }
    </style>

    <div class="row">
        <div class="col-12">

            <div class="card mt-4">

                @if (session()->has('message'))
                    <div class="bg-primary text-light text-bold py-2 px-5  text-center mx-auto" id="session_message">
                        {{ session()->get("message") }}
                    </div>
                 @endif

                <div class="row">
                    <div class="col-12">

                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title text-info text-bold">صيانة تم الالغاء او التسليم</h4>

                                <div class="col-12 m-auto text-center">
                                    <h4 class="col-12 text-bold">
                                        صيانة عن شهر:
                                    </h4>
                                    <input type="month" class="form-control col-md-6 col-12 btn btn-outline-info" onchange="change_month(event)" value="{{ $month }}">
                                </div>


                                <div class="table-responsive m-t-40">
                                    <table id="example23"
                                           class="display nowrap table table-hover table-striped table-bordered"
                                           cellspacing="0" width="100%">
                                        <thead>
                                            <tr class="text-center cursor-pointer">
                                                <th>م</th>
                                                <th>العميل</th>
                                                <th>رقم التليفون</th>
                                                <th>الماركة</th>
                                                <th>الجهاز</th>
                                                <th>المشكلة</th>
                                                <th>ملاحظات</th>
                                                <th>السيريال</th>
                                                <th>الحالة</th>
                                                <th>المستخدم</th>
                                                <th>التاريخ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($service_repairs as $key => $service_repair)
                                                <tr class="{{ $service_repair['removed'] == 1 ? "text-danger" : "" }}">
                                                    <td>{{ $key+1 }}</td>
                                                    <td>{{ $service_repair['client']['name'] }}</td>
                                                    <td>{{ $service_repair['client']['mobile_number'] }}</td>
                                                    <td>{{ $service_repair['brand']['title'] }}</td>
                                                    <td>{{ $service_repair['title'] }}</td>
                                                    <td>{{ $service_repair['problem'] }}</td>
                                                    <td>{{ $service_repair['notes'] }}</td>
                                                    <td>{{ $service_repair['serial_no'] }}</td>
                                                    <td>
                                                        {{ $service_repair['removed'] == 1 ? "تم الالغاء" : "تم الاستلام" }}
                                                    </td>
                                                    <td>{{ $service_repair['user']['name'] }}</td>
                                                    <td>{{ $service_repair['created_at']->format("Y-m-d g:i") . ($service_repair['created_at']->format("A") === "AM" ? " صباحًا ": " مساءًا ") }}</td>

                                                </tr>
                                            @endforeach
                                        </tbody>
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
        setTimeout(()=>{
            $("#session_message")?.remove();
        },5000);

        $(document).ready(function () {
            $('#example23').DataTable({
                "scrollX": true,
            });

        })
        function change_month(event) {
            let month = event.currentTarget.value;
            window.location = `${month}`;
        }
    </script>
@endsection
