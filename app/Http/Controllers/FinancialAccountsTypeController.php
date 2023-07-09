<?php

namespace App\Http\Controllers;

use App\Http\Middleware\adminRoutes;
use App\Models\Financial_accounts_type;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinancialAccountsTypeController extends Controller
{

    public function __construct()
    {
        $this->middleware(adminRoutes::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $accounts_types = Financial_accounts_type::with("user")->get();
        return view("accounts_types.financial_accounts_types",compact("accounts_types"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Financial_accounts_type::create([
            "name" => $request->name,
            "type" => $request->type,
            "user_id" => Auth::id(),
        ]);

        return redirect("/accounts_types")->with("message","تم الاضافة بنجاح");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
       return view("accounts_types.account_types_constraint", compact("id"));
    }

    public function acc_constraint(Request $request)
    {
        $financial_types = Financial_accounts_type::whereId($request->id)->with(["financial_activities"=>function($query)use($request){
                                return $query->with(["financial_accounts_type","sales_invoices"=>function($query){
                                    return $query->with(["product"=>function($query){
                                        return $query->with("barcode");
                                    }]);
                                },"user"])
                                    ->where(
                                        $request->date_from ?
                                            [
                                                ["created_at",">=",$request->date_from],
                                                ["created_at","<=",$request->date_to . " 23:00:00" ],
                                            ] : [
                                                ["created_at","!=",""]
                                                ]
                                    );
                            }])->first();

        return response($financial_types,200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Financial_accounts_type::find($id)->update([
            "name" => $request->name,
            "type" => $request->type,
            "status" => $request->status,
            "user_id" => Auth::id(),
        ]);

        return redirect("/accounts_types")->with("message","تم التعديل بنجاح");
    }
}
