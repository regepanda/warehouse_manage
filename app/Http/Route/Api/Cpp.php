<?php
/**
 * Created by PhpStorm.
 * User: ragpanda
 * Date: 16-5-7
 * Time: 下午5:43
 */
Route::post("/api_cpp_startSession","Api\CppController@startSession");

/**
 *c++模块处理完成，发送相关记录回来
 *需要发送
 *|-intro
 *|-detail
 *|-data
 *|-level 等级
 *|-otherData 健值对数组
 */
Route::post("/api_cpp_receiveLog","Api\CppController@receiveLog");