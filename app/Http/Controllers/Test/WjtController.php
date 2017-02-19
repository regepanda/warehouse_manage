<?php
/**
 * Created by PhpStorm.
 * User: RagPanda
 * Date: 2016/3/9
 * Time: 11:56
 */

namespace App\Http\Controllers\Test;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use MyClass\DatabaseModel;
use MyClass\Model\Admin;
use MyClass\Model\Commodity;
use MyClass\Model\Device;
use MyClass\Model\Entrance;
use MyClass\Model\Environment;
use MyClass\Model\Operator;
use MyClass\Model\OperatorSession;
use MyClass\Model\Goods;
use MyClass\Model\Area;
use MyClass\Model\OperatorGroup;
use MyClass\Model\Log;
use MyClass\Module\Face;

class WjtController extends Controller
{
    public function mongoDBLinkTest()
    {
        $queryModel = new DatabaseModel();
        $result  = $queryModel->add(["name"=>"老大"]);
        dump($result);
        $model = new DatabaseModel($result);
        dump($model);

        //dump($model->update(["name"=>"二傻子123","age"=>16]));
       // echo $result->insertedId;
        //dump($model->delete());

        //dump($queryModel->select(["num"=>2,"start"=>2,"desc"=>true]));
    }

    public function adminTest()
    {
        /*$queryModel = new Admin();
        //dump($r = $queryModel -> addAdmin("王尼玛","admin","123"));
        //dump($r->login("admin","123"));
        //dump(session("admin"));*/
        $queryModel = new DatabaseModel();
        $queryLimit['start'] = 0;
        $queryLimit['num'] = 3;
        $data = $queryModel->select($queryLimit);
        dump($data);
    }

    public function commodityTest()
    {
        $queryModel = new Commodity();
        //dump($model1 = $queryModel->addCommodity("大宝剑1",16.02,null,"哈哈哈哈哈哈","IJK-NIKAZ","介绍"));
        //dump($model2 = $queryModel->addCommodity("大宝剑2",16.02,null,"哈哈哈哈哈哈","IJK-NIKAZ","介绍"));
        //dump($model1->addSonCommodity($model2->id));

       // dump($model1);
        //dump($model2);
        //dump($model1->deleteSonCommodity($model2->id));

        dump($model1->deleteSonCommodity($model2->id));
        dump($model1);
        dump($model2->getInfo());
    }

    public function test()
    {
       /* $operatorSession = new OperatorSession("57048fed217959e01f000029");
        $goodsId = "57049e75217959e01f00002c";
        $operatorSession->inputGoods($goodsId);*/

        /*$goods = new Goods();
        $commodity = "57049d75217959e01f00002a";
        $area = "57049e32217959e01f00002b";
        $remark = "傻逼";
        $goods->addGoods($commodity,$area,$remark);*/

        /*$commodity = new Commodity();
        $name = "iPhone6";
        $price = "5000";
        $class = null;
        $detail = "不错的手机";
        $model = "5";
        $commodity->addCommodity($name,$price,$class,$detail,$model);*/

        /*$area = new Area();
        $intro = "这是一个区域";
        $distance = "不远";
        $no = "23";
        $area->addArea($intro,$distance,$no);*/

        /*$operatorGroup = new OperatorGroup("5704a9bb217959e01f00002d");
        $powerId = 1;
        $operatorGroup->deletePower($powerId);*/

        $log = new Log();
        $intro = "你麻痹";
        $detail = "今天了一个";
        $data = "明天再一个";
        $level = Log::ERROR;
        $otherData = ["goods_id"=>"57049e75217959e01f00002c","type"=>"出库","area"=>"b区域"];
        $log->addLog($intro,$detail,$data,$level,$otherData);
    }

    public function _test()
    {
        $data = Request::all();
        dump($data);
    }


    public function polling()
    {
        return view("Test.Wjt.pollLibTest");
    }
    public function _polling()
    {

        sleep(2);
        return response()->json(["status"=>false,"message"=>"dads"]);

    }

    public function moduleCpp()
    {
        $file = "./image/111.jpg";
        $img  =file_get_contents($file);

        $size = filesize($file);

        //Face::pushTrainImage(1,$img,$size,"update");
        Face::pushRecognizeData("123",$img,$size);
    }
    public function dbJoinExt()
    {

        $c = new Commodity();
        $data = $c->select(["join"=>[0=>["collection"=>"commodity_class","selfKey"=>"class","otherKey"=>"_id"]]]);
        dump($data);
    }
    public function cacheTest()
    {

        $oprs = new OperatorSession();
        $oprsModel = $oprs->addOperatorSession("573d14c44becad4c0f000035","573d14c44becad4c0f000044");
        $re = $oprsModel->addCache("573d14c44becad4c0f000043",Goods::STATUS_OUT);
        $oprsModel->clearCache();
        dump($oprsModel);
        $re = $oprsModel->addCache("573d14c44becad4c0f000044",Goods::STATUS_OUT);
        $re = $oprsModel->addCache("573d14c44becad4c0f000045",Goods::STATUS_IN);
        $re = $oprsModel->addCache("573d14c44becad4c0f000046",Goods::STATUS_IN);
        dump($oprsModel->delCache("573d14c44becad4c0f000046"));
        dump($oprsModel);

        dump($oprsModel->commitCache());

    }
    public function dataSetTest()
    {
        /*
        $d = new Device("574116bab70afadc10000036");
        $d ->pushWaitHandle(["goods_id"=>"574116bbb70afadc10000041"]);
        $d ->pushWaitHandle(["goods_id"=>"574116bbb70afadc10000042"]);
        $d ->pushWaitHandle(["goods_id"=>"574116bbb70afadc10000043"]);*/




        $session =new OperatorSession();
        $session =$session->addOperatorSession("57405de04becad683500003a","57405de04becad6835000034");
        $session->runSession(OperatorSession::CERTIFICATE_FACE,null);

        $session->inputGoods("57405de04becad6835000048");
        $session->inputGoods("57405de04becad6835000049");
        $session->inputGoods("57405de04becad6835000050");
    }



}

