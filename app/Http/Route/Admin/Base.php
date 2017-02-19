<?php
/**
 * Created by PhpStorm.
 * User: RagPanda
 * Date: 2016/3/28
 * Time: 10:50
 */

//管理员登录
Route::get('/admin', function () {
    return redirect("/admin_index");
});

//管理员登录中间件过滤
Route::group(["middleware"=>["AdminLoginCheck"]],function(){
    Route::get("admin_index","Admin\BaseController@index");
    Route::get("admin_commodity_manage","Admin\BaseController@commodityManage");
    Route::get("admin_power_manage","Admin\BaseController@powerManage");
    Route::get("admin_device_manage","Admin\BaseController@deviceManage");
    Route::get("admin_entrance_manage","Admin\BaseController@entranceManage");
    Route::get("admin_log_manage","Admin\BaseController@logManage");
    Route::get("admin_visual_manage","Admin\BaseController@visualManage");
});

//登录界面
Route::get("admin_login","Admin\BaseController@login");
//登录管理员名，密码验证
Route::post("_admin_login","Admin\BaseController@_login");
//管理员登出
Route::get("/admin_logout","Admin\BaseController@logout");






