<?php

namespace PhpEasyData\Inter;
/**
 * Created by PhpStorm.
 * User: 11070
 * Date: 2021/10/24
 * Time: 0:47
 */
interface Driver
{
    public function findAll($params);

    public function find($params);

    public function where($params);

    public function limit($offset,$limit);

    public function order($params);
}