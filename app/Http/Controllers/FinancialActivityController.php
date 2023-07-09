<?php

namespace App\Http\Controllers;

use App\Models\Barcode;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Client;
use App\Models\Financial_accounts_type;
use App\Models\Financial_activity;
use App\Models\Product;
use App\Models\Sales_invoice;
use App\Models\Service_center_repair;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use niklasravnsborg\LaravelPdf\Facades\Pdf;

class FinancialActivityController extends Controller
{
    public function index()
    {
        return view("financial_activities.financial_activities");
    }

    public function daily_data(Request $request)
    {
        $start_date = $request->date . " 06:00:00";
        $end_date = Carbon::parse($request->date)->addDay()->format("Y-m-d") . " 05:59:00";

        $financial_activities = Financial_activity::with(["user","client","sales_invoices"=>function($query){
            return $query->with(["product"=>function($query){
                return $query->with("barcode");
            }]);
        },"financial_accounts_type"])
            ->where("created_at",">=",$start_date)
            ->where("created_at","<=",$end_date)
            ->where("type","!=","اقفال يومية")
            ->where([
                ( $request->acc_type == "acc" ?
                    [ "financial_accounts_type_id" ,"!=","" ]
                    :   [ "user_id","!=","" ]
                )
            ]);

        if ($request->acc_type == "invoice"){
            $financial_activities = $financial_activities->whereHas("sales_invoices")->get();
        }else{
            $financial_activities = $financial_activities->get();
        }

        if ($request->acc_type !== "acc"){
            $sub_day = Carbon::parse($start_date)->subDay()->format("Y-m-d") . " 16:00:00";
            $start_constraint = Financial_activity::with(["user","client","sales_invoices"=>function($query){
                return $query->with(["product"=>function($query){
                    return $query->with("barcode");
                }]);
            },"financial_accounts_type"])
                ->where("created_at","<",$start_date)
                ->where("created_at",">",$sub_day)
                ->where("type","=","اقفال يومية")
                ->orderBy("created_at","DESC")
                ->limit(1)
                ->get();
            $financial_activities = $start_constraint->merge($financial_activities);
        }



        return $financial_activities;
    }

    public function old_invoice(Request $request)
    {
        $invoice = Financial_activity::with(["user","client","sales_invoices"=>function($query){
            return $query->with(["product"=>function($query){
                return $query->with("barcode",function ($query){
                    return $query->with("brand");
                });
            }]);
        },"financial_accounts_type"])
            ->where("type","!=","اقفال يومية")
            ->whereRemoved(0)
            ->whereRefund(0)
            ->find($request->id);
        return $invoice ;
    }

    public function close_daily_acc(Request $request)
    {
        $this->authorize("isEditor");


        Financial_activity::create([
            "user_id" => Auth::id(),
            "notes" => $request->notes,
            "debit" => $request->debit,
            "type" => "اقفال يومية",
            "fawry_balance" => $request->fawry_balance,
            "damen_balance" => $request->damen_balance,
            "created_at" => $request->created_at ? Carbon::parse($request->created_at)->addDay()->format("Y-m-d") . " 02:00:00" : Carbon::now(),
        ]);

        return redirect("/financial_activities")->with(["message"=> "تم تقفيل الحساب بنجاح"]);
    }

    public function create()
    {
        $categories = Category::whereStatus(0)->get();

        $vendors = Vendor::whereStatus(0)->get();

        $accounts_types = Financial_accounts_type::whereStatus(0)->orderBy("name","ASC")->get();
        $expenses = $accounts_types->filter(function ($acc){
            return $acc['type'] == "مصروف" || $acc['type'] == "حساب جاري" ;
        });

        $revenues = $accounts_types->filter(function ($acc){
            return $acc['type'] == "ايراد" || $acc['type'] == "حساب جاري" ;
        });

        $service_center_repair = Service_center_repair::with("client")->whereReceivedAndRemoved(0,0)->get();

        return view("financial_activities.add_financial_activities",
            compact("categories","expenses","revenues","vendors","service_center_repair"));
    }

