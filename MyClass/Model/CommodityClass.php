<?php
/**
 * Created by PhpStorm.
 * User: RagPanda
 * Date: 2016/3/28
 * Time: 11:07
 */

namespace MyClass\Model;

use MyClass\DatabaseModel;


class CommodityClass extends DatabaseModel
{
    public $info;   //和mysql不同 这里是数组形式的
    public $id;     //字符串类型id
    public $_id;    //对象型id
    protected $isNull; //是否有效
    protected $collection="commodity_class";

    /**
     *添加商品类别
     */
    public function addCommodityClass($className,$areaCapacity=null,$area=null,$parent=null,$son=null,$prioty=null)
    {
        //dump($area);
        //自增
        $system = new System();
        $addName = "commodity_class_num";
        $commodityID = $system ->addSelf($addName);
        if($commodityID == false)
        {
            return false;
        }

        $result = $this->add([
            "ID"=>$commodityID,
            "name"=>$className,
            "parent"=>$parent!=null?new \MongoId($parent):null,
            "son"=>[],
            "prioty"=>$prioty,
            "areaCapacity"=>$areaCapacity,
            "area"=>$area
        ]);
        if(false!=$result)
        {
            //添加商品类成功后随即跟新此区域，在此条区域记录中加入容量、当前量、货物列表初始化,但是要先判断此区域里面是否已经有商品，有的话不允许绑定
            $this->bingArea($result,$areaCapacity,$area);
            if($parent!=null)
            {

                $parentObj = new CommodityClass($parent);
                if($parentObj->isNull)
                {
                    return false;
                }
                if(!$parentObj->addSonCommodityClass($result))
                {
                    return false;
                };
            }

            return new CommodityClass($result);
        }
        return false;
    }

    /**
     * 添加子类别
     * @param $class_id
     * 传进来的参数用于构建子类
     */
    public function addSonCommodityClass($class_id)
    {
        $sonCommodityClass = new CommodityClass($class_id);
        if($sonCommodityClass->isNull())
        {
            return false;
        }
        $link = $this->getOriginConnection();
        $sonCommodityClass->update(["parent"=>$this->info["_id"]]);

        $insertData = ["id"=>$sonCommodityClass->_id,"name"=>$sonCommodityClass->info["name"]];
        $updateData['$addToSet']["son"]['$each'][]=$insertData;

        $result = $link->update(["_id"=>$this->info["_id"]],$updateData);
        if($result["err"] == null &&$result["n"]!=0)
        {
            $this->getInfo();  //刷新的儿子
            return true;
        }
        return false;
    }

    /**
     * 删除子类别
     * @param $class_id
     */
    public function deleteSonCommodityClass($class_id)
    {
        $sonCommodityClass = new Commodity($class_id);
        if($sonCommodityClass->isNull)
        {
            return false;
        }
        $updateData = ["id"=>$sonCommodityClass->_id];
        // $updateData = [0=>$sonCommodity->_id];
        $r1 = $sonCommodityClass->update(["parent"=>null]);
        $r2 = $this->getOriginConnection()->update(["_id"=>$this->info["_id"]],['$pull'=>["son"=>$updateData ]]);
        $this->getInfo();
        return $r1 && $r2["err"]==null && $r2["n"]!=0;
    }
    /**
     * 绑定区域调用基函数
     * @param $result  商品类型id
     * @param $areaCapacity  区域容量
     * @param $area   区域ID
     */
    public static function bingArea($result,$areaCapacity,$area)
    {
        $area = new Area($area);
        if($area->isNull())
        {
            return false;
        }
        else
        {
            for($i=0;$i<$areaCapacity;$i++)
            {
                $insertData[$i] = ["ID"=>$i,"status"=>0,"goodsId"=>null];
            }
            //$res = $area->update(["capacity"=>$areaCapacity,"nowCapacity"=>0,"goodsList"=>$insertData]);
            $res = $area->update(["capacity"=>$areaCapacity,"nowCapacity"=>0,"commodityClass"=>$result]);
            if($res)
            {
                $area->getInfo();
            }
        }
    }
    /**
     * 取消绑定区域调用基函数
     * @param $areaId   区域ID
     */
    public static function cancelBind($areaId)
    {
        $area = new Area($areaId);
        if($area->isNull())
        {
            return false;
        }
        $updateData = ["capacity"=>0,"nowCapacity"=>0,"commodityClass"=>0];
        $res = $area->getOriginConnection()->update(["_id"=>$area->info["_id"]],['$unset'=>$updateData]);
        if($res["err"] == null &&$res["n"]!=0)
        {
            $area->getInfo();
            return true;
        }
        return false;
    }
}