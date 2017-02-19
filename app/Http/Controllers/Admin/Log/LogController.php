<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2016/4/6
 * Time: 13:46
 */

namespace App\Http\Controllers\Admin\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use MyClass\Model\Log;

class LogController extends Controller
{
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
        return response()->json($log->info);
    }
}