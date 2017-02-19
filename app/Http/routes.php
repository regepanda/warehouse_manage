<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*
Route::get('/', function () {
   dump(\MyClass\Facade\MongoDBConnection::link()->selectCollection("user"));
    dump(\MyClass\Facade\MongoDBConnection::collection("user"));

});
*/

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(["middleware"=>['api']],function(){
    require __DIR__."/Route/apiLoad.php";
});
Route::group(['middleware' => ['web']], function () {
    require __DIR__.'/Route/webLoad.php';
});
require __DIR__."/Route/Test/load.php";


