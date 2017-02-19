<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2016/4/22
 * Time: 10:52
 */
namespace App\Http\Controllers\Admin\Operator;


 use App\Http\Controllers\Controller;
 use Illuminate\Support\Facades\Request;
 use MyClass\Base\GuiFunction;
 use MongoId;
 use MyClass\Model\Operator;
 use MyClass\Model\OperatorGroup;
 use MyClass\Model\Image;
 use MyClass\Model\System;
 use MyClass\Module\Face;

 class OperatorController extends Controller
{

     public $guiFunction;
    public function __construct(GuiFunction $guiFunc)
    {
        $guiFunc->setModule("powerManage");
        $this->guiFunction = $guiFunc;
    }

    public function sOperator(GuiFunction $guiFunc)
    {
        $guiFunc->setSecondModule("sOperator");
        $queryLimit["desc"] = true;
        $operator = new Operator();
        $returnOperator = $operator -> select($queryLimit);
        $operatorData["data"] = $returnOperator["data"];   //操作员信息
        $operatorGroup = new OperatorGroup();
        $returnOperatorGroup = $operatorGroup ->select($queryLimit);
        $operatorData["groupData"] = $returnOperatorGroup["data"];  //操作员组信息
        return view("Admin.sOperator",$operatorData);
    }

    /**
     * 添加操作员
     * 1.先在操作员表内添加一个操作员
     * 2.往指定权限组内添加该操作员
     * @param GuiFunction $gui
     * @return \Illuminate\Http\RedirectResponse
     */
    public function aOperator(\MyClass\Base\GuiFunction $gui)
    {
        $input = Request::only('operator_name', 'operator_username', 'operator_password', 'operator_group'
                              ,'operator_rfid_key','operator_finger_key');
        $input["operator_group"] = new MongoId($input["operator_group"]);
        $input["operator_password"] = md5($input["operator_password"]);
        $operator = new Operator();
        $operatorGroup = new OperatorGroup($input["operator_group"]);

        if($operatorGroup->isNull() == false)
        {
           // 1.先在操作员表内添加一个操作员
            $returnAdd = $operator -> addOperator($input["operator_username"],$input["operator_password"],$input["operator_name"],$input["operator_group"]
                                                 ,$input["operator_rfid_key"],$input["operator_finger_key"]);
            if($returnAdd != false)
            {
                //2.往指定权限组内添加该操作员
                $returnAddGroup = $operatorGroup ->addOperator($returnAdd->info["_id"]);
                if($returnAddGroup != false)
                {
                    $gui->setMessage(true,"添加操作员成功！");
                    return redirect()->back();
                }
            }
        }
        else
        {
            $gui->setMessage(false,"添加操作员失败！");
            return redirect()->back();
        }

    }




    public function uOperator(\MyClass\Base\GuiFunction $gui)
    {
        $input = Request::only('operator_id','operator_name','operator_username', 'operator_group','operator_rfid_key','operator_finger_key');
        $updateArray["username"] = $input["operator_username"];
        $updateArray["name"] = $input["operator_name"];
        $updateArray["group"] = new MongoId($input["operator_group"]);
        $updateArray["rfid_key"] = $input["operator_rfid_key"];
        $updateArray["finger_key"] = $input["operator_finger_key"];

        $operator = new Operator($input["operator_id"]);
        if($operator ->isNull() == false)
        {
            $returnUpdate = $operator ->update($updateArray);
            if($returnUpdate != false)
            {
                $gui->setMessage(true,"修改操作员信息成功！");
                return redirect()->back();
            }
        }
        else
        {
            $gui->setMessage(false,"修改操作员信息失败！");
            return redirect()->back();
        }
    }

    public function dOperator($operator_id,\MyClass\Base\GuiFunction $gui)
    {
        $operator = new Operator($operator_id);

        //查看此操作员是否有对应的权限组,如果有则从此权限组移除该操作员
        $groupId = $operator -> info["group"];
        if($groupId != null)
        {
            $group = new OperatorGroup($groupId);
            if($group ->isNull() == false)
            {
              $group ->deleteOperator($operator_id);
            }
        }

        if($operator ->isNull() == false)
        {
            $returnDelete =  $operator ->delete();
            if($returnDelete != false)
            {
                $gui->setMessage(true,"删除该操作员成功！");
                return redirect()->back();
            }
        }
        else
        {
            $gui->setMessage(false,"删除该操作员失败！");
            return redirect()->back();
        }
    }



