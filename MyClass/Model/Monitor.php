<?php
/**
 * Created by PhpStorm.
 * User: RagPanda
 * Date: 2016/3/28
 * Time: 11:07
 */

namespace MyClass\Model;

use MyClass\DatabaseModel;

class Monitor extends DatabaseModel
{
    public $info;   //和mysql不同 这里是数组形式的
    public $id;     //字符串类型id
    public $_id;    //对象型id
    protected $isNull; //是否有效
    protected $collection="monitor";

    public function addMonitor($area_id,$name,$login_name,$password,$intro =null)
    {

        $result = $this->add([
            "area_id"=>$area_id,
            "name"=>$name,
            "intro"=>$intro,
            "log"=>["login_name"=>$login_name,
                    "password"=>$password
        ]
        ]);
        if(false!=$result)
        {
            return new Monitor($result);
        }
        return false;

    }


}