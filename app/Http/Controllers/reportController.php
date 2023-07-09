<?php

namespace App\Http\Controllers;

use App\Http\Middleware\adminRoutes;
use App\Models\Financial_activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class reportController extends Controller
{
    public function __construct()
    {
        $this->middleware(adminRoutes::class);
    }
    public function sells_monthly($month)
    {
        $sells =  Financial_activity::with(["user","client","sales_invoices"=>function($query){
            return $query->with(["product"=>function($query){
                return $query->with("barcode",function ($query){
                    return $query->with("brand");
                });
            }]);
        },"financial_accounts_type"])
            ->where("type","!=","اقفال يومية")
            ->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m'))"), "=", $month)
            ->whereHas("sales_invoices")->get();

        $sells_report = [];

//        $sells->mapWithKeys(function ($sell)use($sells_report){
//            $sells_report[$sell['created_at']][] = $sell;
//        });
        foreach ($sells as $sell) {
            $sells_report[$sell['created_at']->format("Y-m-d")][] = $sell;
        }

        return view("reports.sells_monthly")->withMonth($month)->withSells($sells_report);
    }
}
