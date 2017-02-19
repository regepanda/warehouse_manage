<?php
/**
 * Created by PhpStorm.
 * User: RagPanda
 * Date: 2016/3/13
 * Time: 11:29
 */

namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Request;
use MyClass\System\AccessTokenManage;


class AccessTokenCheck
{
    public function __construct()
    {

    }
    public function handle($request, Closure $next)
    {
        /*
        $userModel = new User(1);
        $userModel->setSession();
        return $next($request);*/

        //dump(session("entrance"));exit();
        if(session("entrance.entrance_status")==true)
        {
            return $next($request);
        }
        if($request->input("access_token") == null)
        {
            return response()->json(["status"=>false,"message"=>"需要access_token参数","data"=>[]]);
        }
        $result = AccessTokenManage::checkEntranceAccessToken($request->input("access_token"));

        if($result!=false)
        {
            $result->setSession();
            return $next($request);

        }
        else
        {
            return response()->json(["status"=>false,"message"=>"access token错误","data"=>[]]);
        }

    }
}