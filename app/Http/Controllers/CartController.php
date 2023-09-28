<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CartController extends Controller
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
    $minute = 21600;
    $id = $request->id;
    $quantity = $request->quantity;
    $user = auth("api")->user();

    if (!$user) {
        return response()->json([
            "message" => "User not authenticated."
        ], 401);
    }

    if (Cache::has("cached_products")) {
        $cachedProducts = Cache::get("cached_products");
        $productIndex = array_search($id, array_column($cachedProducts, 'id'));

        if ($productIndex !== false) {
            $cachedProducts[$productIndex]['quantity'] += $quantity;
        } 
        else {
            $shopping_cart=Product::where("id",$id)->first();
            $cachedProducts[] = [
                'id' => $id,
                'quantity' => $quantity,
                'user_id' => $user->id,
                'title'=>$shopping_cart->title,
                'new_price'=>$shopping_cart->new_price,
                'image'=>$shopping_cart->image,
            ];
        }

        Cache::put("cached_products", $cachedProducts, $minute);
    } else {
        // Nếu chưa có cached_products, tạo một mảng mới chứa sản phẩm
        $shopping_cart=Product::where("id",$id)->first();
        Cache::put("cached_products", [
            [
                'id' => $id,
                'quantity' => $quantity,
                'title'=>$shopping_cart->title,
                'new_price'=>$shopping_cart->new_price,
                'image'=>$shopping_cart->image,
                'user_id' => $user->id,
            ]
        ], $minute);
    }

    // Lấy dữ liệu từ cache sau khi đã thêm vào
    $cachedProducts = Cache::get('cached_products');

    return response()->json(
        $cachedProducts
    );
}


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getCart()
    {
        $cart=Cache::get("cached_products");
        $user=auth("api")->user();

        $currentUser=$user->id;
        
        $filteredProducts  = [];
        foreach($cart as $items){
            if ($items['user_id']===$currentUser) {
                $filteredProducts []=[
                    "id"=>$items["id"],
                    "user_id"=>$items["user_id"],
                    "quantity"=>$items["quantity"],
                    "image"=>$items["image"],
                    "new_price"=>$items["new_price"],
                    "title"=>$items["title"]
                ];
            }
        }
        return response()->json(
        $filteredProducts
        );
    }


    public function payment($id)
    {
        $cart=Cache::get("cached_products");
        $user=auth("api")->user();

        $currentUser=$user->id;
        
        $filteredProducts  = [];
        foreach($cart as $items){
            if ($items['user_id']===$currentUser && $items["id"]===$id) {
                $filteredProducts []=[
                    "id"=>$items["id"],
                    "user_id"=>$items["user_id"],
                    "quantity"=>$items["quantity"],
                    "image"=>$items["image"],
                    "new_price"=>$items["new_price"],
                    "title"=>$items["title"]
                ];
            }
        }
        return response()->json(
        $filteredProducts
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            "province"=>"required",
            "districts"=>"required",
            "wrars"=>"required",
            "address"=>"required",
            "id"=>"required",
            "new_price"=>"required"
        ]);

        $province=$request->province;
        $districts=$request->districts;
        $wrars=$request->wrars;
        $address=$request->address;
        $new_price=$request->new_price;
        $id=$request->id;
        $user_id=auth("api")->user();

        $add_address=new Address();
        $add_address->user_id=$user_id->id;
        $add_address->province=$province;
        $add_address->district=$districts;
        $add_address->commune=$wrars;
        $add_address->address=$address;
        $add_address->save();

        $cart=new Order();
        $cart->user_id=$user_id->id;
        $cart->product_id=$id;
        $cart->quantity=1;
        $cart->total=1*intval(floatval($new_price) * 100);
        $cart->save();

        if ($cart) {
            Cache::tags(["cart"])->forget($id);
        }

        if ($add_address && $cart) {
            return response()->json([
                "Thanh cong"
            ]);
        }
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
        $cart=Cache::get("cached_products");
        $user=auth("api")->user();
        $currentUser=$user->id;
        $id=$request->id;
        $updatedCart  = [];

        foreach($cart as $item){
           if ($item['user_id'] !== $currentUser || $item['id'] !== $id) {
            $updatedCart[] = $item;
        }
       
        }
        Cache::put("cached_products", $updatedCart, $minute);
        return response()->json("Delete thành công");
    }
}
