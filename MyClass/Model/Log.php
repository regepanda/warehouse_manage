<?php
/**
 * Created by PhpStorm.
 * User: RagPanda
 * Date: 2016/3/28
 * Time: 11:05
 */

namespace MyClass\Model;

use MyClass\DatabaseModel;

class Log extends DatabaseModel
{
    //主要是处理系统log

    public $info;   //和mysql不同 这里是数组形式的
    public $id;     //字符串类型id
    public $_id;    //对象型id
    protected $isNull; //是否有效
    protected $collection="log";

    const ERROR = 1;
    const WARNING = 2;
    const DEBUG = 3;
    const INFO = 4;
    const SYSTEMINFO = 5;




    /*
     * 自定义查询规则
     * @param $mongoLimit
     * @param $queryLimit
     *  |-$queryLimit["level"] 按照记录等级查找
     *
     *
     *
     * @param $cursor
     * @param $group
     */
    public function selectExtra(&$mongoLimit,&$queryLimit,&$cursor)
    {

        if(isset($queryLimit["level"]))
        {
            $queryLimit["level"] = (int)$queryLimit["level"];
            $mongoLimit['$and'][] = ["level"=> $queryLimit["level"] ];
        }
    }






    /*
     * 生成记录
     * @param $intro
     * @param $detail
     * @param $data
     * @param $level 等级
     * @param $otherData 额外信息，将会按key=>value存入数据库，在数据库中动态增加字段,此变量需传入数组【比如货物出库入库操作】
     * @return bool
     */
    public function addLog($intro,$detail,$data,$level,$otherData = [])
    {
        //自增
        $system = new System();
        $addName = "log_num";
        $logID = $system ->addSelf($addName);
        if($logID == false)
        {
            return false;
        }


        $result = $this->add([
            "ID"=>$logID,
            "intro"=>$intro,
            "detail"=>$detail,
            "data"=>$data,
            "level"=>$level
        ]);
        if($result != false)
        {
            $log = new Log($result);
        }
        if($otherData == null)
        {
            return true;
        }

        foreach($otherData as $key => $value)
        {
            $res = $this->getOriginConnection()
                ->update(["_id"=>$log->_id],
                    ['$set'=>
                        ["$key"=>$value]
                    ]);
            if($res["err"] != null && $res["n"]==0)
            {
                return false;
            }
            else
            {
                $log->getInfo();
            }
        }
    }

}