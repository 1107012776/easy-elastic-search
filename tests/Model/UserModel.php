<?php
namespace PhpEasyData\Tests\Model;
use PhpEasyData\Core\Model;
use PhpEasyData\Driver\ElasticSearchDriver;

class UserModel extends Model{
    protected $tableName = 'user';
    protected $driverClass = ElasticSearchDriver::class;
}