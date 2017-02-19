<?php
/**
 * Created by PhpStorm.
 * User: RagPanda
 * Date: 2016/4/18
 * Time: 13:43
 */

namespace App\Http\Controllers\Admin;



use App\Http\Controllers\Controller;
use MyClass\Model\Admin;
use MyClass\Model\Area;
use MyClass\Model\Commodity;
use MyClass\Model\CommodityClass;
use MyClass\Model\Device;
use MyClass\Model\Entrance;
use MyClass\Model\Environment;
use MyClass\Model\Goods;
use MyClass\Model\Operator;
use MyClass\Model\OperatorGroup;
use MyClass\Model\OperatorSession;
use MyClass\Facade\MongoDBConnection;
class InitController extends Controller
{
    public function __construct()
    {

    }

    public function initDataSet()
    {
        MongoDBConnection::link()->drop();

        dump("添加两个管理员");
        $adminModel = new Admin();
        $adminModel->addAdmin("测试管理员1","admin1","123");
        $adminModel->addAdmin("测试管理员2","admin2","123");
        dump("管理员名和密码 admin1  123 ，admin2  123");

        dump("添加一个用户组");
        $oprGroup = new OperatorGroup();
        $returnGroup = $oprGroup->addOperatorGroup("默认操作组");

        dump("添加六个用户");
        $oprModel = new Operator();
        $returnOperator1 = $oprModel->addOperator("user1","123","张瑞鑫",$returnGroup->_id);
        $returnOperator2 = $oprModel->addOperator("user2","123","鲜昊琦", $returnGroup->_id);
        $returnOperator3 = $oprModel->addOperator("user3","123","左林桐",$returnGroup->_id,null,"0");
        $returnOperator4 = $oprModel->addOperator("user4","123","彭亮", $returnGroup->_id);
        $returnOperator5 = $oprModel->addOperator("user5","123","张浩",$returnGroup->_id,null,"2");
        $returnOperator6 = $oprModel->addOperator("user6","123","陈浩州", $returnGroup->_id,null,"1");

        $returnGroup->addOperator($returnOperator1 -> info["_id"]);
        $returnGroup->addOperator($returnOperator2 -> info["_id"]);
        $returnGroup->addOperator($returnOperator3 -> info["_id"]);
        $returnGroup->addOperator($returnOperator4 -> info["_id"]);
        $returnGroup->addOperator($returnOperator5 -> info["_id"]);
        $returnGroup->addOperator($returnOperator6 -> info["_id"]);
        $returnGroup ->addPower(0);
        $returnGroup -> addPower(1);
        dump("用户名和密码 user1  123 ，user2  123");

        dump("添加入口，添加两个入口");
        $oprEntrance = new Entrance();
        $entranceOne = $oprEntrance->addEntrance("1号入口","entrance1","123");
        $entranceTwo = $oprEntrance->addEntrance("2号入口","entrance2","123");
        dump("入口名和密码 entrance1  123 ，entrance2  123");

        dump("添加设备，添加四台设备");
        $deviceModel = new Device();
        $d1 = $deviceModel->addDevice("设备1 RFID",0,0,"一台RFID",Device::TYPE_RFID,"1");
        $d2 = $deviceModel->addDevice("设备2 CAMERA",0,0,"一台摄像头",Device::TYPE_CAMERA,"2");
        $d3 = $deviceModel->addDevice("设备3 RFID",0,0,"一台RFID",Device::TYPE_RFID,"3");
        $d4 = $deviceModel->addDevice("设备4 CAMERA",0,0,"一台摄像头",Device::TYPE_CAMERA,"4");
        $d5 = $deviceModel->addDevice("指纹设备 FINGER",0,1,"门禁",Device::TYPE_FINGER,"finger_1");
        $d6 = $deviceModel->addDevice("门禁设备 DOOR",0,1,"门禁",Device::TYPE_DOOR,"6");

        dump("给设备关联到入口，一二号设备关联到一号入口，二三号关联到二号入口");
        $entranceOne->addDevice($d1->_id);
        $entranceOne->addDevice($d2->_id);
        $entranceOne->addDevice($d5->_id);
        $entranceTwo->addDevice($d3->_id);
        $entranceTwo->addDevice($d4->_id);

        dump("添加四块区域");
        $areaModel = new Area();
        $a1 =  $areaModel->addArea("A区1",15.00,"A0001");
        $a2 = $areaModel->addArea("A区2",30.00,"A0002");
        $a3 =  $areaModel->addArea("A区3",15.00,"A0003");
        $a4 = $areaModel->addArea("A区4",30.00,"A0004");

        dump("添加一个商品类别");
        $commodityClassModel = new CommodityClass();
        $classOne = $commodityClassModel->addCommodityClass("智能手机",100,$a1->_id,null,null,null);
        $classTwo = $commodityClassModel->addCommodityClass("大屏电视",120,$a2->_id,null,null,null);


        /*
        //更新区域
        $upArea1["nowCapacity"] = 30;
        $upArea2["nowCapacity"] = 40;
        $upArea3["nowCapacity"] = 30;
        $upArea3["capacity"] = 100;
        $upArea4["nowCapacity"] = 60;
        $upArea4["capacity"] = 150;

        $a1 ->update($upArea1);
        $a2 ->update($upArea2);
        $a3 ->update($upArea3);
        $a4 ->update($upArea4);*/


        dump("添加三个商品，s7  htcm10 iphone");
        $commodityModel = new Commodity();
        $c1 = $commodityModel->addCommodity("三星S7 Edge",5499.99,$classOne->_id,"超越美感！","S7BWK-12","一款非常好的手机");
        $c2 = $commodityModel->addCommodity("HTC M10",4522.55,$classOne->_id,"极致，非常薄","S253","nice！！！2016全新旗舰");
        $c3 = $commodityModel->addCommodity("iphone 6s",5500,$classOne->_id,"薄，时尚，耐用","6s","2016新一代苹果手机");



        dump("添加9个货物，商品1 2 3各自三个，商品一在一区，商品二,商品三在二区");
        $goodsList=[];
        $goodModel = new Goods();
        $goodsList[] = $goodModel->addGoods(null,"13310520001","13310520001",null,$c1->_id);
        $goodsList[] = $goodModel->addGoods(null,"13310520002","13310520002",null,$c1->_id);
        $goodsList[] = $goodModel->addGoods(null,"13310520003","13310520003",null,$c1->_id);
        $goodsList[] = $goodModel->addGoods(null,"13310520004","13310520004",null,$c2->_id);
        $goodsList[] = $goodModel->addGoods(null,"13310520005","13310520005",null,$c2->_id);
        $goodsList[] = $goodModel->addGoods(null,"13310520006","13310520006",null,$c2->_id);
        $goodsList[] = $goodModel->addGoods(null,"13310520007","13310520007",null,$c3->_id);
        $goodsList[] = $goodModel->addGoods(null,"13310520008","13310520008",null,$c3->_id);
        $goodsList[] = $goodModel->addGoods(null,"13310520009","13310520009",null,$c3->_id);






        /*
        dump("添加两个会话,会话一属于入口1，会话属于入口2");
        $sessionModel = new OperatorSession();
        $session1 = $sessionModel->addOperatorSession($entranceOne->_id,$returnOperator1->info["_id"]);
        $session2 = $sessionModel->addOperatorSession($entranceTwo->_id,$returnOperator2->info["_id"]);
        $session1->runSession(OperatorSession::CERTIFICATE_FACE,"some face data");
        $session2->runSession(OperatorSession::CERTIFICATE_RFID,"some rfid data");
        $updateData1["operator"] = $returnOperator1 -> info["_id"];
        $updateData2["operator"] = $returnOperator2 -> info["_id"];
        $session1->update($updateData1);
        $session2->update($updateData2);



        dump("全部出库");
        for($i = 0;$i < 3;$i++)
        {
            $session1->outputGoods($goodsList[$i]->_id);
        }
        for($i = 3;$i < 6;$i++)
        {
            $session2->outputGoods($goodsList[$i]->_id);
        }

        dump("关闭2号会话，只留下一号");
        $session2->finishSession();

        dump("给一号入口的设备各添加两条硬件数据");
        $d1->pushWaitHandle(["goods_id"=>$goodsList[0]->_id]);
        $d1->pushWaitHandle(["goods_id"=>$goodsList[1]->_id]);
        $d2->pushWaitHandle(["goods_id"=>$goodsList[2]->_id]);
        $d2->pushWaitHandle(["goods_id"=>$goodsList[3]->_id]);
        */

        dump("添加11条环境数据");
        $obj = new Environment();
        $obj->addEnvironment(15,33);
        $obj->addEnvironment(18,48);
        $obj->addEnvironment(17,49);
        $obj->addEnvironment(16,42);
        $obj->addEnvironment(18,41);
        $obj->addEnvironment(18,30);
        $obj->addEnvironment(22,24);
        $obj->addEnvironment(30,22);
        $obj->addEnvironment(18,30);
        $obj->addEnvironment(19,32);
        $obj->addEnvironment(22,22);


    }


}