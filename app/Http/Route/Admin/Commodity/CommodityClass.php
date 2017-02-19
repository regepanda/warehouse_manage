<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2016/4/21
 * Time: 19:47
 */
Route::group(["middleware"=>["AdminLoginCheck"]],function() {

    /**
     * 获取商品类信息
     * 无需发送数据
     * 返回数据
     * |-status
     * |-data = [
     *            {
    commodityClass表的一条记录
    },
    {...},
    {...}
    ]
     * |-message
     */
    Route::get("/commodity_manage_sCommodityClass", "Admin\Commodity\CommodityClassController@sCommodityClass");



    /**
     * 增加商品类
     * 发送数据
     * |-commodity_class_name
     * 返回数据
     * |-status
     * |-data = []
     * |-message
     */
    Route::post("/commodity_manage_aCommodityClass", "Admin\Commodity\CommodityClassController@aCommodityClass");

    /**
     * 修改商品类
     * 发送数据
     * |-commodity_class_id
     * |-commodity_class_name
     * 返回数据
     * |-status
     * |-data = []
     * |-message
     */
    Route::post("/commodity_manage_uCommodityClass", "Admin\Commodity\CommodityClassController@uCommodityClass");


    /**
     * 删除商品类
     * 发送数据
     * |-commodity_class_id
     * 返回数据
     * |-status
     * |-data = []
     * |-message
     */
    Route::get("/commodity_manage_dCommodityClass", "Admin\Commodity\CommodityClassController@dCommodityClass");
    /**
     * 获取所有区域信息
     * 发送数据
     * |-无
     * 返回数据
     * |-status
     * |-data = []
     * |-message
     */
    Route::get("/commodity_manage_getArea", "Admin\Commodity\CommodityClassController@getArea");
});