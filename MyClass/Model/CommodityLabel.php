<?php
/**
 * Created by PhpStorm.
 * User: RagPanda
 * Date: 2016/3/28
 * Time: 11:07
 */

namespace MyClass\Model;

use MyClass\DatabaseModel;

class CommodityLabel extends DatabaseModel
{
    public $info;   //和mysql不同 这里是数组形式的
    public $id;     //字符串类型id
    public $_id;    //对象型id
    protected $isNull; //是否有效
    protected $collection="commodity_label";

    public function addCommodityLabel($label_name)
    {
        //自增
        $system = new System();
        $addName = "commodity_label_num";
        $commodityLabelID = $system ->addSelf($addName);
        if($commodityLabelID == false)
        {
            return false;
        }

        $result = $this->add([
            "ID"=>$commodityLabelID,
            "name"=>$label_name
        ]);
        if($result == false)
        {
            return false;
        }
        return new CommodityLabel($result);
    }
}