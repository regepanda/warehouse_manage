<?php
/**
 * Created by PhpStorm.
 * User: RagPanda
 * Date: 2016/3/26
 * Time: 13:41
 */

namespace MyClass\System;

class MongoDBConnection
{
    public $connection;

    public function __construct($host="mongodb://localhost:27017")
    {
        $this->connection = new \MongoClient($host);
        $this->connection = $this->connection->selectDB(config("mongodb.mongodb_default_db"));
        //dump($this->connection);
    }
    public function link()
    {
        return $this->connection;
    }
    public function collection($col)
    {
        return $this->connection->selectCollection($col);
    }

}