    public function store(Request $request)
    {
        if ($request->type === "فاتورة جديدة")
        {
            $client_id = false;
            if($request->client_number != "" || $request->client_number == "0"){
                $client_id = Client::whereMobile_number($request->client_number)->first()->id ?? NULL;
            }
            if (!$client_id){
                $client = Client::create([
                    "mobile_number" => $request->client_number,
                    "name" => $request->client_name,
                    "user_id"=> Auth::id()
                ]);
                $client_id = $client->id;
            }

            $price_total = 0;
            foreach ($request->array as $item) {
                $item = (object)$item;
                $price_total += $item->price * $item->quantity;
            }

            $financial_activity = Financial_activity::create([
                "debit" => $price_total,
                "client_id" => $client_id,
                "user_id" => Auth::id(),
                "created_at" => $request->oper_date ? $request->oper_date . date(" 23:i:s") : Carbon::now()
            ]);

            foreach ($request->array as $item) {
                $item = (object)$item;

                if ($item->product_id){
                    $product = Product::find($item->product_id);
                    $product->quantity = $product->quantity - ( $item->category_id ==1 || $item->category_id ==2 || $item->category_id ==16 ? 1 :$item->quantity);
                    $product->save();

                    if ($item->category_id ==1 || $item->category_id ==2|| $item->category_id ==16){
                        Barcode::find($product->barcode_id)->update([ "status" => 1 ]);
                    }
                }
                if ($item->service_repair_id){
                    $service_repair = Service_center_repair::find($item->service_repair_id);
                    $service_repair->received = 1;
                    $service_repair->save();
                }

                Sales_invoice::create([
                    "price" => $item->price,
                    "quantity" => $item->quantity,
                    "product_id" => $item->product_id ?? NULL,
                    "service_center_repair_id" => $item->service_repair_id ?? NULL,
                    "user_id" => Auth::id(),
                    "financial_activity_id" => $financial_activity->id,
                    "warranty_period" => $item->warranty_period && $item->warranty_period != 0 ? $item->warranty_period : NULL,
                ]);
            }
        }
        else if ($request->type === "صرف" || $request->type === "استلام"){

            $financial_activity = Financial_activity::create([
                "debit" => ($request->type === "استلام" ? ($request->financial_accounts_type_id == 1 || $request->financial_accounts_type_id == 3 ? 0 :$request->amount) : 0 ),
                "credit" => ($request->type === "صرف" ? ($request->cash_type == 0 ? $request->amount : 0) : 0 ),
                "fawry_balance" => ($request->financial_accounts_type_id == 1 && $request->type === "استلام" ? $request->amount : 0 ),
                "damen_balance" => ($request->financial_accounts_type_id == 3 && $request->type === "استلام" ? $request->amount : 0 ),
                "notes" => ($request->current_type === "رصيد" ? "رصيد "  : "") . $request->notes,
                "unpaid_debit" => ($request->type === "صرف" && $request->cash_type == 1 && $request->current_type !== "نقدي" ? $request->amount : 0),
                "financial_accounts_type_id" => $request->financial_accounts_type_id,
                "vendor_id" => $request->vendor_id ?? NULL,
                "user_id" => Auth::id(),
                "created_at" => $request->oper_date ? $request->oper_date . date(" 23:i:s") : Carbon::now()
            ]);

            if ($request->type === "صرف" && $request->current_type === "مخزون"){
                foreach ($request->array as $item) {
                    $item = (object)$item;

                    if ($item->product_id){
                        $product = Product::find($item->product_id);
                        $product->quantity = $product->quantity - ( $item->category_id ==1 || $item->category_id ==2 || $item->category_id ==16 ? 1 :$item->quantity);
                        $product->save();

                        if ($item->category_id ==1 || $item->category_id ==2 || $item->category_id ==16){
                            Barcode::find($product->barcode_id)->update([ "status" => 1 ]);
                        }
                    }
                    if ($item->service_repair_id){
                        $service_repair = Service_center_repair::find($item->service_repair_id);
                        $service_repair->received = 1;
                        $service_repair->save();
                    }

                    Sales_invoice::create([
                        "price" => $item->price,
                        "quantity" => $item->quantity,
                        "product_id" => $item->product_id ?? NULL,
                        "service_center_repair_id" => $item->service_repair_id ?? NULL,
                        "user_id" => Auth::id(),
                        "financial_activity_id" => $financial_activity->id,
                        "warranty_period" => $item->warranty_period && $item->warranty_period != 0 ? $item->warranty_period : NULL,
                    ]);
                }
            }


        }

        return response("done",200);
    }

    public function show($id)
    {
        $invoice = Financial_activity::with(["user","client","sales_invoices"=>function($query){
            return $query->with(["product"=>function($query){
                return $query->with("barcode",function ($query){
                    return $query->with("brand");
                });
            }]);
        },"financial_accounts_type"])
            ->where("type","!=","اقفال يومية")
            ->whereHas("sales_invoices")
            ->whereRemoved(0)
            ->whereRefund(0)
            ->findOrFail($id);

        $serial_no = false;
        $warranty_period = false;

        foreach ($invoice['sales_invoices'] as $sales_invoice) {
            if (!$serial_no){
                $serial_no = $sales_invoice['product']['barcode']['serial_no'];
            }
            if (!$warranty_period){
                $warranty_period = $sales_invoice['warranty_period'];
            }
        }

        $pdf = PDF::loadView("pdf.invoice",compact("invoice","serial_no","warranty_period"),[],[
            'format' => [150, 130]
        ]);

        return $pdf->stream($invoice['client']['name'].".pdf");
    }

