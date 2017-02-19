<?php
/**
 * Created by PhpStorm.
 * User: RagPanda
 * Date: 2016/3/28
 * Time: 11:06
 */

namespace MyClass\Model;


use MyClass\DatabaseModel;

class Goods extends DatabaseModel
{
    public $info;   //和mysql不同 这里是数组形式的
    public $id;     //字符串类型id
    public $_id;    //对象型id
    protected $isNull; //是否有效
    protected $collection="goods";

    //状态
    const STATUS_OUT = 0; //库外
    const STATUS_IN = 1;  //库内
    const STATUS_FREEZE = 2; //冻结



    /**
     * @param $mongoLimit
     * @param $queryLimit
     * @param $cursor
     * $queryLimit["goods_ID"]:查询字段ID=$queryLimit["goods_ID"]的记录
     */
    public function selectExtra(&$mongoLimit,&$queryLimit,&$cursor)
    {
        if (isset($queryLimit["bar_code_key"])) {
            $mongoLimit['$and'][] = ["bar_code_key" => $queryLimit["bar_code_key"]];
        }
        if(isset($queryLimit["goods_ID"]))
        {
            $queryLimit["goods_ID"] = (int)$queryLimit["goods_ID"];
            $mongoLimit['$and'][] = ["ID"=>$queryLimit["goods_ID"] ];
        }
        if (isset($queryLimit["commodity"])) {
            $mongoLimit['$and'][] = ["commodity" => $queryLimit["commodity"]];
        }



    }


    /*
     *
     * 添加商品
     * @param $rfid_key
     * @param $two_dimension_key
     * @param $commodity (obj id)
     * @param $area
     */
    public function addGoods($rfid_key,$two_dimension_key,$bar_code_key,$area=null,$commodity=null)
    {
        //自增
        $system = new System();
        $addName = "goods_num";
        $goodsID = $system ->addSelf($addName);
        if($goodsID == false)
        {
            return false;
        }


        if(null!=$area)
        {
            $areaObj = new Area($area);
            $insetData["area"] = $areaObj->_id;
        }



        $insetData["ID"] = $goodsID;
        $insetData["rfid_key"] = $rfid_key;
        $insetData["two_dimension_key"] = $two_dimension_key;
        $insetData["bar_code_key"] = $bar_code_key;
        $insetData["status"] = 1;
        $insetData["commodity"] = $commodity;
        $insetData["log"] = [];
        $result = $this->add($insetData);
        if($result!=false)
        {
            $goods = new Goods($result);
            $goods->setStatusOut();
            return $goods;
        }
        return false;
    }

    /*
     * 写入日志
     * @param $message
     */
    public function addLog($message)
    {
        $result = $this->getOriginConnection()->update(["_id"=>$this->_id,['$push'=>["log"=>$message]]]);
        if($result["err"]==null&&$result['n'] != 0)
        {
            return true;
        }
        return false;
    }

    /*
     * 更新备注
     * @param $remark
     */
    public function updateRemark($remark)
    {
        return $this->update(["remark"=>$remark]);
    }

    /*
     *设定状态出库
     */
    public function setStatusOut()
    {
        if($this->info["status"] == Goods::STATUS_IN)
        {
            return $this->update(["status"=>0]);
        }
        else
        {
            throw new \Exception("无法切换货物状态为出库，当前状态不是库内");
        }
    }

    /*const STATUS_OUT = 0; //库外
    const STATUS_IN = 1;  //库内
    const STATUS_FREEZE = 2; //冻结
     *设定状态入库
     */
    public function setStatusIn()
    {
        if($this->info["status"] != Goods::STATUS_OUT)
        {
            throw new \Exception("无法切换货物状态为入库，当前状态不是库外");
        }
        return $this->update(["status"=>1]);
    }

    /*
     *设定状态冻结
     */
    public function setStatusFreeze()
    {
        if($this->info["status"] != 1)
        {
            return false;
        }
        return $this->update(["status"=>2]);
    }





}