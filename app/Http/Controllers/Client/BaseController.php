<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use MyClass\Base\GuiFunction;
use MyClass\Model\Entrance;
use Illuminate\Support\Facades\Session;
use MyClass\Model\Operator;
use MyClass\Model\OperatorSession;

/**
 * Created by PhpStorm.
 * User: RagPanda
 * Date: 2016/3/29
 * Time: 16:34
 */
class BaseController extends Controller
{
    /**
     * 客户端首页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view("Client.base");
    }

    /**
     * 显示登录页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function login()
    {
        return view("Client.login");
    }

    /**
     * 用户账号密码验证
     * @param GuiFunction $guiFunction
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function _login(GuiFunction $guiFunction)
    {
        $data = Request::all();
        $login = new Entrance();
        $return = $login ->login($data['user_username'],$data['user_password']);
        if($return != false)
        {
            $guiFunction->setMessage(true, "用户登陆成功");
            return redirect("/user_index");
        }
        else
        {
            $guiFunction->setMessage(false, "用户名不存在或密码错误");
            return redirect("/client_base_login");
        }
    }

    /**
     * 用户登出
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout()
    {
        Session::flush();
        return redirect("/client_base_login");
    }


    /**
     * 获取当前登录的操作员信息+头像
     * 无发送数据
     * 返回数据
     * |-status
     * |-data={
     *  operator_username:操作用户名
     *  operator_name:操作员名
     *  operator_image:操作员头像id
     * }
     * |-message
     */
    public function getOperator()
    {
        //dump(session("entrance.entrance_id"));
        $entranceId = new \MongoId(session("entrance.entrance_id"));
        $queryLimit["entrance"] = $entranceId->__toString();
        $queryLimit["operator"] = session("operatorSession.operatorSession_operator","-1");
        //dump(session("operatorSession"));
        //dump($queryLimit);
        $operatorSession = new OperatorSession();
        $returnSelect = $operatorSession -> select($queryLimit);
        //dump($returnSelect);
        if($returnSelect["data"] != null)
        {
            $operatorId = $returnSelect["data"][0]["operator"];
            $operator = new Operator($operatorId);
            if($operator -> isNull() == false)
            {
                $data["operator_name"] = $operator -> info["name"];   //1.获取operator_name
                $data["operator_username"] = $operator -> info["username"];  //2.获取operator_username
                //查找此操作员是否有图片
                $images = $operator ->info["image"];
                if($images != null)
                {
                    //有图片的话，第一张设为图片
                    $data["operator_image"] = $images[0]->__toString();  //3.获取operator_image
                }
                else
                {
                    $data["operator_image"] = null;
                }
                return response()->json(["status" => true, "message" => "获取操作员图片成功", "data" => $data ]);
            }
        }
        return response()->json(["status" => false, "message" => "获取操作员图片失败", "data" => [] ]);
    }

    /**
     * 获取会话值
     * |-key 关键字
     * 返回值
     * |-status true|false
     * |-data   数据，即具体的值
     * |-message “消息”
     *
     */
    public function getSessionVal()
    {

        $recive = Request::input("key");

        $data = session($recive);

        if(!empty($data))
        {
            return response()->json(
                [
                    "status"=>true,
                    "message"=>"得到session",
                    "data"=>$data
                ]
            );
        }
        else
        {
            return response()->json(
                [
                    "status"=>true,
                    "message"=>"没有这个值",
                    "data"=>null
                ]
            );
        }

    }




}