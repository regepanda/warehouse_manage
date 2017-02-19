<?php
/**
 * Created by PhpStorm.
 * User: RagPanda
 * Date: 2016/3/29
 * Time: 15:22
 */


/**
 * 终端扫描后，发送相关数据，启动一个会话
 * 数据
 * |-id     设备id
 * |-type   凭证类型(3:人脸识别，4:RFID，5:FINGER)
 * |-data   凭证数据
 * 返回数据
 * |-status
 * |-data = []
 * |-message
 */
Route::post("/api_device_startSession","Api\DeviceController@startSession");
//Route::get("/api_device_startSession","Api\DeviceController@startSession");  //测试


/**
 * 终端推入一个数据，根据数据格式决定策略
 * 数据
 * |-id 设备id,Android后台做相关处理
 * |-type       设备数据类型
 * |-data       数据（被扫描的货物的相关数据）
 * 返回数据
 * |-status
 * |-data = []
 * |-message
 */
Route::post("/api_device_putData","Api\DeviceController@putData");
//Route::get("/api_device_putData","Api\DeviceController@putData");  //测试


/**
 *
 *硬件发送当前环境信息
 *通常是ZigBee发送给服务器的环境信息
 * 发送数据
 * |-id    self_Id,设备id，
 * |-type  设备数据类型(通常是"ZIGBEE")
 * |-data  7位数
 */
Route::post("/api_device_recvMonitor","Api\DeviceController@recvMonitor");



/**
 * 硬件轮询这个接口获取命令
打印数据
true(状态，如果有指令就是true,没有指令或者错误false)\n
你妈炸了(返回信息)\n
start(这一排是指令)\n
finger_1(这一排是设备id)\n
 */

Route::post("/api_device_getInstruction","Api\DeviceController@getInstruction");
//Route::get("/api_device_getInstruction","Api\DeviceController@getInstruction");  //测试用

