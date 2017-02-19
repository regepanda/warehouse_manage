<?php
/**
 * Created by PhpStorm.
 * User: RagPanda
 * Date: 2016/4/25
 * Time: 14:25
 */

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use MyClass\Model\Device;
use MyClass\Model\Entrance;
use Illuminate\Support\Facades\Request;
use MyClass\Model\Goods;
use MyClass\Model\Log;
use MyClass\Model\Operator;
use MyClass\Model\OperatorSession;
use MyClass\System\AccessTokenManage;
use MongoId;
use MyClass\Model\Commodity;
use MyClass\Model\CommodityClass;


class AppController extends Controller
{
    /**
     * 3.1 登录接口  POST /api_app_entranceLogin
     *  需要发送
    |-username
    |-password
    返回数据
    |-status 是否成功
    |-message 消息
    |-data     status为true， accessToken存在这里面，是一个40位字符串
     *         status为false, data="" (空字符串)
     */
    public function entranceLogin()
    {
          $userData = Request::only("username","password");

          $entrance = new Entrance();
          $entranceModel = $entrance->login($userData["username"], $userData["password"]);
          if ($entranceModel != false) {
              $token = AccessTokenManage::setEntranceAccessToken($entranceModel);
              if($token != false) {
                  $device_self_id =  $entranceModel -> info["android_device"];
                  $data["accessToken"] = $token;
                  $data["device_self_id"] = $device_self_id;
                  return response()->json(["status" => true, "message" => "登录正确", "data" => $data]);
              }
          } else {
              return response()->json(["status" => false, "message" => "登录错误", "data" =>""]);
          }
    }



    /**
     * 3.2 查询当前是否有最新的可用会话 GET /api_app_waitSession
    需要发送
    |-access_token 登录的token
    返回数据
    |-status 是否有最新的会话
    |-message 描述信息
    |-data =
    {
    session_id:string   会话id
    operator_id:string  操作员id
    operator_name:string 操作员名字
    status: int         状态
    }返回session的id
     *
     */
    public function waitSession()
    {

            $queryLimit["status"] = 1;
            $queryLimit["entrance"] = new MongoId(session("entrance.entrance_id"));
            $session = new  OperatorSession();
            $returnSelect = $session->select($queryLimit);
            if ($returnSelect["data"] != null)
            {
                $data["session_id"] = $returnSelect["data"][0]["_id"]->__toString();
                $data["operator_id"] = $returnSelect["data"][0]["operator"]->__toString();
                $data["status"] = $returnSelect["data"][0]["status"];
                $operator = new Operator();
                $queryLimit["id"] = $data["operator_id"];
                $returnOperator = $operator->select($queryLimit);
                if ($returnOperator["data"] != null)
                {
                    $data["operator_name"] = $returnOperator["data"][0]["name"];
                }
                return response()->json(["status" => true, "message" => "有最新会话", "data" => $data]);
            }
            return response()->json(["status" => true, "message" => "没有会话", "data" => []]);

    }


    /**
     * 3.3 关闭一个会话   GET /api_app_finishSession
     * 需要发送
    |-access_token 登陆的token：用于验证身份（此函数不处理，交由中间件处理）
    |-session_id   需要关闭的会话id

    返回数据
    |-status  是否成功
    |-message 描述信息
    |-data    null 空
     *
     */
    public function finishSession()
    {

        $sessionData = Request::only("session_id");

        //1.判断此会话id是否为当前入口的会话
        $queryLimit["entrance"]=new MongoId(session("entrance.entrance_id"));
        $queryLimit["status"] = 1;
        $session = new OperatorSession();
        $sessionSelect =   $session ->select($queryLimit);
        if($sessionSelect["data"] == null)
        {
            return response()->json(["status" => false, "message" => "当前入口无相应的会话", "data" => [] ]);
        }
        if($sessionSelect["data"][0]["_id"]->__toString() != $sessionData["session_id"])
        {
            return response()->json(["status" => false, "message" => "当前入口与当前会话不匹配", "data" => [] ]);
        }

        //2.关闭会话
        $newSession = new OperatorSession($sessionData["session_id"]);
        if($newSession ->isNull() ==false) {
            $returnFinish = $newSession->finishSession();
            if ($returnFinish == true) {
                return response()->json(["status" => true, "message" => "关闭会话成功", "data" => null]);
            }
        }
        else
        {
            return response()->json(["status" => false, "message" => "关闭会话成功", "data" => null]);
        }
    }


