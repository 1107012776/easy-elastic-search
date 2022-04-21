<?php

namespace EasyElasticSearch\Core;


use EasyElasticSearch\Inter\DriverInitInter;
use EasyElasticSearch\Inter\DriverInter;

/**
 * Created by PhpStorm.
 * User: 11070
 * Date: 2021/10/24
 * Time: 0:39
 */
abstract class Model
{
    const TYPE_SHOULD = 'should';
    const TYPE_MUST = 'must';
    const TYPE_MUST_NOT = 'must_not';
    protected $tableName = '';
    protected $driverClass = ''; // string
    /**
     * @var DriverInter
     */
    protected $driver;

    public function __construct()
    {
        /**
         * @var DriverInitInter $driverClass
         */
        $driverClass = $this->driverClass;
        $this->driver = $driverClass::getInstance($this->tableName);
    }

    public function insert($data)
    {
        return $this->driver->insert($data);
    }

    public function save($data)
    {
        return $this->driver->save($data);
    }

    public function findAll()
    {
        return $this->driver->findAll();
    }


    public function field($fields = [])
    {
        $this->driver->field($fields);
        return $this;
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


    /**
     * 获取最后插入的id,有可能是插入失败，返回0
     * @return int
     */
    public function getLastInsertId()
    {
        return $this->driver->getLastInsertId();
    }


    public function delete()
    {
        return $this->driver->delete();
    }

    public function deleteIndex($indexName)
    {
        return $this->driver->deleteIndex($indexName);
    }


}