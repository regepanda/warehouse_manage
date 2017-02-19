<?php
/**
 * Created by PhpStorm.
 * User: RagPanda
 * Date: 2016/3/28
 * Time: 11:05
 */

namespace MyClass\Model;


use MyClass\DatabaseModel;

class Operator extends DatabaseModel
{

    public $info;   //和mysql不同 这里是数组形式的
    public $id;     //字符串类型id
    public $_id;    //对象型id
    protected $isNull; //是否有效
    protected $collection="operator";

    /*
     * 添加操作者
     * @param $username
     * @param $password
     * @param $name
     * @param $group
     */
    public function addOperator($username,$password,$name,$group,$rfid_key=null,$finger_key=null)
    {
        //添加face_key,自增
        $system = new System();
        $addName = "operator_num";
        $face_key = $system ->addSelf($addName);
        if($face_key == false)
        {
            return false;
        }

        $result = $this->add(["username"=>$username,"password"=>$password,"name"=>$name,"group"=>$group
        ,"rfid_key"=>$rfid_key,"finger_key"=>$finger_key,"image"=>[],"face_key"=>$face_key]);
        if($result == false)
        {
            return false;
        }
        else
        {
            return new Operator($result);
        }
    }

    /*
     *设置权限组
     * @param $group
     */
    public function setPowerGroup($group)
    {
        $result = $this->update(["group"=>$group]);
        if($result == false)
        {
            return false;
        }
        else
        {
            return true;
        }
    }


    /*
     *设置人脸识别标示
     */
    public function setFaceKey($key)
    {
        $result = $this->update(["face_key"=>$key]);
        if($result == false)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    /*
     *设定rfid身份标示
     */
    public function setRfidKey($key)
    {
        $result = $this->update(["rfid_key"=>$key]);
        if($result == false)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    /*
     * 传入一个faceKey,查找该faceKey对应的用户
     * @param $faceKey
     */
    public function checkFace($faceKey)
    {
        $result = $this->select(["face_key"=>$faceKey]);
        if(empty($result['data']))
        {
            return false;
        }
        else
        {
            return $result["data"][0]["_id"]->__toString();
        }
    }

    /*
     * 传入一个rfidKey，查找该id对应的用户
     */
    public function checkRfid($rfidKey)
    {
        $result = $this->select(["rfid_key"=>$rfidKey]);
        if(empty($result['data']))
        {
            return false;
        }
        else
        {
            return $result["data"][0]["_id"]->__toString();
        }
    }


    /*
  * 传入一个fingerKey,查找该fingerKey对应的用户
  * @param $fingerKey
  */
    public function checkFinger($fingerKey)
    {
        $result = $this->select(["finger_key"=>"$fingerKey"]);
        if(empty($result['data']))
        {
            return false;
        }
        else
        {
            return $result["data"][0]["_id"]->__toString();
        }
    }

    /*
     * 自定义查询规则
     * @param $mongoLimit
     * @param $queryLimit
     * @param $cursor
     * @param $group
     */
    public function selectExtra(&$mongoLimit,&$queryLimit,&$cursor)
    {
        if(isset($queryLimit["face_key"]))
        {
            $mongoLimit['$and'][] = ["face_key"=>$queryLimit["face_key"] ];
        }
        if(isset($queryLimit["rfid_key"]))
        {
            $mongoLimit['$and'][] = ["rfid_key"=>$queryLimit["rfid_key"]];
        }
        if(isset($queryLimit["finger_key"]))
        {
            $mongoLimit['$and'][] = ["finger_key"=>$queryLimit["finger_key"]];
        }
        if(isset($queryLimit["group"]))
        {
            $mongoLimit['$and'][] = ["group"=>$queryLimit["group"]];
        }
    }


    /**
     * 推入该操作员的图片id到image字段
     * @param $data
     */
    public function pushImage($data)
    {
        //$dataArray["data"] =  $data;
        $result = $this->getOriginConnection()->update(["_id"=>$this->_id],['$push'=>["image"=>$data]]);
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
     * 删除操作员中的一张图片
     * @param object $image_id
     * @return mixed
     * @throws \Exception
     */
    public function deleteImage($image_id)
    {
          $result =   $this->getOriginConnection()->update(["_id"=>$this->_id],
                ['$pull'=>["image"=>$image_id]]
            );

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



}