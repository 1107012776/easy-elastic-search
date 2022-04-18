# easy-elastic-search

简易ElasticSearch类库，犹如操作数据库，便于开发，基于elasticsearch/elasticsearch

# 环境

### ElasticSearch7.7.0 安装如下
https://www.developzhe.com/single249.html

# 安装
You can install the package via composer:
```bash
composer require lys/easy-elastic-search=dev-main
```
# 注意
```bash
//协程环境需要安装如下:
composer require yurunsoft/guzzle-swoole=v2.2

```

# 示例
You need to configure an env file.
```
[elasticSearch]
host=http://127.0.0.1:9200   #多个请使用英文逗号分割如 http://127.0.0.1:9200,http://192.168.1.6:9200
```

```php
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


    public function testAll1()
    {
        $user = new UserModel();
        /*       $res = $user->field(['name'])->where([
                   'name' => 111
               ])->findAll();*/
        $res = $user->where([
            'tt' => 1
        ])->findAll();
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

```
