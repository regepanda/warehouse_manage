<?php
/**
 * Created by PhpStorm.
 * User: ragpanda
 * Date: 16-5-7
 * Time: 下午5:44
 */

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use MyClass\Model\Log;
use MyClass\Model\Operator;
use MyClass\Model\OperatorSession;
use MyClass\Module\Face;

class CppController extends Controller
{

    public function startSession()
    {
        $request = Request::only("module_access","entrance","label");
        $log =new Log();


        $sessionModel = new OperatorSession();
        $operatorModel = new Operator();
        $oprData  = $operatorModel->select(["face_key"=>$request["label"]]);
        if($oprData["data"] == null)
        {
            $log->addLog("FaceModule回调","C++模块请求启动一个会话，但是找不到该用户",null,Log::ERROR,$request);
            return "false";
        }
        $opr = new Operator($oprData["data"][0]["_id"]);
        if(!$sessionModel=$sessionModel->addOperatorSession($request["entrance"],$opr->id))
        {

            $log->addLog("FaceModule回调","C++模块请求启动一个会话，但是启动失败",null,Log::ERROR,$request);
            return "false";
        };
        if(!$sessionModel->runSession(OperatorSession::CERTIFICATE_FACE,$request["label"]))
        {
            $log->addLog("FaceModule回调","C++模块请求启动一个会话，但是无法运行",null,Log::ERROR,$request);
            return "false";
        };
        $log->addLog("FaceModule处理完成","C++模块请求启动一个会话成功",null,Log::DEBUG,$request);
    }


    //zc

    /**
     *c++模块处理完成，发送相关记录回来
     *需要发送
     *|-intro
     *|-detail
     *|-data
     *|-level 等级
     *|-otherData 健值对数组
     */
    public function receiveLog()
    {
        $requestData =  Request::only("intro","detail","data","level","otherData");
        $log = new Log();
        $returnAdd =  $log->addLog("C++日志:".$requestData["intro"].$requestData["detail"],null,$requestData["data"],$requestData["level"]
        ,$requestData["otherData"]);
        if($returnAdd != false)
        {
            return "true";
        }
        else
        {
            return "false";
        }
    }



}
