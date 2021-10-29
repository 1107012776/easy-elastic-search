<?php
namespace PhpEasyData\Tests\Model;
use PhpEasyData\Core\Model;
use PhpEasyData\Core\TransactionTrait;
use PhpEasyData\Driver\ElasticSearchDriver;
use PhpEasyData\Inter\DriverInter;
use PhpEasyData\Inter\TransactionInter;

class ArticleModel extends Model implements DriverInter,TransactionInter {
    use TransactionTrait;
    protected $tableName = 'article';
    protected $driverClass = ElasticSearchDriver::class;
}