<?php

namespace PhpEasyData\Driver;

use Elasticsearch\ClientBuilder;
use PhpEasyData\Components\Common;
use PhpEasyData\Components\ConfigEnv;
use PhpEasyData\Inter\DriverInitInter;
use PhpEasyData\Inter\DriverInter;

/**
 * Created by PhpStorm.
 * User: linyushan
 * Date: 2021/10/24
 * Time: 0:44
 */
class ElasticSearchDriver implements DriverInter,DriverInitInter
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
        if(Common::getCid() > -1){  //协程
            $this->client = ClientBuilder::create()->setHosts([ConfigEnv::get('elasticSearch.host')])->setHandler(new \Yurun\Util\Swoole\Guzzle\Ring\SwooleHandler())->build();
        }else{
            $this->client = ClientBuilder::create()->setHosts([ConfigEnv::get('elasticSearch.host')])->build();
        }
        $this->tableName = $tableName;
    }

    public function findAll()
    {
        $body = [];
        if (!empty($this->_condition)) {
            $body = [
                'query' => $this->_queryPre()
            ];
        }
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
        if (!empty($this->_from)) {
            $body['from'] = intval($this->_from);
        }
        if (!empty($this->_size)) {
            $body['size'] = intval($this->_size);
        }
        if (!empty($this->_order_str)) {
            $body['sort'] = $this->_getSort();
        }
        $params = [
            'index' => $this->tableName,
            'type' => '_doc',
            'body' => $body
        ];
        $response = $this->client->search($params);
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
        return $this->findAll();
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
        $response = $this->client->delete($params);
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
        $response = $this->client->index($params);
        if (!empty($response['_shards']['successful'])
            && !empty($response['_id'])
        ) {
            $this->_last_insert_id = $response['_id'];
            return $response['_id'];
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
     */
    protected function _queryPre()
    {
        return [
            'match' => $this->_condition
        ];
    }

    protected function _getOrderField()
    {
        return Common::getOrderField($this->_order_str);
    }



}
