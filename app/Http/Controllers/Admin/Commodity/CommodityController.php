<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2016/4/21
 * Time: 19:04
 */

namespace App\Http\Controllers\Admin\Commodity;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use MyClass\Base\GuiFunction;
use MyClass\Model\Commodity;
use MongoId;
use MyClass\Model\CommodityClass;


class CommodityController extends Controller
{

    public function __construct(GuiFunction $guiFunc)
    {

        $guiFunc->setModule("commodityManage");
    }


    /**
     * 获取商品信息
     * 无需发送数据
     * 返回数据
     * |-status
     * |-data = [
     *            {
    commodity表的一条记录
     ,className:""
    },
    {...},
    {...}
    ]
     * |-message
     */
    public function sCommodity()
    {
        $queryLimit = Request::all();
        $commodity = new Commodity();
        $returnCommodity = $commodity ->select($queryLimit);
        $commodityClass = new CommodityClass();
        $queryLimitClass["desc"] = true;
        $returnClass = $commodityClass -> select($queryLimitClass);
        //在$returnCommodity["data"]中加入健值对：健名：className
        foreach($returnCommodity["data"] as $commodityKey=>$value)
        {
            foreach($returnClass["data"] as $singleCommodityClass)
            {
                if($value["class"] == null)
                {
                    $returnCommodity["data"][$commodityKey]["className"] = "";
                    continue;
                }
                if($value["class"]->__toString() == $singleCommodityClass["_id"]->__toString() )
                {
                    $returnCommodity["data"][$commodityKey]["className"] = $singleCommodityClass["name"];
                }
            }
        }
        return response()->json($returnCommodity);
    }


    /**
     * 增加商品
     * 发送数据
     * |-commodity_name
     * |-commodity_price
     * |-commodity_model
     * |_commodity_class
     * |-commodity_detail
     * 返回数据
     * |-status
     * |-data = []
     * |-message
     */
  public function aCommodity()
  {

     $input = Request::only('commodity_name', 'commodity_price', 'commodity_model', 'commodity_class','commodity_detail');
     $commodity = new Commodity();
     $returnAdd =  $commodity -> addCommodity($input["commodity_name"],(float)$input["commodity_price"],$input["commodity_class"],$input["commodity_detail"],$input["commodity_model"]);
      if($returnAdd != false)
      {
          return response()->json(["status" => true, "message" => "添加商品成功", "data" => [] ]);
      }
      else
      {
          return response()->json(["status" => false, "message" => "获取商品失败", "data" =>[]]);
      }
  }


        /**
     * 修改商品
     * 发送数据
     * |-commodity_id
     * |-commodity_name
     * |-commodity_price
     * |-commodity_model
     * |_commodity_class
     * |_commodity_detail
     * 返回数据
     * |-status
     * |-data = []
     * |-message
     */
    public function uCommodity()
    {
        $input = Request::only('commodity_id','commodity_name', 'commodity_price', 'commodity_model', 'commodity_class','commodity_detail');
        $updateArray["name"] = $input["commodity_name"];
        $updateArray["price"] = (int)$input["commodity_price"];
        $updateArray["model"] = $input["commodity_model"];
        $updateArray["class"] = $input["commodity_class"];
        $updateArray["detail"] = $input["commodity_detail"];

        $jqueryClass = "?";
        if($updateArray["class"] == $jqueryClass)
        {
            $updateArray["class"]= null;
        }
        else
        {
            $updateArray["class"]=  substr($updateArray["class"],7); //去掉string:,从第7个字母到最后，
            $updateArray["class"] = new MongoId($updateArray["class"]);
        }

        $commodity = new Commodity($input["commodity_id"]);
        if($commodity->isNull() == false)
        {
            $returnUpdate =  $commodity ->update($updateArray);
            if($returnUpdate != false)
            {
                return response()->json(["status" => true, "message" => "修改商品成功", "data" => [] ]);
            }
        }
        else
        {
            return response()->json(["status" => false, "message" => "修改商品失败", "data" => [] ]);
        }

    }


    /**
     * 删除商品
     * 发送数据
     * |-commodity_id
     * 返回数据
     * |-status
     * |-data = []
     * |-message
     */
    public function dCommodity()
    {
        $commodity = Request::only("commodity_id");
        $commodity = new Commodity($commodity["commodity_id"]);
        if($commodity ->isNull() == false)
        {
           $returnDelete =  $commodity ->delete();
            if($returnDelete != false)
            {
                return response()->json(["status" => true, "message" => "删除商品成功", "data" => [] ]);
            }
        }
        else
        {
            return response()->json(["status" => false, "message" => "删除商品失败", "data" => [] ]);
        }

    }






}