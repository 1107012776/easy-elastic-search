<?php

namespace EasyES\Tests\Model;

use EasyES\Core\Model;
use EasyES\Driver\ElasticSearchDriver;
use EasyES\Inter\DriverInter;

class UserModel extends Model implements DriverInter
{
    protected $tableName = 'user';
    protected $driverClass = ElasticSearchDriver::class;
}