<?php
/**
 * Created by PhpStorm.
 * User: RagPanda
 * Date: 2016/4/25
 * Time: 14:03
 */


/**
 * 3.1 登录接口  POST /api_app_entranceLogin
 *  需要发送
    |-username
    |-password
    返回数据
    |-status 是否成功
    |-message 消息
    |-data      accessToken存在这里面，是一个40位字符串(成功时)
 *              ""(失败时，返回空字符串)
 */
Route::post("/api_app_entranceLogin","Api\AppController@entranceLogin");

//Route::get("/api_app_entranceLogin","Api\AppController@entranceLogin"); //测试用

Route::group(/**
 *
 */
    ["middleware"=>["AccessTokenCheck"]],function()
{
    /**
     * 3.2 查询当前是否有最新的可用会话 GET /api_app_waitSession
     * 需要发送
     * |-access_token 登录的token
     * 返回数据
     * |-status 是否有最新的会话
     * |-message 描述信息
     * |-data =
     * {
     * session_id:string   会话id
     * operator_id:string  操作员id
     * operator_name:string 操作员名字
     * status: int         状态
     * }返回session的id
     *
     */
    Route::get("/api_app_waitSession", "Api\AppController@waitSession");

    /**
     * 3.3 关闭一个会话   GET /api_app_finishSession
     * 需要发送
     * |-access_token 登陆的token
     * |-session_id   需要关闭的会话id
     *
     * 返回数据
     * |-status  是否成功
     * |-message 描述信息
     * |-data    null 空
     *
     */
    Route::get("/api_app_finishSession", "Api\AppController@finishSession");


    /**
     *  3.4 获取最新商品的扫描数据   GET /api_app_getScanGoods
     * 需要发送
     * |-access_token 登录的token
     * |-session_id   当前会话id
     *
     * 返回数据
     * |-status 是否成功
     * |-message 描述信息
     * |-data = [
     * {
     * goods_id:id         货物id
     * commodity_name:    商品名
     * commodity_price:   商品价格
     * commodity_model:   商品型号
     * commodity_class:   商品类别
     * goods_status:status  货物状态
     * },
     * .......
     * ]
     */
    Route::get("/api_app_getScanGoods", "Api\AppController@getScanGood");


    /**
     * 3.5 处理货物   POST /api_app_updateGoods
     * 需要发送
     * |-access_token 登陆的token
     * |-session_id   当前会话id
     * |-goods_id     待处理货物的id
     * |-goods_status 货物需要更改到的状态
     *
     * 返回数据
     * |-status 是否成功
     * |-message 描述信息
     * |-data = 更新后的状态id
     *
     * 目前货物状态有三种，对应int
     * 0 库外
     * 1 库内
     * 2 冻结（冻结的商品应该在库内）
     */
    Route::post("/api_app_updateGoods", "Api\AppController@updateGoods");



    /**
     *  3.6 获取自己这个入口的设备  GET /api_app_sDevice
     * 需要发送
     *  |-access_token 登陆的token
     *
     *  返回数据
     * |-status  是否成功
     * |-message
     * |-data = [
     * {
     *  self_id: Android传来的设备id
     *  type:  设备类型
     *  name:  设备名
     * },
     *.......
     * ]
     */
    Route::get("/api_app_sDevice", "Api\AppController@sDevice");


    /**
     * 3.7 获取记录信息 GET /api_app_sLog
       需要发送
       |-access_token 登录的token

       返回数据
       |-status 是否成功
       |-message 消息
       |-data    数据
       data=
       [
        {
            intro:简介,
            detail：详情,
            data：数据
        }

       ]
     */
    Route::get("/api_app_sLog", "Api\AppController@sLog");


    /**
     * ## 3.8 获取缓存数据 GET /api_app_sOperatorCache
        需要发送
        |-offset 需要多少条以后的，默认0
        |-session_id 会话id
        |-access_token 登录的token

        返回数据
        data=
        [
            {

            goods_id:string,
            commodity_name:string,
            aim_status:1/0,
            model:string,
            price:float,
            area:string,//分配目标区域，
            }
        ]
     *
     */
    Route::get("/api_app_sOperatorCache","Api\AppController@sOperatorCache");

    /**
     * ## 3.9 删除一条缓存记录 POST /api_app_delOperatorCache
    需要发送
    |-id 缓存记录的goods_id
    |-session_id 会话id
    |-access_token 登录的token
    返回数据
    |-status 是否成功
    |-message  消息
    |-data=""    数据

     */
    Route::post("/api_app_delOperatorCache","Api\AppController@delOpertorCache");


    /**
     * ## 3.10 提交缓存 POST /api_app_commitOperatorCache
        需要发送
        |-session_id 会话id
        |-access_token 登录token
        提交这个会话的缓存
        返回数据
        |-status 是否成功
        |-message 描述
        |-data =""

     *
     */
    Route::post("/api_app_commitOperatorCache","Api\AppController@commitOperatorCache");


});