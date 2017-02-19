<?php
/**
 * Created by PhpStorm.
 * User: RagPanda
 * Date: 2016/3/29
 * Time: 16:29
 */


//用户登录
Route::get(
    '/user', function () {
    return redirect("/user_index");
});


//用户登录中间件过滤
Route::group(
    ["middleware"=>["UserLoginCheck"]],function(){
    Route::get("/user_index","Client\BaseController@index");

    /**
     * 获取会话值
     * |-key 关键字
     * 返回值
     * |-status true|false
     * |-data   数据，即具体的值
     * |-message “消息”
     *
     */
    Route::get("/client_base_getSessionVal","Client\BaseController@getSessionVal");



    /**
     * 获取当前登录的操作员信息+头像
     * 无发送数据
     * 返回数据
     * |-status
     * |-data={
     *  operator_username:操作用户名
     *  operator_name:操作员名
     *  operator_image:操作员头像id
     * }
     * |-message
     */
    Route::get("/client_base_getOperator","Client\BaseController@getOperator");
});

/**
 * 客户端（出入口：Entrance）登录，识别自己是哪一个入口的客户端
 *
 */
Route::get("/client_base_login","Client\BaseController@login");
Route::post("/_client_base_login","Client\BaseController@_login");

/**
 * 客户端登出，浏览器/客户端操作不再代表这一个入口了
 *
 */
Route::get("/client_base_logout","Client\BaseController@logout");

