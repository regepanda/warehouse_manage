<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2016/4/21
 * Time: 19:05
 */


namespace App\Http\Controllers\Admin\Commodity;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use MyClass\Base\GuiFunction;
use MyClass\Model\Area;
use MyClass\Model\CommodityClass;


class CommodityClassController extends Controller
{
    public function __construct(GuiFunction $guiFunc)
    {

        $guiFunc->setModule("commodityManage");
    }


    /**
     * 获取商品类信息
     * 无需发送数据
     * 返回数据
     * |-status
     * |-data = [
     *            {
    commodityClass表的一条记录
    },
    {...},
    {...}
    ]
     * |-message
     */
    public function sCommodityClass()
    {
        $commodityClass = new CommodityClass();
        $returnCommodity = $commodityClass ->select(Request::all());
//        dump($returnCommodity);
        foreach($returnCommodity['data'] as $key => $value)
        {
            if(isset($value['area']) && $value['area']!=false)
            {
                $area = new Area($value['area']);
                if($area->isNull()){$returnCommodity['data'][$key]['area_name'] = null;}
                $returnCommodity['data'][$key]['area_name'] = $area->info['intro'];
                $returnCommodity['data'][$key]['area_id'] = $area->info['_id']->__toString();
            }
            else{$returnCommodity['data'][$key]['area_name'] = null;}
        }
        return response()->json($returnCommodity);
    }



    /**
     * 增加商品类
     * 发送数据
     * |-commodity_class_name
     * 返回数据
     * |-status
     * |-data = []
     * |-message
     */
    public function aCommodityClass()
    {
        $input = Request::only('commodity_class_name','commodity_class_area_num','area_id');
        if($input['area_id'] == null)
        {
            return response()->json(["status" => false, "message" => "添加商品类型失败,请选择区域进行绑定", "data" => []]);
        }
        $commodityClass = new CommodityClass();
        $returnAdd =  $commodityClass -> addCommodityClass($input["commodity_class_name"],$input['commodity_class_area_num'],$input['area_id']);
        if($returnAdd != false)
        {
            return response()->json(["status" => true, "message" => "添加商品类型成功", "data" => []]);
        }
        else
        {
            return response()->json(["status" => false, "message" => "添加商品类型失败", "data" => []]);
        }
    }



    /**
     * 修改商品类
     * 发送数据
     * |-commodity_class_id
     * |-commodity_class_name
     * 返回数据
     * |-status
     * |-data = []
     * |-message
     */
    public function uCommodityClass()
    {
        $input = Request::only('commodity_class_id','commodity_class_name','commodity_class_area','commodity_class_areaCapacity');
        if($input['commodity_class_area'] == null || $input['commodity_class_area'] == "")
        {
            return response()->json(["status" => false, "message" => "修改商品类型失败,没有选择区域", "data" => []]);
        }
        $commodityClass = new CommodityClass($input["commodity_class_id"]);

        $updateArray["name"] = $input["commodity_class_name"];
        $updateArray["area"] = $input["commodity_class_area"];
        $updateArray["areaCapacity"] = $input["commodity_class_areaCapacity"];

        //这里初始化该商品原来对应的区域和新绑定的区域
        CommodityClass::bingArea($input['commodity_class_id'],$updateArray["areaCapacity"],$updateArray["area"]);//新绑定的区域初始化
        if(isset($commodityClass->info['area']))
        {
            CommodityClass::cancelBind($commodityClass->info['area']);//还原旧区域
        }

        if($commodityClass->isNull() == false)
        {
            $returnUpdate =  $commodityClass ->update($updateArray);
            if($returnUpdate != false)
            {
                return response()->json(["status" => true, "message" => "修改商品类型成功！", "data" => []]);
            }
        }
        else
        {
            return response()->json(["status" => false, "message" => "修改商品类型失败！", "data" => []]);
        }

    }



    /**
     * 删除商品类
     * 发送数据
     * |-commodity_class_id
     * 返回数据
     * |-status
     * |-data = []
     * |-message
     */
    public function dCommodityClass()
    {
        $commodity_class = Request::only('commodity_class_id','commodity_class_area');
        $commodityClass = new CommodityClass($commodity_class["commodity_class_id"]);
        if($commodityClass ->isNull() == false)
        {
            $returnDelete =  $commodityClass ->delete();
            //删除后在恢复原始区域
            CommodityClass::cancelBind($commodity_class["commodity_class_area"]);//还原区域
            if($returnDelete != false)
            {
                return response()->json(["status" => true, "message" => "删除商品类型成功！", "data" => []]);
            }
        }
        else
        {
            return response()->json(["status" => false, "message" => "删除商品类型失败！", "data" => []]);
        }

    }
    /**
     * 获取所有区域信息，为商品类绑定区域
     * 发送数据
     * |-无
     * 返回数据
     * |-status
     * |-data = []
     * |-message
     */
    public function getArea()
    {
        $queryLimit = null;
        $area = new Area();
        $areaData = $area->select($queryLimit);
        foreach($areaData['data'] as $key => $value)
        {
            if(isset($value['commodityClass']))
            {
                unset($areaData['data'][$key]);
            }
        }
        return response()->json($areaData);
    }

}