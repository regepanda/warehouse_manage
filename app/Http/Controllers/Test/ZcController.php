<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2016/4/2
 * Time: 19:21
 */

namespace App\Http\Controllers\Test;


use App\Http\Controllers\Controller;
use MyClass\Model\Area;
use MyClass\Model\Commodity;
use MyClass\Model\CommodityClass;
use MyClass\Model\CommodityLabel;
use MyClass\Model\Device;
use MyClass\Model\Entrance;
use MyClass\Model\Environment;
use MyClass\Model\Goods;
use MyClass\Model\Monitor;
use MyClass\Model\Log;
use MyClass\Model\OperatorSession;
use MongoId;
use MyClass\Model\testArea;

class ZcController extends Controller
{

    public function commodityClassTest()
    {
        $queryModel = new CommodityClass();
      //  dump($model2 = $queryModel->addCommodityClass("shoes"));
       // dump($model1 = $queryModel->addCommodityClass("red_shoes"));
      //  dump( $model2 -> addSonCommodityClass($model1->id));  // 红鞋是鞋子的儿子
     //   $model1 -> getInfo();//刷新儿子
     //   dump($model1);
   //     dump($model2);

        $queryLimit["id"] = "56ffacb5149ad6f421000033";
        dump($queryModel->select($queryLimit));





        /*
        dump($model2 = $queryModel->addCommodity("大宝剑2",16.02,null,"哈哈哈哈哈哈","IJK-NIKAZ","介绍"));
        dump($model1->addSonCommodity($model2->id));


        dump($model1->deleteSonCommodity($model2->id));
        dump($model1);
        dump($model2->getInfo());
        */
    }

    public function commodityLabelTest()
    {
        $queryModel = new CommodityLabel();

       $return =  $queryModel ->addCommodityLabel("zc");

        dump($return);



    }

    public function monitorTest()
    {
        $queryModel = new Monitor();
        $return =  $queryModel ->addMonitor(1,"zc","zcc",123,"hhh");
        dump($return);

    }

    public function logTest()
    {

        $queryModel = new Log();
        $return =  $queryModel-> addLog("ss","ss","ss","ss","ss");
        dump($return);

    }

    public function deviceTest()
    {
        $id = "5708e84445bb4b6d9ef54408";
        $device = new Device($id);
        $device ->popWaitHandle(2);

      //  dump($device);
     //   exit();
    //    $data = "haha";
    //    $return = $device ->pushWaitHandle($data);
       // dump($return);
      //  exit();
      //  $queryLimit["wait_handle_num"] = 2;
      //  $returnSelect = $device ->select($queryLimit);
        //dump($returnSelect["data"]);
        exit();

    }

    public function addData()
    {
        //用户登录(entrance表)
        $entrance = new Entrance();
        $returnEntrance = $entrance -> addEntrance("entrance1","entrance1","123");
        if($returnEntrance != false)
        {
            $returnEntrance -> setSession();
            //用户登录的入口加入会话
            $operateSession = new OperatorSession();
            $entranceId =  new MongoId(session("entrance.entrance_id"));

            //运行会话
            $returnSession =  $operateSession -> addOperatorSession($entranceId);
            if($returnSession != false)
            {
                $returnRun = $returnSession -> runSession("0","RFID识别");
                if($returnRun != false)
                {
                   //return redirect("/client_base_login");
                    //加设备
                    $device = new Device();
                    $returnAdd = $device ->addDevice("扫描设备1","intro:xxx","type:xxxx");
                    if($returnAdd != false)
                    {
                        $deviceId = $returnAdd ->info["_id"];
                      //  dump($deviceId);
                      //  exit();
                        $entrance = new Entrance($entranceId);
                        if($entrance ->isNull() == false) {
                            $returnAdd = $entrance->addDevice($deviceId);
                            if ($returnAdd != false) {

                                return redirect("/user_index");

                            }
                        }

                    }


                }
            }
        }
        else
        {
            echo "插入数据失败";
        }
    }

    public function addGoods()
    {
        //1.加类别
        $communityClass = new CommodityClass();
       $returnClass = $communityClass ->addCommodityClass("生活用品");
        $classId = $returnClass ->id;

        //2.加商品
        $community = new Commodity();
        $returnCommunity = $community -> addCommodity("佳洁士净白牙膏",10,$classId,"此牙膏不添加任何防腐剂","大号");
        $communityId = $returnCommunity ->id;

        //3.加区域
        $area = new Area();
        $returnArea = $area -> addArea("1区:货物最多的区域",100,10);//字段no为编号，在此编号为10
        $areaId = $returnArea -> id;


        //3.加货物
        $goods = new Goods();
        $returnGoods = $goods -> addGoods($communityId,$areaId,"备注：xxxx");
        if($returnGoods != false)
        {
            echo "添加数据成功";
        }
        else
        {
            echo "添加数据失败";
        }

    }

    public function  deleteArea()
    {
      //  $areaId = "57143d4d149ad650060005a5";
        $queryLimit["id"] =  "57143d4d149ad650060005a5";
        $area = new Area();
        $return = $area -> select($queryLimit);
        $return["_id"] = $return["data"][0]["_id"];
      //  dump($return["_id"]);
     //   exit();
        $area = new Area($return["_id"]);
       // dump($area);
       // exit();
        if($area ->isNull() != false) {
            $return = $area->delete();
            if ($return != false) {
                echo "删除区域成功";
            } else {
                echo "删除失败";
            }
        }
        else
        {
            echo "删除区域失败";
        }

    }


    public function d3_Area()
    {
        $area = new testArea();

        $area ->addArea("区域A",500,250);
        $area ->addArea("区域B",600,200);
        $area ->addArea("区域C",700,300);
        $area->addArea("区域D",500,100);
        $area->addArea("区域D",500,200);

    }


    //请求test_area中的所有
    public function requestArea()
    {


        $area = new testArea();
        $query["desc"] = true;

        $returnSelect =  $area -> select($query);
       // dump($returnSelect["data"]);
       // exit();
        return response()->json( $returnSelect["data"]);

    }


    public function addEnvironment()
{
    $en = new Environment();
    $en ->addEnvironment(35,10);
    $en ->addEnvironment(-20,30);
    $en ->addEnvironment(20,20);
    $en ->addEnvironment(10,50);
}

    //请求environment中的所有
    public function requestEn()
    {

        $en = new Environment();
        $query["desc"] = true;
        $returnSelect =  $en -> select($query);
       // dump($returnSelect["data"]);
       // exit();
        return response()->json( $returnSelect["data"]);

    }


}