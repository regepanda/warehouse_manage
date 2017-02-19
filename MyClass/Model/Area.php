<?php
/**
 * Created by PhpStorm.
 * User: RagPanda
 * Date: 2016/3/28
 * Time: 11:06
 */

namespace MyClass\Model;


use MyClass\DatabaseModel;

class Area extends DatabaseModel
{
    public $info;
    public $id;
    public $_id;
    public $isNull;
    public $collection="area";

    //添加区域
    public function addArea($intro,$distance,$no)
    {

        //自增
        $system = new System();
        $addName = "area_num";
        $areaID = $system ->addSelf($addName);
        if($areaID == false)
        {
            return false;
        }

        $result = $this->add(["ID"=>$areaID,"intro"=>$intro,"distance"=>$distance,"no"=>$no]);

        if($result == false)
        {
            return false;
        }

        return new Area($result);
    }


    /**
     * 入库占据库存
     * @param $goods_id
     * @return bool
     * @throws \Exception
     */
    public function inputGoods($goods_id)
    {
        $goods  = new Goods($goods_id);
        $join1["collection"] = "commodity";
        $join1["selfKey"] = "commodity";
        $join1["otherKey"] = "_id";

        $join2["collection"] = "commodity_class";
        $join2["selfKey"] = "commodity_class";
        $join2["otherKey"] = "_id";

        $join[] = $join1;
        $join[] = $join2;
        $data = $goods->select(["id"=>$goods_id,"join"=>$join]);
        if(empty($data["data"]))
        {
            throw new \Exception("Area::inputGoods:没有匹配的货物");
        }

        $data = $data["data"][0];
        $area = $data["commodity_class_area"];
        if(empty($area))
        {
            throw new \Exception("Area::inputGoods:商品类没有分配区域,commodityClass=".$data["commodity_class"]);
        }
        $area = new Area($area);
        $goods->update(["area"=>$area->id]);
        return $area->update(["nowCapacity"=>$area->info["nowCapacity"]+=1]);
        //实例化这个货物

        //实例化这个货物的商品和商品类

        //根据

    }

    /**
     * 出库时清理库存
     * @param $goods_id
     * @return bool
     * @throws \Exception
     */
    public function outputGoods($goods_id)
    {
        $goods  = new Goods($goods_id);
        $join1["collection"] = "commodity";
        $join1["selfKey"] = "commodity";
        $join1["otherKey"] = "_id";

        $join2["collection"] = "commodity_class";
        $join2["selfKey"] = "commodity_class";
        $join2["otherKey"] = "_id";

        $join[] = $join1;
        $join[] = $join2;
        $data = $goods->select(["id"=>$goods_id,"join"=>$join]);
        if(empty($data["data"]))
        {
            throw new \Exception("Area::inputGoods:没有匹配的货物");
        }

        $data = $data["data"][0];
        $area = $data["commodity_class_area"];
        if(empty($area))
        {
            throw new \Exception("Area::inputGoods:商品类没有分配区域,commodityClass=".$data["commodity_class"]);
        }
        $area = new Area($area);
        $goods->update(["area"=>null]);
        return $area->update(["nowCapacity"=>$area->info["nowCapacity"]-=1]);
    }

}