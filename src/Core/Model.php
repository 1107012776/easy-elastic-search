<?php

namespace PhpEasyData\Inter;
/**
 * Created by PhpStorm.
 * User: 11070
 * Date: 2021/10/24
 * Time: 0:39
 */
abstract class Model
{
    /**
     * @var Driver
     */
    protected $driver;

    public function findAll($params)
    {
        return $this->driver->findAll($params);
    }

    public function find($params)
    {
        return $this->driver->find($params);

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