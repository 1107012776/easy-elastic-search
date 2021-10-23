<?php
/**
 * Created by PhpStorm.
 * User: 11070
 * Date: 2021/10/24
 * Time: 0:30
 */
namespace PhpEasyData\Tests;
use PHPUnit\Framework\TestCase;

$file_load_path = '../../../autoload.php';
if (file_exists($file_load_path)) {
    include $file_load_path;
} else {
    include '../vendor/autoload.php';
}
class SearchTest extends TestCase{

}