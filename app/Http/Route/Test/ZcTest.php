<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2016/4/2
 * Time: 19:18
 */

Route::get("/test_zc_mongoDBLinkTest","Test\ZcController@mongoDBLinkTest");
Route::get("/test_zc_commodityClassTest","Test\ZcController@commodityClassTest");
Route::get("/test_zc_commodityLabelTest","Test\ZcController@commodityLabelTest");
Route::get("/test_zc_monitorTest","Test\ZcController@monitorTest");
Route::get("/test_zc_logTest","Test\ZcController@logTest");

Route::get("/test_zc_logTest","Test\ZcController@logTest");

Route::get("/test_zc_deviceTest","Test\ZcController@deviceTest");

//插入数据
Route::get("/test_zc_addData","Test\ZcController@addData");

Route::get("/test_zc_addGoods","Test\ZcController@addGoods");

Route::get("/test_zc_deleteArea","Test\ZcController@deleteArea");


//用于d3测试用的数据库
Route::get("/zc_addArea","Test\ZcController@d3_Area");

Route::get("/zc_d3_request","Test\ZcController@requestArea");

Route::get("/zc_d3_requestEn","Test\ZcController@requestEn");

Route::get("/zc_d3_addEnvironment","Test\ZcController@addEnvironment");

