<?php

namespace EasyElasticSearch\Driver;

use EasyElasticSearch\Core\Model;
use Elasticsearch\ClientBuilder;
use EasyElasticSearch\Components\Common;
use EasyElasticSearch\Components\ConfigEnv;
use EasyElasticSearch\Driver\ElasticSearch\QueryBuilders;
use EasyElasticSearch\Inter\DriverInitInter;
use EasyElasticSearch\Inter\DriverInter;

/**
 * Created by PhpStorm.
 * User: linyushan
 * Date: 2021/10/24
 * Time: 0:44
 */
class ElasticSearchDriver implements DriverInter, DriverInitInter
{
    protected $tableName;
    protected $client;
    protected $_condition = [];
    protected $_field = [];
    protected $_last_insert_id = 0;  //最后插入的id
    protected $_size = 0; //偏移之后返回数
    protected $_from = 0; //偏移量
    protected $_order_str = ''; //排序

    public static function getInstance($tableName)
    {
        return new static($tableName);
    }

    public function __construct($tableName = '')
    {
        $hosts = ConfigEnv::get('elasticSearch.host');
        if (strpos($hosts, ',') !== false) {
            $hosts = explode(',', $hosts);
        }
        if (is_string($hosts)) {
            $hosts = [$hosts];
        }
        if (Common::getCid() > -1) {  //协程
            $client = ClientBuilder::create()
                ->setHosts($hosts)
                ->setHandler(new \Yurun\Util\Swoole\Guzzle\Ring\SwooleHandler());
        } else {
            $client = ClientBuilder::create()
                ->setHosts($hosts);
        }
        $username = ConfigEnv::get('elasticSearch.username');
        if (!empty($username)) {
            $password = ConfigEnv::get('elasticSearch.password');
            $client->setBasicAuthentication($username, $password);
        }
        $this->client = $client->build();
        $this->tableName = $tableName;
    }

    /**
     * 查询需要返回的字段
     * @param array $fields
     * @return $this
     *
     */
    public function field($fields = [])
    {
        if (is_string($fields)) {
            $fields = explode(',', $fields);
            $fields = array_filter($fields);
        }
        $this->_field = array_merge($this->_field, $fields);
        $this->_field = array_filter($this->_field);
        $this->_field = array_unique($this->_field);
        return $this;
    }

    public function findAll()
    {
        $body = [];
        if (!empty($this->_condition)) {
            $body = [
                'query' => $this->_queryPre()
            ];
        }
        if (!empty($this->_from)) {
            $body['from'] = intval($this->_from);
        }
        if (!empty($this->_size)) {
            $body['size'] = intval($this->_size);
        }
        if (!empty($this->_order_str)) {
            $body['sort'] = $this->_getSort();
        }
        if (!empty($this->_field)) {
            $body['_source'] = $this->_field;
        }
        $params = [
            'index' => $this->tableName,
            'type' => '_doc',
            'body' => $body
        ];
        try {
            $response = $this->client->search($params);
        } catch (\Elasticsearch\Common\Exceptions\Missing404Exception $e) {
            return [];
        }
        if (!empty($response['_shards']['successful'])
            && !empty($response['hits']['hits'])
        ) {
            return $response['hits']['hits'];
        }
        return [];
    }

    public function find()
    {
        $this->limit($this->_from, 1);
        $search = $this->findAll();
        return isset($search[0]) ? $search[0] : [];
    }

    public function where($condition)
    {
        $this->_condition = array_merge($this->_condition, $condition);
        return $this;
    }

    public function delete()
    {
        if (!empty($this->_condition['id'])) {
            $id = $this->_condition['id'];
        } else {
            return false;
        }
        $params = [
            'index' => $this->tableName,
            'type' => '_doc',
            'id' => $id
        ];
        try {
            $response = $this->client->delete($params);
        } catch (\Elasticsearch\Common\Exceptions\Missing404Exception $e) {
            return false;
        }
        if (!empty($response['_shards']['successful'])
            && !empty($response['_id'])
        ) {
            $this->_last_insert_id = $response['_id'];
            return $response['_id'];
        }
        return false;
    }

    public function limit($offset, $limit = 0)
    {
        if (empty($limit)) {
            $this->_from = 0;
            $this->_size = sprintf("%.0f", $offset);
        } else {
            $this->_from = sprintf("%.0f", $offset);
            $this->_size = sprintf("%.0f", $limit);
        }
        return $this;
    }

    public function order($params)
    {
        $this->_order_str = $params;
        return $this;
    }

