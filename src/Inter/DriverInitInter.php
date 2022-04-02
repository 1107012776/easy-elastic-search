<?php

namespace EasyElasticSearch\Inter;


interface DriverInitInter
{
    /**
     * @param $tableName
     * @return DriverInter
     */
    public static function getInstance($tableName);

    public function __construct($tableName);

}