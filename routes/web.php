<?php

use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('index');
})->name('dashboard');


Route::group(['prefix'=>'/', 'middleware'=>['auth:sanctum']],function(){
//    home page
    Route::get("/",function (){
        return view("index");
    })->name("dashboard");

    Route::get("/losses/{month}",[ \App\Http\Controllers\LossController::class,"index" ]);
    Route::get("/products_logs/{month}",[ \App\Http\Controllers\ProductsLogController::class,"index" ]);
    Route::get("/done_repair/{month}",[ \App\Http\Controllers\ServiceCenterRepairController::class,"done_service_repair" ]);

//    resources routes
    Route::resources([
        "brands" => \App\Http\Controllers\BrandController::class,
        "categories" => \App\Http\Controllers\CategoryController::class,
        "vendors" => \App\Http\Controllers\VendorController::class,
        "products" => \App\Http\Controllers\ProductController::class,
        "accounts_types" => \App\Http\Controllers\FinancialAccountsTypeController::class,
        "service_repairs" => \App\Http\Controllers\ServiceCenterRepairController::class,
        "financial_activities" => \App\Http\Controllers\FinancialActivityController::class,
        "losses" => \App\Http\Controllers\LossController::class,
        "products_logs" => \App\Http\Controllers\ProductsLogController::class,
    ]);

//    search barcode
    Route::post("/search_barcode",[ \App\Http\Controllers\BarcodeController::class,"search_barcode" ]);
//    search_for_new_invoice
    Route::post("/search_for_invoice",[ \App\Http\Controllers\BarcodeController::class,"search_for_invoice" ]);
//    search old invoice
    Route::post("/old_invoice",[ \App\Http\Controllers\FinancialActivityController::class,"old_invoice" ]);

//    search client number
    Route::post("/search_client",[ \App\Http\Controllers\ClientController::class,"search_client" ]);

//    view client
    Route::view("/clients","clients.client_report");

//    view client
    Route::post("/client_report",[\App\Http\Controllers\ClientController::class,"client_report"]);



    //    close daily account
    Route::post("/close_daily_acc",[ \App\Http\Controllers\FinancialActivityController::class,"close_daily_acc" ]);

    //    daily account
    Route::post("/daily_data",[ \App\Http\Controllers\FinancialActivityController::class,"daily_data" ]);
    Route::post("/get_dated_daily",[ \App\Http\Controllers\FinancialActivityController::class,"get_dated_daily" ]);


    //    print
    Route::get("/barcode_print/{id}",[ \App\Http\Controllers\BarcodeController::class,"barcode_print" ]);

    //    account constraint
    Route::post("/acc_constraint",[ \App\Http\Controllers\FinancialAccountsTypeController::class,"acc_constraint" ]);

    //    vendor constraint
    Route::post("/vendor_acc",[ \App\Http\Controllers\VendorController::class,"vendor_accounts" ]);

    //    All products
    Route::get("/products_archive",[ \App\Http\Controllers\ProductController::class,"products_archive" ]);
    //    All products to filter
    Route::post("/products_type",[ \App\Http\Controllers\ProductController::class,"get_products" ]);
    //    All products to filter
    Route::view("/few_products","products.few_products",
        [
            "brands"=> \App\Models\Brand::whereStatus(0)->get(),
            "categories"=> \App\Models\Category::whereStatus(0)->get(),
            ]);
    Route::post("/few_products",[ \App\Http\Controllers\ProductController::class,"few_products" ]);

    Route::post("/destroy_products",[ \App\Http\Controllers\ProductController::class,"destroyProducts" ]);

//    user profile
    Route::view("/user_profile","profile.custom_profile");

    Route::get("/users", [\App\Http\Controllers\UserController::class,"index"] );
    Route::put("/users/{id}", [\App\Http\Controllers\UserController::class,"update_status"] );
    Route::post("/users", [\App\Http\Controllers\UserController::class,"store"] );

//    reports
    Route::get("/sells_report/{month}", [\App\Http\Controllers\reportController::class,"sells_monthly"] );

});

