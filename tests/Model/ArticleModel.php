<?php
namespace PhpEasyData\Tests\Model;
use PhpEasyData\Core\Model;
use PhpEasyData\Core\TransactionTrait;
use PhpEasyData\Driver\ElasticSearchDriver;

class ArticleModel extends Model{
    use TransactionTrait;
    protected $tableName = 'article';
    protected $driverClass = ElasticSearchDriver::class;
}