<?php
/**
 * Created by PhpStorm.
 * User: RagPanda
 * Date: 2016/3/9
 * Time: 9:26
 */

Route::get("/test_wjt_mongoDBLinkTest","Test\WjtController@mongoDBLinkTest");
Route::get("/test_wjt_adminTest","Test\WjtController@adminTest");
Route::get("/test_wjt_commodityTest","Test\WjtController@commodityTest");

//device测试
Route::get("/test","Test\WjtController@test");

Route::get("/test_wjt_polling","Test\WjtController@polling");
Route::post("/_test_wjt_polling","Test\WjtController@_polling");

//redis module 测试
Route::get("/test_wjt_moduleCpp","Test\WjtController@moduleCpp");

//db join test
Route::get("/test_wjt_dbJoinExt","Test\WjtController@dbJoinExt");

//test Cache
Route::get("/test_wjt_cacheTest","Test\wjtController@cacheTest");


Route::get("/test_wjt_dataSetTest","Test\wjtController@dataSetTest");