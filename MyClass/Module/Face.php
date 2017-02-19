<?php
/**
 * Created by PhpStorm.
 * User: ragpanda
 * Date: 16-5-8
 * Time: 上午11:05
 */

namespace MyClass\Module;


use Illuminate\Support\Facades\Redis;

class Face
{
    public function __construct()
    {

    }

    static public function pushRecognizeData($entrance_str_id,&$face_binary_data,$size)
    {
        //module config
        /*
        "face_recognize_list_key"=>"warehouse:faceRecognizeList",
        "face_recognize_size_list"=>"warehouse:faceRecognizeSizeList",
        "face_recognize_entrance_list"=>"warehouse:faceRecognizeEntranceList",

        "face_train_list" => "warehouse:faceTrainList",
        "face_train_size_list" =>"warehouse:faceTrainSizeList",
        "face_train_label_list" => "warehouse:faceTrainLabelList"
        */

        $list_key = config("my_config.face_recognize_list_key");
        $size_key = config("my_config.face_recognize_size_list");
        $entrance_key = config("my_config.face_recognize_entrance_list");
        Redis::command("rpush",[$entrance_key,$entrance_str_id]);
        Redis::command("rpush",[$size_key,$size]);
        Redis::command("rpush",[$list_key,$face_binary_data]);

        /*
        Redis::transaction()
            ->command("rpush",[$entrance_key,$entrance_str_id])
            ->command("rpush",[$size_key,$size])
            ->command("rpush",[$list_key,$face_binary_data])
            ->execute();*/

        return true;

    }
    static public function pushTrainImage($label_id,$face_binary_data,$size,$action="update")
    {
        $list_key = config("my_config.face_train_list");
        $size_key = config("my_config.face_train_size_list");
        $label_key = config("my_config.face_train_label_list");
        $action_key = config("my_config.face_train_action_list");
        Redis::command("rpush",[$label_key,$label_id]);
        Redis::command("rpush",[$size_key,$size]);
        Redis::command("rpush",[$action_key,$action]);
        Redis::command("rpush",[$list_key,$face_binary_data]);
    }

    //如果出错，清空队列
    static public function clean()
    {

    }
}