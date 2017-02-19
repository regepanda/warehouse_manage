<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2016/5/6
 * Time: 16:16
 */

Route::group(["middleware"=>["AdminLoginCheck"]],function() {

    /**
     * 获取设备信息
     * 无需发送数据
     * 返回数据
     * |-status
     * |-data = [
     *            {
    device表的一条记录
    },
    {...},
    {...}
    ]
     * |-message
     */
    Route::get("/admin_device_sDevice", "Admin\Device\DeviceController@sDevice");

    /**
     * 增加设备
     * 发送数据
     * |-device_name
     * |-device_type
     * |-self_id
     * |_device_intro
     * |-device_entrance 入口id
     * 返回数据
     * |-status
     * |-data = []
     * |-message
     */
    Route::post("/admin_device_aDevice", "Admin\Device\DeviceController@aDevice");
   // Route::get("/admin_device_aDevice", "Admin\Device\DeviceController@aDevice"); //测试

    /**
     * 修改设备
     * 发送数据
     * |-device_id
     * |-self_id
     * |-device_name
     * |-device_type
     * |_device_intro
     * 返回数据
     * |-status
     * |-data = []
     * |-message
     */
    Route::post("/admin_device_uDevice", "Admin\Device\DeviceController@uDevice");

    /**
     * 删除设备
     * 发送数据
     * |-device_id
     * 返回数据
     * |-status
     * |-data = []
     * |-message
     */
    Route::get("/admin_device_dDevice", "Admin\Device\DeviceController@dDevice");


    /**
     * 启用/禁用设备
     * 发送数据
     * |-device_id
     * |-device_use bool值
     * 返回数据
     * |-status
     * |-data = []
     * |-message
     */
    Route::get("/admin_device_uDeviceUse", "Admin\Device\DeviceController@uDeviceUse");

    /**
     * 获得入口信息
     * 发送数据
     * 返回数据
     * |-status
     * |-data = [
     * {一条入口记录}
     * ，，，，，，，
     * ]
     * |-message
     */
    Route::get("/admin_device_sDeviceEntrance", "Admin\Device\DeviceController@sDeviceEntrance");


    /**
     * 切换硬件状态，会尝试给硬件发消息禁用
     *
     */
    Route::post("/admin_device_toggleDevice","Admin\Device\DeviceController@toggleDevice");

});