    /**
     *  3.4 获取最新商品的扫描数据   GET /api_app_getScanGoods
    需要发送
    |-access_token 登录的token：用于验证身份（此函数不处理，交由中间件处理）
    |-session_id   当前会话id

    返回数据
    |-status 是否成功
    |-message 描述信息
    |-data = [
    {
    goods_id:id         货物id
    commodity_name:    商品名
    commodity_price:   商品价格
    commodity_model:   商品型号
    commodity_class:   商品类别
    goods_status:status  货物状态
    },
    .......
    ]

     */
    public function getScanGood()
    {
            $sessionData = Request::only("session_id");
            $data = Array();


           //1.判断此会话id是否为当前入口的会话
           $queryLimit["entrance"]=new MongoId(session("entrance.entrance_id"));
           $queryLimit["status"] = 1;
           $session = new OperatorSession();
           $sessionSelect =   $session ->select($queryLimit);
           if($sessionSelect["data"] == null)
           {
               return response()->json(["status" => false, "message" => "当前入口无相应的会话，获取最新商品的扫描数据失败", "data" => [] ]);
           }
           if($sessionSelect["data"][0]["_id"]->__toString() != $sessionData["session_id"])
           {
               return response()->json(["status" => false, "message" => "当前入口与当前会话不匹配，获取最新商品的扫描数据失败", "data" => [] ]);
           }


           //2.获取相应入口中的设备
           $entrance = new Entrance(session("entrance.entrance_id"));
           if ($entrance->isNull()) {
            return response()->json(["status" => false, "message" => "当前入口不存在，获取最新商品的扫描数据失败", "data" => []]);
           }
           $devices = $entrance->info["device"];
           if($devices == null)
           {
               return response()->json(["status" => true, "message" => "当前入口暂无设备", "data" => []]);
           }


           //3.获取设备的对应货物
           foreach ($devices as $device) {
            //设备中的wait_handle中的goods_id
            $deviceObject = new Device($device["id"]);
               if($deviceObject ->isNull())
               {
                   continue;
               }
            $waitGoods = $deviceObject->info["wait_handle"];
               if($waitGoods == null)
               {
                   continue;
               }

              foreach ($waitGoods as $waitGood) {

                $goods_id = $waitGood["data"]["goods_id"]->__toString();

                $goods = new Goods($goods_id);
                if ($goods->isNull())                //是否合法
                {
                    continue;
                }
                $single["goods_id"] = $goods_id;                   //1.将货物信息写入
                $single["goods_status"] = $goods->info["status"];


                //货物商品信息处理
                $commodityId = $goods->info["commodity"];
                $commodity = new Commodity($commodityId);
                if ($commodity->isNull()) {
                    $data[] = $single;
                    continue;
                }
                $single["commodity_name"] = $commodity->info["name"];   //2.将商品信息写入
                $single["commodity_price"] = $commodity->info["price"];
                $single["commodity_model"] = $commodity->info["model"];


                //商品类型处理
                $commodityClassId = $commodity->info["class"];
                $commodityClass = new CommodityClass($commodityClassId);
                if ($commodityClass->isNull()) {
                    $data[] = $single;
                    continue;
                }
                $single["commodity_class"] = $commodityClass->info["name"]; //3.将商品类别信息写入
                $data[] = $single;
            }
            //清空wait_handle
           $num = count($waitGoods);
           $deviceObject -> popWaitHandle($num);
        }

        if (empty($data)) {
            return response()->json(["status" => true, "message" => "当前入口暂无待处理货物", "data" => [] ]);
        }
        return response()->json(["status" => true, "message" => "获取最新商品的扫描数据成功", "data" => $data]);

    }


    /**
     * 3.5 处理货物   POST /api_app_updateGoods
    需要发送
    |-access_token 登陆的token：用于验证身份（此函数不处理，交由中间件处理）
    |-session_id   当前会话id
    |-goods_id     待处理货物的id
    |-goods_status 货物需要更改到的状态

    返回数据
    |-status 是否成功
    |-message 描述信息
    |-data = 更新后的状态id

    目前货物状态有三种，对应int
    0 库外
    1 库内
    2 冻结（冻结的商品应该在库内）
     */
    public function updateGoods()
    {

        $receiveGoods = Request::only("goods_id", "goods_status","session_id");

        //1.判断此会话id是否为当前入口的会话
        $queryLimit["entrance"]=new MongoId(session("entrance.entrance_id"));
        $session = new OperatorSession();
        $sessionSelect =   $session ->select($queryLimit);
        if($sessionSelect["data"] == null)
        {
            return response()->json(["status" => false, "message" => "当前入口无相应的会话", "data" => [] ]);
        }
        if($sessionSelect["data"][0]["_id"]->__toString() != $receiveGoods["session_id"])
        {
            return response()->json(["status" => false, "message" => "当前入口与当前会话不匹配", "data" => [] ]);
        }
        $goods = new Goods($receiveGoods["goods_id"]);
        $session = new OperatorSession($receiveGoods["session_id"]);
        $status = $receiveGoods["goods_status"];
        //现已改成缓存版本
        if($session->addCache($goods->id, $status))
        {
            return response()->json(["status" => true, "message" => "处理货物成功", "data" => ""]);

        }
        else
        {
            return response()->json(["status" => false, "message" => "处理的货物失败", "data" => -1 ]);
        }


    }




