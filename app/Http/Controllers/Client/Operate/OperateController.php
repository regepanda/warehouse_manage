<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2016/4/9
 * Time: 15:11
 */

namespace App\Http\Controllers\Client\Operate;

use App\Http\Controllers\Controller;
use MyClass\Model\Commodity;
use MyClass\Model\CommodityClass;
use MyClass\Model\Device;
use MyClass\Model\Entrance;
use MyClass\Model\Goods;
use Illuminate\Support\Facades\Request;
use MyClass\Model\OperatorSession;

class OperateController extends Controller
{
    /**
     * 显示操作界面首页
     */
    public function operateIndex()
    {
        return view("Client.operate");
    }


    /**
     * 获取正在处理的货物（1.查询device表的wait_handle的data的goods_id,然后用取到的goods_id取查询goods表，commodity表，commodity_class表
                           2.把数据返回后清空wait_handle )
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
    public function getGoodsDynamic()
    {
        $maxTime = 10;
        //查询出当前入口的devive
        $entrance = new Entrance(session("entrance.entrance_id"));

        if ($entrance->isNull())
        {
            return response()->json(["status" => false, "message" => "获取被扫描货物失败", "data" => [] ]);
        }
        $data = [];

        $entranceDatas = $entrance->info["device"];


        $it = 0;
        while($it<$maxTime)
        {
            foreach ($entranceDatas as $entranceData)
            {


                $deviceId = $entranceData["id"];
                //查询device
                $device = new Device($deviceId);
                if($device->isNull())
                {
                    continue;
                }
                $wait_handles = $device->info["wait_handle"];

                foreach ($wait_handles as $wait_handle)
                {
                    //货物信息处理
                    $goods_id = $wait_handle["data"]["goods_id"]->__toString();     //得到一条货物的id

                    $goods = new Goods($goods_id);      //实例化货物
                    if($goods->isNull())                //是否合法
                    {
                        continue;
                    }

                    $single["goods_id"] = $goods->info["ID"];                   //将货物信息写入
                    $single["goods_status"] = $goods->info["status"];


                    //货物商品信息处理
                    $commodityId = $goods->info["commodity"];
                    $commodity = new Commodity($commodityId);
                    if($commodity->isNull())
                    {
                        $data[] = $single;
                        continue;
                    }
                    $single["commodity_name"] = $commodity->info["name"];   //将商品信息写入
                    $single["commodity_price"] = $commodity->info["price"];
                    $single["commodity_model"] = $commodity->info["model"];


                    //商品类型处理
                    $commodityClassId = $commodity->info["class"];
                    $commodityClass = new CommodityClass($commodityClassId);
                    if($commodityClass -> isNull())
                    {
                        $data[] = $single;
                        continue;
                    }
                    $single["commodity_class"] = $commodityClass->info["name"]; //6.将商品类别信息写入

                    $data[] = $single;  //zc

                }
                //清空wait_handle
                $num = count($wait_handles);
                $device -> popWaitHandle($num);
            }

            $it++;
            if(empty($data))
            {
                if($it<$maxTime)
                {

                   //sleep(1);
                }

            }
            else
            {
                break;
            }


        }

        if(empty($data))
        {
            return response()->json(["status" => false, "message" => "没有数据，请继续轮询", "data" => [] ]);
        }
        return response()->json(["status" => true, "message" => "获取被扫描货物成功", "data" => $data ]);

    }

    /**
     * 人工入库,出库（1.改变货物的状态1为库内，0为库外（goods表）
     *                2.删除wait_handle相应id的数据(device表)）
     * 注意：用===或者!==来判断
     * 发送数据
     * |-goods_id 货物id
     * |-goods_status
     * 返回数据
     * |-status
     * |-data = goods表的一条记录
     * |-message
     */
    public function uGoodsStatus()
    {
        $receiveGoods = Request::only("goods_id","goods_status");
       // $goods = new Goods();

        $goods = new Goods();
        $data = $goods->select(["goods_ID"=>$receiveGoods["goods_id"]]);
        if(empty($data["data"])){throw new \Exception("没有这个货物");}

        $goods = new Goods($data["data"][0]["_id"]->__toString());
        //dump($goods);
        if($goods ->isNull() == false)
        {
            $session_id = session("operatorSession.operatorSession_id");
            $session = new OperatorSession($session_id);

            if($session->addCache($goods ->id,$receiveGoods["goods_status"] ))
            {
                $goods->getInfo();
                return response()->json(["status" => true, "message" => "货物状态切换成功", "data" => $goods->info]);

            }
            else
            {

                return response()->json(["status" => false, "message" => "货物状态切换失败", "data" => [] ]);
            }
        }
        return response()->json(["status" => false, "message" => "处理的货物不存在", "data" => [] ]);

    }

