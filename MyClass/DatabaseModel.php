<?php
/**
 * Created by PhpStorm.
 * User: RagPanda
 * Date: 2016/3/9
 * Time: 8:45
 */

namespace MyClass;


use \MongoId;
use MyClass\Facade\MongoDBConnection;
use Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use  Illuminate\Pagination\LengthAwarePaginator;


class  DatabaseModel
{
    public $info;   //和mysql不同 这里是数组形式的
    public $id;     //字符串类型id
    public $_id;    //对象型id
    protected $isNull; //是否有效
    protected $collection="somecollection";


    /**
     * 查询
     * @param $queryLimit
     * @return 标准结构
     */
    public final function select($queryLimit)
    {
        /*
        * $queryLimit
        * |-start  起始
        * |-num   每页条数
        * |-class  类别（如果有）
        * |-sort   排序
        * |-desc   是否逆转排序即倒序(默认正序)
        * |-id       限制id（制定一个固定id）这里要求是string形式的id
        * |-paginate 分页，$queryLimit["paginate"]=每页条数
         * |-join =
         * [
         *  "collection"=>目标集合,
            "selfKey"=>本集合key,
            "otherKey"=>其他集合key,
         * ]
        * |*/

        /*
         * $returnData
         * |-status 是否成功
         * |-message 消息
         * |-total    数据总条数(不是当前数据的条数，是可按照此限制查出的所有数据)
         * |-data   数据 DB返回的二维结构,第二位是BSON
         *
         */
        $mongoLimit = [];

        if(isset($queryLimit["class"]))
        {
            $mongoLimit['$and'][] = ["class"=>$queryLimit["class"]];
        }
        if(isset($queryLimit["id"]))
        {
            $mongoLimit['$and'][] = ["_id" => new \MongoId($queryLimit["id"])];
        }

        $this->selectExtra($mongoLimit,$queryLimit,$cursor);
        $cursor = MongoDBConnection::collection($this->collection)->find($mongoLimit);
        $total = $cursor->count();
        $desc = 1;
        //进行排序等收尾工作
        if(isset($queryLimit["desc"]))
        {
            $desc = -1;
        }

        if(isset($queryLimit["sort"]))
        {
            $cursor->sort( [$queryLimit["sort"]=>$desc] );
        }
        else
        {
            $cursor->sort(["_id"=>$desc]);
        }
        if(isset($queryLimit["num"]))
        {
            $cursor->limit($queryLimit["num"]);
        }
        if(isset($queryLimit["start"]))
        {
            $cursor->skip($queryLimit["start"]);
        }

        $returnData["data"]=[];
        foreach($cursor as $data)
        {
            //$data["_id"] = $data["_id"]->__toString();
            $returnData["data"][] = $data;
        }

        //附加的join功能
        if(isset($queryLimit["join"]))
        {
            //遍历每一种join
            foreach($queryLimit["join"] as $single)
            {
                //dump($single);
                //遍历每一条记录
                foreach($returnData["data"] as $i => $data)
                {

                    if(!isset($single["selfKey"]))
                    {
                        continue;
                    }
                    $col = $single["collection"];
                    $selfKey = $single["selfKey"];
                    $otherKey = $single["otherKey"];

                    $joinLimit = null;
                    $joinLimit['$and'][] =[ $otherKey => $data[$selfKey] ];

                    $joinCursor = MongoDBConnection::collection($col)->find($joinLimit);
                    if(empty($joinCursor))
                    {
                        return false;
                    }

                    //只会取第一条，如果需要多条，自行扩展
                    $one = 0;
                    foreach($joinCursor as $joinDataSingle)
                    {

                        //dump($joinDataSingle);
                        if($one>0){break;}
                        foreach($joinDataSingle as $k => $v )
                        {
                            $returnData["data"][$i][$col."_".$k] = $v;
                        }

                        $one++;
                    }
                }

            }
        }




        $returnData["status"] = true;
        $returnData["message"]= "成功获取数据";
        $returnData["total"] = $total;
        return $returnData;

    }

