<?php
/**
 * Created by PhpStorm.
 * User: 11070
 * Date: 2021/10/24
 * Time: 0:30
 */

namespace EasyElasticSearch\Tests;

use EasyElasticSearch\Components\ConfigEnv;
use EasyElasticSearch\Tests\Model\ArticleModel;
use EasyElasticSearch\Tests\Model\UserModel;
use PHPUnit\Framework\TestCase;

$file_load_path = '../../../autoload.php';
if (file_exists($file_load_path)) {
    include $file_load_path;
} else {
    include '../vendor/autoload.php';
}
ConfigEnv::loadFile('.env');

class SearchTest extends TestCase
{

    public function testConfig()
    {
        var_dump(ConfigEnv::get('elasticSearch.host'));
    }


    public function testInsert()
    {
        $article = new ArticleModel();
        $res = $article->insert(['name' => 'John Doe1', 'tt' => 1]);
        print_r($res);
    }

    public function testSave()
    {
        $user = new UserModel();
        $res = $user->where([
            'id' => 9
        ])->save(['name' => 11111]);
        var_dump($res);
    }

    public function testAll()
    {
        $user = new UserModel();
        $res = $user->order('name asc,_id desc')->limit(0, 4)->findAll();
        print_r($res);
    }

    public function testArticleAll()
    {
        $model = new ArticleModel();
        $list = $model->limit(0, 100)->findAll();
        var_dump($list);


    }

    public function testDeleteIndex()
    {
        $model = new ArticleModel();
        $response = $model->deleteIndex('article');
        var_dump($response);
    }

    public function testArticleOne()
    {
        $model = new ArticleModel();
        $res = $model->where(['id' => 'f9pqSoABh7k0PM1nEYoz'])->find();
//        $res = $model->where(['id' => 'itqAJ4ABh7k0PM1nMIiI'])->find();
        print_r($res);
    }


    public function testAll1()
    {
        $user = new UserModel();
        $res = $user->where([
            'tt' => 1
        ])->findAll();
        print_r($res);
    }

    /**
     * 模糊匹配
     */
    public function testAllMultiMatch()
    {
        $article = new ArticleModel();
        $res = $article->where([
            'article_title,content_md,article_keyword,article_descript,author' => [
                'multi_match', '项目'
            ],
//            '_id' => 'ydpqSoABh7k0PM1nFore'
        ])->field('article_title,article_cate')->findAll();
        print_r($res);
    }

    public function testAllAticle()
    {
        $article = new ArticleModel();
        /*       $res = $user->field(['name'])->where([
                   'name' => 111
               ])->findAll();*/
        $res = $article->where([
            'tt' => 1
        ])->findAll();
        print_r($res);
    }

    public function testOne()
    {
        $user = new UserModel();
        $res = $user->order('name asc,_id desc')->find();
        print_r($res);
    }


    public function testDelete()
    {
        $user = new UserModel();
        $res = $user->where([
            'id' => 9
        ])->delete();
        print_r($res);
    }

}