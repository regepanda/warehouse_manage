<?php
/**
 * Created by PhpStorm.
 * User: RagPanda
 * Date: 2016/3/26
 * Time: 14:26
 */
return [
    "redis_access_token_expire"=>7*60*60*24,//7天
    "image_upload_dir"=>"/../storage/app/image/",

    //module config
    "face_recognize_list_key"=>"warehouse:faceRecognizeList",
    "face_recognize_size_list"=>"warehouse:faceRecognizeSizeList",
    "face_recognize_entrance_list"=>"warehouse:faceRecognizeEntranceList",

    "face_train_list" => "warehouse:faceTrainList",
    "face_train_size_list" =>"warehouse:faceTrainSizeList",
    "face_train_label_list" => "warehouse:faceTrainLabelList",
    "face_train_action_list" => "warehouse:faceTrainActionList",



    "device_instruct_url" => "http://172.28.47.2:8080/",
];