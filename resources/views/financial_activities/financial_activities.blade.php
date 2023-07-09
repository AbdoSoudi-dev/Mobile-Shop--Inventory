@extends("page_layout")
@section("page_content")

    <style>
        .cursor-pointer{
            cursor: pointer;
        }
        tr th,tfoot tr td{
            font-size: 16px;
            font-weight: bold;
        }
        .bordered{
            border-bottom: 1px solid;
        }
        .text-bold{
            font-weight: bolder;
        }
    </style>

    <div class="row">
        <div class="col-12">

            <div class="card">

                <div class="card-header">
                    <a href="/financial_activities/create" class="btn btn-info btn-rounded m-t-10 float-right">
                        إضافة لليومية
                    </a>
                </div>
                @if (session()->has('message'))
                    <div class="bg-primary text-light text-bold py-2 px-5  text-center mx-auto" id="session_message">
                        {{ session()->get("message") }}
                    </div>
                @endif

                <div class="row">
                    <div class="col-12">

                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title text-info text-bold">كشف حساب باليوم</h4>
                                <div class="col-12 text-center">
                                    <input type="date" value="{{ date("Y-m-d H:i:s") < date("Y-m-d") . " 06:00:00"  ? date("Y-m-d",strtotime("-1 days")) : date("Y-m-d") }}" onchange="daily_data();dated_daily()" class="col-6 form-control m-auto date_daily">
                                </div>

                                <div class="col-12 m-auto text-center">
                                    <h4 class="col-12 text-bold">
                                        نوع العمليات
                                    </h4>
                                    <select class="form-control col-md-6 col-12 btn btn-outline-info" id="acc_type_data" onchange="daily_data()">
                                        <option value="">الكل</option>
                                        <option value="invoice">فواتير</option>
                                        <option value="acc">حسابات</option>
                                    </select>
                                </div>

{{--                                <div class="col-12 text-left">--}}
{{--                                    <div class="text-left btn btn-success cursor-pointer" onclick="daily_data()">--}}
{{--                                        تحديث البيانات--}}
{{--                                    </div>--}}
{{--                                </div>--}}

                                <div class="table-responsive m-t-40">
                                    <table class="table ">
                                        <thead class="thead-dark text-bold ">
                                        <tr class="text-center">
                                            <th scope="col">#</th>
                                            <th scope="col">النوع</th>
                                            <th scope="col">بيان</th>
                                            <th scope="col">ملاحظات</th>
                                            <th scope="col">فاتورة #</th>
                                            <th scope="col">العميل</th>
                                            <th scope="col">الوقت</th>
                                            <th scope="col">المستخدم</th>
                                            <th scope="col">مدين</th>
                                            <th scope="col">دائن</th>
                                            <th scope="col">رصيد</th>
                                            <th scope="col">طباعة</th>
                                        </tr>
                                        </thead>
                                        <tbody id="daily_table">

                                        </tbody>

                                        <tfoot class="bg-dark text-light text-bold text-center">
                                            <tr>
                                                <td colspan="8">مجموع</td>
                                                <td id="maden"></td>
                                                <td id="daen"></td>
                                                <td colspan="2"></td>
                                            </tr>
                                            <tr>
                                                <td colspan="8">مجموع</td>
                                                <td id="total" colspan="2"></td>
                                                <td colspan="2"></td>
                                            </tr>
                                        </tfoot>
                                    </table>

                                </div>
                            </div>
                        </div>


                        <div class="card mt-3">
                            <h4 class="text-center text-info text-bold">
                                عمليات تم تغيير تاريخها
                            </h4>
                            <div class="table-responsive">
                                <table class="table ">
                                    <thead class="thead-dark text-bold ">
                                    <tr class="text-center">
                                        <th scope="col">#</th>
                                        <th scope="col">النوع</th>
                                        <th scope="col">بيان</th>
                                        <th scope="col">ملاحظات</th>
                                        <th scope="col">فاتورة #</th>
                                        <th scope="col">العميل</th>
                                        <th scope="col">الوقت</th>
                                        <th scope="col">المعدل لـ</th>
                                        <th scope="col">المستخدم</th>
                                        <th scope="col">مدين</th>
                                        <th scope="col">دائن</th>
                                        <th scope="col">طباعة</th>
                                    </tr>
                                    </thead>
                                    <tbody id="daily_dated">

                                    </tbody>

                                    <tfoot class="bg-dark text-light text-bold text-center">
{{--                                    <tr>--}}
{{--                                        <td colspan="8">مجموع</td>--}}
{{--                                        <td id="maden"></td>--}}
{{--                                        <td id="daen"></td>--}}
{{--                                        <td colspan="2"></td>--}}
{{--                                    </tr>--}}
                                        <tr>
                                            <td colspan="8">مجموع</td>
                                            <td id="total_dated" colspan="2"></td>
                                            <td colspan="2"></td>
                                        </tr>
                                    </tfoot>
                                </table>

                            </div>
                        </div>


                    </div>
                </div>

            </div>


            @can("isEditor")
                <div class="row">
                    <div class="col-12">
                        <div class="float-left m-3">
                            <button class="btn btn-info" data-toggle="modal" data-target="#close_acc">
                                إقفال حساب اليوم
                            </button>
                        </div>
                    </div>
                </div>
            @endcan

        </div>
    </div>

    <!-- show_details Modal -->
    <div class="modal fade" id="show_details" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">تفاصيل المنتج</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h3 class="text-info text-center" id="title">the title</h3>

                    <div class="row text-center">
                        <h4 class="col-6 mb-2" id="brand_id">brand</h4>
                        <h4 class="col-6 mb-2" id="category_id">category</h4>
                        <div class="col-6 mb-2 mx-auto" >
                            <img src="" id="product_image" class="img-responsive img-fluid" width="300">
                        </div>

                        <div class="col-6 mb-2 text-center m-auto" >
                            <div id="barcode_no"></div>
                            <div id="barcode"></div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>    <!-- show_details Modal -->

