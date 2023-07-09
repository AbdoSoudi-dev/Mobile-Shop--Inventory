<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\adminRoutes;

class BrandController extends Controller
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
        $brands = Brand::with("user")->get();

        return view("brands.brands",compact("brands"));
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
            'image' => 'image|mimes:jpg,png,jpeg,svg|max:5120',
        ]);

        $imageName = NULL;
        if ($request->hasFile("image")){
            $image = $request->file('image');
            $imageName = time().$image->getClientOriginalName();
            $image->move(public_path('images/brands'), $imageName);
        }
        Brand::create([
            "title" => $request->title,
            "user_id" => Auth::id(),
            "image" => $imageName
        ]);

        return redirect("/brands")->with("message","تم الاضافة بنجاح");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Brand  $brands
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|min:3',
            'image' => 'image|mimes:jpg,png,jpeg,svg|max:5120',
            'status' => 'in:0,1'
        ]);
        $brand = Brand::findOrFail($id);
        if ($request->hasFile("image")){
            $image = $request->file('image');
            $imageName = time().$image->getClientOriginalName();
            $image->move(public_path('images/brands'), $imageName);
            @unlink(public_path('images/brands/'.$brand->image));

            $brand->image = $imageName;
        }
        $brand->title = $request->title;
        $brand->status = $request->status;
        $brand->user_id = Auth::id();
        $brand->save();

        return redirect("/brands")->with("message","تم التعديل بنجاح");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Brand  $brands
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);
        @unlink("/public/images/brands/".$brand['image']);
        $brand->delete();
        return  redirect("/brands")->with("message","تم الحذف بنجاح");
    }
}
