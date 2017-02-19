<?php
/**
 * Created by PhpStorm.
 * User: RagPanda
 * Date: 2016/3/29
 * Time: 15:25
 */

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use MyClass\Model\Device;
use MyClass\Model\Entrance;
use MyClass\Model\Environment;
use MyClass\Model\Goods;
use MyClass\Model\Log;
use MyClass\Model\OperatorSession;
use MyClass\Module\Face;
use MyClass\Module\Operator;


class DeviceController extends Controller
{
    public function __construct()
    {

    }

    /**
     * 终端扫描后，发送相关数据，启动一个会话
     * 数据
     * |-id          self_id,设备id,Android后台做相关处理
     * |-type        类型(3:人脸识别，4:RFID，5:FINGER)
     * |-data        发送的数据
     * 返回数据
     * |-status
     * |-data = []
     * |-message
     * 注意：data里面有身份信息，交由中间件处理验证身份
     */
    public function startSession()
    {

        $deviceData = Request::only("type", "data", "id");

        $log = new Log();
        $log->addLog("硬件启动会话请求到达",null,$deviceData["type"]."|".$deviceData["id"],Log::DEBUG);
        //exit();

        //根据id查device_id
        $queryLimit["self_id"] = $deviceData["id"];
        $device = new Device();
        $returnDevice = $device->select($queryLimit);
        if ($returnDevice["data"] == null)
        {
            $log->addLog("没有这个设备匹配，启动会话失败",null,null,Log::ERROR);
            return response()->json(["status" => false, "message" => "启动会话失败,没有硬件设备匹配", "data" => []]);
        }
        //通过entrance里面的device的device_id，反过来查entrance的id
        //如果是android设备，改变查询方式
        if($returnDevice["data"][0]["type"] == 2)
        {
            $queryLimit["android_device"] = $returnDevice["data"][0]["_id"]->__toString();
        }
        else
        {
            $queryLimit["device_id"] = $returnDevice["data"][0]["_id"];
        }
        $entrance = new Entrance();
        $return = $entrance->select($queryLimit);

        //该设备有对应的入口
        if ($return["data"] != null)
        {

            $entranceId = $return["data"][0]["_id"]->__toString();
            $canNext = false;
            //1.如果是人脸识别
            if ($deviceData["type"] == "FACE") {
                if (Request::hasFile("data")) {
                    $fileRequest = Request::file("data");
                    //dump($fileRequest);
                    $fileData = file_get_contents($fileRequest->path());
                    $size = filesize($fileRequest->path());
                    Face::pushRecognizeData($return["data"][0]["_id"]->__toString(), $fileData, $size);
                    return response()->json(["status" => true, "message" => "已将数据交由子模块处理，请询问会话是否开启", "data" => []]);

                }

                //data交由c++处理图片数据，回调函数
            }


            //2.如果是RFID
            if ($deviceData["type"] == "RFID") {
                $deviceData["type"] = 4;
                $operator = new \MyClass\Model\Operator();
                $operatorId = $operator->checkRfid($deviceData["data"]);
                if ($operatorId == false) {
                    return response()->json(["status" => false, "message" => "标签识别后，无此操作员", "data" => []]);
                }
                $canNext = true;
            }
            $operatorId = null;
            //3.如果是指纹识别
            if ($deviceData["type"] == "FINGER") {
                $deviceData["type"] = 5;
                $operator = new \MyClass\Model\Operator();
                $operatorId = $operator->checkFinger($deviceData["data"]);
                $log->addLog("Debug 收到指纹id".$deviceData["data"],null,$operatorId,Log::DEBUG);
                $log->addLog("Debug 收到 = ".$operatorId,null,$operatorId,Log::DEBUG);
                if ($operatorId == false)
                {
                    return response()->json(["status" => false, "message" => "指纹识别后，无此操作员", "data" => []]);
                }
                $canNext = true;
            }
            if( $canNext != true)
            {
                $log->addLog("不正确的硬件数据类型",null,null,Log::ERROR);
                return response()->json(["status" => false, "message" => "不正确的类型", "data" => []]);
            }
            //操作员验证通过，启动会话
            $operateSession = new OperatorSession();
            $returnOperateSession = $operateSession->addOperatorSession($entranceId, $operatorId);
            if ($returnOperateSession != false)
            {
                //交给后台验证
                //成功
                //激活会话
                $returnRun = $returnOperateSession->runSession($deviceData["type"], $deviceData["data"]);
                if ($returnRun != false)
                {
                    //开启门锁
                    $device = new Device();
                    $data = $device->select(["self_id"=>"6"]);
                    $log = new Log();
                   // $log -> addLog("记录查询到的设备",null,null,Log::DEBUG,$data);


                    if(!empty($data["data"]))
                    {

                        //$log -> addLog("找到设备数据",null,null,Log::DEBUG);
                        $id = $data["data"][0]["_id"];
                        $device = new Device($id);
                        $device->startAction();

                        //$log -> addLog("会话启动成功，尝试开启门禁一次",null,null,Log::DEBUG);
                    }

                    return response()->json(["status" => true, "message" => "启动会话正确", "data" => []]);
                }

            }

        }
        $log->addLog("没有对应入口",null,null,Log::ERROR);
        return response()->json(["status" => false, "message" => "启动会话失败,没有对应入口", "data" => []]);
    }
    /**
     * 终端推入一个数据，根据数据格式决定策略
     * 数据
     * |-id         self_id,设备id,Android后台做相关处理
     * |-type       设备数据类型（"BAR_CODE"：条形码）
     * |-data       数据（被扫描的货物的相关数据）
     * 返回数据
     * |-status
     * |-data = []
     * |-message
     */
    public function putData()
    {
        $log  = new Log();

        $deviceData = Request::only("id","type","data");
        $log->addLog("/api_device_putData:PutData硬件设备准备写入数据",$deviceData["id"]."|".$deviceData["type"]."|".$deviceData["data"],null,Log::INFO);

        //根据id查device_id
        $queryLimit["self_id"] = $deviceData["id"];
        $device = new Device();
        $returnDevice = $device->select($queryLimit);
        if($returnDevice["data"] == null)
        {
            return response()->json(["status" => false, "message" => "此设备不存在，终端推入数据失败", "data" => [] ]);
        }

        $goods = new Goods();
           //1.如果是条形码
           if($deviceData["type"] == "BAR_CODE")
           {
              $queryLimit["bar_code_key"] = $deviceData["data"];
              $select = $goods -> select($queryLimit);
              if($select["data"] == null)
              {
                  return response()->json(["status" => false, "message" => "此条形码的货物不存在,终端推入数据失败", "data" => [] ]);
              }
           }
        //向相应设备推入相应货物的id
        $goodsId = $select["data"][0]["_id"];
        $deviceId = $returnDevice["data"][0]["_id"];
        $deviceObject = new Device($deviceId);
        if($deviceObject ->isNull() == false)
        {
            $log->addLog("记录硬件请求",null,json_encode($deviceObject ->info),Log::DEBUG);
            if(!empty($deviceObject->info["wait_handle"]))
            {
                foreach($deviceObject->info["wait_handle"] as $a)
                {
                    if($a["data"]["goods_id"] == $goodsId)
                    {
                        $log->addLog("终端推入重复数据",$goodsId,null,Log::INFO);
                        return response()->json(["status" => false, "message" => "终端推入重复数据", "data" => [] ]);
                    }
                }
            }

            $returnPush = $deviceObject ->pushWaitHandle(["goods_id"=>$goodsId]);
            if($returnPush)
            {
                return response()->json(["status" => true, "message" => "终端推入数据正确", "data" => [] ]);
            }
        }
        return response()->json(["status" => false, "message" => "终端推入数据失败", "data" => [] ]);
    }



