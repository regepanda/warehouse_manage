<?php
/**
 * Created by PhpStorm.
 * User: RagPanda
 * Date: 2016/3/28
 * Time: 11:06
 */

namespace MyClass\Model;


use MyClass\DatabaseModel;


class Commodity extends DatabaseModel
{
    public $info;
    public $id;
    public $isNull;
    public $collection="commodity";




    /**
     * @param $mongoLimit
     * @param $queryLimit
     * @param $cursor
     * $queryLimit["commodity_name"]:查询字段name=$queryLimit["commodity_name"]的记录
     */
    public function selectExtra(&$mongoLimit,&$queryLimit,&$cursor)
    {
        if(isset($queryLimit["commodity_name"]))
        {
            $mongoLimit['$and'][] = ["name"=>$queryLimit["commodity_name"] ];
        }
    }





    /*
     * 添加商品
     * @param $name
     * @param $price
     * @param $class string
     * @param $detail
     * @param $model
     * @param null $intro
     * @param null $parent string
     * @param int $priority
     * @return bool
     */
    public function addCommodity($name,$price,$class,$detail,$model,$intro=null,$parent=null,$priority=0)
    {
        //自增
        $system = new System();
        $addName = "commodity_num";
        $commodityID = $system ->addSelf($addName);
        if($commodityID == false)
        {
            return false;
        }


        $result = $this->add([
                    "ID"=>$commodityID,
                    "name"=>$name,
                    "price"=>$price,
                    "class"=>$class!=null?new \MongoId($class):null,
                    "detail"=>$detail,
                    "model"=>$model,
                    "parent"=>$parent!=null?new \MongoId($parent):null,
                    "priority"=> $priority,
                    "intro"=>$intro,
                    "son"=>[],
                    "labels"=>[]
                   ]);
        if(false!=$result)
        {
            if($parent!=null)
            {
                $parentObj = new Commodity($parent);
                if($parentObj->isNull)
                {
                    return false;
                }
                if(!$parentObj->addSonCommodity($result))
                {
                    return false;
                };
            }

            return new Commodity($result);
        }
        return false;

    }

    /*
     * 为本商品添加子商品,会自动关联修改子商品的父级
     * @param $id
     * @return false|\MyClass\BSONDocument
     */
    public function addSonCommodity($id) //$id是子商品id
    {
        $sonCommodity = new Commodity($id);
        if($sonCommodity->isNull())
        {
            return false;
        }
        $link = $this->getOriginConnection();
        $sonCommodity->update(["parent"=>$this->info["_id"]]);

        $insertData = ["id"=>$sonCommodity->_id,"name"=>$sonCommodity->info["name"]];
        //只将不存于数组 son 中的多值加入到field中，去重
        $updateData['$addToSet']["son"]['$each'][]=$insertData;

        $result = $link->update(["_id"=>$this->info["_id"]],$updateData);
        if($result["err"] == null &&$result["n"]!=0)
        {
            $this->getInfo();  //刷新父亲
            return true;
        }
        return false;
    }

    /*
     * 添加标签
     * @param $id
     * @return bool|false|\MyClass\BSONDocument
     */
    public function addLabel($id)
    {
        $label = new CommodityLabel($id);
        if($label->isNull())
        {
            return false;
        }
        $insertData = ["id"=>$label->_id,"name"=>$label->info["name"]];

        $result = $this->getOriginConnection()->update(["_id"=>$this->info["_id"]],['$addToSet'=>["labels"=>$insertData ]]);
        if($result["err"] == null &&$result["n"]!=0)
        {
            $this->getInfo();
            return true;
        }
        return false;
    }

    /*
     * 删除本商品的一个子商品，会关联取消子商品
     * @param $id
     * @return bool
     */
    public function deleteSonCommodity($id)
    {
        $sonCommodity = new Commodity($id);
        if($sonCommodity->isNull)
        {
            return false;
        }
        $updateData = ["id"=>$sonCommodity->_id];
       // $updateData = [0=>$sonCommodity->_id];
        $r1 = $sonCommodity->update(["parent"=>null]);
        $r2 = $this->getOriginConnection()->update(["_id"=>$this->info["_id"]],['$pull'=>["son"=>$updateData ]]);
        $this->getInfo();
        return $r1 && $r2["err"]==null && $r2["n"]!=0;
    }

    /*
     * 删除一个标签
     * @param $id
     * @return bool
     */
    public function deleteLabel($id)
    {
        $label = new CommodityLabel($id);
        if($label->isNull())
        {
            return false;
        }
        $updateData = ["id"=> $label->_id];
        $r1 = $this->getOriginConnection()->update(["_id"=>$this->_id],['$pull'=>["labels"=>$updateData]]);
        $this->getInfo();
        return $r1["err"]==null && $r1["n"]!=0;
    }

}
