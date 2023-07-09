@extends("page_layout")
@section("page_content")

    <style>
        .text-bold{
            font-weight: bolder !important;
        }
    </style>

    <div class="row">

        <div class="col-12">
            <h4 class="text-info text-bold mt-4">
                تقرير عن العميل
            </h4>
        </div>

        <div class="col-12">

            <div class="card mt-3">
                <div class="row d-flex justify-content-center">
                    <div class="form-group m-auto text-center col-md-3 col-6">
                        <h5 class="col-12 text-center text-bold"> رقم هاتف العميل </h5>
                        <input type="number" class="form-control text-center ">
                    </div>
                </div>

                <div class="row d-flex justify-content-center mt-3">
                    <div class="form-group m-auto text-center col-md-3 col-6">
                        <h5 class="col-12 text-center text-bold"> اسم العميل </h5>
                        <h5 class="col-12 text-center text-bold client_name">  </h5>
                        <h5 class="col-12 text-center text-info text-bold invoice_numbers mt-3">  </h5>
                    </div>
                </div>

                <div class="table-responsive m-t-40">
                    <table class="table " style="display: none">
                        <thead class="thead-dark text-bold ">
                        <tr class="text-center">
                            <th scope="col">#</th>
                            <th scope="col">بيان</th>
                            <th scope="col">ملاحظات</th>
                            <th scope="col">فاتورة #</th>
                            <th scope="col">العميل</th>
                            <th scope="col">الوقت</th>
                            <th scope="col">المستخدم</th>
                            <th scope="col">المبلغ</th>
                            <th scope="col">طباعة</th>
                        </tr>
                        </thead>
                        <tbody id="daily_table">

                        </tbody>

                        <tfoot class="bg-dark text-light text-bold text-center">
                        <tr>
                            <td colspan="7">مجموع</td>
                            <td id="maden"></td>
                            <td id="daen"></td>
                        </tr>
                        </tfoot>
                    </table>

                </div>


            </div>

        </div>


    </div>



    <script>
        $("input").on("keyup",function () {
            let mobile_number = $(this).val();

            $(".invoice_numbers").html("");
            $("table").css("display","none")



            if (mobile_number.toString().length > 5){
                $(".client_name").css("color","blue").html("جاري البحث..")
                $.ajax({
                    url: "/client_report",
                    method : "POST",
                    data: {
                        _token:"{{ csrf_token() }}",
                        mobile_number:mobile_number
                    },
                    dataType: "JSON",
                    success: function (response) {
                        console.log(response);

                        let data_html = "";

                        let maden = 0;
                        let daen = 0;
                        let invoice_numbers = 0;
                        let i = -1;
                        let invoice_refund = 0;

                        if (response.length){
                            $(".client_name").css("color","black").html(response[0].name)

                            for (const acc of response[0].financial_activities) {
                                i++;
                                if(acc.removed == 0){
                                    maden += +acc.debit;
                                    daen += +acc.credit;
                                }
                                invoice_numbers++;

                                if (acc.removed == 1 || acc.refund == 1) {
                                    invoice_refund++;
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
                                    (acc.sales_invoices.length ? acc.refund == 1 ? "مرتجع" : acc.removed == 1 ? "قيد محذوف" :
                                        '              <a href="/financial_activities/'+acc.id+'" target="_blank" class="btn btn-info">'+
                                        '                   <i class="fas fa-print fa-2x text-light"></i>'+
                                        '              </a>' : "") +
                                    "               </td>"+
                                    "         </tr>";
                            }
                            $(".invoice_numbers").html("عدد الفواتير : " + invoice_numbers +" منها مرتجع عدد: " + invoice_refund);

                            $("#maden").html(numberWithCommas(maden));
                            // $("#total").html(numberWithCommas(+maden - +daen))

                            $("tbody#daily_table").html(data_html);
                            $("table").css("display","table")
                        }else{
                            $(".client_name").css("color","red").html("لا توجد نتائج")
                        }

                    },
                    error: function (err) {
                        // console.log(err);
                    }
                })
            }

        })


        function numberWithCommas(x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }
    </script>

@endsection