    public function insert($data)
    {
        if (isset($data['id'])) {
            $id = $data['id'];
            unset($data['id']);
        }
        $params = [
            'index' => $this->tableName,
            'type' => '_doc',
            'body' => $data
        ];
        !empty($id) && $params['id'] = $id;
        $response = $this->client->index($params);
        if (!empty($response['_shards']['successful'])
            && !empty($response['_id'])
        ) {
            $this->_last_insert_id = $response['_id'];
            return $response['_id'];
        }
        return false;
    }

    public function save($data)
    {
        if (isset($data['id'])) {
            unset($data['id']);
        }
        if (!empty($this->_condition['id'])) {
            $id = $this->_condition['id'];
        } else {
            return false;
        }
        $params = [
            'index' => $this->tableName,
            'type' => '_doc',
            'body' => $data
        ];
        !empty($id) && $params['id'] = $id;
        try {
            $response = $this->client->index($params);
        } catch (\Elasticsearch\Common\Exceptions\Missing404Exception $e) {
            return false;
        }
        if (!empty($response['_shards']['successful'])
            && !empty($response['_id'])
        ) {
            $this->_last_insert_id = $response['_id'];
            return $response['_id'];
        }
        return false;
    }


    /**
     * 删除索引
     */
    public function deleteIndex($indexName)
    {
        if ($indexName != $this->tableName) {
            return false;
        }
        $params = [
            'index' => $this->tableName,
        ];
        try {
            $response = $this->client->indices()->delete($params);
        } catch (\Elasticsearch\Common\Exceptions\Missing404Exception $e) {
            return false;
        }
        if (!empty($response['acknowledged'])) {
            return true;
        }
        return false;
    }

    public function getLastInsertId()
    {
        return $this->_last_insert_id;
    }


    protected function _getSort()
    {
        $sort = [];
        $arr = $this->_getOrderField();
        foreach ($arr as $k => $v) {
            $sort[$v[0]] = ['order' => $v[1]];
        }
        return [
            $sort
        ];
    }


    /*************************** 私有方法   ***********************************/

    /**
     * 查询where条件解析
     * @return array
     * https://www.cnblogs.com/yjf512/p/4897294.html
     * https://www.cnblogs.com/tjp40922/p/12897996.html
     */
    protected function _queryPre()
    {
        $queryBuild = new QueryBuilders();
        $must_not_term = $should_term = $must_term = $multi_match_term = [];
        $conditionKeys = array_keys($this->_condition);
        foreach ($this->_condition as $key => $val) {
            if ($key == 'id') {
                $key = '_id';
            }
            if (is_array($val)) {
                if (!isset($val[0]) || !in_array($val[0], [
                        Model::TYPE_MULTI_MATCH,
                        Model::TYPE_SHOULD,
                        Model::TYPE_MUST,
                        Model::TYPE_MUST_NOT,
                    ])) {
                    continue;
                }
                switch ($val[0]) {
                    case Model::TYPE_MULTI_MATCH:
                        if (in_array('id', $conditionKeys)
                            || in_array('_id', $conditionKeys)
                        ) {
                            continue 2;
                        }
                        $multi_match_term = [
                            'query' => $val[1],
                            "fields" => strpos($key, ',') === false ? [$key] : explode(',', $key),    #只要里面一个字段包含值 blog 既可以
                        ];
                        if (isset($val[2])) {
                            $multi_match_term['type'] = $val[2];
                        }
                        !empty($multi_match_term) && $queryBuild->multi_match($multi_match_term);
                        break;
                    case Model::TYPE_SHOULD:
                        $should_term[]['term'] = [
                            $key => $val[1]
                        ];
                        break;
                    case Model::TYPE_MUST:
                        $must_term[]['term'] = [
                            $key => $val[1]
                        ];
                        break;
                    case Model::TYPE_MUST_NOT:
                        $must_not_term[]['term'] = [
                            $key => $val[1]
                        ];
                        break;
                }
            } else {
                $must_term[]['term'] = [
                    $key => $val
                ];
            }
        }
        !empty($should_term) && $queryBuild->should($should_term);
        !empty($must_term) && $queryBuild->must($must_term);
        !empty($must_not_term) && $queryBuild->must_not($must_not_term);
        /*   $params = [
       'index' => 'study_article',
       'type' => '_doc',
       'body' => [
           'query' => [
               'multi_match' => [
                   "query" => "分类",
                   "fields" => ["content_md","article_keyword",'article_descript','author']   #只要里面一个字段包含值 blog 既可以
               ],
//                     "term"=> [
//                         "cate_id"=> 3
//                     ]
           ],
           "size" => 10,
           "from" => 0,
           "sort" => [
               [
                   "_id" => [
                       "order" => "desc"
                   ]
               ]
           ]
       ],
   ];*/

        $query = $queryBuild->build();
        return $query;
    }

    protected function _getOrderField()
    {
        return Common::getOrderField($this->_order_str);
    }


}
