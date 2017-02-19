<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2016/4/25
 * Time: 11:08
 */
namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use MyClass\Base\GuiFunction;
use MyClass\Model\Area;


class AreaController extends Controller
{
    public function __construct(GuiFunction $guiFunc)
    {
        $guiFunc->setModule("sArea");
    }

    public function sArea()
    {
        $queryLimit["desc"] = true;
        $area = new Area();
        $areaSelect = $area ->select($queryLimit);
        $areaData["data"] = $areaSelect["data"];
        return view("Admin.areaManage",$areaData);
    }

    public function aArea(\MyClass\Base\GuiFunction $gui)
    {

        $input = Request::only("area_no","area_distance","area_intro");

        $area = new Area();
        $areaAdd = $area -> addArea($input["area_intro"],(int)($input["area_distance"]),$input["area_no"]);
        if($areaAdd != false)
        {
            $gui->setMessage(true,"添加区域成功！");
            return redirect()->back();
        }
        else
        {
            $gui->setMessage(false,"添加区域失败！");
            return redirect()->back();
        }
    }

    public function uArea(\MyClass\Base\GuiFunction $gui)
    {
        $input = Request::only("area_id","area_no","area_distance","area_intro");
        $area = new Area($input["area_id"]);
        $updateArray["no"] = $input["area_no"];
        $updateArray["distance"] = (int)($input["area_distance"]);
        $updateArray["intro"] = $input["area_intro"];
        if($area ->isNull() == false)
        {
            $returnUpdate = $area ->update($updateArray);
            if($returnUpdate != false)
            {
                $gui->setMessage(true,"修改区域成功！");
                return redirect()->back();
            }
        }
        else
        {
            $gui->setMessage(false,"修改区域失败！");
            return redirect()->back();
        }

    }

    public function dArea($area_id,\MyClass\Base\GuiFunction $gui)
    {

        $area = new Area($area_id);
        if($area -> isNull() == false)
        {
            $returnDelete = $area ->delete();
            if($returnDelete != false)
            {
                $gui->setMessage(true,"删除区域成功！");
                return redirect()->back();
            }
        }
        else
        {
            $gui->setMessage(false,"删除区域失败！");
            return redirect()->back();
        }

    }
}