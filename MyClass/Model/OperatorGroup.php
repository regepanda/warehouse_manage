<?php
/**
 * Created by PhpStorm.
 * User: RagPanda
 * Date: 2016/3/28
 * Time: 11:05
 */

namespace MyClass\Model;
use MyClass\DatabaseModel;
use MyClass\Model\Operator;

class OperatorGroup extends DatabaseModel
{

    public $info;   //和mysql不同 这里是数组形式的
    public $id;     //字符串类型id
    public $_id;    //对象型id
    protected $isNull; //是否有效
    protected $collection="operator_group";


    public  static $power = [
        0=>"出库",
        1=>"入库",
        2=>"冻结商品",
    ];



    public function selectExtra(&$mongoLimit,&$queryLimit,&$cursor)
    {
        if(isset($queryLimit["operator_id"])) {
            $limit["operator_list"]['$all'][]['$elemMatch']['operator_id'] = $queryLimit["operator_id"];
            $mongoLimit['$and'][] = $limit;
        }
    }


    //添加组
    public function addOperatorGroup($name)
    {

        //自增
        $system = new System();
        $addName = "operator_group_num";
        $operatorGroupID = $system ->addSelf($addName);
        if($operatorGroupID == false)
        {
            return false;
        }


        $result = $this->add(["ID"=>$operatorGroupID,"name"=>$name,"operator_list"=>[],"power_list"=>[]]);
        if($result == false)
        {
            return false;
        }
        else
        {
            return new OperatorGroup($result);
        }
    }

    //往该组里面添加操作员
    public function addOperator($operatorId)
    {
        $operator = new Operator($operatorId);
        if($operator->isNull())
        {
            return false;
        }
        $insertData = ["operator_id"=>$operator->_id,"operator_name"=>$operator->info["name"]];
        $result = $this->getOriginConnection()
            ->update(["_id"=>$this->_id],
                ['$addToSet'=>
                    ['operator_list'=> $insertData]
                ]);
        if($result["err"]==null&&$result['n'] != 0)
        {
            $this->getInfo();
            return true;
        }
    }

    //删除该组的一个操作员
    public function deleteOperator($operatorId)
    {
        $operator = new Operator($operatorId);
        if($operator->isNull())
        {
            return false;
        }
        $updateData['operator_id'] = $operator->_id;
        $result = $this->getOriginConnection()
            ->update(["_id"=>$this->_id],
                ['$pull'=>
                    ['operator_list'=> $updateData]
                ]);
        if($result["err"] == null && $result["n"]!=0)
        {
            $this->getInfo();
            return true;
        }
        else
        {
            return false;
        }
    }

    //添加一个权限到当前权限组
    public function addPower($powerId)
    {
        $insertData = ["power_id"=>$powerId,"power_name"=>OperatorGroup::$power[$powerId]];
        $result = $this->getOriginConnection()
            ->update(["_id"=>$this->_id],
                ['$addToSet'=>
                    ['power_list'=> $insertData]
                ]);
        if($result["err"]==null&&$result['n'] != 0)
        {
            $this->getInfo();
            return true;
        }
    }


    public function deletePower($powerId)
    {
        $updateData['power_id'] = $powerId;
        $result = $this->getOriginConnection()
            ->update(["_id"=>$this->_id],
                ['$pull'=>
                    ['power_list'=> $updateData]
                ]);
        if($result["err"] == null && $result["n"]!=0)
        {
            $this->getInfo();
            return true;
        }
        else
        {
            return false;
        }
    }
}