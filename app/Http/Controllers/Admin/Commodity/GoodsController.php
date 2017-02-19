<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2016/5/8
 * Time: 20:32
 */


namespace App\Http\Controllers\Admin\Commodity;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use MyClass\Base\GuiFunction;
use MyClass\Model\Commodity;
use MongoId;
use MyClass\Model\CommodityClass;
use MyClass\Model\Goods;
use MyClass\Model\Area;


class GoodsController extends Controller
{

    public function __construct(GuiFunction $guiFunc)
    {

        $guiFunc->setModule("commodityManage");
    }


    /**
     * 获取货物信息
     * 无需发送数据
     * 返回数据
     * |-status
     * |-data = [
     *            {
    goods表的一条记录
    },
    {...},
    {...}
    ]
     * |-message
     */
    public function sGoods()
    {
        $queryLimit = Request::all();
        $goods = new Goods();
        $returnGoods = $goods ->select($queryLimit);

        $queryLimitCommodity["desc"] = true;

        $commodity = new Commodity();
        $returnCommodtiy = $commodity ->select($queryLimitCommodity);
        foreach($returnGoods["data"] as $key=>$value)
        {
            foreach($returnCommodtiy["data"] as $singleCommodity)
            {

                if($returnGoods["data"][$key]["commodity"] == null)
                {
                    continue;
                }
                if($returnGoods["data"][$key]["commodity"]->__toString() == $singleCommodity["_id"]->__toString())
                {
                    $returnGoods["data"][$key]["commodity_name"] = $singleCommodity["name"];
                }
            }
        }

        return response()->json($returnGoods);
    }


    /**
     * 获取货物区域信息
     * 无需发送数据
     * 返回数据
     * |-status
     * |-data = [
     *            {
    area表的一条记录
    },
    {...},
    {...}
    ]
     * |-message
     */
    public function sGoodsArea()
    {
        $queryLimit["desc"] = true;
        $area = new Area();
        $areaSelect = $area ->select($queryLimit);
        $areaData["data"] = $areaSelect["data"];
        if($areaData["data"] != null)
        {
            return response()->json(["status" => true, "data" => $areaData["data"] ]);
        }
        else
        {
            return response()->json(["status" => false, "data" => null ]);
        }
    }

    /**
     * 增加货物
     * 发送数据
     * |-goods_rfid
     * |-goods_tow_dimension
     * |-goods_area
     * |-goods_commodity
     * |-goods_bar_code
     * 返回数据
     * |-status
     * |-data = []
     * |-message
     */
    public function aGoods()
    {
        $input = Request::only('goods_rfid', 'goods_two_dimension', 'goods_area', 'goods_commodity', 'goods_bar_code');
        // dump($input);
        //  exit();
        $area = new Area($input["goods_area"]);
        if ($area->isNull())
        {
            return response()->json(["status" => false, "message" => "此区域不存在,添加货物失败", "data" => [] ]);
        }
        $goods = new Goods();
        if($input["goods_commodity"] == null)
        {
            $returnAdd = $goods -> addGoods($input["goods_rfid"],$input["goods_two_dimension"],$input["goods_bar_code"],$input["goods_area"],null);
        }
        else
        {
            //绑定商品
            $commodity = new Commodity($input["goods_commodity"]);
            if($commodity ->isNull())
            {
                return response()->json(["status" => false, "message" => "不存在此商品ID，添加货物失败", "data" =>[]]);
            }
            $input["goods_commodity"] = new MongoId($input["goods_commodity"]);
            $returnAdd = $goods -> addGoods($input["goods_rfid"],$input["goods_two_dimension"],$input["goods_bar_code"],$input["goods_area"],$input["goods_commodity"]);
            //添加货物成功后，把区域内的库存当前量加1
            $area = new Area();
            $area->inputGoods($returnAdd->_id);
        }

        if($returnAdd != false)
        {
            return response()->json(["status" => true, "message" => "添加货物成功", "data" => [] ]);
        }
        else
        {
            return response()->json(["status" => false, "message" => "添加货物失败", "data" =>[]]);
        }

    }

    /**
     * 修改货物
     * 发送数据
     * |-goods_id
     * |-goods_status
     * |-goods_rfid
     * |-goods_tow_dimension
     * |-goods_bar_code
     * |-goods_area
     * |-goods_commodity
     * 返回数据
     * |-status
     * |-data = []
     * |-message
     */
    public function uGoods()
    {
        $input = Request::only('goods_id','goods_status', 'goods_rfid', 'goods_two_dimension','goods_bar_code', 'goods_area','goods_commodity');
        $updateArray["status"] = (int)substr($input["goods_status"],7);
        $updateArray["rfid_key"] = $input["goods_rfid"];
        $updateArray["two_dimension_key"] = $input["goods_two_dimension"];
        $updateArray["bar_code_key"] = $input["goods_bar_code"];
        $updateArray["area"] = $input["goods_area"];
       // $updateArray["commodity"] = $input["goods_commodity"];
        $jqueryClass = "?";
        if($updateArray["area"] == $jqueryClass)
        {
            $updateArray["area"]= null;
        }
        else
        {
            $updateArray["area"]=  substr($updateArray["area"],7); //去掉string:,从第7个字母到最后，
            $updateArray["area"] = new MongoId($updateArray["area"]);
        }

        if($input["goods_commodity"] == "")
        {
            //修改绑定商品为空
            $updateArray["commodity"] = null;
        }
        else
        {
            //修改绑定商品不为空
             $commodity = new  Commodity($input["goods_commodity"]);
             if($commodity ->isNull())
             {
                 return response()->json(["status" => false, "message" => "所修改的绑定商品ID不存在，修改货物失败", "data" => [] ]);
             }
            else
            {
                $updateArray["commodity"] = new MongoId($input["goods_commodity"]);
            }
        }

        $goods = new Goods($input["goods_id"]);
        if($goods->isNull() == false)
        {
            $returnUpdate =  $goods ->update($updateArray);
            if($returnUpdate != false)
            {
                return response()->json(["status" => true, "message" => "修改货物成功", "data" => [] ]);
            }
        }
        else
        {
            return response()->json(["status" => false, "message" => "修改货物失败", "data" => [] ]);
        }

    }


    /**
     * 删除货物
     * 发送数据
     * |-goods_id
     * 返回数据
     * |-status
     * |-data = []
     * |-message
     */
    public function dGoods()
    {
        $goods = Request::only("goods_id");
        $goodsObject = new Goods($goods["goods_id"]);
        if($goodsObject ->isNull() == false)
        {
            //把该货物对应的区域当前量减1
            $area = new Area();
            $area->outputGoods($goods["goods_id"]);
            //删除货物
            $returnDelete =  $goodsObject->delete();
            if($returnDelete != false)
            {
                return response()->json(["status" => true, "message" => "删除货物成功", "data" => [] ]);
            }
        }
        else
        {
            return response()->json(["status" => false, "message" => "删除货物失败", "data" => [] ]);
        }

    }

}