<?php
/**
 * Created by PhpStorm.
 * User: RagPanda
 * Date: 2016/3/9
 * Time: 11:46
 */

namespace App\Http\Middleware;


use Closure;
use MyClass\Base\GuiFunction;
use Illuminate\Support\Facades\Request;

class AdminLoginCheck
{
    public function __construct(GuiFunction $baseFunc)
    {
        $this->baseFunc = $baseFunc;
    }
    public function handle($request, Closure $next)
    {
        if(session("admin.admin_status")==true)
        {

            return $next($request);

        }
        else
        {
            if(Request::ajax())
            {
                return response()->json(['status' => false, 'message' =>"operation need login"]);
            }
            else
            {
               // $this->baseFunc->setMessage(false, "本操作需要登录");
                return redirect("/admin_login");
            }

        }

    }
}
