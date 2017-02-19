<?php
/**
 * Created by PhpStorm.
 * User: RagPanda
 * Date: 2016/3/29
 * Time: 14:32
 */

namespace MyClass\Model;


use MyClass\DatabaseModel;

class Entrance extends DatabaseModel
{
    public $info;   //和mysql不同 这里是数组形式的
    public $id;     //字符串类型id
    public $_id;    //对象型id
    protected $isNull; //是否有效
    protected $collection="entrance";

    /**
     * 自定义查询规则
     * @param $mongoLimit
     * @param $queryLimit
     * @param $cursor
     * $queryLimit["login_name"]:查询相应的登录名的记录
     * $queryLimit["password"]:查询密码匹配的相应记录
     * $queryLimit["device_id"]:通过device_id(mongo id)来查找相应的entrance
     */
    public function selectExtra(&$mongoLimit,&$queryLimit,&$cursor)
    {
        if(isset($queryLimit["login_name"]))
        {
            $mongoLimit['$and'][] = ["login_name"=>$queryLimit["login_name"] ];
        }
        if(isset($queryLimit["password"]))
        {
            $mongoLimit['$and'][] = ["password"=>$queryLimit["password"]];
        }
        //设备id
        if(isset($queryLimit["device_id"]))
        {
            $limit["device"]['$all'][]['$elemMatch']['id'] = $queryLimit["device_id"];
            $mongoLimit['$and'][] = $limit;
            /*
            $limit["device"]['$elemMatch']['id'] = $queryLimit["device_id"];
            $limit["device"]['$elemMatch']['name'] = "camera";
            $mongoLimit['$and'][] = $limit;
            */
        }
        if(isset($queryLimit["android_device"]))
        {
            $mongoLimit['$and'][] = ["android_device"=>$queryLimit["android_device"]];
        }
    }

    /**
     * 为这个入口登录
     * @param $login_name
     * @param $password
     */
    public function login($login_name,$password)
    {

        if(empty($login_name)||empty($password))
        {
            return false;
        };
        $result = $this->select(["login_name"=>$login_name,"password"=>$password]);

        if(empty($result['data']))
        {
            return false;
        }
        //instantiation
        $id = $result["data"][0]["_id"];
        $entranceModel = new Entrance($id);

        //set session
        $entranceModel->setSession();
        return $entranceModel;
    }


    /**
     * 设定session
     * @return bool
     */
    public function setSession()
    {
        $sessionStruct["entrance_id"] = $this->id;
        $sessionStruct["entrance_status"] = true;
        $sessionStruct["entrance_name"] = $this->info["name"];
        $sessionStruct["entrance_login_name"] = $this->info["login_name"];

        session(["entrance"=>$sessionStruct]);
        return true;
    }

    /**
     * 添加入口
     * @param $name
     * @param $login_name
     * @param $password
     */
    public function addEntrance($name,$login_name,$password)
    {
        //自增
        $system = new System();
        $addName = "entrance_num";
        $entranceID = $system ->addSelf($addName);
        if($entranceID == false)
        {
            return false;
        }


        $result = $this->add(["ID"=>$entranceID,"name"=>$name,"login_name"=>$login_name,"password"=>$password]);
        if($result == false)
        {
            return false;
        }
        else
        {
           // return new Entrance($result);
            $returnAdd = new Entrance($result);
            //添加android设备
            $addAndroid["name"] = $result . "_" ."AndroidDevice";
            $addAndroid["intro"] = $result ."_" . "AndroidDevice";
            $addAndroid["type"] = 2;
            $addAndroid["use"] = 1;
            $device = new Device();
            $returnAddDevice = $device -> addDevice($addAndroid["name"],$addAndroid["use"],0,$addAndroid["intro"],$addAndroid["type"],null);
            if($returnAddDevice != false)
            {
                $deviceId = $returnAddDevice -> id;
                $updateDevice["self_id"] = $deviceId;
                $returnUpdate = $returnAddDevice ->update($updateDevice);
                if($returnUpdate != false)
                {
                    $updateEntrance["android_device"] = $deviceId;
                    $returnUpdateEntrance = $returnAdd ->update($updateEntrance);
                    if($returnUpdateEntrance != false)
                    {
                         //把此设备加入device
                        $androidAdd =  $returnAdd->addDevice($deviceId);
                        if($androidAdd)
                        {
                            return $returnAdd;
                        }
                    }
                }
            }
        }
    }

    /**
     * 添加日志
     */
    public function addLog($message)
    {
        $result = $this->getOriginConnection()->update(["_id"=>$this->_id,['$push'=>["log"=>$message]]]);
        if($result["err"]==null&&$result['n'] != 0)
        {
            $this->getInfo();
            return true;
        }
        return false;
    }

/**
 * 添加一个设备到这个出入口
 * @param $deviceId
 */
    public function addDevice($deviceId)
    {
        $device = new Device($deviceId);
        if($device->isNull())
        {
            return false;
        }
        $insertData = ["id"=>$device->_id,"name"=>$device->info["name"]];

        $result = $this->getOriginConnection()->update(["_id"=>$this->info["_id"]],['$addToSet'=>["device"=>$insertData ]]);
        if($result["err"] == null && $result["n"]!=0)
        {
            $this->getInfo();
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * 移除一个设备
     * @param $deviceId
     */
    public function removeDevice($deviceId)
    {
        $device = new Device($deviceId);
        if($device->isNull())
        {
            return false;
        }
        $updateData["id"] = $device->_id;
        $result = $this->getOriginConnection()
            ->update(["_id"=>$this->_id],
                ['$pull'=>
                    ['device'=> $updateData]
                ]);
        if($result["err"] == null && $result["n"]!=0)
        {
            $this->getInfo();
            return true;
        }
        else
        {
            return false;
        }
    }


}