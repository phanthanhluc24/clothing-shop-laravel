<?php

use App\Http\Controllers\ArrayController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WishListController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get("/nab_bar",[MenuController::class,"index"]);
Route::post("/register",[AuthenticationController::class,"register"]);
Route::post("/login",[AuthenticationController::class,"login"]);
Route::get("/currentUser",[AuthenticationController::class,"currentUser"]);
Route::post("/addproduct",[ProductController::class,"store"]);
Route::get("/getproducts",[ProductController::class,"index"]);
Route::get("/getproduct/{id}",[ProductController::class,"edit"]);
Route::delete("/deleteproduct/{id}",[ProductController::class,"destroy"]);
Route::get("/getcategory2",[ProductController::class,"show"]);
Route::get("/getcategory13",[ProductController::class,"show_child_product"]);
Route::get("/detail/{id}",[ProductController::class,"detail"]);
Route::get("/relevant/{id}/{category}",[ProductController::class,"relevant"]);
Route::post("/cartstore",[CartController::class,"cache"]);
Route::get("/cart",[CartController::class,"getCart"]);
Route::post("/store_cart",[CartController::class,"store"]);
Route::delete("/deleteItem",[CartController::class,"destroy"]);
Route::get("/cart/{id}",[CartController::class,"payment"]);
Route::get("/readPdf",[PDFController::class,"convertPdfToText"]);

Route::get("/wishlist",[WishListController::class,"getWishlist"]);
Route::post("/store_wishlist",[WishListController::class,"cache"]);
Route::delete("/removeWishlist",[WishListController::class,"destroy"]);

Route::post("/postPdf",[PDFController::class,"storePdf"]);
Route::get("/getCv",[PDFController::class,"getCv"]);
Route::post("/post_comment",[CommentController::class,"store"]);
Route::get("/get_user_comment/{id}",[CommentController::class,"check_user_comment"]);
Route::get("/get_all_comment/{id}",[CommentController::class,"show"]);


// Learn laravel basic

Route::get("/array",[ArrayController::class,"index"]);
Route::get("/sort",[ArrayController::class,"sort"]);
Route::get("/filter",[ArrayController::class,"show"]);
Route::get("/readLine",[ArrayController::class,"getContenttext"]);