    /**
     * 自动入库（改变货物的状态为1）
     * 注意：用===或者!==来判断
     * 发送数据
     * |-goods_outs  待处理的库外货物（数组）
           [
              {
            goods_id:id      货物id
            commodity_name:    商品名
            commodity_price:   商品价格
            commodity_model:   商品型号
           commodity_class:   商品类别
           goods_status:status  0
                 },
           {...},
           {...}
           ]
     * 返回数据
     * |-status
     * |-data=[]
     * |-message
     */
    /*
    public function autoUGoodsInStatus()
    {
        $goodsOutData =  Request::only("goods_outs");
        //轮询数据库device表的wait_handle对应的goods表的所有goods_status为0的，全部改为1
           while(true)
           {
               $i = 0;  //计时器
               $goodsArray = $goodsOutData["goods_outs"];
              // dump($goodsArray);
             //  exit();
               foreach($goodsArray as $singleGoods)
               {
                   $goodsId = $singleGoods["goods_id"];
                   $goods = new Goods($goodsId);
                   if($goods ->isNull() == false)
                   {
                      $returnUpdate =  $goods -> setStatusIn();
                       if($returnUpdate != false)
                       {
                           continue;
                       }
                   }
               }

                   return response()->json(["status" => true, "message" => "自动入库成功", "data" => [] ]);
               sleep(1);
               ++$i;
               if ($i > 10) {
                   break;
               }
           }

        return response()->json(["status" => false, "message" => "自动入库失败", "data" => [] ]);
    }*/



    /**
     * 自动出库（改变货物的状态0）
     * 注意：用===或者!==来判断
     * 发送数据
     * |-goods_Ins  待处理的库内货物（数组）
    [
    {
    goods_id:id      货物id
    commodity_name:    商品名
    commodity_price:   商品价格
    commodity_model:   商品型号
    commodity_class:   商品类别
    goods_status:status  1
    },
    {...},
    {...}
    ]
     * 返回数据
     * |-status
     * |-data=[]
     * |-message
     */
    /*
    public function autoUGoodsOutStatus()
    {
        $goodsInData =  Request::only("goods_ins");
        //轮询数据库device表的wait_handle对应的goods表的所有goods_status为1的，全部改为0
        while(true)
        {
            $i = 0;  //计时器
            $goodsArray = $goodsInData["goods_ins"];
          //  dump($goodsArray);
           // exit();
            foreach($goodsArray as $singleGoods)
            {
                $goodsId = $singleGoods["goods_id"];
                $goods = new Goods($goodsId);
                if($goods ->isNull() == false)
                {
                    $returnUpdate =  $goods -> setStatusOut();
                    if($returnUpdate != false)
                    {
                        continue;
                    }
                }
            }

            return response()->json(["status" => true, "message" => "自动出库成功", "data" => [] ]);
            sleep(1);
            ++$i;
            if ($i > 10) {
                break;
            }
       }
        return response()->json(["status" => false, "message" => "自动出库失败", "data" => [] ]);
    }*/


}