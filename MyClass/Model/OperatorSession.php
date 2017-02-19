<?php
/**
 * Created by PhpStorm.
 * User: RagPanda
 * Date: 2016/3/28
 * Time: 11:06
 */

namespace MyClass\Model;
use Mockery\CountValidator\Exception;
use MongoId;
use MyClass\DatabaseModel;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use MyClass\Model\Goods;

class OperatorSession extends DatabaseModel
{
    public $info;   //和mysql不同 这里是数组形式的
    public $id;     //字符串类型id
    public $_id;    //对象型id
    protected $isNull; //是否有效
    protected $collection="operate_session";

    const STATUS_READY = 0;
    const STATUS_RUN = 1;
    const STATUS_FINISH = 2;

    const CERTIFICATE_FACE = 3;
    const CERTIFICATE_RFID = 4;
    const CERTIFICATE_FINGER = 5;


    /**
     * 添加一个会话
     * @param $entrance 入口id
     *  @param $operator 操作员id
     */
    public function addOperatorSession($entrance,$operator)
    {
        //查看当前是否有会话，如果有停掉
         $queryLimit["status"] = OperatorSession::STATUS_RUN;
         $queryLimit["entrance"] = $entrance;
         $returnSelect =  $this ->select($queryLimit);
         if($returnSelect["data"] != null)
         {
             $log  = new log();
             $log->addLog("发现入口".$entrance."会话没有关闭",null,null,Log::INFO,$returnSelect);
             foreach($returnSelect["data"]  as $v)
             {
                 $tmp = new OperatorSession($v["_id"]->__toString());
                 $tmp->finishSession();
             }
         }


        //自增
        $system = new System();
        $addName = "operator_session_num";
        $operatorSessionID = $system ->addSelf($addName);
        if($operatorSessionID == false)
        {
            return false;
        }

        $result = $this->add(["ID"=>$operatorSessionID,"entrance"=>new \MongoId($entrance),"status"=>OperatorSession::STATUS_READY,
            "operator"=>new \MongoId($operator),"cache"=>[]]);
        if($result == false)
        {
            return false;
        }
        else
        {
            $operatorSession = new OperatorSession($result);

            return $operatorSession;
        }
    }



    /**
     * @param $mongoLimit
     * @param $queryLimit
     * @param $cursor
     * status 为货物的状态
     * entrance 为入口id
     */
    public function selectExtra(&$mongoLimit,&$queryLimit,&$cursor)
    {
        if(isset($queryLimit["status"]))
        {
            $mongoLimit['$and'][] = ["status"=>$queryLimit["status"]];
        }

        if(isset($queryLimit["entrance"]))
        {
            if(is_string($queryLimit["entrance"]))
            {
                $queryLimit["entrance"] = new \MongoId($queryLimit["entrance"]);
            }
            $mongoLimit['$and'][] = ["entrance"=>$queryLimit["entrance"]];
        }
        if(isset($queryLimit["operator"]))
        {
            if(is_string($queryLimit["operator"]))
            {
                $queryLimit["operator"] = new \MongoId($queryLimit["operator"]);
            }
            $mongoLimit['$and'][] = ["operator"=>$queryLimit["operator"]];
        }

    }

