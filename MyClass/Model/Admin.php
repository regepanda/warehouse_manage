<?php
/**
 * Created by PhpStorm.
 * User: RagPanda
 * Date: 2016/3/28
 * Time: 11:04
 */

namespace MyClass\Model;


use MyClass\DatabaseModel;

class Admin extends DatabaseModel
{
    public $info;
    public $id;
    public $isNull;
    public $collection="admin";


    /**
     * 自定义查询规则
     * @param $mongoLimit
     * @param $queryLimit
     * @param $cursor
     */
    public function selectExtra(&$mongoLimit,&$queryLimit,&$cursor)
    {
        if(isset($queryLimit["username"]))
        {
            $mongoLimit['$and'][] = ["username"=>$queryLimit["username"] ];
        }
        if(isset($queryLimit["password"]))
        {
            $mongoLimit['$and'][] = ["password"=>$queryLimit["password"]];
        }
    }

    /**
     *
     * 登录 传入用户名，密码，自动载入session
     * @param $username
     * @param $password
     * @return bool|Admin
     */
    public function login($username, $password)
    {
        $result =$this->select(["username"=>$username,"password"=>$password]);

        if(empty($result["data"]))
        {
            return false;
        }

        //instantiation
        $id = $result["data"][0]["_id"];
        $adminModel = new Admin($id);

        //set session
        $adminModel->setSession();
        return $adminModel;

    }


    /*
     * 设定session
     * @return bool
     */
    public function setSession()
    {
        $sessionStruct["admin_id"] = $this->id;
        $sessionStruct["admin_status"] = true;
        $sessionStruct["admin_nickname"] = $this->info["nickname"];
        $sessionStruct["admin_username"] = $this->info["username"];

        session(["admin"=>$sessionStruct]);
        return true;
    }

    /**
     * 添加一个管理员
     * @param $name
     * @param $username
     * @param $password
     * @return bool|Admin
     */
    public function addAdmin($name,$username,$password)
    {

        //自增
        $system = new System();
        $addName = "admin_num";
        $adminID = $system ->addSelf($addName);
        if($adminID == false)
        {
            return false;
        }


        $result = $this->add(["ID"=>$adminID,"nickname"=>$name,"username"=>$username,"password"=>$password]);

        if($result == false)
        {
            return false;
        }

        return new Admin($result);

    }


}