<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2016/4/9
 * Time: 16:35
 */

Route::get("/api_test","Test\ApiTestController@apiTest");
Route::get("/api_addGoodsData","Test\ApiTestController@addGoodsData");