<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/home', function () {
    return redirect("/");
});

Route::get('/',[UserController::class,"index"])->name("main");

Route::group(['middleware' => ['testing']], function () {

    Route::get('/user',[UserController::class,"users"])->name("users");
    Route::get('/create',[UserController::class,"create"])->name("user.create");
    Route::POST('/store',[UserController::class,"store"])->name("user.store");
    Route::get('/user/edit/{id}',[UserController::class,"edit"])->name("user.edit");
    Route::POST('/user/update/{id}',[UserController::class,"update"])->name("user.update");
    Route::POST('/user/delete',[UserController::class,"delete"])->name("user.delete");

});

Route::group(['middleware' => ['guest']],function(){

    // Authentication
    Route::get('/login',[UserController::class,"login"])->name("login");
    Route::POST('/login',[UserController::class,"login_store"])->name("login.store");
    Route::get('/register',[UserController::class,"register"])->name("register");
    Route::POST('/register',[UserController::class,"register_store"])->name("register.store");

});

Route::get('/logout',[UserController::class,"logout"])->name("logout");



