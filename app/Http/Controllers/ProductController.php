<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product = Product::all();

        return response()->json($product);
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
        $request->validate([
            "title" => "required",
            "new_price" => "required",
            "old_price" => "required",
            "category" => "required",
            "quantity" => "required",
            "desc" => "required",
            "image" => "nullable",
        ]);

        $title = $request->title;
        $new_price = $request->new_price;
        $old_price = $request->old_price;
        $category = $request->category;
        $quantity = $request->quantity;
        $desc = $request->desc;

        $product = new Product();

        $product->title = $title;
        $product->new_price = $new_price;
        $product->old_price = $old_price;
        $product->category_id = $category;
        $product->quantity = $quantity;
        $product->desc = $desc;


        $fileName = Str::random(10) . "." . $request->image->getClientOriginalExtension();
        if ($request->hasFile("image")) {
            Storage::disk("public")->put($fileName, file_get_contents($request->image));
            $product->image = $fileName;
        }

        $product->save();

        if ($product) {
            return response()->json([
                "status" => 200,
                "massage" => "Thêm sản phẩm thành công",
                "data" => $product
            ]);
        }

        return response()->json([
            "status" => 500,
            "massage" => "Thêm sản phẩm thất bại"
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $product = Product::where("category_id", 2)->get();
        return response()->json(
            $product
        );
    }

    public function show_child_product(){
        $product = Product::where("category_id", 1)
        ->Orwhere("category_id",3)->get();
        return response()->json(
            $product
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::where("id", $id)->first();

        return response()->json([$product]);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::destroy($id);
        return response()->json("Xóa thành công");
    }

    public function detail($id)
    {
        $detail=Product::where("id",$id)->first();
        return response()->json($detail);
    }

    public function relevant($id,$category)
    {
        $relevant=Product::where("category_id",$category)
        ->where("id","<>",$id)->get();
        return response()->json($relevant);
    }
}
