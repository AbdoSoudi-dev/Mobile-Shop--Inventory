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

                <div class="card-header">
                        <button type="button" class="btn btn-info btn-rounded m-t-10 float-right"
                                data-toggle="modal" data-target="#addUser">
                            إضافة مستخدم
                        </button>
                </div>
                @if (session()->has('message'))
                    <div class="bg-primary text-light text-bold py-2 px-5  text-center mx-auto" id="session_message">
                        {{ session()->get("message") }}
                    </div>
                @endif

                <div class="card-body">
                    <h4 class="card-title">المستخدمون</h4>
                    <h6 class="card-subtitle">عدد المستخدمون: {{ count($users) }}</h6>
                    <div class="table-responsive m-t-40">
                        <table id="myTable" class="table table-bordered nowrap table-striped w-100">
                            <thead>
                                <tr class="text-center text-bold">
                                    <td>م</td>
                                    <td>الاسم</td>
                                    <td>الايميل</td>
                                    <td>الصلاحيات</td>
                                    <td>الوقت</td>
                                    <td>Action</td>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $key => $user)
                                <tr class="text-center">
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $user['name'] }}</td>
                                    <td>{{ $user['email'] }}</td>
                                    <td>{{ $user['role_type'] }}</td>

                                    <td>{{ $user['created_at']->format("Y-m-d h:i A") }}</td>

                                    <td>
                                        @if(!in_array($user['id'],[1,5]))
                                        <form method="post" action="/users/{{ $user['id'] }}">
                                            @csrf
                                            @method("PUT")

                                            @if($user['removed'] == 0)
                                                <h5 class="btn btn-outline-danger cursor-pointer"
                                                    onclick="event.preventDefault();this.closest('form').submit(); ">
                                                    حذف
                                                </h5>
                                            @else
                                                <h5 class="btn btn-outline-success cursor-pointer"
                                                    onclick="event.preventDefault();this.closest('form').submit(); ">
                                                    إعادة
                                                </h5>
                                            @endif

                                        </form>
                                        @endif
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
    <div id="addUser" class="modal fade in" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-right" id="myModalLabel">إضافة مستخدم جديد</h4>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal form-material" action="{{ url("/users") }}"
                          method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <div class="col-md-12 m-b-20">
                                <input type="text" class="form-control" name="name"
                                       placeholder="الاسم" required>
                            </div>

                            <div class="col-md-12 m-b-20">
                                <input type="email" class="form-control" name="email"
                                       placeholder="الايميل" required>
                            </div>

                            <div class="col-md-12 m-b-20">
                                <input type="password" class="form-control" name="password"
                                       placeholder="كلمة السر" required>
                            </div>
                            <div class="col-md-12 m-b-20">
                                <input type="password" class="form-control" name="password_confirmation"
                                       placeholder="تأكيد كلمة السر" required>
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




    <script>
        $(function () {
            $('#myTable').DataTable({
                "scrollX": true,
            });
        });

        setTimeout(()=>{
            $("#session_message")?.remove();
        },5000);



    </script>
@endsection
