<?php
namespace PhpEasyData\Tests\Model;
use PhpEasyData\Core\Model;
use PhpEasyData\Driver\ElasticSearchDriver;

class ArticleModel extends Model{
    protected $tableName = 'article';
    protected $driverClass = ElasticSearchDriver::class;
}