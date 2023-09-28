<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function check_user_comment($id)
    {
        $user=auth("api")->user();

        $comment=Comment::where("product_id",$id)->get();
        $status=false;
        foreach ($comment as $value) {
            if ($value->product_id==$id && $value->user_id==$user->id) {
                $status=true;
                break;
            }
        }
        

        return response()->json($status);
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
            "id_product"=>"required",
            "comment"=>"required",
            "rating"=>"nullable",
            "image"=>"nullable"
        ]);

        $user=auth("api")->user();
        $id_product=$request->id_product;
        $content=$request->comment;
        $rating=$request->rating;

        $comment=new Comment();
        $fileName = Str::random(10) . "." . $request->image->getClientOriginalExtension();
        if ($request->hasFile("image")) {
            Storage::disk("public")->put($fileName, file_get_contents($request->image));
            $comment->image = $fileName;
        }
        if ($rating==0) {
            $comment->start=null;
        }
        $comment->start=$rating;
        $comment->comment=$content;
        $comment->product_id=$id_product;
        $comment->user_id=$user->id;
        $comment->save();

        return response()->json("Bình luận thành công");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
{
    $comment = Comment::where("product_id", $id)->get();
    $all_product_comment = [];
    $total_start = 0;

    foreach ($comment as $value) {
        $all_product_comment[] = [
            "comment" => $value["comment"],
            "image"=>$value["image"],
            "start" => $value["start"]
        ];

        $total_start += $value["start"];
    }

    $rating = 0;
    
    if ($comment->count() > 0) {
        $rating = intval($total_start / $comment->count());
    }

    return response()->json([
        "comments" => $all_product_comment,
        "rating" => $rating
    ]);
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
    public function destroy($id)
    {
        //
    }
}
