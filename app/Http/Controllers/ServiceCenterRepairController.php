<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Client;
use App\Models\Service_center_repair;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;

class ServiceCenterRepairController extends Controller
{

    public function index()
    {
        $service_repairs = Service_center_repair::whereRemovedAndReceived(0,0)->with(["user","brand","client"])->latest()->get();
        return view("service_repairs.service_repairs",compact("service_repairs"));
    }

    public function done_service_repair($month)
    {
        $service_repairs = Service_center_repair::where(function ($query){
            return $query->whereReceived(1)->orWhere("removed",1);
        })->with(["user","brand","client"])
            ->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m'))"), "=", $month)
            ->latest()->get();
        return view("service_repairs.service_repairs_done",compact("service_repairs","month"));
    }

    public function create()
    {
        $brands = Brand::whereStatus(0)->get();
        return view("service_repairs.add_service_repairs",compact("brands"));
    }



    public function store(Request $request)
    {
//        $validated =$request->validate([]);
        if ($request['client_number'] || $request['client_number'] == 0 ){
            $client_id = Client::whereMobile_number($request['client_number'])->first()->id ?? NULL;
        }
        if (!$client_id){
            $client = Client::create([
                "mobile_number" => $request->client_number,
                "name" => $request['client_name'],
                "user_id"=> Auth::id()
            ]);
            $client_id = $client->id;
        }

        Service_center_repair::create([
            "client_id" => $client_id,
            "brand_id" => $request['brand_id'],
            "title" => $request['title'],
            "problem" => $request['problem'],
            "serial_no" => $request['serial_no'],
            "notes" => $request['notes'] ,
            "user_id" => Auth::id()
        ]);

        return redirect("/service_repairs")->with("message","تمت إضافة للصيانة بنجاح");

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Service_center_repair  $service_center_repair
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $service_repair = Service_center_repair::whereId($id)->with("brand")->first();

        $pdf = PDF::loadView("pdf.service_repair",compact("service_repair"));
//        $pdf->setOption('page-width', '210')
//            ->setOption('page-height', '140')
//            ->setOption('margin-left', '0')
//            ->setOption('margin-right', '0');
        return $pdf->stream($service_repair['client_name'].".pdf");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Service_center_repair  $service_center_repair
     * @return \Illuminate\Http\Response
     */
    public function edit(Service_center_repair $service_center_repair)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Service_center_repair  $service_center_repair
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Service_center_repair $service_center_repair)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Service_center_repair  $service_center_repair
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Service_center_repair::find($id)->update([ "removed" => 1 ]);

        return redirect("/service_repairs")->with("message","تمت الالغاء من الصيانة بنجاح");
    }
}
