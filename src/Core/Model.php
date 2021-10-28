<?php

namespace PhpEasyData\Core;
use PhpEasyData\Driver\ElasticSearchDriver;
use PhpEasyData\Inter\Driver;

/**
 * Created by PhpStorm.
 * User: 11070
 * Date: 2021/10/24
 * Time: 0:39
 */
abstract class Model
{
    protected $tableName = '';
    protected $driverClass = ''; // string
    /**
     * @var Driver
     */
    protected $driver;
    public function __construct()
    {
        /**
         * @var Driver $driverClass
         */
        $driverClass = $this->driverClass;
        $this->driver = $driverClass::getInstance($this->tableName);
    }

    public function insert($data){
        return $this->driver->insert($data);
    }

    public function save($data){
        return $this->driver->save($data);
    }

    public function findAll()
    {
        return $this->driver->findAll();
    }

    public function find()
    {
        return $this->driver->find();

    }

    public function where($params)
    {
        return $this->driver->where($params);
    }

    public function limit($offset, $limit)
    {
        return $this->driver->limit($offset, $limit);
    }

    public function order($params)
    {
        return $this->driver->order($params);
    }
}