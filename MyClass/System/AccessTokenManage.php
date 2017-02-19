<?php
/**
 * Created by PhpStorm.
 * User: RagPanda
 * Date: 2016/4/25
 * Time: 14:36
 */

namespace MyClass\System;


use Illuminate\Support\Facades\Redis;
use MyClass\Model\Entrance;
use MyClass\Model\Operator;

class AccessTokenManage
{
    public static function setEntranceAccessToken(Entrance $entranceModel)
    {
        if($entranceModel->isNull())
        {
            return false;
        }
        $key = $entranceModel->id.rand(10000,99999).rand(100,999);
        $value = sha1($key);
        Redis::command("set",["warehouse:entrance:".$value,$entranceModel->id]);
        Redis::command("expire",["warehouse:entrance:".$value,
            config("my_config.redis_access_token_expire")]);
        return $value;
    }


    public static function checkEntranceAccessToken($accessToken)
    {
        $return = Redis::command("get",["warehouse:entrance:".$accessToken]);
        if($return != null)
        {

            return new Entrance($return);
        }
        return false;
    }

}