{{--   close_daily_acc --}}
    <div class="modal fade" id="close_acc" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">اقفال حساب اليوم</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="{{ url("/close_daily_acc") }}" method="POST">
                        @csrf
                        <div class="row justify-content-center">

                            <div class="form-group col-md-6 col-12">
                                <h4 class="col-12 text-bold text-center">حساب يوم قديم؟</h4>
                                <input type="date" name="created_at" class="form-control m-auto text-center">
                            </div>

                        </div>
                        <div class="row justify-content-center">

                            <div class="form-group col-md-6 col-12">
                                <h4 class="col-12 text-bold text-center">رصيد فوري</h4>
                                <input type="number" step="0.01" name="fawry_balance" class="form-control m-auto text-center" required>
                            </div>

                            <div class="form-group col-md-6 col-12">
                                <h4 class="col-12 text-bold text-center">رصيد ضامن</h4>
                                <input type="number" step="0.01" name="damen_balance" class="form-control m-auto text-center" required>
                            </div>

                            <div class="form-group col-md-6 col-12">
                                <h4 class="col-12 text-bold text-center">نقدي</h4>
                                <input type="number" step="0.01" name="debit" class="form-control m-auto text-center" required>
                            </div>

                        </div>

                        <div class="row justify-content-center">

                            <div class="form-group col-12">
                                <h4 class="col-12 text-bold text-center">ملاحظات</h4>
                                <textarea name="notes" class="form-control m-auto text-center" required></textarea>
                            </div>

                            <div class="form-group col-md-6 col-12">
                                <input type="submit" class="form-control m-auto text-center btn btn-success" value="حفظ اليومية">
                            </div>

                        </div>

                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        setTimeout(()=>{
            $("#session_message")?.remove();
        },5000);

        $(".date_daily, #acc_type_data").on("change",function () {
            $(".preloader").fadeIn();
        })

        function daily_data(){
            if ($(".date_daily").val()){
                $.ajax({
                    url: "/daily_data",
                    method: "POST",
                    data: {
                        date: $(".date_daily").val(),
                        acc_type: $("#acc_type_data").val(),
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: "JSON",
                    success: function (response) {
                        console.log(response);

                        let data_html = "";

                        let balance = 0;
                        let maden = 0;
                        let daen = 0;


                        for (let i = 0; i < response.length; i++) {
                            const acc = response[i];

                            if(acc.type === "اقفال يومية"){
                                maden = 0;
                                balance = 0
                                daen = 0;
                            }

                            if(acc.removed == 0){
                                balance += +acc.debit;
                                maden += +acc.debit;
                                balance -= +acc.credit;
                                daen += +acc.credit;
                            }

                            let sales_inv_html = '<div class="col-12 row justify-content-center">';
                            if (acc.sales_invoices.length){
                                for (let j = 0; j < acc.sales_invoices.length; j++) {

                                    sales_inv_html += '<div class="col-12">' + acc.sales_invoices[j].product.barcode.title + "</div>";
                                    sales_inv_html += '<div class="col-12 bordered">' + " كمية: " + acc.sales_invoices[j].quantity + " * " + acc.sales_invoices[j].price + "</div>";
                                }
                            }
                            sales_inv_html += '</div>';


                            data_html += "<tr class='"+ (acc.removed == 1 ? "bg-danger text-white" : (acc.refund == 1 ? "text-success text-bold" : "") )+"'>"+
                                "              <td>"+(i+1)+"</td>"+
                                "              <td>"+
                                "                    "+(acc.sales_invoices.length ? (acc.financial_accounts_type?.name ? "حساب جاري" : "فاتورة عملاء") :
                                    (acc.financial_accounts_type && acc.debit != "0.00" && acc.refund == 0 ?  "ايراد" :
                                        (acc.financial_accounts_type && acc.credit != "0.00" && acc.refund == 0 ? "مصروف"
                                            : (acc.type === "اقفال يومية" ?  "رصيد افتتاحي" : "") )) )+
                                "               </td>"+
                                "              <td>"+
                                "                   <a target='"+(acc.financial_accounts_type_id ? "_blank" : "")+"' class='"+(acc.financial_accounts_type_id ? "text-info" : "text-dark")+"' href='"+(acc.financial_accounts_type_id ? "/accounts_types/" + acc.financial_accounts_type_id :"#!" )+"'>"+
                                "                    "+ (acc.financial_accounts_type?.name ? acc.financial_accounts_type.name : acc.sales_invoices.length ? ""
                                    :(acc.fawry_balance > 0 || acc.damen_balance > 0 ?  " رصيد ضامن: " + numberWithCommas(acc.damen_balance) +  "<br> رصيد فوري: "  + numberWithCommas(acc.fawry_balance) :"" )  ) +
                                "                   </a> " +
                                "                     "+ (acc.sales_invoices.length ? (acc.financial_accounts_type_id ? "<br>" : "") + sales_inv_html : "") +
                                "              </td>"+
                                "              <td>"+
                                "                    "+ ( ( Math.floor(acc.unpaid_debit) > 0 ? (acc.notes + "<br>" + "مبلغ آجل : "  + acc.unpaid_debit) : ( acc.notes ?? "" ) ) ) +
                                "               </td>"+
                                "              <td>"+
                                "                    "+ (acc.removed == 0 && acc.refund == 0 ? "رقم :" + acc.id : "" ) +
                                "               </td>"+
                                "              <td>"+
                                "                    "+ (acc.client?.name ?? "") +
                                "               </td>"+
                                "              <td>"+
                                "                    "+ acc.created_at_format +
                                "               </td>"+
                                "              <td>"+
                                "                    "+ acc.user.name +
                                "               </td>"+
                                "              <td>"+
                                "                    "+ numberWithCommas(acc.debit) +
                                "               </td>"+
                                "              <td>"+
                                "                    "+ numberWithCommas(acc.credit) +
                                "               </td>"+
                                "              <td>"+
                                "                    "+ (acc.removed == 0 ? numberWithCommas(balance) : "قيد محذوف") +
                                "               </td>"+
                                "              <td>"+
                                (acc.sales_invoices.length ? acc.refund == 1 ? "مرتجع" :
                                    '              <a href="/financial_activities/'+acc.id+'" target="_blank" class="btn btn-info">'+
                                    '                   <i class="fas fa-print fa-2x text-light"></i>'+
                                    '              </a>' : "") +
                                "               </td>"+
                                "         </tr>";

                        }

                        $("#maden").html(numberWithCommas(maden));
                        $("#daen").html(numberWithCommas(daen));
                        $("#total").html(numberWithCommas(+maden - +daen))

                        $("tbody#daily_table").html(data_html);


                        $(".preloader").fadeOut();
                    },
                    error: function (err) {
                        // console.log(err);
                    }
                })
            }
        }

        daily_data();

        var today = "{{ date('Y-m-d') }}";
        var yesterday = "{{ date('Y-m-d',strtotime('-1 day')) }}";

        setInterval(()=>{
            if (!document.hidden && (yesterday == $(".date_daily").val() || today == $(".date_daily").val())){
                daily_data()
            }
        },15000)


        function dated_daily(){
            if ($(".date_daily").val()){

                $.ajax({
                    url: "/get_dated_daily",
                    method: "POST",
                    data: {
                        date: $(".date_daily").val(),
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: "JSON",
                    success: function (response) {
                        console.log(response);

                        let data_html = "";

                        let maden = 0;
                        let daen = 0;


                        for (let i = 0; i < response.length; i++) {
                            const acc = response[i];

                            if(acc.removed == 0){
                                maden += +acc.debit;
                                daen += +acc.credit;
                            }

                            let sales_inv_html = '<div class="col-12 row justify-content-center">';
                            if (acc.sales_invoices.length){
                                for (let j = 0; j < acc.sales_invoices.length; j++) {

                                    sales_inv_html += '<div class="col-12">' + acc.sales_invoices[j].product.barcode.title + "</div>";
                                    sales_inv_html += '<div class="col-12 bordered">' + " كمية: " + acc.sales_invoices[j].quantity + " * " + acc.sales_invoices[j].price + "</div>";
                                }
                            }
                            sales_inv_html += '</div>';


                            data_html += "<tr class='"+ (acc.removed == 1 ? "bg-danger text-white" : (acc.refund == 1 ? "text-success text-bold" : "") )+"'>"+
                                "              <td>"+(i+1)+"</td>"+
                                "              <td>"+
                                "                    "+(acc.sales_invoices.length ? (acc.financial_accounts_type?.name ? "حساب جاري" : "فاتورة عملاء") :
                                    (acc.financial_accounts_type && acc.debit != "0.00" && acc.refund == 0 ?  "ايراد" :
                                        (acc.financial_accounts_type && acc.credit != "0.00" && acc.refund == 0 ? "مصروف"
                                            : (acc.type === "اقفال يومية" ?  "رصيد افتتاحي" : "") )) )+
                                "               </td>"+
                                "              <td>"+
                                "                   <a target='"+(acc.financial_accounts_type_id ? "_blank" : "")+"' class='"+(acc.financial_accounts_type_id ? "text-info" : "text-dark")+"' href='"+(acc.financial_accounts_type_id ? "/accounts_types/" + acc.financial_accounts_type_id :"#!" )+"'>"+
                                "                    "+ (acc.financial_accounts_type?.name ? acc.financial_accounts_type.name : acc.sales_invoices.length ? ""
                                    :(acc.fawry_balance > 0 || acc.damen_balance > 0 ?  " رصيد ضامن: " + numberWithCommas(acc.damen_balance) +  "<br> رصيد فوري: "  + numberWithCommas(acc.fawry_balance) :"" )  ) +
                                "                   </a> " +
                                "                     "+ (acc.sales_invoices.length ? (acc.financial_accounts_type_id ? "<br>" : "") + sales_inv_html : "") +
                                "              </td>"+
                                "              <td>"+
                                "                    "+ ( ( Math.floor(acc.unpaid_debit) > 0 ? (acc.notes + "<br>" + "مبلغ آجل : "  + acc.unpaid_debit) : ( acc.notes ?? "" ) ) ) +
                                "               </td>"+
                                "              <td>"+
                                "                    "+ (acc.removed == 0 && acc.refund == 0 ? "رقم :" + acc.id : "" ) +
                                "               </td>"+
                                "              <td>"+
                                "                    "+ (acc.client?.name ?? "") +
                                "               </td>"+
                                "              <td>"+
                                "                    "+ acc.updated_at.split("T")[0] +
                                "               </td>"+
                                "              <td>"+
                                "                    "+ acc.created_at_format +
                                "               </td>"+
                                "              <td>"+
                                "                    "+ acc.user.name +
                                "               </td>"+
                                "              <td>"+
                                "                    "+ numberWithCommas(acc.debit) +
                                "               </td>"+
                                "              <td>"+
                                "                    "+ numberWithCommas(acc.credit) +
                                "               </td>"+
                                "              <td>"+
                                (acc.sales_invoices.length ? acc.refund == 1 ? "مرتجع" : acc.removed == 1 ? "قيد محذوف" :
                                '              <a href="/financial_activities/'+acc.id+'" target="_blank" class="btn btn-info">'+
                                    '                   <i class="fas fa-print fa-2x text-light"></i>'+
                                    '              </a>' : "") +
                                "               </td>"+
                                "         </tr>";

                        }

                        $("#total_dated").html(numberWithCommas(+maden - +daen))

                        $("tbody#daily_dated").html(data_html);


                        $(".preloader").fadeOut();
                    },
                    error: function (err) {
                        // console.log(err);
                    }
                })
            }
        }

        dated_daily();


        function numberWithCommas(x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        $("form").on("submit",function () {
            $(".preloader").fadeIn();
        })


    </script>
@endsection
