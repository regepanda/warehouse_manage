<?php namespace MyClass;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use MyClass\Base\GuiFunction;
use MyClass\System\MongoDBConnection;
class MyClassServiceProvider extends ServiceProvider {
    
    

    /**
     * 执行注册后的启动服务。
     *
     * @return void
     */
    public function boot()
    {
        //$this->loadViewsFrom(__DIR__."/views" , 'MyClass');
        //scanDir(__DIR__."/Test/Route");
    }
    

    /**
     * 在容器中注册绑定。
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton("GuiFunction",function($app){
            return new GuiFunction();
        });
        $this->app->singleton('MongoDBConnection',function($app){
            $mongodb = new MongoDBConnection(config("mongodb.mongodb_connection"));
            return $mongodb;
        });
    }

}

function scanDir($dir = __DIR__)
{
    //dump($dir);
    $handle = opendir ($dir);
    while ( false !== ($file = readdir ( $handle )) )
    {
        if($file == "." || $file == ".."){continue;}


        if ( is_dir($dir.'/'.$file) ) {
            //dump("文件夹：".$dir.'/'.$file);
            scanDir($dir.'/'.$file);

        }
        else
        {
            //dump("文件：".$dir.'/'.$file);
            require $dir.'/'.$file;
        }
    }

}
