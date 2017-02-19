<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2016/4/22
 * Time: 10:53
 */

namespace App\Http\Controllers\Admin\Operator;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use MyClass\Base\GuiFunction;
use MyClass\Model\OperatorGroup;
use MyClass\Model\Operator;
use MongoId;

class OperatorPowerGroupController extends Controller
{

    public function __construct(GuiFunction $guiFunc)
    {
        $guiFunc->setModule("powerManage");
    }

    public function sOperatorPowerGroup(GuiFunction $guiFunc)
    {

        $guiFunc->setSecondModule("sOperatorPowerGroup");
        $queryLimit["desc"] = true;
        $operatorGroup = new OperatorGroup();
        $returnOperatorGroup = $operatorGroup ->select($queryLimit);
        $operatorGroupData["data"] = $returnOperatorGroup["data"];  //操作员组信息
        return view("Admin.sOperatorPowerGroup",$operatorGroupData);
    }

    public function aOperatorPowerGroup(\MyClass\Base\GuiFunction $gui)
    {

        $input = Request::only( 'operator_group_name');
        $operatorGroup = new OperatorGroup();
        $returnAdd = $operatorGroup -> addOperatorGroup($input["operator_group_name"]);
        if($returnAdd != false)
        {
            $gui->setMessage(true,"添加操作员权限组成功！");
            return redirect()->back();
        }
        else
        {
            $gui->setMessage(false,"添加操作员权限组失败！");
            return redirect()->back();
        }

    }

    public function uOperatorPowerGroup(\MyClass\Base\GuiFunction $gui)
    {
        $input = Request::only('operator_group_id','operator_group_name');
        $updateArray["name"] = $input["operator_group_name"];
        $operatorGroup = new OperatorGroup($input["operator_group_id"]);
        if($operatorGroup ->isNull() == false)
        {
            $returnUpdate = $operatorGroup -> update($updateArray);
            if($returnUpdate != false)
            {
                $gui->setMessage(true,"修改操作员权限组信息成功！");
                return redirect()->back();
            }
        }
        else
        {
            $gui->setMessage(false,"修改操作员权限组信息失败！");
            return redirect()->back();
        }
    }

    public function dOperatorPowerGroup($operator_group_id,\MyClass\Base\GuiFunction $gui)
    {
        $operatorGroup = new OperatorGroup($operator_group_id);
        if($operatorGroup ->isNull() == false)
        {
            $returnDelete =  $operatorGroup ->delete();
            if($returnDelete != false)
            {
                $gui->setMessage(true,"删除该操作员权限组成功！");
                return redirect()->back();
            }
        }
        else
        {
            $gui->setMessage(false,"删除该操作员权限组失败！");
            return redirect()->back();
        }
    }




    //对权限组内部的增删改查

    public function moreOperatorPowerGroup($group_id)
    {
        $operatorGroup = new OperatorGroup($group_id);
        $operatorGroupData["data"] = $operatorGroup ->info;
        //所有固定的权限
        $all_power = $operatorGroup::$power;
        $operatorGroupData["all_power"] = $all_power;
        //所有固定的操作员
        $operator = new Operator();
        $queryLimit["desc"] = true;
        $returnOperator = $operator -> select($queryLimit);
        $operatorGroupData["all_operator"] = $returnOperator["data"];
      //该权限组所有操作员
        $operator_ids = array();
        foreach ($operatorGroupData["data"]["operator_list"] as $value) {
            $operator_ids[] = $value["operator_id"];
        }
        $operatorGroupData["operator_ids"] = $operator_ids;
      //该权限组所有权限
        $power_ids = array();
        foreach ($operatorGroupData["data"]["power_list"] as $value) {
            $power_ids[] = $value["power_name"];
        }
        $operatorGroupData["power_ids"] = $power_ids;

        return view("Admin.moreOperatorPowerGroup",$operatorGroupData);
    }


