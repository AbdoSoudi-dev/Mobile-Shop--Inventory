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

                @if (session()->has('message'))
                    <div class="bg-primary text-light text-bold py-2 px-5  text-center mx-auto" id="session_message">
                        {{ session()->get("message") }}
                    </div>
                @endif


                <div class="row">
                    <div class="col-12">

                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title text-info text-bold">كشف حساب</h4>
                                <h4 class="card-title text-info text-bold text-center">
                                    {{ $vendor->name }}
                                </h4>
                                <div class="col-12 text-center">
                                    <label class="col-12"> من</label>
                                    <input type="date" class="col-6 form-control m-auto date_from">
                                </div>
                                <div class="col-12 text-center">
                                    <label class="col-12"> الى</label>
                                    <input type="date" onchange="vendors_acc()" class="col-6 form-control m-auto date_to">
                                </div>
                                <div class="col-12 text-center">
                                    <input type="submit" onclick="vendors_acc()" class="col-6 form-control m-auto btn btn-info" value="بحث">
                                </div>
                                <div class="table-responsive m-t-40">
                                    <table class="table ">
                                        <thead class="thead-dark text-bold ">
                                            <tr class="text-center">
                                                <th scope="col">#</th>
                                                <th scope="col">النوع</th>
                                                <th scope="col">بيان</th>
                                                <th scope="col">ملاحظات</th>
                                                <th scope="col">فاتورة #</th>
                                                <th scope="col">الوقت</th>
                                                <th scope="col">المستخدم</th>
                                                <th scope="col">مدين</th>
                                                <th scope="col">دائن</th>
                                                <th scope="col">رصيد</th>
                                            </tr>
                                        </thead>
                                        <tbody id="daily_table">

                                        </tbody>

                                        <tfoot class="bg-dark text-light text-bold text-center">
                                        <tr>
                                            <td colspan="7">مجموع</td>
                                            <td id="maden"></td>
                                            <td id="daen"></td>
                                            <td colspan="1"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="7">مجموع</td>
                                            <td id="total" colspan="2"></td>
                                            <td colspan="1"></td>
                                        </tr>
                                        </tfoot>
                                    </table>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

        </div>
    </div>

    <!-- openImage Popup Model -->
    <div id="openImage" class="modal fade in" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-right" id="myModalLabel">عرض الفاتورة</h4>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">×</button>
                </div>
                <div class="modal-body">

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
        setTimeout(()=>{
            $("#session_message")?.remove();
        },5000);

        function vendors_acc() {
            let date_from = $(".date_from").val();
            let date_to = $(".date_to").val();

            if ((date_from && date_to) || (!date_from && !date_to)) {

                $(".preloader").fadeIn();
                $.ajax({
                    url: "/vendor_acc",
                    method: "POST",
                    data: {
                        id: "{{ $vendor->id }}",
                        date_from: date_from,
                        date_to: date_to,
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

                            if(!acc.removed || acc.removed == 0){
                                maden += +(acc.credit ? acc.credit : 0);
                                maden += +(acc.unpaid_debit ? acc.unpaid_debit : 0);

                                balance += +(acc.debit ? acc.debit : 0);
                                balance += +(acc.price ? acc.price : 0);
                                balance -= +(acc.credit ? acc.credit : 0);

                                balance -= +(acc.unpaid_debit ? acc.unpaid_debit : 0);
                                daen += +(acc.price ? acc.price : 0);
                                daen += +(acc.debit ? acc.debit : 0);
                            }

                            let sales_inv_html = '<div class="col-12 row justify-content-center">';
                            if (acc.sales_invoices?.length){
                                for (let j = 0; j < acc.sales_invoices.length; j++) {

                                    sales_inv_html += '<div class="col-12">' + acc.sales_invoices[j].product.barcode.title + "</div>";
                                    sales_inv_html += '<div class="col-12 bordered">' + " كمية: " + acc.sales_invoices[j].quantity + "</div>";
                                }
                            }else if (acc.products?.length){
                                for (let j = 0; j < acc.products.length; j++) {
                                    sales_inv_html += '<div class="col-12">' + acc.products[j].barcode.title + "</div>";
                                    sales_inv_html += '<div class="col-12 bordered">' + " كمية: " + (+acc.products[j].quantity +
                                                            acc.products[j].sales_invoices.map( (x) => +x.quantity ).reduce((a, b) => a + b, 0) ) + "</div>";
                                }
                            }
                            sales_inv_html += '</div>';


                            data_html += "<tr class='"+ (acc.removed == 1 ? "bg-danger text-white" : (acc.refund == 1 ? "text-success text-bold" : "") )+"'>"+
                                "              <td>"+(i+1)+"</td>"+
                                "              <td>"+
                                "                    "+ (acc?.products ? "استلام مخزون" : (acc.sales_invoices?.length ? "صرف مخزون" : "") ) +
                                "               </td>"+
                                "              <td>"+ sales_inv_html +
                                "               </td>"+
                                "              <td>"+
                                "                    "+ (acc.notes ? acc.notes : (acc.image ? '<img onclick="openImage(event)" width="100" src="/images/purchases/'+acc.image+'">' : "")) +
                                "               </td>"+
                                "              <td>"+
                                "                    "+ (acc.removed == 0 && acc.refund == 0 ? "رقم :" + acc.id : "" ) +
                                "               </td>"+
                                "              <td>"+
                                "                    "+ acc.created_at_format +
                                "               </td>"+
                                "              <td>"+
                                "                    "+ acc.user.name +
                                "               </td>"+
                                "              <td>"+
                                "                    "+( Math.floor(acc.credit) ? numberWithCommas(acc.credit) : numberWithCommas(acc.unpaid_debit) ) +
                                "               </td>"+
                                "              <td>"+
                                "                    "+  ( acc.price ? numberWithCommas(acc.price) : numberWithCommas(acc.debit)  )+
                                "               </td>"+
                                "              <td>"+
                                "                    "+ (acc.removed == 1 ?  "قيد محذوف" : numberWithCommas(balance) ) +
                                "               </td>"+
                                "              <td>"+
                                "         </tr>";

                        }

                        $("#maden").html(numberWithCommas(maden));
                        $("#daen").html(numberWithCommas(daen));
                        $("#total").html(numberWithCommas(+daen - +maden))

                        $("tbody#daily_table").html(data_html);


                        $(".preloader").fadeOut();
                    },
                    error: function (err) {
                        // console.log(err);
                    }
                })

            } else {
                alert("يجب تحديد من والى");
            }
        }

        vendors_acc();


        function numberWithCommas(x) {
            if (x){
                return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            }else {
                return 0;
            }
        }

        function openImage(event) {

            $(".modal-body").html('<img width="100%" src="'+event.currentTarget.getAttribute("src")+'">')

            $("#openImage").modal("show");
        }


    </script>
@endsection
