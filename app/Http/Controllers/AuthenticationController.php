<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $request->validate([
            "fullname"=>"required",
            "email"=>"required",
            "phone"=>"required",
            "password"=>"required"
        ]);

        $fullname=$request->fullname;
        $email=$request->email;
        $phone=$request->phone;
        $password=$request->password;

        $user=new User();
        $user->fullname=$fullname;
        $user->email=$email;
        $user->phone=$phone;
        $user->password=bcrypt($password);
        $user->type=0;
        $user->role="USR";
        $user->save();
        if (!$user) {
            return response()->json([
                "status"=>500,
                "massege"=>"Tạo tài khoản thất bại"
            ]);
        }

        return response()->json([
            "status"=>200,
            "massege"=>"Tạo tài khoản thành công",
            "user"=>$user
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $request->validate([
            "email"=>"required",
            "password"=>"required"
        ]);

        $credentials=$request->only("email","password");

        if (!Auth::attempt($credentials)) {
            return response()->json([
                "status"=>500,
                "massage"=>"Đăng nhập thất bại"
            ]);
        }
        $token = Auth::guard('api')->attempt($credentials);

        if (!$token) {
            return response()->json([
                "status"=>500,
                "massage"=>"Không thể lấy token"
            ]);
        }

        return response()->json([
            "status"=>200,
            "massage"=>"Đăng nhập thành công",
            "token"=>$token
        ])->cookie("access_token",$token,3600,null,null,false,true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function currentUser(Request $request)
    {
        $user=auth("api")->user();

        return response()->json($user);
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
    public function destroy($id)
    {
        //
    }
}
