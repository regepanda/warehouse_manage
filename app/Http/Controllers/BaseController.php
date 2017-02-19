<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2016/5/5
 * Time: 16:20
 */
namespace App\Http\Controllers;


class BaseController extends Controller
{
    /**
     * 用户和管理员登录界面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function login()
    {
        return view("login");
    }

}
