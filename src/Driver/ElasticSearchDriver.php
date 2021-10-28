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
    protected $condition = [];

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

    }

    public function find()
    {
        $params = [
            'index' => $this->tableName,
            'type' => '_doc',
            'body' => [
                'query' => [
                    'match' => $this->condition
                ]
            ]
        ];
        $response = $this->client->search($params);
        return $response;
    }

    public function where($condition)
    {
        $this->condition = array_merge($this->condition, $condition);
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
