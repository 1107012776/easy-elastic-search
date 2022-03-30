<?php

namespace PhpEasyData\Tests\Model;

use PhpEasyData\Core\Model;
use PhpEasyData\Driver\ElasticSearchDriver;
use PhpEasyData\Inter\DriverInter;

class ArticleModel extends Model implements DriverInter
{
    protected $tableName = 'article';
    protected $driverClass = ElasticSearchDriver::class;
}