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
        .text-bold{
            font-weight: bold !important;
        }

        .bg-success-gray{
            background-color: #6bd9be;
        }
        .bg-danger{
            background-color:#edb5ba !important;
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
        .search-input{
            top:2%;
            left: 6%;
            z-index: 10;
            top: 13%;
            left: 6%;
            z-index: 10;
            cursor: pointer;
            transform: rotate(89deg);
        }
    </style>

    <div class="col-12">
        <div class="d-flex justify-content-center text-center">
            <h3 class="cursor-pointer invoice_btn col-3 btn btn-primary text-bold text-center mt-2 p-2">فاتورة جديدة</h3>
            <h3 class="cursor-pointer invoice_btn col-3 btn btn-info text-bold text-center mx-2 mt-2 p-2">مرتجع</h3>
            <h3 class="cursor-pointer invoice_btn col-3 btn btn-info text-bold text-center mx-2 mt-2 p-2">صرف</h3>
            <h3 class="cursor-pointer invoice_btn col-3 btn btn-info text-bold text-center mt-2 p-2">استلام</h3>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    {{--                    <div class="col-12">--}}
                    {{--                        <div>{!! DNS1D::getBarcodeHTML('10000', 'C39') !!}</div></br>--}}
                    {{--                    </div>--}}

                    <h4 class="col-12 text-center bg-info text-bold text-light py-2 mt-2 mb-4" id="title">
                        فاتورة جديدة
                    </h4>

                    @can("isEditor")
                        <div class="col-md-3 col-6 mx-auto mb-3 oper_date">
                            <div class="form-group row m-auto">
                                <label class="control-label col-12 text-center mt-1 text-bold">تاريخ العملية</label>
                                <div class="col-12 m-auto text-center">
                                    <input type="date" class="form-control m-auto text-center oper_date" name="oper_date">
                                </div>
                            </div>
                        </div>
                    @endcan

                    <form id="formId" action="{{ url("/financial_activities") }}" method="post" translate="no">
                        @csrf



                        <div class="new_invoice row bg-gray-primary justify-content-center mb-2 row_array">

                            <div class="col-md-3 col-6 mb-3">
                                <div class="form-group row m-auto">
                                    <label class="control-label col-12 text-center mt-1 text-bold">اسم العميل</label>
                                    <div class="col-12 m-auto text-center">
                                        <input type="text" class="form-control m-auto text-center client_name new_invoice_input" name="client_name" required>
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

                        <div class="new_invoice row bg-gray-primary justify-content-center mb-2 row_array">

                            <div class="col-md-3 col-6 mb-3">
                                <div class="form-group row m-auto">
                                    <label class="control-label col-12 text-center mt-1 text-bold">القسم</label>
                                    <div class="col-12 m-auto text-center">
                                        <select class="form-control m-auto text-center selectpicker category_id new_invoice_input" data-live-search="true" name="category_id" required>
                                            <option value="" class="text-info">إختر القسم</option>
                                            @foreach($categories as $category)
                                                <option name="{{ $category['title'] }}" value="{{ $category['id'] }}">{{ $category['title'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 col-6 mb-3">
                                <div class="form-group row m-auto">
                                    <label class="control-label col-12 text-center mt-1 text-bold">بحث</label>
                                    <div class="col-12 m-auto text-center">
                                        <input type="text" class="form-control m-auto text-center search_product new_invoice_input" name="search_product" required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 col-6 mb-3">
                                <div class="form-group row m-auto">
                                    <label class="control-label col-12 text-center mt-1 text-bold">المنتج</label>
                                    <div class="col-12 m-auto text-center">
                                        <input type="text" list="search_results" autocomplete="off" class="form-control m-auto text-center product new_invoice_input" name="product" required>
                                        <datalist id="search_results"></datalist>
                                        <input type="hidden" class="form-control m-auto text-center product_id new_invoice_input" name="product_id" required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 col-6 mb-3">
                                <div class="form-group row m-auto">
                                    <label class="control-label col-12 text-center mt-1 text-bold">الكمية</label>
                                    <div class="col-12 m-auto text-center">
                                        <input type="number" class="form-control m-auto text-center problem new_invoice_input" min="1" name="quantity" value="1" required>
                                        <label> </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 col-6 mb-3">
                                <div class="form-group row m-auto">
                                    <label class="control-label col-12 text-center mt-1 text-bold">السعر</label>
                                    <div class="col-12 m-auto text-center">
                                        <input type="number" class="form-control m-auto text-center price new_invoice_input" step="0.01" name="price" required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 col-6 mb-3">
                                <div class="form-group row m-auto">
                                    <label class="control-label col-12 text-center mt-1 text-bold">الضمان بالايام</label>
                                    <div class="col-12 m-auto text-center">
                                        <input type="number" class="form-control m-auto text-center warranty_period new_invoice_input" name="warranty_period">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 col-6 mb-3">
                                <div class="form-group row m-auto">
                                    <div class="col-12 m-auto text-center">
                                        <i class="fas fa-trash-alt text-danger fa-2x cursor-pointer mt-4 delete_row"></i>
                                    </div>
                                </div>
                            </div>


                        </div>

                        <div class="row reverse_invoice mb-2 bg-gray-primary justify-content-center">
                            <div class="col-md-3 col-6 mb-3">
                                <div class="form-group row m-auto">
                                    <label class="control-label col-12 text-center mt-1 text-bold">رقم الفاتورة</label>
                                    <div class="col-12 m-auto text-center position-relative">
                                        <input type="number" class="form-control m-auto text-center invoice_no" name="invoice_no">
                                        <i class="fas fa-search text-info fa-2x position-absolute search-input"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">

                                <div class="row mb-2 bg-gray-primary justify-content-center reverse_invoice_data">

                                </div>


                            </div>

                        </div>

                        <div class="row expenses mb-2 bg-gray-primary justify-content-center">
                            <div class="col-md-6 col-12 mb-3">

                                <div class="form-group row m-auto">
                                    <label class="control-label col-12 text-center mt-1 text-bold">المصروف</label>
                                    <div class="col-12 m-auto text-center">
                                        <select name="expense_type" id="" class="form-control selectpicker exp_input" data-live-search="true">
                                            <option value="">اختر المصروف</option>
                                            @foreach($expenses as $expense)
                                                <option type="{{ $expense['type'] }}" value="{{ $expense['id'] }}">{{ $expense['name'] }}  {{ ($expense['type'] === "حساب جاري" && $expense['id'] != 3 && $expense['id'] != 1 ? " - " . $expense['type'] : "") }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="row revenues mb-2 bg-gray-primary justify-content-center">

                            <div class="col-md-6 col-12 mb-3">

                                <div class="form-group row m-auto">
                                    <label class="control-label col-12 text-center mt-1 text-bold">نوع الايراد</label>
                                    <div class="col-12 m-auto text-center">
                                        <select name="rev_type" id="" class="form-control selectpicker rev_input" data-live-search="true">
                                            <option value="">اختر الايراد</option>
                                            @foreach($revenues as $revenue)
                                                <option type="{{ $revenue['type'] }}" value="{{ $revenue['id'] }}">{{ $revenue['name'] }} {{ ($revenue['type'] === "حساب جاري" && $revenue['id'] != 3 && $revenue['id'] != 1 ? " - " . $revenue['type'] : "") }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row mt-2 expenses_revenues mb-2 bg-gray-primary justify-content-center">

                            <div class="col-12 mb-3 current_type_expense m-auto">
                                <div class="col-8 m-auto">

                                    <h5 class="text-center text-bold"> نوع النقدية </h5>

                                    <div class="row d-flex justify-content-center mb-2">

                                        <label for="show" class="col-1 pt-2 text-bold">نقدي</label>
                                        <input type="radio" class="form-control col-1" value="0" checked name="status" id="show">

                                        <div class="col-2"></div>

                                        <label for="hide" class="col-1 pt-2 text-bold">آجل</label>
                                        <input type="radio" class="form-control col-1" value="1" name="status" id="hide">

                                    </div>
                                </div>

                            </div>

                            <div class="col-12 mb-3 current_type_expense m-auto">
                                <div class="col-10 m-auto cash_current_type">

                                    <h5 class="text-center text-bold"> نوع العملية </h5>

                                    <div class="row d-flex justify-content-center mb-2">

                                        {{--                                        <label for="cash" class="col-1 pt-2 text-bold">نقدي</label>--}}
                                        {{--                                        <input type="radio" class="form-control col-1" checked value="نقدي" name="current_type" id="cash" >--}}

                                        {{--                                        <div class="col-1"></div>--}}

                                        <label for="balance_damen" class="col-1 pt-2 text-bold">رصيد</label>
                                        <input type="radio" class="form-control col-1" value="رصيد" name="current_type" id="balance_damen">

                                        <div class="col-2"></div>

                                        <label for="products" class="col-1 pt-2 text-bold">مخزون</label>
                                        <input type="radio" class="form-control col-1" value="مخزون" name="current_type" id="products" >

                                    </div>
                                </div>

                            </div>

                            <div class="col-12 inv_current">

                            </div>

                            <div class="col-md-6 col-12 mb-3">

                                <div class="form-group row m-auto">
                                    <label class="control-label col-12 text-center mt-1 text-bold">ملاحظات **هامة</label>
                                    <div class="col-12 m-auto text-center">
                                        <textarea name="notes_exp_rev" class="form-control exp_rev_input"></textarea>
                                    </div>
                                </div>

                            </div>

                        </div>

                        <div class="row expenses_vendors mb-2 bg-gray-primary justify-content-center" style="display: none">

                            <div class="col-md-6 col-12 mb-3">

                                <div class="form-group row m-auto">
                                    <label class="control-label col-12 text-center mt-1 text-bold">التاجر</label>
                                    <div class="col-12 m-auto text-center">
                                        <select name="vendor_id" class="form-control selectpicker" data-live-search="true">
                                            <option value="">اختر التاجر</option>
                                            @foreach($vendors as $vendor)
                                                <option value="{{ $vendor['id'] }}">{{ $vendor['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row expenses_revenues mb-2 bg-gray-primary justify-content-center">
                            <div class="col-md-6 col-12 mb-3">

                                <div class="form-group row m-auto">
                                    <label class="control-label col-12 text-center mt-1 text-bold">المبلغ</label>
                                    <div class="col-12 m-auto text-center">
                                        <input type="number" step="0.01" name="amount_exp_rev" class="form-control exp_rev_input">
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="col-12 text-center submit_btn">
                            <input type="submit" class="btn btn-outline-info text-bold text-2xl py-2 px-3 mt-2 col-md-3 col-6" value="حفظ الفاتورة">
                        </div>

                        <div class="new_invoice row">
                            <div class="col-12">
                                <div class="float-left">
                                    <i id="add_financial" class="fas fa-plus cursor-pointer text-light bg-success fa-3x mb-2 p-1"></i>
                                </div>
                            </div>
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
            $(".reverse_invoice").css("display","none");
            $(".expenses").css("display","none");
            $(".revenues").css("display","none");
            $(".expenses_revenues").css("display","none");
        });

        $(document).on("click",".delete_row",function () {
            $(this).parents().eq(3).remove();
            sum_prices();
        })

        $("#add_financial").on("click",function () {

            let num = $(".inv_current").children().length + $(".row_array").length;

            let row = '' +
                '<div class="new_invoice row bg-gray-primary justify-content-center mb-2 row_array">'+

                ' <div class="col-md-3 col-6 mb-3">'+
                '     <div class="form-group row m-auto">'+
                '         <label class="control-label col-12 text-center mt-1 text-bold">القسم</label>'+
                '         <div class="col-12 m-auto text-center">'+
                '             <select class="form-control m-auto text-center selectpicker category_id new_invoice_input" data-live-search="true" name="category_id" required>'+
                '                 <option value="" class="text-info">إختر القسم</option>'+
                '                 @foreach($categories as $category)'+
                '                    <option name="{{ $category['title'] }}" value="{{ $category['id'] }}">{{ $category['title'] }}</option>'+
                '                 @endforeach'+
                '             </select>'+
                '         </div>'+
                '     </div>'+
                ' </div>'+

                ' <div class="col-md-3 col-6 mb-3">'+
                '     <div class="form-group row m-auto">'+
                '         <label class="control-label col-12 text-center mt-1 text-bold">بحث</label>'+
                '         <div class="col-12 m-auto text-center">'+
                '             <input type="text" class="form-control m-auto text-center search_product new_invoice_input" name="search_product" required>'+
                '         </div>'+
                '     </div>'+
                ' </div>'+

                ' <div class="col-md-3 col-6 mb-3">'+
                '     <div class="form-group row m-auto">'+
                '         <label class="control-label col-12 text-center mt-1 text-bold">المنتج</label>'+
                '         <div class="col-12 m-auto text-center">'+
                '             <input type="text" list="search_results'+num+'" autocomplete="off" class="form-control m-auto text-center product new_invoice_input" name="product" required>'+
                '             <datalist id="search_results'+num+'"></datalist>'+
                '             <input type="hidden" class="form-control m-auto text-center product_id new_invoice_input" name="product_id" required>'+
                '         </div>'+
                '     </div>'+
                ' </div>'+

                ' <div class="col-md-3 col-6 mb-3">'+
                ' <div class="form-group row m-auto">'+
                ' <label class="control-label col-12 text-center mt-1 text-bold">الكمية</label>'+
                ' <div class="col-12 m-auto text-center">'+
                ' <input type="number" class="form-control m-auto text-center problem new_invoice_input" min="1" name="quantity" value="1" required>'+
                '<label> </label>'+
                '</div>'+
                '</div>'+
                '</div>'+

                '<div class="col-md-3 col-6 mb-3">'+
                ' <div class="form-group row m-auto">'+
                '     <label class="control-label col-12 text-center mt-1 text-bold">السعر</label>'+
                '     <div class="col-12 m-auto text-center">'+
                '         <input type="number" class="form-control m-auto text-center price new_invoice_input" step="0.01" name="price" required>'+
                '     </div>'+
                ' </div>'+
                '</div>'+

                '<div class="col-md-3 col-6 mb-3">'+
                ' <div class="form-group row m-auto">'+
                '     <label class="control-label col-12 text-center mt-1 text-bold">الضمان بالايام</label>'+
                '     <div class="col-12 m-auto text-center">'+
                '         <input type="number" class="form-control m-auto text-center warranty_period  new_invoice_input" name="warranty_period">'+
                '     </div>'+
                ' </div>'+
                '</div>'+

                ' <div class="col-md-3 col-6 mb-3">'+
                '     <div class="form-group row m-auto">'+
                '          <div class="col-12 m-auto text-center">'+
                '              <i class="fas fa-trash-alt text-danger fa-2x cursor-pointer mt-4 delete_row"></i>'+
                '          </div>'+
                '     </div>'+
                ' </div>'+

                '</div>';
            $(".submit_btn").before(row);
            $('.selectpicker').selectpicker();
        })

        $(".invoice_btn").on("click",function () {
            $(".invoice_btn").removeClass("btn-primary").addClass("btn-info");
            $(this).removeClass("btn-info").addClass("btn-primary");
            let type = $.trim($(this).text());
            $("#title").text(type);
            $(".expenses_vendors").css("display","none");
            $(".new_invoice").css("display","none");
            $(".reverse_invoice").css("display","none");
            $(".expenses").css("display","none");
            $(".revenues").css("display","none");
            $(".expenses_revenues").css("display","none");
            $(".new_invoice_input").prop("required",false);
            $(".exp_rev_input").prop("required",false);
            $(".rev_input").prop("required",false);
            $(".exp_input").prop("required",false);
            $('[name="expense_type"]').val("").change();
            $('[name="rev_type"]').val("").change();
            // $(".current_type").css("display","none")
            $(".current_type_expense").css("display","none")
            $(".inv_current").html("");
            $('#balance_damen').prop("checked",false)
            $('#products').prop("checked",false)
            $('#show').prop("checked",true)
            $(".cash_current_type").css("display","none");
            $('[name="current_type"]').prop("checked",false);
            $(".submit_btn").css("display","block");

            $(".oper_date").css("display","block");

            $(".price_sum").css("display","none");
            $('[name="amount_exp_rev"]').val("").prop("readonly",false);

            if(type === "فاتورة جديدة"){
                $(".new_invoice").css("display","flex");
                $(".new_invoice_input").prop("required",true);
                $(".price_sum").css("display","block");
                $(".submit_btn input").val("حفظ الفاتورة").removeClass("btn-outline-danger").addClass("btn-outline-info")
            }else if(type === "مرتجع"){
                $(".oper_date").css("display","none");
                $(".reverse_invoice").css("display","flex");
                $(".submit_btn").css("display","none");
                $(".submit_btn input").val("الغاء").removeClass("btn-outline-info").addClass("btn-outline-danger")
            }else if(type === "صرف"){

                $(".submit_btn input").val("صرف").removeClass("btn-outline-danger").addClass("btn-outline-info")

                $(".expenses").css("display","flex");
                $(".expenses_revenues").css("display","flex");
                $(".exp_rev_input").prop("required",true);
                $(".exp_input").prop("required",true);
            }else if(type === "استلام"){

                $(".submit_btn input").val("استلام").removeClass("btn-outline-danger").addClass("btn-outline-info")

                $(".revenues").css("display","flex");
                $(".expenses_revenues").css("display","flex");
                $(".exp_rev_input").prop("required",true);
                $(".rev_input").prop("required",true);
            }
            $('.selectpicker').selectpicker();
        })

        $(".invoice_no").on("keyup",function () {
            $(".reverse_invoice_data").html("");
            $(".submit_btn").css("display","none");
        })

        $(".search-input").on("click",function () {
            let invoice_no = $(".invoice_no").val();
            if (invoice_no) {
                $(".reverse_invoice_data").html("جاري البحث");
                $.ajax({
                    url: "/old_invoice",
                    method: "POST",
                    data: {
                        id: invoice_no,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: "JSON",
                    success: function (response) {
                        console.log(response);

                        let res_html = "";

                        if (response.sales_invoices.length){
                            if (response.client_id != 1 && response.client_id){
                                res_html += '<div class="form-group col-6 text-center" >'+
                                    '              <label class="col-12 text-bold" > اسم العميل </label>'+
                                    '              <input type="text" value="'+response.client.name+'" class="form-control" readonly >'+
                                    '        </div>';
                                res_html += '<div class="form-group col-6 text-center" >'+
                                    '              <label class="col-12 text-bold" > رقم العميل </label>'+
                                    '              <input type="text" value="'+response.client.mobile_number+'" class="form-control" readonly >'+
                                    '        </div>';
                            }
                            for (let i = 0; i < response.sales_invoices.length; i++) {
                                const product = response.sales_invoices[i].product;

                                res_html += '<div class="form-group col-6 text-center" >'+
                                    '              <label class="col-12 text-bold" > المنتج </label>'+
                                    '              <input type="text" value="'+( product.barcode.brand.title + " - " + product.barcode.title ) +'" class="form-control" readonly >'+
                                    '        </div>';
                                res_html += '<div class="form-group col-3 text-center" >'+
                                    '              <label class="col-12 text-bold" > الكمية </label>'+
                                    '              <input type="text" value="'+response.sales_invoices[i].quantity+'" class="form-control" readonly >'+
                                    '        </div>';
                                res_html += '<div class="form-group col-3 text-center" >'+
                                    '              <label class="col-12 text-bold" > السعر للوحدة </label>'+
                                    '              <input type="text" value="'+response.sales_invoices[i].price+'" class="form-control" readonly >'+
                                    '        </div>';
                            }

                            $(".reverse_invoice_data").html(res_html);
                            $(".submit_btn").css("display","block");
                        }
                        if (response.financial_accounts_type_id){

                            res_html += '<div class="form-group col-6 text-center" >'+
                                '              <label class="col-12 text-bold" > اسم الحساب </label>'+
                                '              <input type="text" value="'+response.financial_accounts_type.name+'" class="form-control" readonly >'+
                                '        </div>';
                            res_html += '<div class="form-group col-6 text-center" >'+
                                '              <label class="col-12 text-bold" > ملاحظات </label>'+
                                '              <textarea type="text" value="'+response.notes+'" class="form-control" readonly ></textarea>'+
                                '        </div>';
                            res_html += '<div class="form-group col-6 text-center" >'+
                                '              <label class="col-12 text-bold" > النوع </label>'+
                                '              <input type="text" value="'+(Math.floor(response.debit) != 0 ? "استلام" : "صرف")+'" class="form-control" readonly >'+
                                '        </div>';
                            res_html += '<div class="form-group col-6 text-center" >'+
                                '              <label class="col-12 text-bold" > المبلغ </label>'+
                                '              <input type="text" value="'+(Math.floor(response.debit) != 0 ? response.debit : response.credit) +'" class="form-control" readonly >'+
                                '        </div>';
                            if (Math.floor(response.unpaid_debit) != 0){
                                res_html += '<div class="form-group col-6 text-center" >'+
                                    '              <label class="col-12 text-bold" > مبلغ مؤجل </label>'+
                                    '              <input type="text" value="'+response.unpaid_debit +'" class="form-control" readonly >'+
                                    '        </div>';
                            }

                            $(".reverse_invoice_data").html(res_html);
                            $(".submit_btn").css("display","block");
                        }
                        // res_html += "</div>";

                        $('.selectpicker').selectpicker();
                    },
                    error: function (err) {
                        // console.log(err);
                        $(".reverse_invoice_data").html("لا توجد نتائج");
                    }
                })

            }
        })

        $(document).on("keyup",".search_product",function () {
            let search = $.trim($(this).val());

            let row = $(this).parents(":eq(3)");

            row.find("[name='product']").val("").attr("placeholder","");
            row.find("[name='price']").val("");
            row.find("[name='product_id']").val("");
            row.find("[name='warranty_period']").val("");

            row.find("[name='quantity']").attr("max","");

            sum_prices();

            $(this).parents(":eq(3)").removeClass("bg-success-gray").addClass("bg-danger");
            if($(".search_product").is(":focus")) {
                row.find("datalist").html("");
                let category_id = row.find("select.category_id").val();

                if ((!+search && search.length > 1) ||( (+search) && search.toString().length >= 5)) {

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
                                row.find("[name='product']").val(response[0].brand.title + " - " + response[0].title);
                                row.find("[name='product_id']").val(response[0].products[0].id);
                                row.find("[name='price']").val(response[0].products[0]?.selling_price ?? 0);
                                row.find("[name='category_id']").val(response[0].category_id).change();
                                row.find("[name='price']").focus();
                                row.find("[name='quantity']").attr("max",response[0].products[0]?.quantity);

                                sum_prices()
                            }else if(response.length){
                                row.find("[name='product']").attr("placeholder","تم العثور على نتائج");

                                let options = "";
                                for (let i = 0; i < response.length; i++) {
                                    options += "<option data-data='" + JSON.stringify(response[i]) + "' value='" + response[0].brand.title + " - " + response[i].title + (response[i].serial_no ? " - " + response[i].serial_no : "" ) + "'>";
                                }
                                row.find("datalist").html(options);
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

            let optionslist = row.find("datalist")[0].options;
            row.find('[name="product_id"]').val("")
            row.find("[name='price']").val("");
            row.find("[name='quantity']").attr("max","");
            row.find("[name='warranty_period']").val("");

            sum_prices();

            let category_id = row.find("select.category_id").val();
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

                        sum_prices();

                        break;
                    }
                }

            }
        });

        $(document).on("change",".category_id",function () {
            $(this).parents(":eq(3)").find('[name="search_product"]').focus();



            // let category_values_serial = ["1","2","3","16"];
            //
            // if ( category_values_serial.includes($(this).val())  ){
            //     console.log($(this).parents(":eq(4)").find('[name="quantity"]'));
            //     $(this).parents(":eq(4)").find('[name="quantity"]').attr("type","text").val("1").prop("readonly",true);
            // }else{
            //     $(this).parents(":eq(4)").find('[name="quantity"]').attr("type","number").prop("readonly",false);
            // }


            if ($(this).val() == 3){
                if (!$(this).parents(":eq(4)").find('.service_center').length){
                    $(this).parents(":eq(3)").after(
                        '<div class="col-md-3 col-6 mb-3 service_center">' +
                        '     <div class="form-group row m-auto">' +
                        '         <label class="control-label col-12 text-center mt-1 text-bold">نوع جهاز</label>' +
                        '         <div class="col-12 m-auto text-center">' +
                        '             <select class="form-control m-auto text-center selectpicker service_repair_id new_invoice_input" data-live-search="true" name="service_repair_id" required>' +
                        '                 <option value="" class="text-info">إختر</option>' +
                        '                 @foreach($service_center_repair as $center_repair)' +
                        '                     <option name="{{ $center_repair['title'] }}" value="{{ $center_repair['id'] }}">   {{ $center_repair['client']['name'] }} -  {{ $center_repair['title'] }} - {{ $center_repair['problem'] }} </option>' +
                        '                 @endforeach' +
                        '             </select>' +
                        '         </div>' +
                        '     </div>' +
                        ' </div>'
                    )
                }
            }else{
                $(this).parents(":eq(4)").children(".service_center")?.remove();



            }
            $('.selectpicker').selectpicker();



        })

        $(document).ready(function() {
            $(window).keydown(function(event){
                if(event.keyCode == 13) {
                    event.preventDefault();
                    return false;
                }
            });
        });

        $('#formId').on('keyup keypress', function(e) {
            var keyCode = e.keyCode || e.which;
            if (keyCode === 13) {
                e.preventDefault();
                return false;
            }
        });

        $("#formId").on("submit",function (e) {
            e.preventDefault();
            let type = $.trim($("#title").text());

            let data = {};
            data.type = type;
            data._token = "{{ csrf_token() }}";

            let row_array = [];

            if(type === "فاتورة جديدة"){

                let client_name = $('[name="client_name"]').val();
                let client_number = $('[name="client_number"]').val();



                $(".row_array").each(function (index,data) {
                    if(data.querySelector("[name='category_id']")){
                        let category_id =  data.querySelector("[name='category_id']").value
                        row_array.push({
                            "category_id" : category_id,
                            "product_id" :  data.querySelector("[name='product_id']").value ,
                            "service_repair_id" : (category_id == 3 ? data.querySelector("[name='service_repair_id']").value : ""),
                            "price" : data.querySelector("[name='price']").value,
                            "quantity" : data.querySelector("[name='quantity']").value,
                            "warranty_period" : data.querySelector("[name='warranty_period']").value,
                        });
                    }

                })
                data.client_number = client_number;
                data.client_name = client_name;
                data.array = row_array;
            }
            else if (type === "صرف" || type === "استلام"){
                data.financial_accounts_type_id = type === "استلام" ? $('[name="rev_type"]').val() : $('[name="expense_type"]').val();
                data.notes = $('[name="notes_exp_rev"]').val();
                data.amount = $('[name="amount_exp_rev"]').val();
                data.cash_type = $('[name="status"]:checked').val();
                data.current_type = $('[name="current_type"]:checked').val();

                if ( type === "صرف" && $('[name="current_type"]:checked').val() == "مخزون" ){

                    $(".row_array_current").each(function (index,data) {
                        if(data.querySelector("[name='category_id']")){
                            let category_id =  data.querySelector("[name='category_id']").value
                            row_array.push({
                                "category_id" : category_id,
                                "product_id" :  data.querySelector("[name='product_id']").value ,
                                "service_repair_id" : (category_id == 3 ? data.querySelector("[name='service_repair_id']").value : ""),
                                "price" : data.querySelector("[name='price']").value,
                                "quantity" : data.querySelector("[name='quantity']").value,
                                "warranty_period" : data.querySelector("[name='warranty_period']").value,
                            });
                        }

                    })

                    data.array = row_array;
                }

                if ( data.financial_accounts_type_id == 15 ){
                    data.vendor_id = $('[name="vendor_id"]').val();
                }
            }

            data.oper_date = $('[name="oper_date"]').val();

            $(".submit_btn").remove();
            $(".preloader").fadeIn();

            $.ajax({
                url: (type === "مرتجع" ? "/financial_activities/" + $(".invoice_no").val() : "/financial_activities"),
                method : ( type === "مرتجع" ? "PUT" : "POST"),
                data: data,
                dataType: "JSON",
                success: function (response) {
                    // console.log(response);
                    location.reload()
                },
                error: function (err) {
                    // console.log(err);
                    location.reload()
                }
            })

        })

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

        $('[name="expense_type"]').on("change",function () {

            $(".inv_current").html("");
            $('[name="current_type"]').prop("checked",false);
            $(".cash_current_type").css("display","none");
            $('[name="current_type"]').prop("required",false);

            $('[name="amount_exp_rev"]').val("").prop("readonly",false);

            let current = $('option:selected', this).attr('type');
            $('[name="current_type"]').prop("required",false);
            $('#show').prop("checked",true)
            // $(".current_type").css("display","none")
            $(".current_type_expense").css("display","none")
            $(".expenses_vendors").css("display","none");

            if(current ===  "حساب جاري" && $(this).val() != 1 && $(this).val() != 3 ){
                // $(".current_type").css("display","block")
                $(".current_type_expense").css("display","block")
            }
            else if ( $(this).val() == 15 ){
                $(".expenses_vendors").css("display","flex");
                $(".current_type_expense").css("display","block")
            }
            $('.selectpicker').selectpicker();
        })

        // $('[name="rev_type"]').on("change",function () {
        //     let current = $('option:selected', this).attr('type');
        //
        //     $(".current_type").css("display","none")
        //
        //     if(current === "حساب جاري"){
        //         $(".current_type").css("display","block")
        //     }
        //
        //
        // })

        $('[name="current_type"]').on("change",function () {
            if ( $(this).val() === "مخزون" ){
                let num = $(".inv_current").children().length + $(".row_array").length;
                let row = '' +
                    '<div class="row bg-gray-primary justify-content-center mb-2 row_array_current">'+

                    ' <div class="col-md-3 col-6 mb-3">'+
                    '     <div class="form-group row m-auto">'+
                    '         <label class="control-label col-12 text-center mt-1 text-bold">القسم</label>'+
                    '         <div class="col-12 m-auto text-center">'+
                    '             <select class="form-control m-auto text-center selectpicker category_id new_invoice_input" data-live-search="true" name="category_id" required>'+
                    '                 <option value="" class="text-info">إختر القسم</option>'+
                    '                 @foreach($categories as $category)'+
                    '                    <option name="{{ $category['title'] }}" value="{{ $category['id'] }}">{{ $category['title'] }}</option>'+
                    '                 @endforeach'+
                    '             </select>'+
                    '         </div>'+
                    '     </div>'+
                    ' </div>'+

                    ' <div class="col-md-3 col-6 mb-3">'+
                    '     <div class="form-group row m-auto">'+
                    '         <label class="control-label col-12 text-center mt-1 text-bold">بحث</label>'+
                    '         <div class="col-12 m-auto text-center">'+
                    '             <input type="text" class="form-control m-auto text-center search_product new_invoice_input" name="search_product" required>'+
                    '         </div>'+
                    '     </div>'+
                    ' </div>'+

                    ' <div class="col-md-3 col-6 mb-3">'+
                    '     <div class="form-group row m-auto">'+
                    '         <label class="control-label col-12 text-center mt-1 text-bold">المنتج</label>'+
                    '         <div class="col-12 m-auto text-center">'+
                    '             <input type="text" list="search_results'+num+'" autocomplete="off" class="form-control m-auto text-center product new_invoice_input" name="product" required>'+
                    '             <datalist id="search_results'+num+'"></datalist>'+
                    '             <input type="hidden" class="form-control m-auto text-center product_id new_invoice_input" name="product_id" required>'+
                    '         </div>'+
                    '     </div>'+
                    ' </div>'+

                    ' <div class="col-md-3 col-6 mb-3">'+
                    ' <div class="form-group row m-auto">'+
                    ' <label class="control-label col-12 text-center mt-1 text-bold">الكمية</label>'+
                    ' <div class="col-12 m-auto text-center">'+
                    ' <input type="number" class="form-control m-auto text-center problem new_invoice_input" min="1" name="quantity" value="1" required>'+
                    '<label> </label>'+
                    '</div>'+
                    '</div>'+
                    '</div>'+

                    '<div class="col-md-3 col-6 mb-3">'+
                    ' <div class="form-group row m-auto">'+
                    '     <label class="control-label col-12 text-center mt-1 text-bold">السعر</label>'+
                    '     <div class="col-12 m-auto text-center">'+
                    '         <input type="number" class="form-control m-auto text-center price new_invoice_input" step="0.01" name="price" required>'+
                    '     </div>'+
                    ' </div>'+
                    '</div>'+

                    '<div class="col-md-3 col-6 mb-3">'+
                    ' <div class="form-group row m-auto">'+
                    '     <label class="control-label col-12 text-center mt-1 text-bold">الضمان بالايام</label>'+
                    '     <div class="col-12 m-auto text-center">'+
                    '         <input type="number" class="form-control m-auto text-center warranty_period new_invoice_input" name="warranty_period">'+
                    '     </div>'+
                    ' </div>'+
                    '</div>'+

                    ' <div class="col-md-3 col-6 mb-3">'+
                    '     <div class="form-group row m-auto">'+
                    '          <div class="col-12 m-auto text-center">'+
                    '              <i class="fas fa-trash-alt text-danger fa-2x cursor-pointer mt-4 delete_row"></i>'+
                    '          </div>'+
                    '     </div>'+
                    ' </div>'+

                    ' <div class="col-md-3 col-6 mb-3">'+
                    '     <div class="form-group row m-auto">'+
                    '          <div class="col-12 m-auto text-center">'+
                    '              <i class="fas fa-plus text-success fa-2x cursor-pointer mt-4 add_current"></i>'+
                    '          </div>'+
                    '     </div>'+
                    ' </div>'+

                    '</div>';
                $(".inv_current").html(row);
                $('.selectpicker').selectpicker();
            }else{
                $(".inv_current").html("");
                $('.selectpicker').selectpicker();
            }
        })

        $(document).on("click",".add_current",function () {
            let num = $(".inv_current").children().length + $(".row_array").length;
            let row = '' +
                '<div class="row bg-gray-primary justify-content-center mb-2 row_array_current">'+

                ' <div class="col-md-3 col-6 mb-3">'+
                '     <div class="form-group row m-auto">'+
                '         <label class="control-label col-12 text-center mt-1 text-bold">القسم</label>'+
                '         <div class="col-12 m-auto text-center">'+
                '             <select class="form-control m-auto text-center selectpicker category_id new_invoice_input" data-live-search="true" name="category_id" required>'+
                '                 <option value="" class="text-info">إختر القسم</option>'+
                '                 @foreach($categories as $category)'+
                '                    <option name="{{ $category['title'] }}" value="{{ $category['id'] }}">{{ $category['title'] }}</option>'+
                '                 @endforeach'+
                '             </select>'+
                '         </div>'+
                '     </div>'+
                ' </div>'+

                ' <div class="col-md-3 col-6 mb-3">'+
                '     <div class="form-group row m-auto">'+
                '         <label class="control-label col-12 text-center mt-1 text-bold">بحث</label>'+
                '         <div class="col-12 m-auto text-center">'+
                '             <input type="text" class="form-control m-auto text-center search_product new_invoice_input" name="search_product" required>'+
                '         </div>'+
                '     </div>'+
                ' </div>'+

                ' <div class="col-md-3 col-6 mb-3">'+
                '     <div class="form-group row m-auto">'+
                '         <label class="control-label col-12 text-center mt-1 text-bold">المنتج</label>'+
                '         <div class="col-12 m-auto text-center">'+
                '             <input type="text" list="search_results'+num+'" autocomplete="off" class="form-control m-auto text-center product new_invoice_input" name="product" required>'+
                '             <datalist id="search_results'+num+'"></datalist>'+
                '             <input type="hidden" class="form-control m-auto text-center product_id new_invoice_input" name="product_id" required>'+
                '         </div>'+
                '     </div>'+
                ' </div>'+

                ' <div class="col-md-3 col-6 mb-3">'+
                ' <div class="form-group row m-auto">'+
                ' <label class="control-label col-12 text-center mt-1 text-bold">الكمية</label>'+
                ' <div class="col-12 m-auto text-center">'+
                ' <input type="number" class="form-control m-auto text-center problem new_invoice_input" name="quantity" min="1" value="1" required>'+
                '<label> </label>'+
                '</div>'+
                '</div>'+
                '</div>'+

                '<div class="col-md-3 col-6 mb-3">'+
                ' <div class="form-group row m-auto">'+
                '     <label class="control-label col-12 text-center mt-1 text-bold">السعر</label>'+
                '     <div class="col-12 m-auto text-center">'+
                '         <input type="number" class="form-control m-auto text-center price new_invoice_input" step="0.01" name="price" required>'+
                '     </div>'+
                ' </div>'+
                '</div>'+

                '<div class="col-md-3 col-6 mb-3">'+
                ' <div class="form-group row m-auto">'+
                '     <label class="control-label col-12 text-center mt-1 text-bold">الضمان بالايام</label>'+
                '     <div class="col-12 m-auto text-center">'+
                '         <input type="number" class="form-control m-auto text-center warranty_period new_invoice_input" name="warranty_period">'+
                '     </div>'+
                ' </div>'+
                '</div>'+

                ' <div class="col-md-3 col-6 mb-3">'+
                '     <div class="form-group row m-auto">'+
                '          <div class="col-12 m-auto text-center">'+
                '              <i class="fas fa-trash-alt text-danger fa-2x cursor-pointer mt-4 delete_row"></i>'+
                '          </div>'+
                '     </div>'+
                ' </div>'+

                ' <div class="col-md-3 col-6 mb-3">'+
                '     <div class="form-group row m-auto">'+
                '          <div class="col-12 m-auto text-center">'+
                '              <i class="fas fa-plus text-success fa-2x cursor-pointer mt-4 add_current"></i>'+
                '          </div>'+
                '     </div>'+
                ' </div>'+

                '</div>';

            $(".inv_current").append(row);
            $('.selectpicker').selectpicker();
        })

        $('[name="status"]').on("change",function () {
            if ( $(this).val() == 1 ){
                $(".cash_current_type").css("display","block");
                $('[name="current_type"]').prop("required",true);
            }
            else{

                $(".cash_current_type").css("display","none");
                $('[name="current_type"]').prop("required",false);
            }
        })


        $(document).on("keyup",'[name="quantity"], [name="price"]',function () {
            sum_prices();
        })

        function sum_prices() {

            let type = $.trim($("#title").text());

            let price = 0;


            if (type === "فاتورة جديدة"){
                $(".price_sum").remove();
                $(".row_array").each(function (index,data) {
                    if (data.querySelector("[name='price']")){
                        price += (+data.querySelector("[name='price']").value * +data.querySelector("[name='quantity']").value);
                    }
                })
                $(".submit_btn").after(
                    "<h5 class='text-bold text-center text-info price_sum mt-4'> إجمالي المبلغ:  "+price+"</h5>"
                );
            }else if (type === "صرف"){

                $('[name="amount_exp_rev"]').val("").prop("readonly",true);

                $(".row_array_current").each(function (index,data) {
                    price += (+data.querySelector("[name='price']").value * +data.querySelector("[name='quantity']").value);
                })
                $('[name="amount_exp_rev"]').val(price);
            }

        }

    </script>

@endsection
