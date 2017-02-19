<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2016/5/19
 * Time: 17:02
 */



/**
 * 爬虫的商品信息插入数据库  POST /api_data_interface_addCommodity
 *  需要发送
|-name
|-price
|-detail
|—url
返回数据
|-status 是否成功
|-message 消息
|-data   []
 */
Route::post("/api_data_interface_addCommodity","Api\DataInterfaceController@addCommodity");

/**
 * 爬虫的货物信息插入数据库  POST /api_data_interface_addGoods
 *  需要发送
   不定数据
|_commodity_id
|-rfid
|-two_dimension
|-bar_code
|-area
返回数据
|-status 是否成功
|-message 消息
|-data   []
 */
Route::post("/api_data_interface_addGoods","Api\DataInterfaceController@addGoods");
