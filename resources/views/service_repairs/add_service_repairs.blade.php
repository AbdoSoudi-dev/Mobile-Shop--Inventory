@extends("page_layout")
@section("page_content")

    <style>
        .bg-gray-primary{
            background-color: #d0ddf1;
            padding: 0 10px;
        }
        .cursor-pointer{
            cursor: pointer;
        }
    </style>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

{{--                    <div class="col-12">--}}
{{--                        <div>{!! DNS1D::getBarcodeHTML('10000', 'C39') !!}</div></br>--}}
{{--                    </div>--}}

                    <h4 class="col-12 text-center bg-info text-bold text-light py-2 mt-2 mb-4">
                        إضافة صيانة
                    </h4>

                    <form action="{{ url("/service_repairs") }}" method="post">
                        @csrf

                        <div class="row bg-gray-primary justify-content-center mb-2 ">

                            <div class="col-md-3 col-6 mb-3">
                                <div class="form-group row m-auto">
                                    <label class="control-label col-12 text-center mt-1 text-bold">اسم العميل</label>
                                    <div class="col-12 m-auto text-center">
                                        <input type="text" class="form-control m-auto text-center client_name" name="client_name" required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 col-6 mb-3">
                                <div class="form-group row m-auto">
                                    <label class="control-label col-12 text-center mt-1 text-bold">رقم العميل *<small>اختياري</small></label>
                                    <div class="col-12 m-auto text-center">
                                        <input type="number" class="form-control m-auto text-center client_number" placeholder="ابحث برقم التليفون" name="client_number">
                                    </div>
                                </div>
                            </div>

                        </div>


                            <div class="row bg-gray-primary justify-content-center mb-2 ">

                                <div class="col-md-3 col-6 mb-3">
                                    <div class="form-group row m-auto">
                                        <label class="control-label col-12 text-center mt-1 text-bold">الماركة</label>
                                        <div class="col-12 m-auto text-center">
                                            <select class="form-control m-auto text-center selectpicker brand_id" data-live-search="true" name="brand_id" required>
                                                <option value="" class="text-info">إختر الماركة</option>
                                                @foreach($brands as $brand)
                                                    <option name="{{ $brand['title'] }}" value="{{ $brand['id'] }}">{{ $brand['title'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 col-6 mb-3">
                                    <div class="form-group row m-auto">
                                        <label class="control-label col-12 text-center mt-1 text-bold">اسم الجهاز</label>
                                        <div class="col-12 m-auto text-center">
                                            <input type="text" class="form-control m-auto text-center title" name="title" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 col-6 mb-3">
                                    <div class="form-group row m-auto">
                                        <label class="control-label col-12 text-center mt-1 text-bold">نوع العطل</label>
                                        <div class="col-12 m-auto text-center">
                                            <input type="text" class="form-control m-auto text-center problem" name="problem" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 col-6 mb-3">
                                    <div class="form-group row m-auto">
                                        <label class="control-label col-12 text-center mt-1 text-bold">رقم السيريال</label>
                                        <div class="col-12 m-auto text-center">
                                            <input type="number" class="form-control m-auto text-center serial_no" name="serial_no">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-6 mb-3">
                                    <div class="form-group row m-auto">
                                        <label class="control-label col-12 text-center mt-1 text-bold">ملاحظات</label>
                                        <div class="col-12 m-auto text-center">
                                            <textarea type="text" class="form-control m-auto text-center notes" name="notes"></textarea>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        <div class="col-12 text-center">
                            <input type="submit" class="btn btn-info text-bold text-2xl py-2 px-3 mt-2" value="حفظ">
                        </div>


                    </form>


                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('.selectpicker').selectpicker();
            $("#inv_form").trigger("reset");

        });
        $('[name="client_number"]').on("keyup",function () {

            $('[name="client_name"]').val("");

            if($(this).val()){
                $.ajax({
                    url: "/search_client",
                    method: "POST",
                    data: {
                        search: $(this).val(),
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: "JSON",
                    success: function (response) {
                        console.log(response);
                        $('[name="client_name"]').val( response.name ?? "" );
                    },
                    error: function (err) {
                        // console.log(err);
                    }
                })
            }

        })

        $("form").on("submit",function () {
            $(".preloader").fadeIn();
        })
    </script>

@endsection
