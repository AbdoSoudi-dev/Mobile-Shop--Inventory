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
                        إضافة مخزون جديد
                    </h4>

                    <form action="{{ url("/products") }}" method="post" id="inv_form" enctype="multipart/form-data" translate="no">
                        @csrf

                        <div class="row justify-content-center mb-2">

                            <div class="col-md-4 col-6 mb-3">
                                <div class="form-group row m-auto">
                                    <label class="control-label col-12 text-center mt-1 text-bold">التاجر</label>
                                    <div class="col-12 m-auto text-center">
                                        <select class="form-control m-auto text-center selectpicker" data-live-search="true" name="vendor_id">
                                            <option value="" class="text-info">إختر التاجر</option>
                                            @foreach($vendors as $vendor)
                                                <option value="{{ $vendor['id'] }}">{{ $vendor['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 col-6 mb-3">
                                <div class="form-group row m-auto">
                                    <label class="control-label col-12 text-center mt-1 text-bold">صورة الفاتورة</label>
                                    <div class="col-12 m-auto text-center">
                                        <input type="file" class="form-control m-auto text-center" name="purchase_image" >
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div id="products_data">
                            <div class="row bg-gray-primary justify-content-center mb-2 array_row" data-num="0">
                                <div class="col-md-3 col-6 mb-3">
                                    <div class="form-group row m-auto">
                                        <label class="control-label col-12 text-center mt-1 text-bold">بحث</label>
                                        <div class="col-12 m-auto text-center">

                                            <input list="search_results0" autocomplete="off" class="form-control m-auto text-center barcode" name="barcode">

                                            <datalist id="search_results0"></datalist>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6 mb-3">
                                    <div class="form-group row m-auto">
                                        <label class="control-label col-12 text-center mt-1 text-bold">القسم</label>
                                        <div class="col-12 m-auto text-center">
                                            <select class="form-control m-auto text-center selectpicker category_id" data-live-search="true" name="category_id0" required>
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
                                        <label class="control-label col-12 text-center mt-1 text-bold">الماركة</label>
                                        <div class="col-12 m-auto text-center">
                                            <select class="form-control m-auto text-center selectpicker brand_id" data-live-search="true" name="brand_id0" required>
                                                <option value="" class="text-info">إختر الماركة</option>
                                                @foreach($brands as $brand)
                                                    <option {{ $brand['id'] == 10 ? "selected" : "" }} name="{{ $brand['title'] }}" value="{{ $brand['id'] }}">{{ $brand['title'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 col-6 mb-3">
                                    <div class="form-group row m-auto">
                                        <label class="control-label col-12 text-center mt-1 text-bold">اسم المنتج</label>
                                        <div class="col-12 m-auto text-center">
                                            <input type="text" class="form-control m-auto text-center title" name="title0" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 col-6 mb-3">
                                    <div class="form-group row m-auto">
                                        <label class="control-label col-12 text-center mt-1 text-bold">الكمية</label>
                                        <div class="col-12 m-auto text-center">
                                            <input type="number" class="form-control m-auto text-center quantity" name="quantity0" value="1" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 col-6 mb-3">
                                    <div class="form-group row m-auto">
                                        <label class="control-label col-12 text-center mt-1 text-bold">سعر الشراء</label>
                                        <div class="col-12 m-auto text-center">
                                            <input type="number" class="form-control m-auto text-center price" step="0.01" name="price0" placeholder="السعر للمنتج الواحد" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6 mb-3">
                                    <div class="form-group row m-auto">
                                        <label class="control-label col-12 text-center mt-1 text-bold">سعر البيع</label>
                                        <div class="col-12 m-auto text-center">
                                            <input type="number" class="form-control m-auto text-center selling_price" step="0.01" name="selling_price0" placeholder="السعر للمنتج الواحد" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 col-6 mb-3 upload_photo">
                                    <div class="form-group row m-auto">
                                        <label class="control-label col-12 text-center mt-1 text-bold">ارفاق صورة</label>
                                        <div class="col-12 m-auto text-center">
                                            <input type="file" class="form-control m-auto text-center image" name="image0" >
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" value="0" name="count" />

                                <div class="col-md-3 col-6 mb-3">
                                    <div class="form-group row m-auto">
                                        <div class="col-12 m-auto text-center">
                                            <i class="fas fa-trash-alt text-danger fa-2x cursor-pointer mt-4 delete_row"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 col-6 mb-3">
                                    <div class="form-group row m-auto">
                                        <div class="col-12 m-auto text-center">
                                            <i id="add_inv" class="fas fa-plus cursor-pointer text-light bg-success fa-3x mb-2 p-1"></i>
                                        </div>
                                    </div>
                                </div>

                                {{--                            <div class="col-md-6">--}}
                                {{--                                <div class="form-group row">--}}
                                {{--                                    <label class="control-label text-right col-md-3 mt-1 text-bold">Address 1</label>--}}
                                {{--                                    <div class="col-md-9">--}}
                                {{--                                        <input type="text" class="form-control">--}}
                                {{--                                    </div>--}}
                                {{--                                </div>--}}
                                {{--                            </div>--}}
                                {{--                            <div class="col-md-6">--}}
                                {{--                                <div class="form-group row">--}}
                                {{--                                    <label class="control-label text-right col-md-3 mt-1 text-bold">Address 2</label>--}}
                                {{--                                    <div class="col-md-9">--}}
                                {{--                                        <input type="text" class="form-control">--}}
                                {{--                                    </div>--}}
                                {{--                                </div>--}}
                                {{--                            </div>--}}
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-12">
                                <div class="float-left">
                                    <i id="add_inv" class="fas fa-plus cursor-pointer text-light bg-success fa-3x mb-2 p-1"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 text-center">
                            <input type="submit" class="btn btn-info text-bold text-2xl py-2 px-3 mt-2 btn-submitt" value="حفظ المخزون">
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


            $(document).on("change",".category_id",function () {

                let cat_id = $(this).val()
                let array_cateogries = ["1","2","16"];
                let category =  $('option:selected', this).attr('name');
                let num = $(this).parents(":eq(4)").attr("data-num");
                $('[name="quantity'+num+'"]').val(1).prop("readonly",true)
                if( array_cateogries.includes(cat_id) ){
                    if( !$(this).parents(":eq(4)").children(".serial_number").length ){
                        $(this).parents(":eq(4)").children(".upload_photo").after(
                            '<div class="col-md-3 col-6 mb-3 serial_number">'+
                            '   <div class="form-group row m-auto">'+
                            '       <label class="control-label col-12 text-center mt-1 text-bold">السيريال</label>'+
                            '       <div class="col-12 m-auto text-center">'+
                            '            <input type="number" class="form-control m-auto text-center serial_no" name="serial_no'+(num)+'" placeholder="رقم سيريال التليفون" >'+
                            '       </div>'+
                            '   </div>'+
                            '</div>'
                        );
                    }

                }else {
                    $(this).parents(":eq(4)").children(".serial_number")?.remove();
                    $('[name="quantity'+num+'"]').val(1).prop("readonly",false)

                }
            });

            $(document).on("click",".delete_row",function () {
                $(this).parents().eq(3).remove();
                $('[name="count"]').remove();
                let num = +$("#products_data").children(".array_row").length;
                if(num > 0){
                    $("#products_data").append('<input type="hidden" value="'+(num-1)+'" name="count" />');
                }
                arrange_rows();
            })

            $(document).on("click","#add_inv",function () {

                let num_val = $(this).parents(":eq(3)").attr("data-num");

                let category_id = ( $('[name="category_id'+num_val+'"]').val() ? $('[name="category_id'+num_val+'"]').val() : "");
                let brand_id = ( $('[name="brand_id'+num_val+'"]').val() ? $('[name="brand_id'+num_val+'"]').val() : "");
                let title = ( $('[name="title'+num_val+'"]').val() ? $('[name="title'+num_val+'"]').val() : "");
                let quantity = ( $('[name="quantity'+num_val+'"]').val() ? $('[name="quantity'+num_val+'"]').val() : "");
                let price = ( $('[name="price'+num_val+'"]').val() ? $('[name="price'+num_val+'"]').val() : "");
                let selling_price = ( $('[name="selling_price'+num_val+'"]').val() ? $('[name="selling_price'+num_val+'"]').val() : "");
                let serial_no = ( $('[name="serial_no'+num_val+'"]') ? $('[name="serial_no'+num_val+'"]') : "");

                let num = $("#products_data").children(".array_row").length;
                $('[name="count"]').remove();
                let data_html = '' +
                    '<div class="row bg-gray-primary justify-content-center array_row mb-2" data-num="'+num+'">'+

                    '<div class="col-md-3 col-6 mb-3">'+
                    '   <div class="form-group row m-auto">'+
                    '       <label class="control-label col-12 text-center mt-1 text-bold">بحث</label>'+
                    '       <div class="col-12 m-auto text-center">'+
                    '           <input list="search_results'+num+'" class="form-control m-auto text-center barcode" autocomplete="off" name="barcode'+num+'">'+
                    '           <datalist id="search_results'+num+'"></datalist>'+
                    '       </div>'+
                    '   </div>'+
                    '</div>'+

                    '<div class="col-md-3 col-6 mb-3">'+
                    '   <div class="form-group row m-auto">'+
                    '       <label class="control-label col-12 text-center mt-1 text-bold">القسم</label>'+
                    '           <div class="col-12 m-auto text-center">'+
                    '           <select class="form-control m-auto text-center selectpicker category_id" data-live-search="true" name="category_id'+num+'" required>'+
                    '           <option value="" class="text-info">إختر القسم</option>'+
                    '            @foreach($categories as $category)'+
                    '               <option name="{{ $category['title'] }}"  '+(num_val && category_id == {{ $category['id'] }} ? "selected" : "" )+' value="{{ $category['id'] }}">{{ $category['title'] }}</option>'+
                    '            @endforeach'+
                    '           </select>'+
                    '       </div>'+
                    '   </div>'+
                    '</div>'+

                    '<div class="col-md-3 col-6 mb-3">'+
                    '   <div class="form-group row m-auto">'+
                    '       <label class="control-label col-12 text-center mt-1 text-bold">الماركة</label>'+
                    '       <div class="col-12 m-auto text-center">'+
                    '           <select class="form-control m-auto text-center selectpicker brand_id" data-live-search="true" name="brand_id'+num+'" required>'+
                    '           <option value="" class="text-info">إختر الماركة</option>'+
                    '              @foreach($brands as $brand)'+
                    '                   <option '+(num_val && brand_id == {{ $brand['id'] }} ? "selected" : "" )+' name="{{ $brand['title'] }}" value="{{ $brand['id'] }}">{{ $brand['title'] }}</option>'+
                    '              @endforeach ' +
                    '           </select>'+
                    '       </div>'+
                    '   </div>'+
                    '</div>'+

                    '<div class="col-md-3 col-6 mb-3">'+
                    '   <div class="form-group row m-auto">'+
                    '       <label class="control-label col-12 text-center mt-1 text-bold">اسم المنتج</label>'+
                    '       <div class="col-12 m-auto text-center">'+
                    '           <input type="text" class="form-control m-auto text-center title" name="title'+num+'" value="'+title+'" required>'+
                    '       </div>'+
                    '   </div>'+
                    '</div>'+

                    '<div class="col-md-3 col-6 mb-3">'+
                    '   <div class="form-group row m-auto">'+
                    '       <label class="control-label col-12 text-center mt-1 text-bold">الكمية</label>'+
                    '       <div class="col-12 m-auto text-center">'+
                    '            <input type="number" class="form-control m-auto text-center quantity" name="quantity'+num+'" value="'+(quantity ? quantity : 1)+'" '+(serial_no.length ? "readonly" : "")+' required>'+
                    '       </div>'+
                    '   </div>'+
                    '</div>'+

                    '<div class="col-md-3 col-6 mb-3">'+
                    '   <div class="form-group row m-auto">'+
                    '       <label class="control-label col-12 text-center mt-1 text-bold">سعر الشراء</label>'+
                    '       <div class="col-12 m-auto text-center">'+
                    '           <input type="number" class="form-control m-auto text-center price" step="0.01" name="price'+num+'" value="'+price+'" placeholder="السعر للمنتج الواحد" required>'+
                    '       </div>'+
                    '   </div>'+
                    '</div>'+

                    '<div class="col-md-3 col-6 mb-3">'+
                    '   <div class="form-group row m-auto">'+
                    '       <label class="control-label col-12 text-center mt-1 text-bold">سعر البيع</label>'+
                    '       <div class="col-12 m-auto text-center">'+
                    '           <input type="number" class="form-control m-auto text-center selling_price" step="0.01" name="selling_price'+num+'" value="'+selling_price+'" placeholder="السعر للمنتج الواحد" required>'+
                    '       </div>'+
                    '   </div>'+
                    '</div>'+

                    '<div class="col-md-3 col-6 mb-3 upload_photo">'+
                    '   <div class="form-group row m-auto">'+
                    '       <label class="control-label col-12 text-center mt-1 text-bold">ارفاق صورة</label>'+
                    '       <div class="col-12 m-auto text-center">'+
                    '           <input type="file" class="form-control m-auto text-center image" name="image'+num+'" >'+
                    '       </div>'+
                    '   </div>'+
                    '</div>'+
                    ( serial_no.length ?
                        '<div class="col-md-3 col-6 mb-3 serial_number">'+
                        '   <div class="form-group row m-auto">'+
                        '       <label class="control-label col-12 text-center mt-1 text-bold">السيريال</label>'+
                        '       <div class="col-12 m-auto text-center">'+
                        '            <input type="number" class="form-control m-auto text-center serial_no" name="serial_no'+(num)+'" placeholder="رقم سيريال التليفون" >'+
                        '       </div>'+
                        '   </div>'+
                        '</div>' : "" )+

                    '<input type="hidden" value="'+num+'" name="count" />'+

                    '<div class="col-md-3 col-6 mb-3">'+
                    '   <div class="form-group row m-auto">'+
                    '       <div class="col-12 m-auto text-center">'+
                    '           <i class="fas fa-trash-alt text-danger fa-2x cursor-pointer mt-4 delete_row"></i>'+
                    '       </div>'+
                    '   </div>'+
                    '</div>'+

                    '<div class="col-md-3 col-6 mb-3">'+
                    '   <div class="form-group row m-auto">'+
                    '       <div class="col-12 m-auto text-center">'+
                    '           <i id="add_inv" class="fas fa-plus cursor-pointer text-light bg-success fa-3x mb-2 p-1"></i>'+
                    '       </div>'+
                    '   </div>'+
                    '</div>'+

                    '</div>';
                $("#products_data").append(data_html);
                $('.selectpicker').selectpicker();
            })

            function arrange_rows() {
                $(".array_row").each(function (index,data) {
                    data.setAttribute("data-num",index);
                    data.querySelector("select.category_id").setAttribute("name","category_id"+index);
                    data.querySelector("select.brand_id").setAttribute("name","brand_id"+index);
                    data.querySelector("input.title").setAttribute("name","title"+index);
                    data.querySelector("input.barcode").setAttribute("name","barcode"+index);
                    data.querySelector("input.quantity").setAttribute("name","quantity"+index);
                    data.querySelector("input.price").setAttribute("name","price"+index);
                    data.querySelector("input.selling_price").setAttribute("name","selling_price"+index);
                    data.querySelector("input.image").setAttribute("name","image"+index);
                    data.querySelector("input.barcode_id")?.setAttribute("name","barcode_id"+index);
                    data.querySelector("input.serial_no")?.setAttribute("name","serial_no"+index);
                })
            }

            $(document).on("keyup",".barcode",function () {

                if($(".barcode").is(":focus")) {
                    let search_value = $(this).val().trim();
                    let num = $(this).parents(":eq(3)").attr("data-num");

                    if (search_value) {
                        $("[name='serial_no" + num + "']").parents(":eq(3)").children(".serial_number").remove();

                        $("[name='category_id" + num + "']").val("").prop("disabled", false).change();
                        $("[name='brand_id" + num + "']").val("").prop("disabled", false).change();
                        $("[name='title" + num + "']").val("").prop("disabled", false);
                        $("[name='quantity" + num + "']").val("1");
                        $("[name='price" + num + "']").val("");
                        $("[name='selling_price" + num + "']").val("");
                        $("#search_results" + num).html("");
                        $("[name='serial_no" + num + "']").parents(":eq(2)")?.remove();

                        $("[name='barcode_id" + num + "']")?.remove();


                        $.ajax({
                            url: "/search_barcode",
                            method: "POST",
                            data: {
                                search_value: search_value,
                                _token: '{{ csrf_token() }}'
                            },
                            dataType: "JSON",
                            success: function (response) {
                                console.log(response);
                                if (response.length == 1) {

                                    $("[name='category_id" + num + "']").val(response[0].category_id).prop("disabled", true).change();
                                    $("[name='brand_id" + num + "']").val(response[0].brand_id).prop("disabled", true).change();
                                    $("[name='title" + num + "']").val(response[0].title).prop("disabled", true);
                                    $("[name='title" + num + "']").after("<input type='hidden' class='barcode_id' name='barcode_id"+num+"' value='"+response[0].id+"'>");
                                    $('[name="price'+num+'"]').focus();
                                    if (response[0].serial_no && !$("[name='serial_no" + num + "']").length ) {
                                        $("[name='price" + num + "']").parents(":eq(3)").children(".upload_photo").after(
                                            '<div class="col-md-3 col-6 mb-3 serial_number">' +
                                            '   <div class="form-group row m-auto">' +
                                            '       <label class="control-label col-12 text-center mt-1 text-bold">السيريال</label>' +
                                            '       <div class="col-12 m-auto text-center">' +
                                            '            <input type="number" class="form-control m-auto text-center serial_no" disabled value="' + response[0].serial_no + '" name="serial_no' + (num) + '" placeholder="رقم سيريال التليفون" >' +
                                            '       </div>' +
                                            '   </div>' +
                                            '</div>'
                                        );
                                    }
                                    $("[name='serial_no" + num + "']")?.val(response[0].serial_no).prop("disabled", true);


                                } else {
                                    $("[name='barcode_id" + num + "']")?.remove();
                                    $("[name='price" + num + "']").parents(":eq(3)").children(".serial_number").remove();
                                    $("[name='category_id" + num + "']").val("").prop("disabled", false).change();
                                    $("[name='brand_id" + num + "']").val("").prop("disabled", false).change();
                                    $("[name='title" + num + "']").val("").prop("disabled", false);
                                    let options = "";
                                    for (let i = 0; i < response.length; i++) {
                                        options += "<option data-data='" + JSON.stringify(response[i]) + "' value='" + response[i].title + "'>";
                                    }
                                    $("#search_results" + num).html(options);
                                }

                                $('.selectpicker').selectpicker();

                            },
                            error: function (err) {
                                console.log(err);
                            }
                        })
                    }
                }

            });

            $(document).on('change', '.barcode', function(){
                let num = $(this).parents(":eq(3)").attr("data-num");

                $(this).parents(":eq(3)").children(".serial_number")?.remove();

                $("[name='barcode_id" + num + "']")?.remove();

                var optionslist = $('#search_results'+num)[0].options;
                if(optionslist.length){
                    var value = $(this).val();
                    for (var x=0;x<optionslist.length;x++){
                        if (optionslist[x].value === value) {
                            //Alert here value
                            let barcode = JSON.parse(optionslist[x].getAttribute("data-data"));
                            $('[name="price'+num+'"]').focus();
                            $("[name='category_id" + num + "']").val(barcode.category_id).prop("disabled", true).change();
                            $("[name='brand_id" + num + "']").val(barcode.brand_id).prop("disabled", true).change();
                            $("[name='title" + num + "']").val(barcode.title).prop("disabled", true);

                            $("[name='title" + num + "']").after("<input type='hidden' class='barcode_id' name='barcode_id"+num+"' value='"+barcode.id+"'>");

                            if (barcode.serial_no && !$("[name='serial_no" + num + "']").length) {
                                $("[name='price" + num + "']").parents(":eq(3)").children(".upload_photo").after(
                                    '<div class="col-md-3 col-6 mb-3 serial_number">' +
                                    '   <div class="form-group row m-auto">' +
                                    '       <label class="control-label col-12 text-center mt-1 text-bold">السيريال</label>' +
                                    '       <div class="col-12 m-auto text-center">' +
                                    '            <input type="number" class="form-control m-auto text-center serial_no" disabled value="' + barcode.serial_no + '" name="serial_no' + (num) + '" placeholder="رقم سيريال التليفون" >' +
                                    '       </div>' +
                                    '   </div>' +
                                    '</div>'
                                );
                            }
                            $("[name='serial_no" + num + "']")?.val(barcode.serial_no).prop("disabled", true)

                            break;
                        }
                    }
                }
            });

        });



        $("#inv_form").on("submit",function (e) {
            // e.preventDefault();
            $(".btn-submitt").remove();

            // let formData = new FormData(document.getElementById("inv_form"));

            let row_array = [];
            $("#products_data").children(".array_row").each(function (index,data) {

                row_array.push({
                    "category_id" : data.querySelector("[name='category_id']").value,
                    "brand_id" : data.querySelector("[name='brand_id']").value,
                    "title" : data.querySelector("[name='title']").value,
                    "quantity" : data.querySelector("[name='quantity']").value,
                    "price" : data.querySelector("[name='price']").value,
                    "serial_no" : data.querySelector("[name='serial_no']")?.value,
                    "image" : "image" + index
                });
            })

            if(row_array.length){
                let vendor = {};
                    {{--formData.append("_token",'{{ csrf_token() }}');--}}
                    {{--formData.append("row_array",JSON.stringify(row_array));--}}
                    {{--formData.append("vendor",vendor);--}}

                var formData = $('#inv_form').serializeArray();
                // $.ajax({
                //     url:"/products",
                //     method:"POST",
                //     data:formData,
                //     cache: false,
                //     contentType: false,
                //     processData: false,
                //     success:function (res) {
                //         console.log(res);
                //     },
                //     error:function (err) {
                //         console.log(err);
                //     }
                // })
            }

        })

        $(document).ready(function() {
            $(window).keydown(function(event){
                if(event.keyCode == 13) {
                    event.preventDefault();
                    return false;
                }
            });
        });

        $('#inv_form').on('keyup keypress', function(e) {
            var keyCode = e.keyCode || e.which;
            if (keyCode === 13) {
                e.preventDefault();
                return false;
            }
        });


    </script>

@endsection
