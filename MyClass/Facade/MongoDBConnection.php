<?php
/**
 * Created by PhpStorm.
 * User: RagPanda
 * Date: 2016/3/27
 * Time: 10:47
 */

namespace MyClass\Facade;


use Illuminate\Support\Facades\Facade;

class MongoDBConnection extends  Facade
{
    public static function getFacadeAccessor()
    {
        return 'MongoDBConnection';
    }
}

