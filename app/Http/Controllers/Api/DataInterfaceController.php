<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2016/5/19
 * Time: 17:01
 */



namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use MyClass\Model\Goods;
use MyClass\Model\SpiderData;


class DataInterfaceController extends Controller
{



    /**
     * 爬虫的商品信息插入数据库  POST /api_data_interface_addCommodity
    需要发送
    |-name
    |-price
    |-detail
    |—url
    返回数据
    |-status 是否成功
    |-message 消息
    |-data   []
     */
    public function addCommodity()
    {
        $spiderData = Request::only("name","price","url","detail");
        $spiderData["price"] = (float)$spiderData["price"];

        $spider = new SpiderData();
        $returnAdd = $spider->addSpiderData($spiderData["name"],$spiderData["price"],$spiderData["detail"],$spiderData["url"]);

        if($returnAdd != false )
        {
            return response()->json(["status" => true, "message" => "添加商品成功", "data" => []]);
        }
        else {
            return response()->json(["status" => false, "message" => "添加商品失败", "data" => []]);
        }
    }


    /**
     * 爬虫的货物信息插入数据库  POST /api_data_interface_addGoods
     *  需要发送
    不定数据
    |-commodity_id
    |-area
    返回数据
    |-status 是否成功
    |-message 消息
    |-data   []
     */
    public function addGoods()
    {
        $goodsData = Request::only("commodity_id","rfid","two_dimension","bar_code");
        $goods = new Goods();
        $returnAdd = $goods -> addGoods($goodsData["rfid"],$goodsData["two_dimension"],$goodsData["bar_code"],$goodsData["area"]
        ,$goodsData["commodity_id"]);
        if($returnAdd != false)
        {
            return response()->json(["status" => true, "message" => "添加商品成功", "data" => []]);
        }
        else
        {
            return response()->json(["status" => false, "message" => "添加商品失败", "data" => []]);
        }
    }



}








