<?php

namespace EasyElasticSearch\Inter;
/**
 * Created by PhpStorm.
 * User: 11070
 * Date: 2021/10/24
 * Time: 0:47
 */
interface DriverInter
{

    public function findAll();

    public function find();

    public function field($params);

    /**
     * @param $params
     * @return DriverInter
     */
    public function where($params);

    /**
     * @param $offset
     * @param $limit
     * @return DriverInter
     */
    public function limit($offset, $limit);

    /**
     * @param $params
     * @return DriverInter
     */
    public function order($params);

    public function insert($params);

    public function save($params);

    public function delete();

    public function getLastInsertId();


}