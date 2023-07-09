@extends("page_layout")
@section("page_content")


    <div class="row">

        <div class="col-12">

            <div class="card">

                <div class="card-header">
                        <button type="button" class="btn btn-info btn-rounded m-t-10 float-right"
                                data-toggle="modal" data-target="#addCategory">
                            إضافة قسم
                        </button>
                </div>
                @if (session()->has('message'))
                    <div class="bg-primary text-light text-bold py-2 px-5  text-center mx-auto" id="session_message">
                        {{ session()->get("message") }}
                    </div>
                @endif

                <div class="card-body">
                    <h4 class="card-title">الأقسام</h4>
                    <h6 class="card-subtitle">عدد الأقسام: {{ count($categories) }}</h6>
                    <div class="table-responsive m-t-40">
                        <table id="myTable" class="table table-bordered table-striped w-100">
                            <thead>
                                <tr class="text-center text-bold">
                                    <td>م</td>
                                    <td>الاسم</td>
                                    <td>الحالة</td>
                                    <td>المستخدم</td>
                                    <td>تاريخ</td>
                                    <td>Actions</td>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($categories as $key => $category)
                                <tr class="text-center">
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $category['title'] }}</td>
                                    <td>
                                        <span class="text-bold label {{ $category['status'] == 1 ? "label-danger" : "label-success" }}">
                                            {{ $category['status'] == 1 ? "إخفاء" : "ظهور" }}
                                        </span>
                                    </td>
                                    <td>{{ $category['user']['name'] }}</td>
                                    <td>{{ $category['updated_at']->format("Y-m-d g:i") . ($category['updated_at']->format("A") === "AM" ? " صباحًا ": " مساءًا ") }}</td>
                                    <td>
                                        <div class="d-flex justify-content-center">
                                            <div class="col-6">
                                                <button class="border-0" data-toggle="modal" data-target="#editCategory" onclick="edit_category({{ json_encode($category) }})">
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
    <div id="addCategory" class="modal fade in" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-right" id="myModalLabel">إضافة قسم جديد</h4>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal form-material" action="{{ url("/categories") }}"
                          method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <div class="col-md-12 m-b-20">
                                <input type="text" class="form-control" name="title"
                                       placeholder="اسم القسم" required>
                            </div>
{{--                            <div class="col-md-12 mb-3 mx-auto text-center">--}}
{{--                                <div class="fileupload btn btn-danger btn-rounded waves-effect waves-light">--}}
{{--                                        <span>--}}
{{--                                            <i class="icon-upload m-r-5"></i>ارفاق صورة لو متوفرة--}}
{{--                                        </span>--}}
{{--                                    <input type="file" class="upload" name="image">--}}
{{--                                </div>--}}
{{--                            </div>--}}
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
    <div id="editCategory" class="modal fade in" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-right" id="myModalLabel">تعديل قسم</h4>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal form-material" id="edit_category"
                          method="post" enctype="multipart/form-data">
                        @csrf
                        @method("PUT")
                        <div class="form-group">

                            <div class="col-md-12 m-b-20">
                                <input type="text" class="form-control" name="title" id="title"
                                       placeholder="اسم القسم" required>
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



    <script>
        $(function () {
            $('#myTable').DataTable({
                "scrollX": true,
            });
        });

        setTimeout(()=>{
            $("#session_message")?.remove();
        },3000);

        function edit_category(category) {
            $("#title").val(category.title);
            $("#edit_category").attr("action","/categories/"+category.id);

            if(category.status == 0){
                $('#show').prop("checked",true);
            }else{
                $('#hide').prop("checked",true);
            }
        }


    </script>
@endsection
