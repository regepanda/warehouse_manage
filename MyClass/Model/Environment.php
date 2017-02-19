<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2016/5/12
 * Time: 11:26
 */

namespace MyClass\Model;


use MyClass\DatabaseModel;

class Environment extends DatabaseModel
{
    public $info;   //和mysql不同 这里是数组形式的
    public $id;     //字符串类型id
    public $_id;    //对象型id
    protected $isNull; //是否有效
    protected $collection="environment";


    /**
     * @param $temperature 温度
     * @param $humidity 湿度
     * @return bool|Environment
     *
     */
    public function addEnvironment($temperature, $humidity)
    {

        //自增
        $system = new System();
        $addName = "environment_num";
        $environmentID = $system ->addSelf($addName);
        if($environmentID == false)
        {
            return false;
        }

        $result = $this->add(["ID"=>$environmentID,"temperature"=>$temperature,"humidity"=>$humidity]);
        if($result == false)
        {
            return false;
        }
        else
        {
            return new Environment($result);
        }
    }

    public function getNowEnv()
    {
        $data = $this->select(["num"=>1,"desc"=>true]);
        if(empty($data))
        {
            return false;
        }
        return $data[0];
        //{"temperature":int,"humidity":int}
        //
    }




}