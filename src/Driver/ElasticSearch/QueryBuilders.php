<?php

namespace PhpEasyData\Driver\ElasticSearch;
/**
 * https://zhuanlan.zhihu.com/p/68159178
 *
 * https://blog.csdn.net/tclzsn7456/article/details/79956625
 *
 * https://blog.csdn.net/danger0629/article/details/102695894  elasticsearch优化之多字段搜索multi_match查询
 * Query DSL
 * Class QueryBuilders
 * @package PhpEasyData\Driver\ElasticSearch
 * 注意一下，term结合bool使用时：should是或，must是与，must_not是非
 *
 *
 *
 */
class QueryBuilders
{
    public $should = [];
    public $must = [];  //(其实就是与)


    public $must_not = []; //(其实就是或)

    /**
     * 这个例子包含两个匹配查询，返回地址中包含“mill”和“lane”的所有帐户:
     * bool must子句指定了所有必须为true的查询，则将文档视为匹配。(与)
     */
    public function must($must)
    {
        /* curl -X GET "localhost:9200/bank/_search" -H 'Content-Type: application/json' -d'
            {
              "query": {
                "bool": {
                  "must": [
                    { "match": { "address": "mill" } },
                    { "match": { "address": "lane" } }
                  ]
                }
              }
            }
        */
        $this->must = $must;
        return true;
    }


    /**
     * 这个例子包含两个匹配查询，返回地址中既不包含“mill”也不包含“lane”的所有帐户:
     * bool must_not子句指定了一个查询列表，其中没有一个查询必须为真，才能将文档视为匹配。
     */
    public function must_not($must_not){
        /* curl -X GET "localhost:9200/bank/_search" -H 'Content-Type: application/json' -d'
        {
          "query": {
            "bool": {
              "must_not": [
                { "match": { "address": "mill" } },
                { "match": { "address": "lane" } }
              ]
            }
          }
        }*/
        $this->must_not = $must_not;
        return true;

    }

    /**
     * 这个例子包含两个匹配查询，并返回地址中包含“mill”或“lane”的所有帐户:
     * bool should子句指定了一个查询列表，其中任何一个查询必须为真，才能将文档视为匹配。(其实就是或)
     */
    public function should($should){
        /*curl -X GET "localhost:9200/bank/_search" -H 'Content-Type: application/json' -d'
            {
              "query": {
                "bool": {
                  "should": [
                    { "match": { "address": "mill" } },
                    { "match": { "address": "lane" } }
                  ]
                }
              }
            }
        }*/
        $this->should = $should;
        return true;

    }

    /**
     * 构造Query DSL
     * @return array
     */
    public function build(){
        $queryBuild = [];
        if(!empty($this->must)){
            $queryBuild['bool']['must'] = $this->must;
        }
        if(!empty($this->must_not)){
            $queryBuild['bool']['must_not'] = $this->must_not;
        }
        if(!empty($this->should)){
            $queryBuild['bool']['should'] = $this->should;
        }
        return $queryBuild;
    }
}