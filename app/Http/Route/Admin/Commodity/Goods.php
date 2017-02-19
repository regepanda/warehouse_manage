<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2016/5/8
 * Time: 20:32
 */


Route::group(["middleware"=>["AdminLoginCheck"]],function() {


    /**
     * 获取货物信息
     * 无需发送数据
     * 返回数据
     * |-status
     * |-data = [
     *            {
    goods表的一条记录
    },
    {...},
    {...}
    ]
     * |-message
     */
    Route::get("/goods_manage_sGoods", "Admin\Commodity\GoodsController@sGoods");


    /**
     * 获取区域信息
     * 无需发送数据
     * 返回数据
     * |-status
     * |-data = [
     *            {
    area表的一条记录
    },
    {...},
    {...}
    ]
     * |-message
     */
    Route::get("/goods_manage_sGoodsArea", "Admin\Commodity\GoodsController@sGoodsArea");


    /**
     * 增加货物
     * 发送数据
     * |-goods_rfid
     * |-goods_tow_dimension
     * |-goods_area
     * |-goods_commodity
     * |-bar_code
     * 返回数据
     * |-status
     * |-data = []
     * |-message
     */
    Route::post("/goods_manage_aGoods", "Admin\Commodity\GoodsController@aGoods");


    /**
     * 修改货物
     * 发送数据
     * |-goods_id
     * |-goods_status
     * |-goods_rfid
     * |-goods_tow_dimension
     * |-goods_bar_code
     * |-goods_area
     * 返回数据
     * |-status
     * |-data = []
     * |-message
     */
    Route::post("/goods_manage_uGoods", "Admin\Commodity\GoodsController@uGoods");

    /**
     * 删除货物
     * 发送数据
     * |-goods_id
     * 返回数据
     * |-status
     * |-data = []
     * |-message
     */
    Route::get("/goods_manage_dGoods", "Admin\Commodity\GoodsController@dGoods");

    Route::get("/www", "Admin\Commodity\GoodsController@www");
});

