@extends("page_layout")
@section("page_content")

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/node_modules/datatables.net-bs4/css/dataTables.bootstrap4.css') }}">


    <div class="row">

        <div class="col-12">

            <div class="card">

                <div class="card-header">
                        <button type="button" class="btn btn-info btn-rounded m-t-10 float-right"
                                data-toggle="modal" data-target="#addVendor">
                            إضافة تاجر
                        </button>
                </div>
                @if (session()->has('message'))
                    <div class="bg-primary text-light text-bold py-2 px-5  text-center mx-auto" id="session_message">
                        {{ session()->get("message") }}
                    </div>
                @endif

                <div class="card-body">
                    <h4 class="card-title">التجار</h4>
                    <h6 class="card-subtitle">عدد التجار: {{ count($vendors) }}</h6>
                    <div class="table-responsive m-t-40">
                        <table id="myTable" class="table table-bordered table-striped w-100">
                            <thead>
                                <tr class="text-center text-bold">
                                    <td>م</td>
                                    <td>الاسم</td>
                                    <td>الحالة</td>
                                    <td>رقم التليفون</td>
                                    <td>العنوان</td>
                                    <td>المستخدم</td>
                                    <td>تاريخ</td>
                                    <td>Actions</td>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($vendors as $key => $vendor)
                                <tr class="text-center">
                                    <td>{{ $key+1 }}</td>
                                    <td>
                                        <a href="{{ url("/vendors/".$vendor['id']) }}" class="text-info text-bold">
                                            {{ $vendor['name'] }}
                                        </a>
                                    </td>
                                    <td>
                                        <span class="text-bold label {{ $vendor['status'] == 1 ? "label-danger" : "label-success" }}">
                                            {{ $vendor['status'] == 1 ? "إخفاء" : "ظهور" }}
                                        </span>
                                    </td>
                                    <td>{{ $vendor['mobile_number'] .
                                        ($vendor['mobile_number_sec'] ? " - " . $vendor['mobile_number_sec'] : "") }}</td>
                                    <td>{{ $vendor['address'] }}</td>
                                    <td>{{ $vendor['user']['name'] }}</td>
                                    <td>{{ $vendor['updated_at']->format("Y-m-d g:i") . ($vendor['updated_at']->format("A") === "AM" ? " صباحًا ": " مساءًا ") }}</td>
                                    <td>
                                        <div class="d-flex justify-content-center">
                                            <div class="col-6">
                                                <button class="border-0" data-toggle="modal" data-target="#editVendor" onclick="edit_vendor({{ json_encode($vendor) }})">
                                                    <i class="fas fa-edit fa-2x text-primary cursor-pointer"></i>
                                                </button>
                                            </div>
{{--                                            <div class="col-6">--}}
{{--                                                <form action="{{ url("brands/".$brand['id']) }}" method="post">--}}
{{--                                                    @csrf--}}
{{--                                                    @method("DELETE")--}}
{{--                                                    <button type="submit" class="border-0">--}}
{{--                                                        <i class="fas fa-trash-alt fa-2x text-danger cursor-pointer"></i>--}}
{{--                                                    </button>--}}
{{--                                                </form>--}}
{{--                                            </div>--}}
                                        </div>
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


    <!-- Add vendor Popup Model -->
    <div id="addVendor" class="modal fade in" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-right" id="myModalLabel">إضافة تاجر جديد</h4>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal form-material" action="{{ url("/vendors") }}"
                          method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <div class="col-md-12 m-b-20">
                                <input type="text" class="form-control" name="name"
                                       placeholder="اسم التاجر" required>
                            </div>
                            <div class="col-md-12 m-b-20">
                                <input type="number" class="form-control" name="mobile_number"
                                       placeholder="رقم التليفون" required>
                            </div>
                            <div class="col-md-12 m-b-20">
                                <input type="number" class="form-control" name="mobile_number_sec"
                                       placeholder="رقم التليفون آخر" >
                            </div>
                            <div class="col-md-12 m-b-20">
                                <input type="text" class="form-control" name="address"
                                       placeholder="عنوان التاجر" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-12">
                                <input type="submit" class="btn btn-info col-md-3 col-6" value="حفظ">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Cancel</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>


    <!-- edit brand Popup Model -->
    <div id="editVendor" class="modal fade in" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-right" id="myModalLabel">تعديل التاجر</h4>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal form-material" id="edit_vendor"
                          method="post" enctype="multipart/form-data">
                        @csrf
                        @method("PUT")
                        <div class="form-group">

                            <div class="col-md-12 m-b-20">
                                <input type="text" class="form-control" name="name" id="name"
                                       placeholder="اسم التاجر" required>
                            </div>
                            <div class="col-md-12 m-b-20">
                                <input type="number" class="form-control" name="mobile_number" id="mobile_number"
                                       placeholder="رقم التليفون" required>
                            </div>
                            <div class="col-md-12 m-b-20">
                                <input type="number" class="form-control" name="mobile_number_sec" id="mobile_number_sec"
                                       placeholder="رقم التليفون آخر" >
                            </div>
                            <div class="col-md-12 m-b-20">
                                <input type="text" class="form-control" name="address" id="address"
                                       placeholder="عنوان التاجر" required>
                            </div>

                            <label for="status" class="col-12 text-bold m-auto text-center">
                                <h4>
                                    اخفاء/ظهور
                                </h4>
                            </label>
                            <div class="m-b-30">
                                <div class="row d-flex justify-content-center">

                                    <label for="show" class="col-1 pt-2 text-bold">ظهور</label>
                                    <input type="radio" class="form-control col-1" value="0" name="status" id="show">

                                    <div class="col-2"></div>

                                    <label for="hide" class="col-1 pt-2 text-bold">اخفاء</label>
                                    <input type="radio" class="form-control col-1" value="1" name="status" id="hide">

                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-12 text-center ">
                                <input type="submit" class="btn btn-info col-md-4 col-6" value="تعديل">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Cancel</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>



    <script src="{{ asset('assets/node_modules/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script>
        $(function () {
            $('#myTable').DataTable({
                "scrollX": true,
            });
        });

        setTimeout(()=>{
            $("#session_message")?.remove();
        },3000);

        function edit_vendor(vendor) {
            $("#name").val(vendor.name);
            $("#address").val(vendor.address);
            $("#mobile_number").val(vendor.mobile_number);
            $("#mobile_number_sec").val(vendor.mobile_number_sec);

            $("#edit_vendor").attr("action","/vendors/"+vendor.id);

            if(vendor.status == 0){
                $('#show').prop("checked",true);
            }else{
                $('#hide').prop("checked",true);
            }
        }


    </script>
@endsection
