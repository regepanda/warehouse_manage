<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2016/4/6
 * Time: 13:44
 */


/**
 * 客户端等待会话轮询,客户端轮询请求这个地址，直到一个操作人员通过硬件设备的验证，开始一个会话
 * 无需请求值
 * 返回值
 * |-status = true|false 如果有会话true，其余情况false
 * |-data = {session_id:""}
 * |-message = "提示信息"
 *
 */
Route::post("/client_wait_waitSession","Client\Operate\WaitController@waitSession");

//Route::get("/client_wait_waitSession","Client\Operate\WaitController@waitSession"); //单测试用

/**
 * 客户端进入会话轮询后，通过轮询这个地址，获取是否有最新硬件信息到达服务器
 * 无需请求值
 * 返回值
 * |-status = true|false 如果有最新的请求为true，其余情况是false
 * |-data = [{商品操作记录信息}]
 * |-message = "提示信息"
 */
//Route::post("/client_wait_waitOperate","Client\Operate\WaitController@waitOperate");
//Route::get("/client_wait_waitOperate","Client\Operate\WaitController@waitOperate"); //单测试用

/**
 * 登出会话接口，请求这个接口后，服务器会关闭当前的会话，清理相关信息，客户端也会应该回到等待会话的轮询状态
 * 无需请求任何值
 * 返回值
 * |-status = true|false
 * |-data = null
 * |-message = "提示信息"
 */
Route::get("/client_wait_logoutSession","Client\Operate\WaitController@logoutSession");


