<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2016/5/24
 * Time: 20:51
 */

/**
 *  获取区域数据  GET /api_D3_getArea
 * 返回数据
 * |-data = [
       记录，
 *     记录
 *  ，，，，，，
 * ]
 */
Route::get("/api_D3_getArea", "Api\D3Controller@getArea");


/**
 *  获取环境数据  GET /api_D3_getEnvironment
 * 返回数据
 * |-data = [
     记录，
     记录
  ，，，，，，
 * ]
 */
Route::get("/api_D3_getEnvironment", "Api\D3Controller@getEnvironment");

