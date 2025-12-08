<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Middleware\CorsMiddleware;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

//user
Route::get('/users', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post("/register" , [AuthController::class , "register"]);
Route::post("/login" , [AuthController::class , "login"]);
Route::post("/logout" , [AuthController::class , "logout"])->middleware('auth:sanctum');



Route::middleware(['signed'])->group(function () {
    Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class , "verify"])->name('verification.verify');
});


// // // ///////////


Route::middleware("auth:sanctum")->group(function(){
    Route::get("/cart",[CartController::class , "index"])->name("api.cart.index");
    Route::get("/cart/{cartItem}",[CartController::class , "show"])->name("api.cart.show");
    Route::post("/cart/{productId}",[CartController::class , "add"])->name("api.cart.add");
    Route::put("/cart/update/{product}",[CartController::class , "update"])->name("api.cart.update");
    Route::delete("/cart/remove/{item}",[CartController::class , "remove"])->name("api.cart.remove");
    Route::delete("/cart/clear",[CartController::class , "clear"])->name("api.cart.clear");
});

Route::middleware("auth:sanctum")->group(function(){
    Route::post("/checkout" , [OrderController::class , "processCheckout"]);
    Route::get("/orders" , [OrderController::class , "index"]);
    Route::get("/orders/{order}" , [OrderController::class , "show"]);
});

Route::apiResource("/category" , CategoryController::class);
Route::delete("/deleteallcategories" , [CategoryController::class , "deleteAllCategories"]);


Route::apiResource("/product" , ProductController::class);
Route::delete("/deleteallproduct" , [ProductController::class , "deleteAllProduct"]);

//admin
Route::post("/signup" , [AdminController::class , "register"]);
Route::post("/signin" , [AdminController::class , "login"]);
Route::post("/Logout" , [AdminController::class , "logout"])->middleware('auth:sanctum');

Route::get('/admin', function (Request $request) {
    return $request->admin();
})->middleware('auth:sanctum');
