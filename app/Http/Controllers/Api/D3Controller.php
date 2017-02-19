<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2016/5/24
 * Time: 20:54
 */

namespace App\Http\Controllers\Api;

use MyClass\Model\Environment;
use App\Http\Controllers\Controller;
use MyClass\Model\Area;


class D3Controller extends Controller
{

    /**
     *  获取区域数据  GET /api_D3_getArea
     * 返回数据
     * |-data = [
    记录，
     *     记录
     *  ，，，，，，
     * ]
     */
    public function getArea()
    {

        $area = new Area();
       // $area = new testArea();
      //  $query["desc"] = true;
        $query["ss"] =0;
        $returnSelect =  $area -> select($query);

        if($returnSelect["data"] == null)
        {
            return response()->json([]);
        }
        return response()->json( $returnSelect["data"]);
    }

    /**
     *   获取环境数据  GET /api_D3_getArea
     * 返回数据
     * |-data = [
          记录，
          记录
     *  ，，，，，，
     * ]
     */
    public function getEnvironment()
    {

        $en = new Environment();
        $query["ss"] =0;
        $query["desc"] = true;
        $query["num"] = 10;
        $returnSelect =  $en -> select($query);
        if($returnSelect["data"] == null)
        {
            return response()->json([]);
        }
        return response()->json( $returnSelect["data"]);


    }

}