    /*
     * 在select查询之前会调用这个函数，在这里添加一些每个类自定义的操作
     * 不要返回值，这里传的引用
     * @param $mongoLimit  将会传入find函数的查询限制
     * @param $queryLimit   调用者传入的限制参数数组
     * @param $cursor
     */
    public function selectExtra(&$mongoLimit,&$queryLimit,&$cursor)
    {

    }

    /*
     * 添加
     * @param $dataArray
     * @return string $id|false id支付串表达形式，或者false
     */
    public final function add($dataArray)
    {

        $dataArray["create_time"] = date('Y-m-d H:i:s');//new \MongoDate(time());//UTCDateTime(time());
        $dataArray["update_time"] = date('Y-m-d H:i:s');//new \MongoDate(time());
        $dataArray["_id"] = new MongoId() ;
        $this::addExtra($dataArray);

        $result = MongoDBConnection::collection($this->collection)->insert($dataArray);
        if($result["err"] == null)
        {
            return $dataArray["_id"]->__toString();

        }
        return false;

    }

    /*
     * 在add查询之前会调用这个函数，在这里添加一些每个类自定义的操作
     * 不要返回值，这里传的引用
     * @param $dataArray
     */
    public  function addExtra(&$dataArray)
    {

    }


    /*
     * 构造函数，会自动刷新数据
     * @param $id string
     */
    public function __construct($id=null)
    {
        if($id == null)
        {
            $this->isNull = true;
            return ;
        }
        else
        {
            $this->isNull = false;
        }


        if($id instanceof MongoId)
        {
            $id = $id->__toString();
        }
        $this->id = $id;
        $this->getInfo();
        $this->isNull = false;

    }

    /*
     * 这个对象是否为空
     * @return bool
     */
    public function isNull()
    {
        if($this->isNull)
        {
            return true;
        }

        $result = $this->select(["_id"=>new MongoId($this->id)]);
        if(empty($result["data"]))
        {
            return true;
        }
        return false;
    }

    /*
     *刷新信息
     * info中是数组排列，这和传统的mysql查询出来的对象排列不同
     */
    public function getInfo()
    {
        if($this->isNull()){ return false;}
        $this->info = MongoDBConnection::collection($this->collection)->findOne(["_id"=>new \MongoId($this->id)]);
        if(empty($this->info))
        {
            throw new \Exception("尝试去获取一个不存在ID的信息，ID:".$this->id." 集合:".$this->collection);
        }
        $this->_id = $this->info["_id"];
        return $this->info;
    }


    /*
     * 删除
     * @return true|false
     */
    public final function delete()
    {
        if($this->isNull()){return false;}
        $this->deleteExtra();
        $result = MongoDBConnection::collection($this->collection)->remove(["_id"=>new \MongoId($this->id)]);

        if($result["err"] == null)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /*
     **在delete之前会调用这个函数，在这里添加一些每个类自定义的操作
     * 一般不需要返回值
     */
    public function deleteExtra()
    {

    }


    /*
     * 更新，传入你要更新的字段即可，如果数据库里面没有这个字段，那么会新插入
     * @param $dataArray
     * @return BSONDocument | false 成功返回最新的数据集，失败或者无改动false
     */
    public function update($dataArray)
    {
        if($this->isNull()){return false;}
        $dataArray["update_time"] = date('Y-m-d H:i:s');
        $limit["_id"]=new \MongoId($this->id);
        $update['$set'] = $dataArray;
        $this->updateExtra($dataArray,$limit,$update);
        $result = MongoDBConnection::collection($this->collection)->update($limit,$update);

        if($result["err"] == null )
        {
            //return $this->getInfo();
            return $result;
        }
        else
        {
            throw new \Exception("更新错误，".$result["err"]);
        }
    }

    /**
     * 在update之前会调用这个函数，在这里添加一些每个类自定义的操作
     * 一般不需要返回值,注意传入的是引用
     * @param $dataArray 传入修改数据
     * @param $limit     限制集
     * @param $update    修改集
     *
     */
    public function updateExtra(&$dataArray,&$limit,&$update)
    {

    }

    /**
     * 获取原始的连接，可以进行一些原始的操作
     * @return mixed
     */
    public function getOriginConnection()
    {
        return MongoDBConnection::collection($this->collection);
    }


}