    /**
     *  3.6 获取自己这个入口的设备  GET /api_app_sDevice
     * 需要发送
     *  |-access_token 登陆的token
     *  返回数据
     * |-status  是否成功
     * |-message
     * |-data = [
     * {
     *  self_id: Android传来的设备id
     *  type:  设备类型
     *  name:  设备名
     * },
     *.......
     * ]
     */
     public function sDevice()
     {

             $entrance = new Entrance(session("entrance.entrance_id"));
             $data = Array();
             if($entrance ->isNull() == false) {
                 $devices = $entrance->info["device"];
                 if($devices == null)
                 {
                     return response()->json(["status" => true, "message" => "获取自己所在入口的设备成功，但此入口无设备", "data" => []]);
                 }
                 foreach ($devices as $device) {
                     $deviceId = $device["id"];
                     $deviceObject = new Device($deviceId);
                     if ($deviceObject->isNull() == true) {
                         continue;
                     }
                     $single["self_id"] = $deviceObject->info["self_id"];  //将设备信息写入
                     $single["type"] = $deviceObject->info["type"];
                     $single["name"] = $deviceObject->info["name"];
                     $data[] = $single;
                 }
                 return response()->json(["status" => true, "message" => "获取自己所在入口的设备成功", "data" => $data]);
             }
         return response()->json(["status" => false, "message" => "获取自己所在入口的设备失败", "data" => []]);
     }

    /**
     * 3.7 获取记录信息 GET /api_app_sLog
        需要发送
        |-access_token 登录的token

        返回数据
        |-status 是否成功
        |-message 消息
        |-data    数据
        data=
        [
        {
        intro:简介,
        detail：详情,
        data：数据
        }

        ]
     */
    public function sLog()
    {
        $limit = Request::only("desc","num");
        $log = new Log();
        $data = $log->select($limit);
        foreach($data as $k => $v)
        {
            $data[$k]["_id"] = $v["_id"]->__toString();
        }
        $response = ["status"=>true,"message"=>"获取到了日志记录","data"=>$data];
        return response()->json($response);
    }


    /**
     * 3.8 获取缓存数据 GET /api_app_sOperatorCache
        需要发送
        |-offset 需要多少条以后的，默认0
        |-session_id 会话id
        |-access_token 登录的token

        返回数据
        data=
        [
            {

                goods_id:string,
                commodity_name:string,
                aim_status:1/0,
                model:string,
                price:float,
                area:string,//分配目标区域，
            }
        ]
     *
     */
    public function sOperatorCache()
    {
        $requestData = Request::only("offset","session_id");

        $sessionModel = new OperatorSession($requestData["session_id"]);

        $offset = empty($requestData["offset"])?0:$requestData["offset"];
        $result = $sessionModel->getCache($offset);

        $response["status"] = true;
        $response["message"] = "获取指定偏移量 $offset 参数";
        $response["data"] = $result;
        return response()->json($response);
    }



    /**
     * ## 3.9 删除一条缓存记录 POST /api_app_delOperatorCache
        需要发送
        |-id 缓存记录的goods_id
        |-session_id 会话id
        |-access_token 登录的token
        返回数据
        |-status 是否成功
        |-message  消息
        |-data=""    数据
     *
     */
    public function delOpertorCache()
    {
        $requestData = Request::only("id","session_id");
        $sessionModel = new OperatorSession($requestData["session_id"]);
        $r = $sessionModel->delCache($requestData["id"]);
        if($r)
        {
            $r = ["status"=>true,"message"=>"删除成功，id=".$requestData["id"],"data"=>""];
            return response()->json($r);
        }
        else
        {
            $r = ["status"=>false,"message"=>"删除失败，id=".$requestData["id"],"data"=>""];
            return response()->json($r);
        }


    }





    /**
     * 3.10 提交缓存 POST /api_app_commitOperatorCache
        需要发送
        |-session_id 会话id
        |-access_token 登录token
        提交这个会话的缓存
        返回数据
        |-status 是否成功
        |-message 描述
        |-data =""
     *
     */
    public function commitOperatorCache()
    {
        $request =Request::only("session_id");
        $session = new OperatorSession($request["session_id"]);
        if($session->commitCache())
        {
            return response()->json(["status"=>true,"message"=>"提交完成","data"=>""]);
        }
        return response()->json(["status"=>false,"message"=>"提交失败","data"=>""]);
    }




}

