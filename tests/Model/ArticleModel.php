<?php

namespace EasyES\Tests\Model;

use EasyES\Core\Model;
use EasyES\Driver\ElasticSearchDriver;
use EasyES\Inter\DriverInter;

class ArticleModel extends Model implements DriverInter
{
    protected $tableName = 'article';
    protected $driverClass = ElasticSearchDriver::class;
}