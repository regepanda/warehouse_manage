<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2016/4/6
 * Time: 13:44
 */


/**
 * 查看所有记录
 */
Route::get("/admin_log_sAllLog","Admin\Log\LogController@sAllLog");
/**
 * 查看详情
 */
Route::get("/admin_log_sDetailLog","Admin\Log\LogController@sDetailLog");