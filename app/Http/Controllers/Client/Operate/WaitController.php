<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2016/4/6
 * Time: 13:47
 */

namespace App\Http\Controllers\Client\Operate;
use App\Http\Controllers\Controller;
use MongoId;
use MyClass\Model\Device;
use MyClass\Model\Entrance;
use MyClass\Model\Log;
use MyClass\Model\OperatorSession;


class WaitController extends Controller
{
    /**
     * 客户端等待会话轮询,客户端轮询请求这个地址，直到一个操作人员通过硬件设备的验证，开始一个会话
     * 无需请求值
     * 返回值
     * |-status = true|false 如果有会话true，其余情况false
     * |-data = {session_id:""}
     * |-message = "提示信息"
     */
    public function waitSession()
    {
        //查询operate_session表相关数据
        $operateSession = new OperatorSession();
        $queryLimit["status"] = 1; //1表示正在处理
        $queryLimit["entrance"] = new MongoId(session("entrance.entrance_id"));
        $i = 0;
        //轮询
        while(true)
        {
            $returnData = $operateSession ->select($queryLimit);
            if($returnData["data"])
            {
                $data["session_id"] = $returnData["data"][0]["_id"];

                //把session写进去
                $sessionStruct["operatorSession_id"] = $data["session_id"] -> __toString();
                $sessionStruct["operatorSession_operator"] = $returnData["data"][0]["operator"]->__toString();
                session(["operatorSession"=>$sessionStruct]);



                return response()->json(["status"=>true,"data"=>$data,"message"=>"操作员通过硬件设备的验证"]);
            }
            /*sleep(1);
            ++$i;
            if($i>3)
            {
                break;
            }*/
            break;
        }
        return response()->json(["status"=>false,"data"=>[],"message"=>"没有最新的会话"]);
    }

    /**
     * 客户端进入会话轮询后，通过轮询这个地址，获取是否有最新硬件信息到达服务器
     * （ 1.查询该入口的相应设备id（entrance表),有多个设备
     *    2.根据设备id查询相应的wait_handle（device表），多个设备id对应的wait_handle）
     * 无需请求值
     * 返回值
     * |-status = true|false 如果有最新的请求为true，其余情况是false
     * |-data = [{商品操作记录信息}]
     * |-message = "提示信息"
     */
    /*
    public function waitOperate()
    {
        $entrance = new Entrance();
        $queryLimit["id"] = session("entrance.entrance_id");
        $data = array();
        $i = 0;  //计时器
        $j = 0; //$data数组下标
        //轮询
        while (true) {

            $returnData = $entrance->select($queryLimit);
            if ($returnData["data"] != null) {
                $devices = $returnData["data"][0]["device"];
                //1.查看每一个设备的wait_handle是否有数据

                foreach ($devices as $device) {
                    $device_id = $device["id"]->__toString();
                    $selectDevice = new Device();
                    $queryLimit["id"] = $device_id; //字符串id
                    $returnDeviceData = $selectDevice->select($queryLimit);
                    if ($returnDeviceData["data"]) {
                        //2.取出wait_handle
                        $waitHandleDatas = $returnDeviceData["data"][0]["wait_handle"];
                        if ($waitHandleDatas) {
                            foreach ($waitHandleDatas as $waitHandleData) {
                                $data[$j] = $waitHandleData;
                                $j++;
                            }

                        }
                    }
                }
            }


            if ($j != 0) {
                return response()->json(["status" => true, "data" => $data, "message" => "有最新硬件信息到达服务器"]);
            }

            sleep(1);
            ++$i;
            if ($i > 10) {
                break;
            }
        }
        return response()->json(["status" => false, "data" => [], "message" => "无最新硬件信息到达服务器"]);
    }
*/
    /**
     * 登出会话接口，请求这个接口后，服务器会关闭当前的会话，清理相关信息，客户端也会应该回到等待会话的轮询状态
     * 无需请求任何值
     * 返回值
     * |-status = true|false
     * |-data = []
     * |-message = "提示信息"
     */
    public function logoutSession()
    {
        $sessionId = session("operatorSession.operatorSession_id");
        if($sessionId == null)
        {
            return response()->json(["status" => false, "data" => [], "message" => "会话为空,关闭会话失败"]);
        }
        $operateSession = new OperatorSession($sessionId);
        if(!$operateSession ->isNull()) {
            $returnFinish = $operateSession->finishSession();
            if ($returnFinish == true) {
                return response()->json(["status" => true, "data" => [], "message" => "关闭会话成功"]);
            }
            return response()->json(["status" => false, "data" => [], "message" => "关闭会话失败"]);
        }
    }




}