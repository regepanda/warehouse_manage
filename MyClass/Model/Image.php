<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2016/5/6
 * Time: 21:18
 */

namespace MyClass\Model;


use MyClass\DatabaseModel;

class Image extends DatabaseModel
{
    public $info;   //和mysql不同 这里是数组形式的
    public $id;     //字符串类型id
    public $_id;    //对象型id
    protected $isNull; //是否有效
    protected $collection = "image";




    public function putImage($inputData, $file)
    {


        //1.文件移动
        $storage_path = config("my_config.image_upload_dir") . session("admin.admin_id");  //存贮文件的相对路径
        $path = $_SERVER['DOCUMENT_ROOT'] . $storage_path;  //存贮文件的绝对路径
        $name = date('YmdHis') . session("admin.admin_id") . rand(1000, 9999) . "." . $file->getClientOriginalExtension();  //自动生成路径

        //2.数据库添加
        //获取与前端file相关的数据库量
        $inputData["format"] = $file->getClientOriginalExtension();   //文件格式
        $inputData["path"] = $storage_path."/" . $name;  //相对路径/自动生成名

      //  DB::beginTransaction();


        //自增
        $system = new System();
        $addName = "image_num";
        $imageID = $system ->addSelf($addName);
        if($imageID == false)
        {
            return false;
        }

        $result = $this->add(["ID"=>$imageID,"operator"=>$inputData["operator_id"],"name"=>$inputData["image_name"],"format"=>$inputData["format"],"path"=>$inputData["path"],"practice"=>false]);
        if($result == false)
        {
            return false;
        }
        else
        {
            $moveReturn = $file->move($path, $name);  //移动文件到指定目录
            if ($moveReturn) {
       //         DB::commit();  //若移动文件或添加进数据库失败，则事务回滚
                return new Image($result);
            }
        }

    }



    /*

     /*
     * 上传图片(把图片移到本地文件夹,并且添到数据库)
     * @param $inputData
     * @param $file
     * @return bool
     */
    /*
    public static function putImage($inputData, $file)
    {
        //验证字段
        $errorInfo["required"] = ":attribute必填";
        $errorInfo["max"] = ":attribute不应大于20字节";

        $validator = Validator::make($inputData, [
            'image_name' => 'required|max:20',
        ], $errorInfo);
        if ($validator->fails()) {
            $messages = $validator->errors();
            $errorStr = "";
            foreach ($messages->all() as $message) {
                $errorStr .= $message . " | ";
            }
            throw new SysValidatorException("字段格式有错误！" . $errorStr, "/admin_api_sImage");
        }

        //1.文件移动
        $storage_path = config("my_config.image_upload_dir") . session("user.user_id");  //存贮文件的相对路径
        $path = $_SERVER['DOCUMENT_ROOT'] . $storage_path;  //存贮文件的绝对路径
        $name = date('YmdHis') . session("user.user_id") . rand(1000, 9999) . "." . $file->getClientOriginalExtension();  //自动生成路径

        //2.数据库添加
        //获取与前端file相关的数据库量
        $inputData["image_format"] = $file->getClientOriginalExtension();   //文件格式
        $inputData["image_path"] = $storage_path . $name;  //相对路径/自动生成名
        $inputData["image_create_time"] = date('Y-m-d H:i:s');
        $inputData["image_update_time"] = date('Y-m-d H:i:s');

        DB::beginTransaction();
        $add = DB::table('image')
            ->insert($inputData);
        if ($add) {
            //上传图片，成功时添加日志
            $message = date("Y-m-d H-i-s") . session("admin.admin_nickname") . "管理员上传图片成功";
            $admin = session("admin.admin_id");
            $level = DBLog::INFO;
            $logData = "上传图片";
            DBLog::adminLog($message, $admin, $level, $logData);

            $moveReturn = $file->move($path, $name);  //移动文件到指定目录
            if ($moveReturn) {
                DB::commit();  //若移动文件或添加进数据库失败，则事务回滚
                return true;
            }
            return false;
        } else {
            //上传图片，失败时添加日志
            $message = date("Y-m-d H-i-s") . session("admin.admin_nickname") . "管理员上传图片失败";
            $admin = session("admin.admin_id");
            $level = DBLog::ERROR;
            $logData = "上传图片";
            DBLog::adminLog($message, $admin, $level, $logData);
            return false;
        }
    }

*/
    /**
     * 访问图片
     * @param $image_id
     */

    public function getImage($image_id)
    {
        if ($image_id == 0) {
            header("Content-type:image/jpeg");
            readfile($_SERVER["DOCUMENT_ROOT"] . "/image/default.jpg");
        }
        $queryLimit["id"] = $image_id;
        $returnSelect = $this ->select($queryLimit);
        if($returnSelect["data"] != []) {
            $path = $returnSelect["data"][0]["path"];
            $format = $returnSelect["data"][0]["format"];
            switch ($format) {
                case "gif":
                    $ctype = "image/gif";
                    break;
                case "png":
                    $ctype = "image/png";
                    break;
                case "jpeg":
                case "jpg":
                    $ctype = "image/jpeg";
                    break;
                default:
                    $ctype = "image/jpeg";
            }
            header('Content-type: ' . $ctype);
            readfile($_SERVER['DOCUMENT_ROOT'] . $path);  //读文件并返回
        }else
        {
            header("Content-type:image/jpeg");
            readfile($_SERVER["DOCUMENT_ROOT"] . "/images/default.jpg");
        }


        /*

        $imageData = DB::table("image")
            ->where("image_id", "=", $image_id)
            ->first();
        if ($imageData != NULL) {
            $path = $imageData->image_path;
            $format = $imageData->image_format;
            switch ($format) {
                case "gif":
                    $ctype = "image/gif";
                    break;
                case "png":
                    $ctype = "image/png";
                    break;
                case "jpeg":
                case "jpg":
                    $ctype = "image/jpeg";
                    break;
                default:
                    $ctype = "image/jpeg";
            }
            header('Content-type: ' . $ctype);
            readfile($_SERVER['DOCUMENT_ROOT'] . $path);  //读文件并返回
        } else //如果没有图片的，换上一张默认图片
        {
            header("Content-type:image/jpeg");
            readfile($_SERVER["DOCUMENT_ROOT"] . "/images/default.jpg");
        }
        */
    }


    public function deleteImage()
    {
        $imageInfo = $this->info;
        //判断数据库是否存在此图片
        if ($imageInfo == null) {
            return false;
        }

        //1.先删除数据库的
     //   DB::beginTransaction();    //开始事务
        $returnDelete =  $this ->delete();
        if($returnDelete == false)
        {
            return false;
        }
        //2.删文件里的
        $getPath = $_SERVER['DOCUMENT_ROOT'] . $imageInfo["path"];  //提取路径
        if (unlink($getPath)) { //unlink是删除里面的路径
    //        DB::commit(); //提交事务
            return true;
        }
        return false;




        /*
        $count = DB::table('image')->where('image_user', '=', $userId)->where('image_id', '=', $imageId)->delete();  //先删数据库的
        if ($count == 0) {
            return false;
        }

        //2.删文件里的
        $getPath = $_SERVER['DOCUMENT_ROOT'] . $imageInfo->image_path;  //提取路径
        if (unlink($getPath)) { //unlink是删除里面的路径
            DB::commit(); //提交事务
            return true;
        }
        return false;
        */

    }

    public function setHaveTrain()
    {

        return $this->update(["practice"=>true]);

    }

    public function cancelTrain()
    {
        return $this->update(["practice"=>false]);
    }



}

