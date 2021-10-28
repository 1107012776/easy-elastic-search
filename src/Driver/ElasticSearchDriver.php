<?php

namespace PhpEasyData\Driver;

use Elasticsearch\ClientBuilder;
use PhpEasyData\Components\ConfigEnv;
use PhpEasyData\Inter\Driver;

/**
 * Created by PhpStorm.
 * User: linyushan
 * Date: 2021/10/24
 * Time: 0:44
 */
class ElasticSearchDriver implements Driver
{
    protected $tableName;
    protected $client;
    protected $_condition = [];
    protected $_condition_str = '';
    protected $_condition_bind = [];
    protected $_field = [];
    protected $_limit_str = '';
    protected $_order_str = '';
    protected $_group_str = '';
    protected $_field_str = '*';
    protected $_insert_data = [];
    protected $_update_data = [];
    protected $_last_insert_id = 0;  //最后插入的id
    protected $_offset = 0; //偏移量
    protected $_offset_limit = 0; //偏移之后返回数
    
    public static function getInstance($tableName)
    {
        return new static($tableName);
    }

    public function __construct($tableName = '')
    {
        $this->client = ClientBuilder::create()->setHosts([ConfigEnv::get('elasticSearch.host')])->build();
        $this->tableName = $tableName;
    }

    public function findAll()
    {
        $body = [];
        if(!empty($this->_condition)){
            $body = [
                'query' => [
                    'match' => $this->_condition
                ]
            ];
        }
        $params = [
            'index' => $this->tableName,
            'type' => '_doc',
            'body' => $body
        ];
        $response = $this->client->search($params);
        return $response;
    }

    public function find()
    {
        $body = [];
        if(!empty($this->_condition)){
            $body = [
                'query' => [
                    'match' => $this->_condition
                ]
            ];
        }
        $params = [
            'index' => $this->tableName,
            'type' => '_doc',
            'body' => $body
        ];
        $response = $this->client->search($params);
        return $response;
    }

    public function where($condition)
    {
        $this->_condition = array_merge($this->_condition, $condition);
        return $this;
    }

    public function limit($offset, $limit)
    {

    }

    public function order($params)
    {

    }

    public function insert($data)
    {
        if(isset($data['id'])){
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
        return $response;
    }

    public function save($params)
    {

    }


}
