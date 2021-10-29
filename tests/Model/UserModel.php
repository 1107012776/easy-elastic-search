<?php
namespace PhpEasyData\Tests\Model;
use PhpEasyData\Core\Model;
use PhpEasyData\Driver\ElasticSearchDriver;
use PhpEasyData\Inter\DriverInter;

class UserModel extends Model implements DriverInter {
    protected $tableName = 'user';
    protected $driverClass = ElasticSearchDriver::class;
}