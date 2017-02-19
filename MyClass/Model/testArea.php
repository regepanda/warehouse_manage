<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2016/5/22
 * Time: 21:26
 */

namespace MyClass\Model;


use MyClass\DatabaseModel;

class testArea extends DatabaseModel
{

    protected $collection="testArea";

    public function addArea($area_name,$can_goods,$now_goods)
    {
        $insetData["name"] = $area_name;
        $insetData["can_goods"] = $can_goods;
        $insetData["now_goods"] = $now_goods;

        $result = $this->add($insetData);
        if($result!=false)
        {
            return new testArea($result);
        }
        return false;
    }

}