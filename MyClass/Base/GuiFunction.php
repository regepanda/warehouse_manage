<?php
/**
 * Created by PhpStorm.
 * User: RagPanda
 * Date: 2016/3/7
 * Time: 21:21
 */

namespace MyClass\Base;


use Illuminate\Support\Facades\Session;

class GuiFunction
{
    public function  __construct()
    {

    }

    public function setMessage($status, $message)
    {
        $data["__component_messageBar_status"] = $status;
        $data["__component_messageBar_message"] = $message;
        Session::flash('other',$data);
    }
    public function setModule($moduleName)
    {
        Session::put('other.nowModule',$moduleName);
    }
    public function setSecondModule($secondModuleName)
    {
        Session::put('other.nowSecondModule',$secondModuleName);
    }
    public function setThirdModule($thirdModuleName)
    {
        Session::put('other.nowThirdModule',$thirdModuleName);
    }
}