    /*
     * 激活一个会话，需要传入认证的信息
     * @param $certificateType
     * @param $certificateData
     * @return bool
     */
    public function runSession($certificateType, $certificateData)
    {
        $result = $this->update([
            "status"=>OperatorSession::STATUS_RUN,
            "certificate_type"=>$certificateType,
            "certificateData"=>$certificateData,
            "start_date"=>time()
        ]);
        if($result == false)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    /**
     * 完成一个会话
     * @return bool
     */
    public function finishSession()
    {
        $result = $this->update(["status"=>OperatorSession::STATUS_FINISH,"end_date"=>time()]);
        if($result == false)
        {
            return false;
        }
        else
        {
            session(["operatorSession"=>null]);
            return true;
        }
    }

    /**
     * 添加记录
     * @param $message
     * @return bool
     */
    public function addLog($message)
    {
        $result = $this->getOriginConnection()->update(["_id"=>$this->_id,['$push'=>["log"=>$message]]]);
        if($result["err"]==null&&$result['n'] != 0)
        {
            $this->getInfo();
            return true;
        }
        return false;
    }


    /**
     * 给一个商品加入缓存，状态为入库
     * @param $goodsId
     * @return bool
     */
    public function inputGoods($goodsId)
    {
        $goods = new Goods($goodsId);
        if($goods->isNull())
        {
            return false;
        }

        //区域操作

        $result  = $this->addCache($goods->id,Goods::STATUS_IN,"区域目前还未实装,这里是一个字符串");
        //修改商品状态为入库

        if($result == false)
        {
            return false;
        }
        //向会话里面添加记录
       /* $insertData = ["goods_id"=>$goods->_id,"type"=>"入库","area_id"=>$goods->info['area'],"date"=>time()];
        $res = $this->getOriginConnection()
            ->update(["_id"=>$this->_id],
                ['$addToSet'=>
                    ['log'=> $insertData]
                ]);
        if($res["err"]==null&&$res['n'] != 0)
        {
            $this->getInfo();
            return true;
        }*/
    }

    /**
     * 给一个商品加入缓存，状态出库
     * @param $goodsId
     * @return bool
     */
    public function outputGoods($goodsId)
    {
        $goods = new Goods($goodsId);
        if($goods->isNull())
        {
            return false;
        }
        //修改商品状态为出库
        $result  = $this->addCache($goods->id,Goods::STATUS_OUT);
        if($result == false)
        {
            return false;
        }
        return true;
        //向会话里面添加记录
        //$insertData = ["goods_id"=>$goods->_id,"type"=>"出库","area_id"=>$goods->info['area'],"date"=>time()];
        /*$res = $this->getOriginConnection()
            ->update(["_id"=>$this->_id],
                ['$addToSet'=>
                    ['log'=> $insertData]
                ]);*/

    }

    //wjt2016/4/18 这是什么函数？？谁写的
    /*
     * 新增一个商品进入仓库
     * @param $goodsId
     */
    public function addGoods($goodsId)
    {
        $goods = new Goods($goodsId);
        if($goods->isNull())
        {
            return false;
        }
        $insertData = ["id"=>$goods->_id,"type"=>$goods->info['type'],
            "area_id"=>$goods->info['area_id'],"date"=>time()];
    }

    /**
     * 添加缓存
     * @param $goods_id
     * @param $commodity_name
     * @param $aim_status
     * @return bool
     */
    public function addCache($goods_id,  $aim_status)
    {
        $goods = new Goods();
        $join[] = ["collection" => "commodity","selfKey"=>"commodity","otherKey"=>"_id"];
        $join[] = ["collection" => "commodity_class","selfKey"=>"commodity_class","otherKey"=>"_id"];
        $goodsData = $goods->select(["id"=>$goods_id,"join"=>$join]);
        if(empty($goodsData["data"]))
        {
            throw new \Exception("没有这个货物");
        }
        $goodsData = $goodsData["data"][0];
        $insertData["goods_id"] = $goodsData["_id"]->__toString();
        $insertData["commodity_name"] = $goodsData["commodity_name"];
        $insertData["aim_status"] = $aim_status;
        $insertData["model"] = $goodsData["commodity_model"];
        $insertData["price"] = $goodsData["commodity_price"];

        $area = new Area($goodsData["commodity_class_area"]);
        $insertData["area_detail"] = $area->info["no"].":". $area->info["intro"];

        if(!empty($this->info["cache"]))
        {
            foreach($this->info["cache"] as $v)
            {
                if($v["goods_id"] == $insertData["goods_id"])
                {
                    throw new \Exception("缓存中已经有相同的商品被操作");
                }
            }
        }


        $res = $this->getOriginConnection()
            ->update(["_id"=>$this->_id],
                ['$addToSet'=>
                    ['cache'=> $insertData]
                ]);


        if($res["err"]==null&&$res['n'] != 0)
        {
            $this->getInfo();
            return true;
        }
        return false;
    }

    /**
     * 非事务安全的提交缓存
     * @throws \Exception
     */
    public function commitCache()
    {
        $this->getInfo();
        $cache = &$this->info["cache"];
        $k = 0;
        try
        {
            foreach($cache as $k => $v)
            {
                $goods = new Goods($v["goods_id"]);
                if($v["aim_status"] == Goods::STATUS_OUT)
                {
                    $goods->setStatusOut();
                    $area = new Area();
                    $area->outputGoods($goods->id);

                }
                if($v["aim_status"] == Goods::STATUS_IN)
                {
                    $goods->setStatusIn();
                    $area = new Area();
                    $area->inputGoods($goods->id);
                }
            }
        }
        catch(\Exception $e)
        {
            $data = [];
           for($i = $k+1;$i <sizeof($cache);$i++)
           {
               $data[] = $cache[$i];
           }
            $this->update(["cache"=>$data]);
            throw new \Exception("提交处理中断，中断点以后剩余任务已经存储，中断点($k) :".json_encode($cache[$k])." 原始错误：".$e->getMessage().$e->getFile().$e->getLine());
        }

        return $this->update(["cache"=>[]]);
    }

    /**
     * 删除一条缓存
     * @param $goods_id
     * @return bool
     */
    public function delCache($goods_id)
    {
        if($goods_id instanceof MongoId)
        {
            $goods_id = $goods_id->__toString();
        }
        foreach($this->info["cache"] as $k =>$v)
        {
            if($v["goods_id"] == $goods_id)
            {
                $n = sizeof($this->info["cache"]);
                for($i = $k;$i < $n;$i++)
                {
                    if($i+1 == $n)
                    {
                        unset($this->info["cache"][$i]);
                    }
                    else
                    {
                        $this->info["cache"][$i] = $this->info["cache"][$i+1];
                    }

                }
                $this->update(["cache"=>$this->info["cache"]]);
                return true;
            }
        }
        return false;
    }

    /**
     * 清空缓存
     * @return bool
     */
    public function clearCache()
    {
        return $this->update(["cache"=>[]]);
    }


    /**
     * 获取缓存数据
     * @param $offset 可以指定偏移量
     * @return array
     */
    public function getCache($offset = 0)
    {
        $this->getInfo();
        //dump($this);
        $data = $this->info["cache"];
        //dump($data);exit();
        $i = 0;
        $result = [];

        for($i = $offset;$i < sizeof($data);$i++)
        {
            //dump($i);
            $result[] = $data[$i];
        }
        return $result;

    }
}