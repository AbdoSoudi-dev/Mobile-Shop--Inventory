@extends("page_layout")
@section("page_content")

    <style>
        .text-bold{
            font-weight: bold;
        }

        input {
            background-color: transparent;
            color: black;
        }
        input:out-of-range {
            background-color: red;
            color: white;
        }
        input:in-range + label::after {
            content: '';
        }

        input:out-of-range + label::after {
            color: red;
            content: 'هذه الكمية غير متوفرة في المخزون';
        }
        .bg-success-gray{
            background-color: #6bd9be;
        }
        .bg-danger{
            background-color:#edb5ba !important;
        }
    </style>

    <div class="row">

        <div class="col-12">

            <div class="card">

                <div class="card-header">
                        <button type="button" class="btn btn-info btn-rounded m-t-10 float-right"
                                data-toggle="modal" data-target="#addLoss">
                            إضافة
                        </button>
                </div>
                @if (session()->has('message'))
                    <div class="bg-primary text-light text-bold py-2 px-5  text-center mx-auto" id="session_message">
                        {{ session()->get("message") }}
                    </div>
                @endif

                <div class="col-12 m-auto text-center">
                    <h4 class="col-12 text-bold">
                        خسارة عن شهر:
                    </h4>
                    <input type="month" class="form-control col-md-6 col-12 btn btn-outline-info" onchange="change_month(event)" value="{{ $month }}">
                </div>

                <div class="card-body">
                    <h4 class="card-title">الخسائر</h4>
                    <h6 class="card-subtitle">عدد: {{ count($losses) }}</h6>
                    <div class="table-responsive m-t-40">
                        <table id="myTable" class="table table-bordered nowrap table-striped w-100">
                            <thead>
                                <tr class="text-center text-bold">
                                    <td>م</td>
                                    <td>القسم</td>
                                    <td>الماركة</td>
                                    <td>المنتج</td>
                                    <td>الكمية</td>
                                    <td>المستخدم</td>
                                    <td>الوقت</td>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($losses as $key => $loss)
                                <tr class="text-center">
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $loss['product']['barcode']['category']['title'] }}</td>
                                    <td>{{ $loss['product']['barcode']['brand']['title'] }}</td>
                                    <td>{{ $loss['product']['barcode']['title'] }}</td>
                                    <td>{{ $loss['quantity'] }}</td>
                                    <td>{{ $loss['user']['name'] }}</td>
                                    <td>{{ $loss['created_at']->format("Y-m-d h:i A") }}</td>
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
    <div id="addLoss" class="modal fade in" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-right" id="myModalLabel">خسائر</h4>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal form-material" action="{{ url("/losses") }}"
                          method="post">
                        @csrf

                        <div class="row bg-gray-primary justify-content-center mb-2">

                            <div class="col-md-6 col-12 mb-3">
                                <div class="form-group row m-auto">
                                    <label class="control-label col-12 text-center mt-1 text-bold">القسم</label>
                                    <div class="col-12 m-auto text-center">
                                        <select class="form-control m-auto text-center category_id new_invoice_input" data-live-search="true" name="category_id" required>
                                            <option value="" class="text-info">إختر القسم</option>
                                            @foreach($categories as $category)
                                                <option name="{{ $category['title'] }}" value="{{ $category['id'] }}">{{ $category['title'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <div class="form-group row m-auto">
                                    <label class="control-label col-12 text-center mt-1 text-bold">بحث</label>
                                    <div class="col-12 m-auto text-center">
                                        <input type="text" class="form-control m-auto text-center search_product new_invoice_input" name="search_product" required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <div class="form-group row m-auto">
                                    <label class="control-label col-12 text-center mt-1 text-bold">المنتج</label>
                                    <div class="col-12 m-auto text-center">
                                        <input type="text" list="search_results" autocomplete="off" class="form-control m-auto text-center product new_invoice_input" name="product" required>
                                        <datalist id="search_results"></datalist>
                                        <input type="hidden" class="form-control m-auto text-center product_id new_invoice_input" name="product_id" required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <div class="form-group row m-auto">
                                    <label class="control-label col-12 text-center mt-1 text-bold">الكمية</label>
                                    <div class="col-12 m-auto text-center">
                                        <input type="number" class="form-control m-auto text-center problem new_invoice_input" min="1" name="quantity" value="1" required>
                                        <label> </label>
                                    </div>
                                </div>
                            </div>


                        </div>

                        <div class="form-group">
                            <div class="col-12 m-auto text-center">
                                <input type="submit" class="btn btn-info col-md-6 col-12" value="حفظ">
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

        $(document).on("keyup",".search_product",function () {
            let search = $.trim($(this).val());

            let row = $(this).parents(":eq(3)");

            row.find("[name='product']").val("").attr("placeholder","");
            row.find("[name='product_id']").val("");

            row.find("[name='quantity']").attr("max","");


            $(this).parents(":eq(3)").removeClass("bg-success-gray").addClass("bg-danger");
            if($(".search_product").is(":focus")) {
                row.find("#search_results").html("");
                let category_id = row.find("select.category_id").val();

                if ((!+search && search.length > 1) ||( (+search) && search.toString().length >= 9)) {

                    row.find("[name='product']").attr("placeholder","جاري البحث..");

                    $.ajax({
                        url: "/search_for_invoice",
                        method: "POST",
                        data: {
                            search: search,
                            category_id: category_id,
                            _token: '{{ csrf_token() }}'
                        },
                        dataType: "JSON",
                        success: function (response) {
                            console.log(response);

                            if(response.length == 1){
                                row.removeClass("bg-danger").addClass("bg-success-gray");
                                row.find("[name='product']").val(response[0].title);
                                row.find("[name='product_id']").val(response[0].products[0].id);
                                row.find("[name='category_id']").val(response[0].category_id).change();
                                row.find("[name='quantity']").attr("max",response[0].products[0]?.quantity);

                            }else if(response.length){
                                row.find("[name='product']").attr("placeholder","تم العثور على نتائج");

                                let options = "";
                                for (let i = 0; i < response.length; i++) {
                                    options += "<option data-data='" + JSON.stringify(response[i]) + "' value='" + response[i].title + (response[i].serial_no ? " - " + response[i].serial_no : "" ) + "'>";
                                }
                                row.find("#search_results").html(options);
                            }else{
                                row.find("[name='product']").attr("placeholder","لا توجد نتائج");
                            }


                            $('.selectpicker').selectpicker();
                        },
                        error: function (err) {
                            console.log(err);
                        }
                    })

                }
            }

        })
        $(document).on('change', '[name="product"]', function(){
            let row = $(this).parents(":eq(3)");
            $(this).parents(":eq(3)").removeClass("bg-success-gray").addClass("bg-danger");

            let optionslist = row.find("#search_results")[0].options;
            row.find('[name="product_id"]').val("")
            row.find("[name='quantity']").attr("max","");

            if(optionslist.length){
                var value = $(this).val();
                for (var x=0;x<optionslist.length;x++){
                    if (optionslist[x].value === value) {
                        //Alert here value


                        $(this).parents(":eq(3)").removeClass("bg-danger").addClass("bg-success-gray");
                        let data = JSON.parse(optionslist[x].getAttribute("data-data"));
                        row.find('[name="product_id"]').val(data.products[0].id)
                        row.find("[name='price']").val(data.products[0]?.selling_price ?? 0);
                        row.find("[name='category_id']").val(data.category_id).change();
                        row.find("[name='price']").focus();
                        row.find("[name='quantity']").attr("max",data.products[0]?.quantity);


                        break;
                    }
                }

            }
        });

        $("form").on("submit",function () {
            $(".preloader").fadeIn();
        })
    </script>
@endsection
