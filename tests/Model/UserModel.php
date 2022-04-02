<?php

namespace EasyElasticSearch\Tests\Model;

use EasyElasticSearch\Core\Model;
use EasyElasticSearch\Driver\ElasticSearchDriver;
use EasyElasticSearch\Inter\DriverInter;

class UserModel extends Model implements DriverInter
{
    protected $tableName = 'user';
    protected $driverClass = ElasticSearchDriver::class;
}