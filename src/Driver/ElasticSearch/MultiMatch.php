<?php

namespace EasyElasticSearch\Driver\ElasticSearch;
/**
 * https://blog.csdn.net/danger0629/article/details/102695894
 * GET /news/_search
 * {
 * "query": {
 * "multi_match": {
 * "query": "李安生日",
 * "fields": ["headline", "summary"],
 * "type":    "best_fields",
 * }
 * }
 * Class MultiMatch
 * @package EasyElasticSearch\Driver\ElasticSearch
 */
class MultiMatch
{
    public $query = '';
    public $fields = [];
    //分别是best_fields（最佳字段） 、 most_fields（多数字段） 和 cross_fields（跨字段）
    public $type = 'best_fields';

    public $content = [];

    public function __construct($multi_match)
    {
        $this->content = $multi_match;
    }

    public function build(){
        return $this->content;
    }

}