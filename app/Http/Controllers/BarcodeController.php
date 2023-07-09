<?php

namespace App\Http\Controllers;

use App\Models\Barcode;
use Illuminate\Http\Request;

use niklasravnsborg\LaravelPdf\Facades\Pdf;

class BarcodeController extends Controller
{
    public function search_barcode(Request $request)
    {
        $barcode = Barcode::where(
            is_numeric($request->search_value) ? "barcode" : "title",
            "LIKE",
            is_numeric($request->search_value) ? $request->search_value
                : $request->search_value."%"
        )->whereStatus(0)->limit(10)->get();
        return $barcode;
    }

    public function search_for_invoice(Request $request)
    {
            $search = Barcode::whereStatus(0)
                ->with("products","brand:id,title")
                ->whereHas("products",function($query){
                    return $query->where("quantity",">","0");
                })
                ->where(
                    is_numeric($request->search) ? (strlen($request->search) > 10 ? "serial_no" : "barcode") : "title",
                    "LIKE",
                    is_numeric($request->search) ? $request->search
                        :  $request->search. "%"
                )
                ->where("category_id",
                    $request->category_id ? $request->category_id
                        : "!=", "")
                ->limit(10)
                ->get();

        return $search;
    }

    public function barcode_print($id)
    {
        $barcode = Barcode::whereId($id)->with(["brand","category"])->first();

        $pdf = PDF::loadView("pdf.barcode",compact("barcode"),[],[
            'format' => [230, 80]
        ]);
        return $pdf->stream($barcode['title'].".pdf");
    }
}


