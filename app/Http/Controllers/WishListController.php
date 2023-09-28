<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class WishListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function cache(Request $request)
    {
        $id=$request->id;
        $user = auth("api")->user();
        if (Cache::has("wishlist")) {
            $wishlist=Cache::get("wishlist");
            $product=Product::where("id",$id)->first();

            $wishlist[]=[
                "id"=>$id,
                "title"=>$product->title,
                "image"=>$product->image,
                "new_price"=>$product->new_price,
                "user_id"=>$user->id
            ];

            Cache::put("wishlist",$wishlist,21600);
        }else{
            $product=Product::where("id",$id)->first();
            Cache::put("wishlist",[
                [
                    "id"=>$id,
                    "title"=>$product->title,
                    "image"=>$product->image,
                    "new_price"=>$product->new_price,
                    "user_id"=>$user->id
                ]
                ],21600);
        }

        $cacheWishlist=Cache::get("wishlist");

        return response()->json($cacheWishlist);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getWishlist(Request $request)
    {
        $user = auth("api")->user();
        $wishlist=Cache::get("wishlist");
        $currentUser=$user->id;

        $arrayWishlist=[];

        foreach ($wishlist as $value) {
            if ($value["user_id"]===$currentUser) {
                $arrayWishlist[]=[
                    "id"=>$value["id"],
                    "title"=>$value["title"],
                    "image"=>$value["image"],
                    "new_price"=>$value["new_price"]
                ];
            }
        }

        return response()->json($arrayWishlist);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
    public function destroy(Request $request)
    {
        $minute = 21600;
        $wishlist=Cache::get("wishlist");
        $user=auth("api")->user();
        $currentUser=$user->id;
        $id=$request->id;
        $updatedWishlist  = [];

        foreach($wishlist as $item){
           if ($item['user_id'] !== $currentUser || $item['id'] !== $id) {
            $updatedWishlist[] = $item;
        }
       
        }
        Cache::put("wishlist", $updatedWishlist, $minute);
        return response()->json("Delete thành công");
    }
}
