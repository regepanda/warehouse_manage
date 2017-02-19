<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2016/4/21
 * Time: 19:47
 */
Route::group(["middleware"=>["AdminLoginCheck"]],function() {


    /**
     * 获取商品信息
     * 无需发送数据
     * 返回数据
     * |-status
     * |-data = [
     *            {
        commodity表的一条记录
    },
    {...},
    {...}
    ]
     * |-message
     */
    Route::get("/commodity_manage_sCommodity", "Admin\Commodity\CommodityController@sCommodity");


    /**
     * 增加商品
     * 发送数据
     * |-commodity_name
     * |-commodity_price
     * |-commodity_model
     * |_commodity_class
     * |-commodity_detail
     * 返回数据
     * |-status
     * |-data = []
     * |-message
     */
    Route::post("/commodity_manage_aCommodity", "Admin\Commodity\CommodityController@aCommodity");


    /**
     * 修改商品
     * 发送数据
     * |-commodity_id
     * |-commodity_name
     * |-commodity_price
     * |-commodity_model
     * |_commodity_class
     * |_commodity_detail
     * 返回数据
     * |-status
     * |-data = []
     * |-message
     */
    Route::post("/commodity_manage_uCommodity", "Admin\Commodity\CommodityController@uCommodity");

    /**
     * 删除商品
     * 发送数据
     * |-commodity_id
     * 返回数据
     * |-status
     * |-data = []
     * |-message
     */
    Route::get("/commodity_manage_dCommodity", "Admin\Commodity\CommodityController@dCommodity");
});

