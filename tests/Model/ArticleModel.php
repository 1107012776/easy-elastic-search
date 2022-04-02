<?php

namespace EasyElasticSearch\Tests\Model;

use EasyElasticSearch\Core\Model;
use EasyElasticSearch\Driver\ElasticSearchDriver;
use EasyElasticSearch\Inter\DriverInter;

class ArticleModel extends Model implements DriverInter
{
    protected $tableName = 'article';
    protected $driverClass = ElasticSearchDriver::class;
}