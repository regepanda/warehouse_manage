<?php
/**
 * Created by PhpStorm.
 * User: RagPanda
 * Date: 2016/3/9
 * Time: 11:56
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Request;
use MyClass\Model\Admin;
use MyClass\Base\GuiFunction;


class BaseController extends Controller
{
    //显示管理界面
    public function manageIndex(GuiFunction $guiFunc)
    {
        $guiFunc->setModule("Manage");
        return view("Admin.Manage.index");
    }

    //显示系统界面
    public function system(GuiFunction $guiFunc)
    {
        $guiFunc->setModule("System");
        return view("Admin.System.index");
    }

    //管理员登录进去的首页界面
    public function index()
    {
        return view("Admin.index");
    }

    //显示登录页面
    public function login()
    {
        return view("Admin.login");
    }

    //管理员账号密码登录验证
    public function _login(GuiFunction $guiFunction)
    {
        $data = Request::all();
        $login = new Admin();
        $return = $login ->login($data['user_username'],$data['user_password']);
        if($return != false)
        {
           $guiFunction->setMessage(true, "登陆成功");
           return redirect("/admin_index");
        }
        else
        {
          $guiFunction->setMessage(false, "用户名不存在或密码错误");
          return redirect("admin_login");
        }
    }

    //管理员登出
    public function logout()
    {
        Session::flush();
        return redirect("admin_login");
    }


    //商品管理界面
    public function commodityManage(GuiFunction $guiFunc)
    {
        $guiFunc->setModule("commodityManage");
        return view("Admin.commodityManage");
    }

    //权限管理界面
    public function powerManage(GuiFunction $guiFunc)
    {
        $guiFunc->setModule("powerManage");
        return view("Admin.powerManage");
    }

    //设备管理界面
    public function deviceManage(GuiFunction $guiFunc)
    {
        $guiFunc->setModule("sDevice");
        return view("Admin.deviceManage");
    }

    //入口管理界面
    public function  entranceManage(GuiFunction $guiFunc)
    {
        $guiFunc->setModule("sEntrance");
        return view("Admin.entranceManage");
    }

    public function logManage(GuiFunction $guiFunc)
    {
        $guiFunc->setModule("sLog");
        return view("Admin.sLog");
    }

    //数据可视化界面
    public function visualManage(GuiFunction $guiFunc)
    {
        $guiFunc->setModule("visualManage");
        return view("Admin.visualManage");
    }
}