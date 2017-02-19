<?php
/**
 * Created by PhpStorm.
 * User: RagPanda
 * Date: 2016/3/29
 * Time: 14:54
 */

namespace MyClass\Model;


use MyClass\DatabaseModel;
use MyClass\Module\Instruction;

class Device extends DatabaseModel
{
    public $info;   //和mysql不同 这里是数组形式的
    public $id;     //字符串类型id
    public $_id;    //对象型id
    protected $isNull; //是否有效
    protected $collection="device";

    const TYPE_RFID = 0;
    const TYPE_CAMERA = 1;
    const TYPE_ANDROID = 2; //Android设备
    const TYPE_ZIGBEE = 3;   //ZIGBEE设备
    const TYPE_DOOR = 4; //门禁设备
    const TYPE_FINGER = 5; //指纹设备

    //指令
    const INSTRUCTION_START = "start"; //开始指令
    const INSTRUCTION_STOP = "stop";  //结束指令


    /**
     * 添加设备
     * @param $name
     * @param $intro
     * @param $type
     * @param $self_id
     * @param $use
     */
    public function addDevice($name,$use = 0,$control=0,$intro = null,$type = null,$self_id=null)
    {

        //自增
        $system = new System();
        $addName = "device_num";
        $deviceID = $system ->addSelf($addName);
        if($deviceID == false)
        {
            return false;
        }

        $result = $this->add(["ID"=>$deviceID,"name"=>$name,"intro"=>$intro,"type"=>$type,"wait_handle"=>[],"self_id"=>$self_id,"use"=>$use,
            "control"=>$control,"instructions"=>[]]);
        if($result == false)
        {
            return false;
        }
        else
        {
            return new Device($result);
        }
    }


    /**
     * @param $mongoLimit
     * @param $queryLimit
     * @param $cursor
     * $queryLimit["wait_handle_num"]:查询wait_handle字段的前$queryLimit["wait_handle_num"]条
     * $queryLimit["self_id"]:查询字段self_id=$queryLimit["self_id"]的记录
     */
    public function selectExtra(&$mongoLimit,&$queryLimit,&$cursor)
    {
        if(isset($queryLimit["wait_handle_num"]))
        {
            $limit["wait_handle"]['$slice'] = $queryLimit["wait_handle_num"];
            $mongoLimit['$and'][] = $limit;
        }
        if(isset($queryLimit["self_id"]))
        {
            $mongoLimit['$and'][] = ["self_id"=>$queryLimit["self_id"] ];
        }
    }

    /**
     *设定该设备为可用
     */
    public function setUse()
    {
        $result = $this->update(["use"=>true]);
        if($result == false)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    /**
     *设定该设备为不可用
     */
    public function setNotUse()
    {
        $result = $this->update(["use"=>false]);
        if($result == false)
        {
            return false;
        }
        else
        {
            return true;
        }
    }



    /**
     * 推入一条数据，从等待队列
     * @param $data
     */
    public function pushWaitHandle($data)
    {
        $dataArray["data"] =  $data;
        $result = $this->getOriginConnection()->update(["_id"=>$this->_id],['$push'=>["wait_handle"=>$dataArray]]);
        if(($result["err"]==null&&$result['n'] != 0))
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
     * 从等待处理队列取出num条数据,默认取出1条数据
     * @param $num
     */
    public function popWaitHandle($num=1)
    {
        $limit["wait_handle"]['$slice'] = $num;
        $result = $this->getOriginConnection()->findOne(["_id"=>$this->_id],
            $limit
            );
        $waitHandleData = $result["wait_handle"];
        $num = sizeof($waitHandleData);

        while($num-- > 0)
        {
            $this->getOriginConnection()->update(["_id"=>$this->_id],
                ['$pop'=>["wait_handle"=>1]]
            );
        }
        $this->getInfo();
        return $waitHandleData;
    }

    /**
     * 推入一条指令数据到指令队列
     * @param $data
     */
    public function pushInstructionsHandle($data)
    {
        $result = $this->getOriginConnection()->update(["_id"=>$this->_id],['$push'=>["instructions"=>$data]]);
        if(($result["err"]==null&&$result['n'] != 0))
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
     * 从等待处理队列取出num条数据,默认取出1条数据
     * @param $num
     */
    public function popInstructionHandle($num=1)
    {
        $limit["instructions"]['$slice'] = $num;
        $result = $this->getOriginConnection()->findOne(["_id"=>$this->_id],
            $limit
        );
        $instructionsHandleData = $result["instructions"];
        $num = sizeof($instructionsHandleData);

        while($num-- > 0)
        {
            $this->getOriginConnection()->update(["_id"=>$this->_id],
                ['$pop'=>["instructions"=>1]]
            );
        }
        $this->getInfo();
        return $instructionsHandleData;
    }







    /**
     * 添加记录
     * @param $message
     */
    public function addLog($message)
    {
        $result = $this->getOriginConnection()->update(["_id"=>$this->_id],['$push'=>["log"=>$message]]);
        if($result["err"]==null&&$result['n'] != 0)
        {
            $this->getInfo();
            return true;
        }
        return false;
    }

    /**
     * 发送开始指令
     * @param $instruct
     * @param null $config
     */
    public function startAction()
    {
        if($this->info["control"] == 1)
        {
               $data["instruction"] = Device::INSTRUCTION_START;
               $returnPush = $this ->pushInstructionsHandle($data);
               if($returnPush != false)
               {
                   return true;
               }
        }
        return false;
    }

    /**
     * 发送停止指令
     * @return bool
     */
    public function stopAction()
    {
        if($this->info["control"] == 1)
        {

            $data["instruction"] = Device::INSTRUCTION_STOP;
            $returnPush = $this ->pushInstructionsHandle($data);
            if($returnPush != false)
            {
                return true;
            }
        }
        return false;
    }


}