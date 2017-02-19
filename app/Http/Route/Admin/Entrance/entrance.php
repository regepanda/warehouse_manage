<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2016/5/7
 * Time: 19:31
 */

Route::group(["middleware"=>["AdminLoginCheck"]],function() {



    /**
     * 获取入口信息
     * 无需发送数据
     * 返回数据
     * |-status
     * |-data = [
     *            {
    entrance表的一条记录
    },
    {...},
    {...}
    ]
     * |-message
     */
    Route::get("/entrance_manage_sEntrance", "Admin\Entrance\EntranceController@sEntrance");


    /**
     * 增加入口
     * 发送数据
     * |-entrance_name
     * |-entrance_login_name
     * |-entrance_password
     * 返回数据
     * |-status
     * |-data = []
     * |-message
     */
    Route::post("/entrance_manage_aEntrance", "Admin\Entrance\EntranceController@aEntrance");


    /**
     * 修改入口
     * 发送数据
     * |-entrance_id
     * |-entrance_name
     * |-entrance_login_name
     * 返回数据
     * |-status
     * |-data = []
     * |-message
     */
    Route::post("/entrance_manage_uEntrance", "Admin\Entrance\EntranceController@uEntrance");

    /**
     * 删除入口
     * 发送数据
     * |-entrance_id
     * 返回数据
     * |-status
     * |-data = []
     * |-message
     */
    Route::get("/entrance_manage_dEntrance", "Admin\Entrance\EntranceController@dEntrance");

    /**
     * 获取入口设备
     * 返回数据
     * |-status
     * |-data = [
     * {相应设备的一条记录}
     * ，，，，
     * ]
     * |-message
     */
    Route::get("/entrance_manage_sEntranceDevice", "Admin\Entrance\EntranceController@sEntranceDevice");


    /**
     * 移除入口设备
     * 发送数据
     * |-entrance_id
     * |-device_id
     * 返回数据
     * |-status
     * |-data = []
     * |-message
     */
    Route::get("/entrance_manage_removeEntranceDevice", "Admin\Entrance\EntranceController@removeEntranceDevice");



});