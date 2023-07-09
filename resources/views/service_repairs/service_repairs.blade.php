@extends("page_layout")
@section("page_content")
    <style>
        .cursor-pointer{
            cursor: pointer;
        }
    </style>

    <div class="row">
        <div class="col-12">

            <div class="card">

                @if (session()->has('message'))
                    <div class="bg-primary text-light text-bold py-2 px-5  text-center mx-auto" id="session_message">
                        {{ session()->get("message") }}
                    </div>
                 @endif

                <div class="row">
                    <div class="col-12">

                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title text-info text-bold">صيانة لم يتم التسليم</h4>
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
                                                <th>المستخدم</th>
                                                <th>التاريخ</th>
                                                <th>طباعة</th>
                                                <th>الغاء</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($service_repairs as $key => $service_repair)
                                                <tr>
                                                    <td>{{ $key+1 }}</td>
                                                    <td>{{ $service_repair['client']['name'] }}</td>
                                                    <td>{{ $service_repair['client']['mobile_number'] }}</td>
                                                    <td>{{ $service_repair['brand']['title'] }}</td>
                                                    <td>{{ $service_repair['title'] }}</td>
                                                    <td>{{ $service_repair['problem'] }}</td>
                                                    <td>{{ $service_repair['notes'] }}</td>
                                                    <td>{{ $service_repair['serial_no'] }}</td>
                                                    <td>{{ $service_repair['user']['name'] }}</td>
                                                    <td>{{ $service_repair['updated_at']->format("Y-m-d g:i") . ($service_repair['updated_at']->format("A") === "AM" ? " صباحًا ": " مساءًا ") }}</td>
                                                    <td>
                                                        <a href="/service_repairs/{{ $service_repair['id'] }}" target="_blank" class="btn btn-info">
                                                            <i class="fas fa-print fa-2x text-light"></i>
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <form method="post" action="/service_repairs/{{ $service_repair['id'] }}">
                                                            @csrf
                                                            @method("DELETE")
                                                            <h5 class="btn btn-outline-danger cursor-pointer"
                                                                onclick="event.preventDefault();if (confirm('هل انت متاكد من الالغاء؟')){this.closest('form').submit()}; ">
                                                                الغاء
                                                            </h5>
                                                        </form>
                                                    </td>
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
    </script>
@endsection
