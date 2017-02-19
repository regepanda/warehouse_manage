<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2016/4/6
 * Time: 13:46
 */

namespace App\Http\Controllers\Client\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use MyClass\Model\Log;

class LogController extends Controller
{
    /**
     *  记录界面首页
     *  @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function sLog()
    {
        return view("Client.sLog");
    }
    /**
     *  列出所有记录
     *  @return JSON
     */
    public function sAllLog()
    {
        $queryLimit = Request::all();
        $log = new Log();
        $returnLog = $log ->select($queryLimit);
        return response()->json($returnLog);
    }
    /**
     *  记录详情
     *  @return JSON
     */
    public function sDetailLog()
    {
        $sDetailLogData = Request::all();
        $log = new Log($sDetailLogData['log_id']);
        //dump($log->info['create_time']->sec);

        //dump($log->info);
        return response()->json($log->info);
    }
}