<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Loss;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LossController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($month)
    {
        return view("losses.losses")->withLosses(
            Loss::with(["product"=>function($query){
                return $query->with(["barcode"=>function($query){
                    return $query->with(["brand:id,title","category:id,title"]);
                }]);
            },"user"])
             ->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m'))"), "=", $month)
            ->get()
        )
         ->withMonth($month)
         ->withCategories(
             Category::whereStatus(0)->get()
         );
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
        $product = Product::find($request->product_id);
        $product->quantity = $product->quantity - $request->quantity;
        $product->save();

        Loss::create([
            "user_id" => Auth::id(),
            "quantity" => $request->quantity,
            "product_id" => $request->product_id
        ]);
        return redirect("/losses/".date("Y-m"))->with(["message"=> "تم ادخال الخسارة"]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Loss  $loss
     * @return \Illuminate\Http\Response
     */
    public function show(Loss $loss)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Loss  $loss
     * @return \Illuminate\Http\Response
     */
    public function edit(Loss $loss)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Loss  $loss
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Loss $loss)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Loss  $loss
     * @return \Illuminate\Http\Response
     */
    public function destroy(Loss $loss)
    {
        //
    }
}
