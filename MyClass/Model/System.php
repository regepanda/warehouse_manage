<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2016/5/9
 * Time: 13:30
 */

namespace MyClass\Model;
use MyClass\DatabaseModel;


class System extends DatabaseModel
{

    public $info;   //和mysql不同 这里是数组形式的
    public $id;     //字符串类型id
    public $_id;    //对象型id
    protected $isNull; //是否有效
    protected $collection="system";






    public function addSelf($addName) //addName为字段名
    {
        //第一条记录
        $queryLimit["desc"] = true;
        $returnSelect = $this->select($queryLimit);
        if($returnSelect["data"] == null) {
           $returnId = $this->add(["operator_num" => 0, "operator_group_num" => 0, "operator_session_num" => 0, "goods_num" => 0,
                "image_num" => 0, "log_num" => 0, "environment_num" => 0, "entrance_num" => 0, "device_num" => 0, "commodity_num" => 0,
                "commodity_class_num" => 0, "commodity_label_num" => 0, "area_num" => 0, "admin_num" => 0,"spider_data_num"=>0]);

            $system = new System($returnId);
            if($system ->isNull())
            {
                return false;
            }
            $update[$addName] = $system ->info[$addName] + 1;
            $returnUpdate =  $system -> update($update);
            if($returnUpdate != false)
            {
                return $update[$addName];
            }
            else
            {
                return false;
            }
        }
        else
        {
            //非第一条记录
            //更新此记录operator_num++
            $num = $returnSelect["data"][0][$addName] + 1;
            $update[$addName] = $num;
            $systemId = $returnSelect["data"][0]["_id"];
            $system = new System($systemId);
            if($system ->isNull())
            {
                return false;
            }
            $returnUpdate =  $system -> update($update);
            if($returnUpdate != false)
            {
                return $num;
            }
            else
            {
                return false;
            }
        }
    }



    /*
    public function selectSystem($queryLimit)
    {
        $mongoLimit = [];

        if(isset($queryLimit["class"]))
        {
            $mongoLimit['$and'][] = ["class"=>$queryLimit["class"]];
        }
        $origin =  $this ->getOriginConnection();
        $origin ->find($mongoLimit);
    }
    */


}