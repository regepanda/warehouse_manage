<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use MyClass\Model\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        try
        {
            $log = new Log();
            $log -> addLog("系统错误，无法捕捉：".$e->getMessage().$e->getFile().":".$e->getLine(),null,null,2);

        }
        catch(Exception $e)
        {
            return response()->json(["status"=>false,
                "message"=>$e->getMessage().$e->getFile().":".$e->getLine(),"data"=>[],"result_code"=>-1]);
        }


        if($request->ajax()||
            $request->header("X-XSRF-TOKEN",null)!=null||$request->is('api*'))
        {
            return response()->json(["status"=>false,
                "message"=>$e->getMessage().$e->getFile().":".$e->getLine(),"data"=>[],"result_code"=>-1]);
        }
        else
        {
            return parent::render($request, $e);
        }
    }
}
