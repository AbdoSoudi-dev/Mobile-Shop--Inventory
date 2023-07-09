@extends("page_layout")
@section("page_content")



    <style>
        .cursor-pointer{
            cursor: pointer;
        }
        .text-bold{
            font-weight: bold;
        }
    </style>

    <div class="row">
        <div class="col-12">

            <div class="card">

                <div class="card-header">
                    <a href="/products/create" class="btn btn-info btn-rounded m-t-10 float-right">
                        إضافة مخزون
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
                                <h4 class="card-title text-info text-bold">أرشيف المخزون</h4>
                                @can("isAdmin")
                                    <p class="text-center">
                                        <button class="btn btn-info" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                            عرض الاسعار
                                        </button>
                                    </p>
                                    <div class="collapse" id="collapseExample">
                                        <div class="card card-body">
                                            <h4 class="text-center text-info text-bold">
                                                إجمالي اسعار الشراء:
                                                <div id="total_buy"></div>
                                            </h4>
                                            <h4 class="text-center text-info text-bold">
                                                إجمالي اسعار البيع:
                                                <div id="total_sell"></div>
                                            </h4>
                                        </div>
                                    </div>
                                @endcan


                                <div class="row d-flex justify-content-center text-center">
                                    <div class="form-group col-md-4 col-10">
                                        <h4 class="text-bold text-info">القسم</h4>
                                        <select id="category_id"
                                                class="selectpicker form-control text-center m-auto">
                                            <option value="">الكل</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category['id'] }}">{{ $category['title'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4 col-10">
                                        <h4 class="text-bold text-info">الماركة</h4>
                                        <select id="brand_id"
                                                class="selectpicker form-control text-center m-auto">
                                            <option value="">الكل</option>
                                            @foreach($brands as $brand)
                                                <option value="{{ $brand['id'] }}">{{ $brand['title'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="table-responsive m-t-40">
                                    <table id="myTable"
                                           class="nowrap table table-hover table-striped table-bordered w-100">
                                        <thead>
                                        <tr class="text-center">
                                            <th>م</th>
                                            <th>القسم</th>
                                            <th>الماركة</th>
                                            <th>الاسم</th>
                                            <th>الكمية</th>
                                            <th>السعر</th>
                                            <th>باركود</th>
                                            <th>السيريال</th>
                                            <th>المستخدم</th>
                                            <th>التاريخ</th>
                                            <th>طباعة</th>
                                        </tr>
                                        </thead>
                                        <tbody class="tbody_data">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
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
    </div>

    <!-- show_details Modal -->
    <div class="modal fade" id="edit_details" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">تعديل المنتج</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" id="edit_product">
                        @csrf
                        @method("PUT")
                        <h3 class="text-info text-center" id="product_title">

                        </h3>

                        <div class="row bg-gray-primary justify-content-center mb-2 ">



                            <div class="col-md-6 col-12 mb-3">
                                <div class="form-group row m-auto">
                                    <label class="control-label col-12 text-center mt-1 text-bold">المنتج</label>
                                    <div class="col-12 m-auto text-center">
                                        <input type="text" autocomplete="off" class="form-control m-auto text-center title " name="title" required>

                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-12 mb-3 serial">
                                <div class="form-group row m-auto">
                                    <label class="control-label col-12 text-center mt-1 text-bold">السيريال</label>
                                    <div class="col-12 m-auto text-center">
                                        <input type="number" class="form-control m-auto text-center serial_no " name="serial_no"  >
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <div class="form-group row m-auto">
                                    <label class="control-label col-12 text-center mt-1 text-bold">الكمية</label>
                                    <div class="col-12 m-auto text-center">
                                        <input type="number" class="form-control m-auto text-center quantity " name="quantity" required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <div class="form-group row m-auto">
                                    <label class="control-label col-12 text-center mt-1 text-bold">سعر الشراء</label>
                                    <div class="col-12 m-auto text-center">
                                        <input type="number" class="form-control m-auto text-center price " step="0.01" name="price" required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-12 mb-3">
                                <div class="form-group row m-auto">
                                    <label class="control-label col-12 text-center mt-1 text-bold">سعر البيع</label>
                                    <div class="col-12 m-auto text-center">
                                        <input type="number" class="form-control m-auto text-center selling_price " step="0.01" name="selling_price" required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mb-3">
                                <div class="form-group row m-auto">
                                    <label class="control-label col-12 text-center mt-1 text-bold">ملاحظات</label>
                                    <div class="col-12 m-auto text-center">
                                        <textarea class="form-control m-auto text-center notes col-md-6 col-12" name="notes"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 text-center submit_btn">
                                <input type="submit" class="btn btn-info text-bold text-2xl py-2 px-3 mt-2" value="حفظ التعديل">
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
        var products = [];
        $(document).ready(function () {
            setTimeout(()=>{
                $("#session_message")?.remove();
            },5000);

            $('.selectpicker').selectpicker();

            $.ajax({
                url: "/products_type",
                method: "POST",
                data: {
                    type:"archive",
                    _token: '{{ csrf_token() }}'
                },
                dataType: "JSON",
                async:false,
                success: function (response) {
                    // console.log(response);
                    products = response;
                    display_data();
                },
                error: function (err) {
                    // console.log(err);
                }
            })

        })


        $(".show_detail").on("click",function () {
            let product = JSON.parse($(this).attr("data-data"));
            let barcode = $(this).attr("image_barcode");

            if(product.barcode.image){
                $("#product_image").css("display","block").attr("src","images/products/"+product.barcode.image);
            }else{
                $("#product_image").css("display","none").attr("src","");
            }
            $("#title").text(product.barcode.title);
            $("#brand_id").text(product.barcode.brand.title);
            $("#category_id").text(product.barcode.category.title);
            $("#barcode").html(barcode);
            $("#barcode_no").html(product.barcode.barcode);
        })

        function edit_details(product) {
            $(".preloader").fadeIn();

            // product = JSON.parse(product);
            $("#edit_product").attr("action",`/products/${product.id}`)

            $('[name="title"]').val(product.barcode.title)

            if (product.barcode.serial_no){
                $(".serial").css("display","block")
                $('[name="serial_no"]').val(product.barcode.serial_no).prop("required",true)
            }else{
                $(".serial").css("display","none")
                $('[name="serial_no"]').val("").prop("required",false)
            }

            $('[name="quantity"]').val(product.quantity)
            $('[name="selling_price"]').val(product.selling_price)
            $('[name="price"]').val(product.price)
            $('[name="notes"]').val(product.notes)

            $(".preloader").fadeOut();
        }

        $("#edit_product").on("submit",function () {
            $(".preloader").fadeIn();
        })







        function display_data() {
            let filter_products = products.filter((prod)=>{
                return ($("#brand_id").val() ? prod.brand_id == $("#brand_id").val() : true) &&
                    ( $("#category_id").val() ? prod.category_id == $("#category_id").val() : true )
            });
            let data_html = "";

            let total_price = 0;
            let total_selling_price = 0;

            for (let i = 0; i < filter_products.length; i++) {
                const product = filter_products[i];

                total_price += +product.total_price;
                total_selling_price += +product.total_selling_price;

                data_html +=
                    '<tr class="text-center">' +
                    '<td>'+ (i+1) +'</td>' +
                    '<td>'+ product.category_title +'</td>' +
                    '<td>'+ product.brand_title +'</td>' +
                    '<td>'+ product.title +'</td>' +
                    '<td>'+ product.quantity +'</td>' +
                    "<td onclick='alert("+ '"' + "سعر الشراء:" + product.price + '"' +")'>"+product.selling_price+'</td>' +
                    '<td>' + product.barcode  +'</td>' +
                    '<td>' + (product.serial_no ?? "") +'</td>' +
                    '<td>' + product.user_name + '</td>' +
                    "<td onclick='alert("+ '"' + "آخر تعديل في:" + product.updated_at + '"' +")'>" + product.created_at + '</td>' +
                    '<td>' +
                    ( product.quantity > 0 ?
                        '   <a href="/barcode_print/'+product.barcode_id +'" target="_blank" class="btn btn-info">' +
                        '       <i class="fas fa-print fa-2x text-light"></i>' +
                        '   </a>' : "" )+

                    '</td>' +
                    '</tr>' ;
            }
            $(".tbody_data").html(data_html)

            $("#total_buy")?.html(numberWithCommas(total_price));
            $("#total_sell")?.html(numberWithCommas(total_selling_price));

            @can("isAdmin")
            $('#myTable').DataTable({
                dom: 'lBfrtip ',
                "scrollX": true,
                buttons: [
                    'pdf'
                ]
            });
            $(' .buttons-pdf').addClass('btn btn-primary mr-1');
            @else
            $('#myTable').DataTable( {
                "scrollX": true
            });
            @endcan
        }



        $("#brand_id, #category_id").on("change",function () {
            $('#myTable').DataTable().destroy();
            display_data();
        })
        function numberWithCommas(x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }
    </script>
@endsection
