<?php

namespace App\Http\Controllers;

use App\Models\products_log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductsLogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($month)
    {
        return view("products_logs.products_logs")->withProducts_logs(
                    products_log::with(["product"=>function($query){
                        return $query->with(["barcode"=>function($query){
                            return $query->with(["brand:id,title","category:id,title"]);
                        }]);
                    },"user"])
                     ->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m'))"), "=", $month)
                     ->latest()
                    ->get()
                )
                 ->withMonth($month);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\products_log  $products_log
     * @return \Illuminate\Http\Response
     */
    public function show(products_log $products_log)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\products_log  $products_log
     * @return \Illuminate\Http\Response
     */
    public function edit(products_log $products_log)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\products_log  $products_log
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, products_log $products_log)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\products_log  $products_log
     * @return \Illuminate\Http\Response
     */
    public function destroy(products_log $products_log)
    {
        //
    }
}
