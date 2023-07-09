<?php

namespace App\Http\Controllers;

use App\Models\Barcode;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Financial_accounts_type;
use App\Models\Product;
use App\Models\products_log;
use App\Models\Purchase;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DataTables;
use Illuminate\Support\Facades\DB;


class ProductController extends Controller
{
    public function index()
    {
        return view("products.products")->withCategories(Category::whereStatus(0)->get())
                ->withBrands(Brand::whereStatus(0)->get());
    }

    public function products_archive()
    {
        return view("products.products_archive")->withCategories(Category::whereStatus(0)->get())
            ->withBrands(Brand::whereStatus(0)->get());
    }

    public function create()
    {
        $categories = Category::whereStatus(0)->get();
        $brands = Brand::whereStatus(0)->get();
        $vendors = Vendor::whereStatus(0)->get();

        return view("products.add_product",
            compact("categories","brands","vendors"));
    }

    public function store(Request $request)
    {
        if ($request->count >= 0 && $request->count != NULL)
        {
            $purchase_id = NULL;
            if ($request->vendor_id){
                $price = 0;
                for($i =0; $i < $request->count+1; $i++){
                    $price += $request['price'.$i] * $request['quantity'.$i];
                }
                if ($request->hasFile('purchase_image')){
                    $image = $request->file('purchase_image');
                    $purchase_image = time().$image->getClientOriginalName();
                    $image->move(public_path('images/purchases'), $purchase_image);
                }
                $purchase = Purchase::create([
                                "price" => $price,
                                "image" => $purchase_image ?? NULL,
                                "vendor_id" => $request->vendor_id,
                                "user_id" => Auth::id(),
                            ]);
                $purchase_id = $purchase->id;
            }

            for($i =0; $i < $request->count+1; $i++){
                $barcode_id = $request['barcode_id'.$i];

                if ($request->hasFile('image'.$i)){
                    $image = $request->file('image'.$i);
                    $product_image = time().$image->getClientOriginalName();
                    $image->move(public_path('images/products'), $product_image);
                }

                if (!$barcode_id){
                  $barcode =Barcode::create([
                        "title" => $request['title'.$i],
                        "barcode" => $this->generate_barcode(),
                        "image" => $product_image ?? NULL,
                        "serial_no" => $request['serial_no'.$i] ?? NUlL,
                        "brand_id" => $request['brand_id'.$i],
                        "category_id" => $request['category_id'.$i],
                      "user_id" => Auth::id(),
                    ]);
                   $barcode_id = $barcode->id;
                }
                else{
                    if ($request->hasFile('image'.$i)){
                        $old_image = Barcode::find($barcode_id)->first()->image;
                        if ($old_image){
                            @unlink(public_path('images/products'), $old_image);
                        }
                        Barcode::find($barcode_id)->update([ "image" => $product_image ]);
                    }
                }

                Product::create([
                    "quantity" => $request['quantity'.$i],
                    "price" => $request['price'.$i],
                    "selling_price" => $request['selling_price'.$i],
                    "purchase_id" => $purchase_id,
                    "barcode_id" => $barcode_id,
                    "user_id" => Auth::id(),
                ]);

            }

        }
        else{
            return redirect("/products")->with("message","لم يتم اضافة اي مخزون");
        }
        return redirect("/products")->with("message","تمت إضافة المخزون بنجاح");
    }

    public function generate_barcode() {
        $result = '';

        for($i = 0; $i < 10; $i++) {
            $result .= mt_rand(0, 9);
        }
        if ($this->barcode_exists($result)) {
            return $this->generate_barcode();
        }

        return $result;
    }

    public function barcode_exists($number) {
        return Barcode::whereBarcode($number)->exists();
    }

    public function show(Product $products)
    {
        //
    }

    public function edit(Product $products)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $this->authorize("isEditor");

        $product = Product::find($id);

        products_log::create([
            "title" => json_encode([$product->barcode->title,$request->title ]),
            "serial_no" => json_encode([$product->barcode->serial_no ?? "",$request->serial_no ?? ""]),
            "notes" => json_encode([$product->notes ?? "",$request->notes ?? ""]),
            "selling_price" => json_encode([$product->selling_price,$request->selling_price ]),
            "price" => json_encode([$product->price,$request->price ]),
            "quantity" => json_encode([$product->quantity,$request->quantity ]),
            "product_id" => $id,
            "user_id" => Auth::id()
        ]);

        $product->update([
           "quantity" => $request->quantity,
           "price" => $request->price,
           "selling_price" => $request->selling_price,
            "notes" => $request->notes,
            "user_id" => Auth::id(),
        ]);

        $product->barcode->update([
            "title" => $request->title,
            "serial_no" => $request->serial_no,
        ]);
        return redirect("/products")->with("message"," تمت تعديل المخزون $request->title بنجاح ");
    }

    public function destroy(Product $products)
    {
        //
    }

    public function destroyProducts(Request $request)
    {
        $this->authorize("isEditor");

        Barcode::with("products")
            ->whereHas("products",function ($query)use($request){
                return $query->whereIn("id",$request->ids);
            })
            ->update([
                "status" => 1
            ]);
        Product::whereDoesntHave("sales_invoices")->whereIn("id",$request->ids)->delete();
        Product::whereHas("sales_invoices")->whereIn("id",$request->ids)->update([
            "quantity" => 0
        ]);

        return true;
    }

    public function get_products(Request $request){

        $products = DB::table("products")
                        ->join("barcodes","products.barcode_id","=","barcodes.id")
                        ->where([
                            ["barcodes.status","=","0"],
                            ($request->type !== 'archive' ?
                                [ "products.quantity",">",0 ]
                                : [ "products.quantity","!=",NULL ]
                            )
                        ])
                        ->selectRaw("
                                      products.*, barcodes.*, barcodes.id as barcode_id, products.id as id,
                                      DATE_FORMAT(products.created_at, '%Y-%m-%d %h:%l %p') as created_at,
                                      DATE_FORMAT(products.updated_at, '%Y-%m-%d %h:%l %p') as updated_at,
                                      (SELECT name FROM users WHERE users.id = products.user_id) as user_name,
                                      (SELECT title FROM categories WHERE categories.id = barcodes.category_id) as category_title,
                                      (SELECT title FROM brands WHERE brands.id = barcodes.brand_id) as brand_title,
                                       (products.price * products.quantity) as total_price,
                                       (products.selling_price * products.quantity) as total_selling_price
                                       ")
                        ->get();
        return $products;
    }

    public function few_products(Request $request){

        $products = Barcode::with(["brand:title,id","category:title,id","user:name,id","products"=>function($query){
            $query->select("selling_price","price","barcode_id")->latest();
            }])
            ->withSum("products","quantity")
            ->whereNotIn("category_id",[1,2,16])
            ->groupBy("id")
            ->get();

        $products_filter = [];
        $product_sort = [];
        foreach ($products as $product) {
            if ($product->products_sum_quantity < 6) {
                $product['created_at_format'] = Carbon::parse($product->created_at)->format("Y-m-d h:i A");
                $product['updated_at_format'] = Carbon::parse($product->updated_at)->format("Y-m-d h:i A");
                $products_filter[] = $product;
                $product_sort[] = $product->products_sum_quantity;
            }
        }

        array_multisort($product_sort,SORT_ASC, $products_filter);
        return $products_filter;
    }
}