    //上传操作员图片
    public function aOperatorImage(\MyClass\Base\GuiFunction $gui)
    {
        if (Request::hasFile('operator_image'))
        {
            $imageFile = Request::file('operator_image');

            $input["image_name"] = $_POST["image_name"];
            $input["operator_id"] = new MongoId($_POST["operator_id"]);
            $image = new Image();
            $returnImage = $image -> putImage($input,$imageFile);   //1.添加图片到图片表
            if($returnImage != false)
            {
                //2.添加图片id到operator的operator表的image
                $imageId = $returnImage -> info["_id"];
                $operator = new Operator($_POST["operator_id"]);
                if($operator -> isNull() == false)
                {
                    $returnPush = $operator -> pushImage($imageId);
                    if($returnPush != false) {
                        $gui->setMessage(true, "添加操作员图片成功！");
                        return redirect()->back();
                    }
                }
            }
        }
        else
        {
            $gui->setMessage(false,"添加操作员图片失败！");
            return redirect()->back();
        }

    }


    public function sOperatorImage($operator_id,\MyClass\Base\GuiFunction $gui)
    {
        $operator = new Operator($operator_id);
        $data= Array();
        if($operator -> isNull() == false)
        {
            $operatorImages = $operator ->info["image"];
            if($operatorImages == null)
            {
                $gui->setMessage(false,"此操作员暂无图片,请上传图片！");
                return redirect("/operator_sOperator");
            }
            foreach($operatorImages as $imageId)
            {
                $image = new Image($imageId);
                if($image ->isNull() == true)
                {
                    continue;
                }
                $data[] = $image ->info;
            }
            $imageData["data"] = $data;
            return view("Admin.sOperatorImage",$imageData);
        }
        else{
            $gui->setMessage(false,"不存在此操作员");
            return redirect()->back();
        }

    }

    public function dOperatorImage($image_id,\MyClass\Base\GuiFunction $gui)
    {
        $image = new Image($image_id);
        if($image ->isNull() == false)
        {
            $returnDelete = $image ->deleteImage();   //删除image表中的
            if($returnDelete != false)
            {
                $operatorId = $image -> info["operator"];
                $operator = new Operator($operatorId);
                if($operator -> isNull() == false)
                {
                    $imageId = $image ->_id;
                    $returnDeleteImage = $operator -> deleteImage($imageId);   //删除operator中的
                    if($returnDeleteImage != false)
                    {

                        $gui->setMessage(true,"删除操作员图片成功！");
                        return redirect()->back();
                    }
                }
            }
        }
        else
        {
            $gui->setMessage(false,"删除操作员图片失败！");
            return redirect()->back();
        }
    }

    //访问图片
    public function getImage($image_id = 0)
    {
        ob_end_clean();
        $image = new Image();
        $image ->getImage($image_id);
    }

    //训练，交由服务器处理相应图片
    public function practiceOperatorImage($image_id,\MyClass\Base\GuiFunction $gui)
    {
        $image = new Image($image_id);
        if(!$image -> isNull())
        {
            $path = $image ->info["path"];
            $imageFile = file_get_contents($_SERVER["DOCUMENT_ROOT"].$path);  //取得文件实体
            $size = filesize($_SERVER["DOCUMENT_ROOT"].$path);    //取得文件大小

            //获取face_key
            $operatorId = $image ->info["operator"]->__toString();
            $operator = new Operator($operatorId);
            if(!$operator -> isNull() )
            {
                $label_id = $operator ->info["face_key"];  //获取$label_id

                Face::pushTrainImage($label_id,$imageFile,$size,'update');
                $image->setHaveTrain();
                $this->guiFunction->setMessage(true,"训练完成");
                return redirect()->back();
            }
        }
        $this->guiFunction->setMessage(true,"训练失败");
        return redirect()->back();
    }

    //重新训练，交由服务器处理相应图片
    public function practiceAgainOperatorImage($image_id)
    {
        $image = new Image($image_id);
        if(!$image -> isNull())
        {

            $path = $image ->info["path"];
            $imageFile = file_get_contents($_SERVER["DOCUMENT_ROOT"].$path);  //取得文件实体
            $size = filesize($_SERVER["DOCUMENT_ROOT"].$path);    //取得文件大小

            //dump($image);
            //获取face_key
            $operatorId = $image ->info["operator"]->__toString();
            $operator = new Operator($operatorId);
            //dump($operator);
            if(!$operator -> isNull())
            {
                //dump($operator);
                $label_id = $operator ->info["face_key"];  //获取$label_id

                //$face = new Face();
                Face::pushTrainImage($label_id,$imageFile,$size,'train');
                foreach($operator->info["image"] as $value)
                {
                    $imageOther = new Image($value);
                    $imageOther->cancelTrain();
                }
                $image->setHaveTrain();
                $this->guiFunction->setMessage(true,"已经覆盖原有训练数据");
                return redirect()->back();
            }
        }
        $this->guiFunction->setMessage(false,"覆盖失败");
        return redirect()->back();
    }



}
