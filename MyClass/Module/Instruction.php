<?php
/**
 * Created by PhpStorm.
 * User: RagPanda
 * Date: 2016/5/13
 * Time: 14:04
 */

namespace MyClass\Module;


class Instruction
{
    public static function addInstruction($instruction,$device,$config=null)
    {
        $url = config("my_config.device_instruct_url");
        $data["instruct"] = $instruction;
        $data["device"] = $device;
        //exit();
        if(isset($config)){$data["config"] = $config;}

        $response = \Requests::POST($url,[],$data);
        //dump($response);
        return true;

    }
}