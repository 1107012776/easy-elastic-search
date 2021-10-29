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
    /**
     * @param $tableName
     * @return Driver
     */
    public static function getInstance($tableName);

    public function __construct($tableName);

    public function findAll();

    public function find();

    /**
     * @param $params
     * @return Driver
     */
    public function where($params);

    /**
     * @param $offset
     * @param $limit
     * @return Driver
     */
    public function limit($offset,$limit);

    /**
     * @param $params
     * @return Driver
     */
    public function order($params);

    public function insert($params);

    public function save($params);
    public function startTrans();
    public function commit();
    public function rollback();
    public function getLastInsertId();



}