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
    public static function getInstance($tableName);

    public function __construct($tableName);

    public function findAll();

    public function find();

    public function where($params);

    public function limit($offset,$limit);

    public function order($params);

    public function insert($params);

    public function save($params);
}