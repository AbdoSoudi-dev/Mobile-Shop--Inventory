<?php

namespace App\Http\Controllers;

use App\Http\Middleware\adminRoutes;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
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
        $categories = Category::with("user")->get();

        return view("categories.categories",compact("categories"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       $request->validate([
            'title' => 'required|string|min:3',
        ]);

        Category::create([
            "title" => $request->title,
            "user_id" => Auth::id(),
        ]);

        return redirect("/categories")->with("message","تم الاضافة بنجاح");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|min:3',
            'status' => 'in:0,1'
        ]);
        $category = Category::findOrFail($id);
        $category->title = $request->title;
        $category->status = $request->status;
        $category->user_id = Auth::id();
        $category->save();

        return redirect("/categories")->with("message","تم التعديل بنجاح");
    }
}