    public function removeOperatorToOperatorPowerGroup(\MyClass\Base\GuiFunction $gui)
    {
        $input = Request::only("group_id","operator_id");
        $operatorGroup = new OperatorGroup($input["group_id"]);
        if($operatorGroup -> isNull() == false) {
            $returnDelete = $operatorGroup->deleteOperator($input["operator_id"]);
            if ($returnDelete != false) {
                //把operator的group置空
                $operator = new Operator($input["operator_id"]);
                if($operator ->isNull() == false)
                {
                   $returnSet =  $operator ->setPowerGroup(null);
                    if($returnSet)
                    {
                        $gui->setMessage(true, "从此操作员组移除操作员成功！");
                        return redirect()->back();

                    }
                }
            }
        }
        else
        {
            $gui->setMessage(false,"从此管理员组移除管理员失败！");
            return redirect()->back();
        }
    }


    public function addPowerToOperatorPowerGroup(\MyClass\Base\GuiFunction $gui)
    {

        $input = Request::only("group_id","power_id_array");
        $power = [
            "出库"=>0,
            "入库"=>1,
            "冻结商品"=>2,
        ];


        if($input["power_id_array"] == null) //若未选择任何权限就点击添加
        {
            $gui->setMessage(false,"您未选择任何权限，在此操作员组添加权限失败！");
            return redirect()->back();
        }

        $operatorGroup = new OperatorGroup($input["group_id"]);
        if($operatorGroup ->isNull() == false)
        {
            foreach($input["power_id_array"] as $powerValue)
            {
                $operatorGroup -> addPower($power["$powerValue"]);
            }
            $gui->setMessage(true,"在此操作员组添加权限成功！");
            return redirect()->back();

        }
        else
        {
            $gui->setMessage(false,"在此操作员组添加权限失败！");
            return redirect()->back();
        }

    }


    public function addOperatorToOperatorPowerGroup(\MyClass\Base\GuiFunction $gui)
    {
        $input = Request::only('group_id','operator_id_array');

        if($input["operator_id_array"] == null) //若未选择任何操作员就点击添加
        {
            $gui->setMessage(false,"您未选择任何操作员，在此操作员组添加权限失败！");
            return redirect()->back();
        }

        $admin_id_array = $input["operator_id_array"];
        $operatorGroup = new OperatorGroup($input["group_id"]);
        if($operatorGroup ->isNull() == false )
        {
            foreach($admin_id_array as $operatorId)
            {
                //1.改变operator的group为当前group
                $operator = new Operator($operatorId);
                $input["group_id"] = new MongoId($input["group_id"]);
                $operator ->setPowerGroup($input["group_id"]);

                    //2.判断此操作员是否已经有权限组
                    $operatorGroupSelect = new OperatorGroup();
                    $queryLimit["operator_id"] = new MongoId($operatorId);
                    $returnSelect = $operatorGroupSelect -> select($queryLimit);
                  //  dump($returnSelect);
                  //  exit();
                    if($returnSelect["data"] != null)
                    {
                        //3.如果此操作员有权限组了，移除
                        $oldGroupId = $returnSelect["data"][0]["_id"];
                        $oldGroup = new OperatorGroup($oldGroupId);
                        $oldGroup ->deleteOperator($operatorId);
                    }


                $returnAdd = $operatorGroup -> addOperator($operatorId);
                if($returnAdd != false)
                {
                   continue;
                }
            }
            $gui->setMessage(true,"在此操作员组添加操作员成功！");
            return redirect()->back();
        }
        else
        {
            $gui->setMessage(false,"在此操作员组添加操作员失败！");
            return redirect()->back();
        }

    }



    public function removePowerToOperatorPowerGroup(\MyClass\Base\GuiFunction $gui)
    {
        $powerId = (int)($_POST["power_id"]);
        $groupId = $_POST["group_id"];


        $operatorGroup = new OperatorGroup($groupId);
        if($operatorGroup ->isNull() == false)
        {
            $returnDelete = $operatorGroup ->deletePower($powerId);
            if($returnDelete != false)
            {
                $gui->setMessage(true,"在此操作员组中移除该权限成功！");
                return redirect()->back();
            }
        }
        else
        {
            $gui->setMessage(false,"在此操作员组中移除该权限失败！");
            return redirect()->back();
        }


    }





}