    public function edit(Financial_activity $financial_activity)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $financial_activity = Financial_activity::with(["user","client","sales_invoices"=>function($query){
            return $query->with(["product"=>function($query){
                return $query->with("barcode",function ($query){
                    return $query->with("brand");
                });
            }]);
        },"financial_accounts_type"])->find($id);

        if (Carbon::parse($financial_activity->created_at)->format("Y-m-d") == date("Y-m-d") )
        {
            Financial_activity::find($id)->update([
                "removed" => "1",
                "created_at"=> $financial_activity->created_at,
                "updated_at"=> $financial_activity->updated_at
            ]);

            if (count($financial_activity->sales_invoices)){
                foreach ($financial_activity->sales_invoices as $sales_invoice) {
                    $item = (object)$sales_invoice;

                    if ($item->product_id){
                        $product = Product::find($item->product_id);
                        $product->quantity = $product->quantity + $item->quantity;
                        $product->save();

                        Barcode::find($product->barcode_id)->update([ "status" => 0 ]);

                    }
                    if ($item->service_center_repair_id){
                        $service_repair = Service_center_repair::find($item->service_center_repair_id);
                        $service_repair->received = 0;
                        $service_repair->save();
                    }

                    Sales_invoice::find($item->id)->update([ "removed" => 1 ]);
                }
            }

        }else{

            Financial_activity::find($id)->update([
                "refund" => "1",
                "created_at"=> $financial_activity->created_at,
                "updated_at"=> $financial_activity->updated_at
            ]);

            $financial_activity_updated = Financial_activity::create([
                "debit" => $financial_activity->credit ,
                "credit" =>$financial_activity->debit,
                "fawry_balance" => $financial_activity->fawry_balance,
                "damen_balance" => $financial_activity->damen_balance,
                "notes" => " قيد عكسي للعملية رقم " . $id . " بتاريخ "
                    . ($financial_activity->updated_at->format("Y-m-d g:i") . ($financial_activity->updated_at->format("A") === "AM" ? " صباحًا ": " مساءًا "))
                    . " || ". $financial_activity->notes,
                "unpaid_debit" => - $financial_activity->unpaid_debit ,
                "financial_accounts_type_id" => $financial_activity->financial_accounts_type_id,
                "vendor_id" => $financial_activity->vendor_id,
                "refund" => 1,
                "client_id" => $financial_activity->client_id,
                "user_id" => Auth::id()
            ]);
            if (count($financial_activity->sales_invoices)){

                foreach ($financial_activity->sales_invoices as $sales_invoice) {
                    $item = (object)$sales_invoice;

                    if ($item->product_id){
                        $product = Product::find($item->product_id);
                        $product->quantity = $product->quantity + $item->quantity;
                        $product->save();

                        Barcode::find($product->barcode_id)->update([ "status" => 0 ]);

                    }
                    if ($item->service_center_repair_id){
                        $service_repair = Service_center_repair::find($item->service_center_repair_id);
                        $service_repair->received = 0;
                        $service_repair->save();
                    }

                    Sales_invoice::create([
                        "price" => $item->price,
                        "quantity" => $item->quantity,
                        "refund" => 1,
                        "product_id" => $item->product_id ?? NULL,
                        "service_center_repair_id" => $item->service_center_repair_id ?? NULL,
                        "user_id" => Auth::id(),
                        "financial_activity_id" => $financial_activity_updated->id
                    ]);
                }

            }

        }

        return response("done",200);

    }

    public function destroy(Financial_activity $financial_activity)
    {
        //
    }

    public function get_dated_daily(Request $request)
    {
        $start_date = $request->date . " 06:00:00";
        $end_date = Carbon::parse($request->date)->addDay()->format("Y-m-d") . " 05:59:00";
        $financial_activities = Financial_activity::with(["user","client","sales_invoices"=>function($query){
            return $query->with(["product"=>function($query){
                return $query->with("barcode");
            }]);
        },"financial_accounts_type"])
            ->where("created_at",">=",$start_date)
            ->where("created_at","<=",$end_date)
//            ->where("type","!=","اقفال يومية")
            ->whereRaw("created_at != updated_at")
            ->get();

        return $financial_activities;
    }

}
