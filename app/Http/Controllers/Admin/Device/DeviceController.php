<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2016/5/6
 * Time: 16:34
 */

namespace App\Http\Controllers\Admin\Device;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use MyClass\Base\GuiFunction;
use MyClass\Model\Device;
use MyClass\Model\Entrance;
use MongoId;

class DeviceController extends Controller
{

    public function __construct(GuiFunction $guiFunc)
    {
        $guiFunc->setModule("sDevice");
    }


    /**
     * 获取设备信息
     * 无需发送数据
     * 返回数据
     * |-status
     * |-data = [
     *            {
    device表的一条记录
    },
    {...},
    {...}
    ]
     * |-message
     */
    public function sDevice()
    {
        $queryLimit = Request::all();
      //  $queryLimit["desc"] = true;
        $device = new Device();
        $returnDevice = $device ->select($queryLimit);

        //device记录中加入字段device_entrance

            foreach ($returnDevice["data"] as $key=>$value)
            {
                $deviceId = $returnDevice["data"][$key]["_id"];
                $queryLimitEntrance["device_id"] = $deviceId;
                $entrance = new Entrance();
                $returnSelect = $entrance->select($queryLimitEntrance);
                if ($returnSelect["data"] != null)
                {
                    $returnDevice["data"][$key]["device_entrance"] = $returnSelect["data"][0]["_id"];
                }
                else
                {
                    $returnDevice["data"][$key]["device_entrance"] = null;
                }
            }
        return response()->json($returnDevice);
    }


    /**
     * 增加设备
     * 发送数据
     * |-device_name
     * |-device_type
     * |-self_id
     * |_device_intro
     * |-device_entrance 入口id
     * |-device_control
     * 返回数据
     * |-status
     * |-data = []
     * |-message
     */
    public function aDevice()
    {
        $input = Request::only('device_control','device_name', 'device_type', 'self_id', 'device_intro','device_entrance');
        //self_id不能为空
        if($input["self_id"] == null)
        {
            return response()->json(["status" => false, "message" => "self_id不能为空", "data" =>[]]);
        }
        //判断self_id是否已经存在
        $queryLimit["self_id"] = $input["self_id"];
        $device = new Device();
        $returnSelect = $device ->select($queryLimit);
        if($returnSelect["data"] != [])
        {
            return response()->json(["status" => false, "message" => "此self_id已经存在", "data" =>[]]);
        }
        $returnAdd =  $device -> addDevice($input["device_name"],0,(int)$input["device_control"],$input["device_intro"],$input["device_type"],$input["self_id"]);
        if($returnAdd != false)
        {
           //把此设备添加到相应的入口
            $entrance  = new Entrance($input["device_entrance"]);
            if($entrance ->isNull() == false)
            {
                $deviceId = $returnAdd ->info["_id"];
                $returnAddDevice = $entrance ->addDevice($deviceId);
                if($returnAddDevice != false)
                {
                    return response()->json(["status" => true, "message" => "添加设备成功", "data" => [] ]);
                }
            }
            else
            {
                return response()->json(["status" => true, "message" => "添加设备成功", "data" => [] ]);
            }
        }
        else
        {
            return response()->json(["status" => false, "message" => "添加设备失败", "data" =>[]]);
        }
    }


    /**
     * 修改设备
     * 发送数据
     * |-device_id
     * |-self_id
     * |-device_name
     * |-device_type
     * |_device_intro
     * |-device_entrance 入口id
     * |-device_control
     * 返回数据
     * |-status
     * |-data = []
     * |-message
     */
    public function uDevice()
    {

        $input = Request::only('device_control','device_id','device_name', 'self_id', 'device_type', 'device_intro','device_entrance');
        //$updateArray["id"] = $input["device_id"];
        $updateArray["name"] = $input["device_name"];
        $updateArray["self_id"] = $input["self_id"];
        $updateArray["type"] = $input["device_type"];
        $updateArray["intro"] = $input["device_intro"];
        $updateArray["control"] = $input["device_control"];

        $jqueryClass = "?";
        if($updateArray["type"] == $jqueryClass)
        {
            $updateArray["type"]= null;
        }
        else
        {
            $updateArray["type"]=  substr($updateArray["type"],7); //去掉string:,从第7个字母到最后，
            $updateArray["type"] = (int)$updateArray["type"];
        }


        if($updateArray["control"] == $jqueryClass)
        {
            $updateArray["control"]= null;
        }
        else
        {
            $updateArray["control"]=  substr($updateArray["control"],7); //去掉string:,从第7个字母到最后，
            $updateArray["control"] = (int)$updateArray["control"];
        }

        //修改设备对应的入口
        if($input["device_entrance"] == $jqueryClass)
        {
            //此设备以前无入口
            $input["device_entrance"] = substr($input["device_entrance"],7);
            $entrance = new Entrance($input["device_entrance"]);
            if($entrance -> isNull() == false)
            {
                $entrance -> addDevice($input["device_id"]);
            }
        }
        else
        {
            //此设备以前有入口

            //1.删除以前入口的设备
            $queryLimit["device_id"] = new MongoId($input["device_id"]);
            $entrance = new Entrance();
            $returnSelect = $entrance ->select($queryLimit);
            if($returnSelect["data"] != null)
            {
                $oldEntrance = $returnSelect["data"][0]["_id"]->__toString();
                $entrance = new Entrance($oldEntrance);
                $returnRemove = $entrance -> removeDevice($input["device_id"]);
                if($returnRemove != false)
                {
                    //2.把此设备添加到新入口
                    $input["device_entrance"] = substr($input["device_entrance"],7);
                    $entrance = new Entrance($input["device_entrance"]);
                    if($entrance -> isNull() == false)
                    {
                        $entrance -> addDevice($input["device_id"]);
                    }
                }
            }
        }

        $device = new Device($input["device_id"]);
        if($device->isNull() == false)
        {
            $returnUpdate =  $device ->update($updateArray);
            if($returnUpdate != false)
            {
                return response()->json(["status" => true, "message" => "修改设备成功", "data" => [] ]);
            }
        }
        else
        {
            return response()->json(["status" => false, "message" => "修改设备失败", "data" => [] ]);
        }

    }


