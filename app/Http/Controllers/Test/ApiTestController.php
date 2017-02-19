<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2016/4/9
 * Time: 16:37
 */

namespace App\Http\Controllers\Test;


use App\Http\Controllers\Controller;
use MyClass\Model\Device;
use MyClass\Model\Goods;
use  MyClass\Model\Entrance;

class ApiTestController extends Controller
{

    public function apiTest()
    {
        return view("ApiTest.ApiTest");
    }

    public function addGoodsData()
    {
        $entrance = new Entrance(session("entrance.entrance_id"));
        //此入口的device
        if($entrance ->isNull() == false)
        {
            $devices = $entrance ->info["device"];
            $i = 0;
            foreach($devices as $device)
            {
                if($i == 3)
                {
                    break;
                }
                $deviceId = $device["id"];
                $newDevice = new Device($deviceId);
                if($newDevice ->isNull() != false)
                {
                    return response()->json(["status" => false, "data" => [], "message" => "加入失败"]);
                }
                //查goods表相应的id
                $goods = new Goods();
                $queryLimit["desc"] = true;
                $returnGoods = $goods  ->select($queryLimit);
                foreach($returnGoods["data"] as $single)
                {
                    $goodsId = $single["_id"];
                    $data["goods_id"] = $goodsId;

                    $returnPush = $newDevice -> pushWaitHandle($data);
                    if($returnPush != false)
                    {
                        if($i == 3)
                        {
                            return response()->json(["status" => true, "data" => [], "message" => "加入成功"]);
                        }
                        $i ++;
                    }
                }
            }
        }
        else
        {
            return response()->json(["status" => false, "data" => [], "message" => "加入失败"]);
        }

    }


}