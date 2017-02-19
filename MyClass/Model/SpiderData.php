<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2016/5/19
 * Time: 17:55
 */

namespace MyClass\Model;


use MyClass\DatabaseModel;


class SpiderData extends DatabaseModel
{
    public $info;
    public $id;
    public $isNull;
    public $collection = "spider_data";


    /**
     * @param $name
     * @param $price
     * @param $detail
     * @param $url
     * @return bool
     */
    public function addSpiderData($name,$price,$detail,$url)
    {
        //自增
        $system = new System();
        $addName = "spider_data_num";
        $spiderID = $system ->addSelf($addName);
        if($spiderID == false)
        {
            return false;
        }

        $result = $this->add([
            "ID"=>$spiderID,
            "name"=>$name,
            "price"=>$price,
            "detail"=>$detail,
            "url"=>$url
        ]);
        if(false!=$result)
        {
           return true;
        }
        return false;
    }




}