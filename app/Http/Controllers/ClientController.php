<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function search_client(Request $request)
    {
        $client = Client::whereMobile_number($request->search)->first();

        return $client;
    }

    public function client_report(Request $request)
    {
        $clientReport = Client::with(["financial_activities"=>function($query){
                            return $query->with(["user","sales_invoices"=>function($query){
                                return $query->with(["product"=>function($query){
                                    return $query->with(["barcode"=>function($query){
                                        return $query->with(["brand:id,title","category:id,title"]);
                                    }]);
                                }]);
                            }]);
                        }])
                        ->whereMobile_number($request->mobile_number)
                        ->get();
        return $clientReport;
    }
}
