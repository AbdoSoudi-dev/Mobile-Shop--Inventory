<?php

namespace App\Http\Controllers;

use App\Http\Middleware\adminRoutes;
use App\Models\Financial_activity;
use App\Models\Purchase;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorController extends Controller
{

    public function __construct()
    {
        $this->middleware(adminRoutes::class);
    }
    public function index()
    {
        $vendors = Vendor::with("user")->get();
        return view("vendors.vendors",compact("vendors"));
    }

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Vendor::create([
            "name" => $request->name,
            "mobile_number" => $request->mobile_number,
            "mobile_number_sec" => ($request->mobile_number_sec ?? NULL),
            "address" => $request->address,
            "user_id" => Auth::id()
        ]);

        return redirect("/vendors")->with("message","تم إضافة التاجر بنجاح");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function show(Vendor $vendor)
    {
        return view("vendors.vendors_constraint",compact("vendor"));
    }

    public function vendor_accounts(Request $request)
    {

        $credit = Purchase::with(["products"=>function($query){
                        return $query->with(["barcode:title,id","sales_invoices"=>function($query){
                            return $query->whereRemovedAndRefund(0,0)->select("quantity","product_id");
                        }]);
                    },"user:name,id"])
                    ->whereVendor_id($request->id)
                    ->where(
                        $request->date_from ?
                            [
                                ["created_at",">=",$request->date_from],
                                ["created_at","<=",$request->date_to . " 23:00:00" ],
                            ] : [
                                  ["created_at","!=",""]
                                 ]
                    )->get();

        $debit = Financial_activity::with(["vendor","sales_invoices"=>function($query){
                        return $query->with(["product"=>function($query){
                            return $query->with("barcode");
                        }]);
                    },"user"])
                    ->whereVendor_id($request->id)
                    ->where(
                        $request->date_from ?
                            [
                                ["created_at",">=",$request->date_from],
                                ["created_at","<=",$request->date_to . " 23:00:00" ],
                            ] : [
                            ["created_at","!=",""]
                        ]
                    )->get();


        $vendor_acc = [];
        $created_at_sort = [];
        foreach ($debit->merge($credit) as $item) {
            $vendor_acc[] = $item;
            $created_at_sort[] = $item['created_at']->format("Y-m-d H:i:s");
        }

        array_multisort($created_at_sort,SORT_ASC, $vendor_acc);

        return $vendor_acc;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function edit(Vendor $vendor)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Vendor::find($id)->update([
            "name" => $request->name,
            "mobile_number" => $request->mobile_number,
            "mobile_number_sec" => ($request->mobile_number_sec ?? NULL),
            "address" => $request->address,
            "user_id" => Auth::id()
        ]);

        return redirect("/vendors")->with("message","تم تعديل التاجر بنجاح");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vendor $vendor)
    {
        //
    }
}