    /**
     *
     *硬件发送当前环境信息
     *通常是ZigBee发送给服务器的环境信息
     * 发送数据
     * |-id    self_Id,设备id， //暂不处理
     * |-type  设备数据类型(通常是"ZIGBEE") //暂不处理
     * |-data  7位数(温度(2位),湿度(2),后三位无用)
     */
    public function recvMonitor()
    {
        $deviceData = Request::only("id","type","data");
        $log = new Log();
        $log->addLog("收到环境信息","xxx",json_encode($deviceData),Log::DEBUG);

        $temperature = substr($deviceData["data"],0,2);  //温度
        $humidity = substr($deviceData["data"],2,2);  //湿度
        $environment = new Environment();
        $environment ->addEnvironment((int)$temperature,(int)$humidity);
        echo "true";
    }


    /**
     * 硬件轮询这个接口获取命令
     打印数据
     true(状态，如果有指令就是true,没有指令或者错误false)\n
     你妈炸了(返回信息)\n
     start(这一排是指令)\n
     finger_1(这一排是设备id)\n
     */
    public function getInstruction()
    {
        $log = new Log();
        try {

            $i = 0; //查找指令计数
            //查设备
            $device = new Device();
            $queryLimit["desc"] = true;
            $returnSelect = $device->select($queryLimit);

            if ($returnSelect["data"] == null)
            {
                throw new \Exception("no device");

            }


            //查设备的指令
            foreach ($returnSelect["data"] as $single)
            {
                //如果是android设备
                if ($single["_id"]->__toString() == $single["self_id"])
                {
                    continue;
                }
                if(empty($single["instructions"]))
                {
                    continue;
                }

                $instructions = $single["instructions"];



                //如果一个设备有指令
                foreach ($instructions as $instruction)
                {
                    $order = $instruction["instruction"];
                    $deviceId = $single["self_id"];


                    /*
                    $output = "status:true\n".
                        "message:get instruction successfully!\n".
                        "instruction:".$order."\n".
                        "id:".$deviceId ."\n";*/

                    //取出该指令
                    $device = new Device($single["_id"]);
                    $device->popInstructionHandle();

                    $i++;
                    $log->addLog("硬件设备请求获得指令成功","设备id=".$deviceId."|指令=".$order,null,Log::INFO);
                    return response()->json(["status"=>true,"message"=>"get instruction","instruction"=>$order,"id"=>$deviceId]);
                    break;
                }
            }

            //如果所有设备都暂无指令
            if ($i == 0)
            {
                return response()->json(["status"=>false,"message"=>"no instruction"]);
                //throw new \Exception();

            } else {
                return;
            }
        }
        catch(\Exception $e)
        {
            $log->addLog("硬件设备请求获得指令,但是程序发生内部错误",$e->getMessage(),null,Log::INFO);
            //dump($e);
            return response()->json(["status"=>false,"message"=>"get instruction false"]);
            //$output="status:false\n"."message:error in program:".$e->getMessage()."\n";

            exit();
        }

    }






}
