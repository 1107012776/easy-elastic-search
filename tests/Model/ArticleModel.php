<?php

namespace PhpEasyEs\Tests\Model;

use PhpEasyEs\Core\Model;
use PhpEasyEs\Driver\ElasticSearchDriver;
use PhpEasyEs\Inter\DriverInter;

class ArticleModel extends Model implements DriverInter
{
    protected $tableName = 'article';
    protected $driverClass = ElasticSearchDriver::class;
}