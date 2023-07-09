@extends("page_layout")
@section("page_content")



    <div class="row">

        <div class="col-12">

            <div class="card">

                <div class="card-header">
                        <button type="button" class="btn btn-info btn-rounded m-t-10 float-right"
                                data-toggle="modal" data-target="#addBrand">
                            إضافة ماركة
                        </button>
                </div>
                @if (session()->has('message'))
                    <div class="bg-primary text-light text-bold py-2 px-5  text-center mx-auto" id="session_message">
                        {{ session()->get("message") }}
                    </div>
                @endif

                <div class="card-body">
                    <h4 class="card-title">الماركات</h4>
                    <h6 class="card-subtitle">عدد الماركات: {{ count($brands) }}</h6>
                    <div class="table-responsive m-t-40">
                        <table id="myTable" class="table table-bordered nowrap table-striped w-100">
                            <thead>
                                <tr class="text-center text-bold">
                                    <td>م</td>
                                    <td>الاسم</td>
                                    <td>الحالة</td>
                                    <td>الصورة</td>
                                    <td>المستخدم</td>
                                    <td>تاريخ</td>
                                    <td>Actions</td>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($brands as $key => $brand)
                                <tr class="text-center">
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $brand['title'] }}</td>
                                    <td>
                                        <span class="text-bold label {{ $brand['status'] == 1 ? "label-danger" : "label-success" }}">
                                            {{ $brand['status'] == 1 ? "إخفاء" : "ظهور" }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($brand['image'])
                                            <img src="{{ asset('images/brands/'.$brand['image']) }}" class="img-responsive img-fluid" width="70">
                                         @endif
                                    </td>
                                    <td>{{ $brand['user']['name'] }}</td>
                                    <td>{{ $brand['updated_at']->format("Y-m-d g:i") . ($brand['updated_at']->format("A") === "AM" ? " صباحًا ": " مساءًا ") }}</td>
                                    <td>
                                        <div class="d-flex justify-content-center">
                                            <div class="col-6">
                                                <button class="border-0" data-toggle="modal" data-target="#editBrand" onclick="edit_brand({{ json_encode($brand) }})">
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


    <!-- Add brand Popup Model -->
    <div id="addBrand" class="modal fade in" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-right" id="myModalLabel">إضافة ماركة جديدة</h4>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal form-material" action="{{ url("/brands") }}"
                          method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <div class="col-md-12 m-b-20">
                                <input type="text" class="form-control" name="title"
                                       placeholder="اسم الماركة" required>
                            </div>
                            <div class="col-md-12 mb-3 mx-auto text-center">
                                <div class="fileupload btn btn-danger btn-rounded waves-effect waves-light">
                                        <span>
                                            <i class="icon-upload m-r-5"></i>ارفاق صورة لو متوفرة
                                        </span>
                                    <input type="file" class="upload" name="image">
                                </div>
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
    <div id="editBrand" class="modal fade in" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-right" id="myModalLabel">تعديل ماركة</h4>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal form-material" id="edit_brand"
                          method="post" enctype="multipart/form-data">
                        @csrf
                        @method("PUT")
                        <div class="form-group">

                            <div class="col-md-12 m-b-20">
                                <input type="text" class="form-control" name="title" id="title"
                                       placeholder="اسم الماركة" required>
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

                            <div class="col-md-12 my-3 mx-auto text-center">
                                <div class="fileupload btn btn-danger btn-rounded waves-effect waves-light">
                                        <span>
                                            <i class="icon-upload m-r-5"></i>تغيير صورة لو متوفرة
                                        </span>
                                    <input type="file" class="upload" name="image">
                                </div>
                            </div>

                            <div class="col-12 m-auto text-center">
                                <img src="" id="old_photo" class="img-fluid" width="150">
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



    <script>
        $(function () {
            $('#myTable').DataTable({
                "scrollX": true,
            });
        });

        setTimeout(()=>{
            $("#session_message")?.remove();
        },5000);

        function edit_brand(brand) {
            $("#title").val(brand.title);
            $("#old_photo").attr("src","/images/brands/"+brand.image);
            $("#edit_brand").attr("action","/brands/"+brand.id);

            if(brand.status == 0){
                $('#show').prop("checked",true);
            }else{
                $('#hide').prop("checked",true);
            }
        }


    </script>
@endsection
