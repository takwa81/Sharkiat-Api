<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ConfigController;
use App\Models\Product ;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;


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


/********************PUBLIC API********************/
/***************************************************/

//Auth Api //
Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);

//Products Api //
Route::get('/products',[ProductController::class,'index']);
Route::get('/product/{id}',[ProductController::class,'show']);
Route::get('/products/search/{name}',[ProductController::class,'search']);

//Categories Api//
Route::get('/categories' ,[CategoryController::class , 'index']);
Route::get('/category/{id}' ,[CategoryController::class , 'show']);
Route::get('/categories/search/{name}' ,[CategoryController::class , 'search']);

//Config Api //
Route::get('/config',[ConfigController::class,'index']);


/********************Private Api********************/
/***************************************************/
Route::group(['middleware'=>['auth:sanctum']], function () {
    Route::post('/product' ,[ProductController::class , 'store']);
    Route::post('/updateProduct/{id}',[ProductController::class,'updateProduct']);
    Route::delete('/product/{id}',[ProductController::class,'destroy']);
    Route::post('/category' ,[CategoryController::class , 'store']);
    Route::post('/updateCategory/{id}' ,[CategoryController::class , 'updateCategory']);
    Route::delete('/category/{id}' ,[CategoryController::class , 'destroy']);
    Route::put('/config',[ConfigController::class,'store']);
    Route::post('/logout',[AuthController::class,'logout']);

});


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });