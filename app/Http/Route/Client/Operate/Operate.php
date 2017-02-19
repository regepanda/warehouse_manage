<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2016/4/9
 * Time: 14:54
 */

/**
 * 操作界面首页
 */
Route::get("/client_operate_index","Client\Operate\OperateController@operateIndex");


/**
 * 获取正在处理的货物（查询device表的wait_handle的data的goods_id,然后用取到的goods_id取查询goods表，commodity表，commodity_class表)
 * 无需发送数据
 * 返回数据
 * |-status
 * |-data = [
 *            {
goods_id:id      货物id
commodity_name:    商品名
commodity_price:   商品价格
commodity_model:   商品型号
commodity_class:   商品类别
goods_status:status  货物状态
},
{...},
{...}
]
 * |-message
 */
Route::get("/client_operate_getGoodsDynamic","Client\Operate\OperateController@getGoodsDynamic");



/**
 * 人工入库,出库（改变货物的状态,删除操作会话中操作记录的相应数据）
 * 发送数据
 * |-goods_id 货物id
 * 返回数据
 * |-status
 * |-data = goods表的一条记录
 * |-message
 */
Route::post("/client_operate_uGoodsStatus","Client\Operate\OperateController@uGoodsStatus");


/**
 * 自动入库（改变货物的状态为1,删除操作会话中操作记录的相应数据）
 * 注意：用===或者!==来判断
 * 发送数据
 * |-goods_id 货物id
 * 返回数据
 * |-status
 * |-data=[]
 * |-message
*/
Route::post("/client_operate_autoUGoodsInStatus","Client\Operate\OperateController@autoUGoodsInStatus");

/**
 * 自动出库（改变货物的状态0,删除操作会话中操作记录的相应数据）
 * 注意：用===或者!==来判断
 * 发送数据
 * |-goods_id 货物id
 * 返回数据
 * |-status
 * |-data=[]
 * |-message
 */
Route::post("/client_operate_autoUGoodsOutStatus","Client\Operate\OperateController@autoUGoodsOutStatus");
