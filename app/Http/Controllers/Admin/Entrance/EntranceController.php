<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2016/5/7
 * Time: 19:44
 */

namespace App\Http\Controllers\Admin\Entrance;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use MyClass\Base\GuiFunction;
use MyClass\Model\Device;
use MyClass\Model\Entrance;


class EntranceController extends Controller
{

    public function __construct(GuiFunction $guiFunc)
    {

        $guiFunc->setModule("sEntrance");
    }



    /**
     * 获取入口信息
     * 无需发送数据
     * 返回数据
     * |-status
     * |-data = [
     *            {
    entrance表的一条记录
    },
    {...},
    {...}
    ]
     * |-message
     */
    public function sEntrance()
    {
        $queryLimit = Request::all();
        $entrance = new Entrance();
        $returnEntrance = $entrance ->select($queryLimit);
        return response()->json($returnEntrance);
    }


    /**
     * 增加入口
     * 发送数据
     * |-entrance_name
     * |-entrance_login_name
     * |-entrance_password
     * 返回数据
     * |-status
     * |-data = []
     * |-message
     */
    public function aEntrance()
    {
        $input = Request::only('entrance_name', 'entrance_login_name', 'entrance_password');
        $input["entrance_password"] = md5($input["entrance_password"]);
        $entrance = new Entrance();
        $returnAdd = $entrance -> addEntrance($input["entrance_name"],$input["entrance_login_name"],$input["entrance_password"]);
        if($returnAdd != false)
        {
            return response()->json(["status" => true, "message" => "添加入口成功", "data" => [] ]);
        }
        else
        {
            return response()->json(["status" => false, "message" => "添加入口失败", "data" =>[]]);
        }
    }


    /**
     * 修改入口
     * 发送数据
     * |-entrance_id
     * |-entrance_name
     * |-entrance_login_name
     * 返回数据
     * |-status
     * |-data = []
     * |-message
     */
    public function uEntrance()
    {
        $input = Request::only('entrance_id','entrance_name', 'entrance_login_name');
        $updateArray["name"] = $input["entrance_name"];
        $updateArray["login_name"] = $input["entrance_login_name"];

        $entrance = new Entrance($input["entrance_id"]);
        if($entrance->isNull() == false)
        {
            $returnUpdate =  $entrance ->update($updateArray);
            if($returnUpdate != false)
            {
                return response()->json(["status" => true, "message" => "修改入口成功", "data" => [] ]);
            }
        }
        else
        {
            return response()->json(["status" => false, "message" => "修改入口失败", "data" => [] ]);
        }

    }


    /**
     * 删除入口
     * 发送数据
     * |-entrance_id
     * 返回数据
     * |-status
     * |-data = []
     * |-message
     */
    public function dEntrance()
    {
        $entrance = Request::only("entrance_id");
        $entranceObject = new Entrance($entrance["entrance_id"]);
        if($entranceObject ->isNull() == false)
        {
            $returnDelete =  $entranceObject ->delete();
            if($returnDelete != false)
            {
                //删除入口相应的Android设备
                $deviceId = $entranceObject -> info["android_device"];
                $device = new Device($deviceId);
                if($device -> isNull() == false)
                {
                    $returnDeviceDelete =  $device ->delete();
                    if($returnDeviceDelete != false)
                    {
                        return response()->json(["status" => true, "message" => "删除入口成功", "data" => [] ]);
                    }
                }
            }
        }
        else
        {
            return response()->json(["status" => false, "message" => "删除入口失败", "data" => [] ]);
        }

    }


    /**
     * 获取入口设备
     * 发送数据
     * |-entrance_id
     * 返回数据
     * |-status
     * |-data = [
     * {相应设备的一条记录}
     * ，，，，
     * ]
     * |-message
     */
    public function sEntranceDevice()
    {
        $entrance = Request::only("entrance_id");
       // $entrance["entrance_id"] = new MongoId($entrance["entrance_id"]);
        $entrance = new Entrance($entrance["entrance_id"]);
        $data = Array();
        if($entrance -> isNull() ==false)
        {
           $entranceDevices =  $entrance -> info["device"];
            if($entranceDevices != null)
            {
                foreach($entranceDevices as $entranceDevice )
                {
                    $deviceId = $entranceDevice["id"];
                    $device  = new Device($deviceId);
                    if($device -> isNull() == true)
                    {
                        continue;
                    }
                    $data[] = $device -> info;
                }
                return response()->json(["status" => true, "message" => "查询入口设备成功", "data" => $data ]);
            }
            else
            {
                return response()->json(["status" => -1, "message" => "此入口暂无设备", "data" => [] ]);
            }

        }
        return response()->json(["status" => false, "message" => "查询入口设备失败", "data" => [] ]);
    }


    /**
     * 移除入口设备
     * 发送数据
     * |-entrance_id
     * |-device_id
     * 返回数据
     * |-status
     * |-data = []
     * |-message
     */
    public function removeEntranceDevice()
    {
        $entrance = Request::only("entrance_id","device_id");
        $entranceObject = new Entrance($entrance["entrance_id"]);
        if($entranceObject ->isNull() == false)
        {
           $returnMove = $entranceObject ->removeDevice($entrance["device_id"]);
            if($returnMove != false)
            {
                return response()->json(["status" => true, "message" => "移除入口设备成功", "data" => [] ]);
            }
        }
        else
        {
            return response()->json(["status" => false, "message" => "移除入口设备失败", "data" => [] ]);
        }

    }






}