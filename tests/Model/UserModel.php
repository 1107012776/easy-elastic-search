<?php

namespace PhpEasyEs\Tests\Model;

use PhpEasyEs\Core\Model;
use PhpEasyEs\Driver\ElasticSearchDriver;
use PhpEasyEs\Inter\DriverInter;

class UserModel extends Model implements DriverInter
{
    protected $tableName = 'user';
    protected $driverClass = ElasticSearchDriver::class;
}