    /**
     * 删除设备(1.若此设备关联到了相应的入口，则先从该入口移除该设备，再删除该设备
     *         2.若此设备没有关联到入口，则直接删除该设备)
     * 发送数据
     * |-device_id
     * 返回数据
     * |-status
     * |-data = []
     * |-message
     */
    public function dDevice()
    {
        $deviceData = Request::only("device_id");
        //1.移除相应入口的设备
        $entrance = new Entrance();
        $queryLimit["device_id"] = new MongoId($deviceData["device_id"]);
        $returnEntrance = $entrance -> select($queryLimit);
        if($returnEntrance["data"] != null)
        {
            $entranceId = $returnEntrance["data"][0]["_id"];
            $entranceObject = new Entrance($entranceId);
            $returnDevice = $entranceObject ->removeDevice($deviceData["device_id"]);
            if($returnDevice != false)
            {
                //2.删除设备
                $device = new Device($deviceData["device_id"]);
                if($device ->isNull() == false)
                {
                    $returnDelete =  $device ->delete();
                    if($returnDelete != false)
                    {
                        return response()->json(["status" => true, "message" => "删除设备成功", "data" => [] ]);
                    }
                }
            }
            else
            {
                return response()->json(["status" => false, "message" => "删除设备失败", "data" => [] ]);
            }
        }
        else
        {
            //删除设备
            $device = new Device($deviceData["device_id"]);
            if($device ->isNull() == false)
            {
                $returnDelete =  $device ->delete();
                if($returnDelete != false)
                {
                    return response()->json(["status" => true, "message" => "删除设备成功", "data" => [] ]);
                }
            }
            else
            {
                return response()->json(["status" => false, "message" => "删除设备失败", "data" => [] ]);
            }
        }

    }


    /**
     * 启用/禁用设备
     * 发送数据
     * |-device_id
     * |-device_use bool值
     * 返回数据
     * |-status
     * |-data = []
     * |-message
     */
    public function uDeviceUse()
    {

        $deviceData = Request::only("device_id","device_use");
        $device = new Device($deviceData["device_id"]);
        if($device ->isNull() == false)
        {

             if((int)$deviceData["device_use"] == 0)
             {
                 $updateArray["use"] = 1;
                 $returnUpdate = $device ->update($updateArray);
                 if($returnUpdate != false)
                 {
                     return response()->json(["status" => true, "message" => "启用设备成功", "data" => [] ]);
                 }
             }

            if((int)$deviceData["device_use"] == 1)
            {
                $updateArray["use"] = 0;
                $returnUpdate = $device ->update($updateArray);
                if($returnUpdate != false)
                {
                    return response()->json(["status" => true, "message" => "禁用设备成功", "data" => [] ]);
                }
            }
        }
        else
        {
            return response()->json(["status" => false, "message" =>"启用/禁用设备失败", "data" => [] ]);
        }

    }

    /**
     * 获得入口信息
     * 发送数据
     * 返回数据
     * |-status
     * |-data = [
     * {一条入口记录}
     * ，，，，，，，
     * ]
     * |-message
     */
    public function sDeviceEntrance()
    {
        $entrance = new Entrance();
        $queryLimit["desc"] = true;
        $returnSelect = $entrance ->select($queryLimit);
        if($returnSelect["data"] != null)
        {
            return response()->json(["status" => true, "data" =>$returnSelect ]);
        }
        else
        {
            return response()->json(["status" => false, "data" =>null ]);
        }

    }


    public function toggleDevice()
    {
        $re = Request::only("device_id");
        $device = new Device($re["device_id"]);

        //如果是门禁设备
        if($device->info["type"]==Device::TYPE_DOOR)
        {
            $returnStart =  $device->startAction();
            $updateArray["use"] = 1;
            $returnUpdate = $device ->update($updateArray);
            if($returnUpdate != false&&$returnStart!=false)
            {
                return response()->json(["status" => true, "message" => "已开门", "data" => [] ]);
            }
        }
        //如果是不可用设备
        if($device->info["use"] == 0)
        {
            $returnStart =  $device->startAction();
            if($returnStart != false)
            {
                $updateData["use"] = 1;
                if($device->update($updateData))
                {
                    return response()->json(["status"=>true,"message"=>"切换状态，目前是".$updateData["use"],"data"=>[]]);
                }
                else
                {
                    return response()->json(["status"=>false,"message"=>"切换失败，目前是","data"=>[]]);
                }
            }
            return response()->json(["status"=>false,"message"=>"设备不可控","data"=>[]]);
        }
        else  //如果是可用设备
        {
            $returnStop =  $device->stopAction();
            if($returnStop != false)
            {
                $updateData["use"] = 0;
                if($device->update($updateData))
                {
                    return response()->json(["status"=>true,"message"=>"切换状态，目前是".$updateData["use"],"data"=>[]]);
                }
                else
                {
                    return response()->json(["status"=>false,"message"=>"切换失败，目前是","data"=>[]]);
                }
            }
            return response()->json(["status"=>false,"message"=>"设备不可控","data"=>[]]);
        }

    }

}