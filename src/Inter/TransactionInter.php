<?php
namespace PhpEasyData\Inter;
interface TransactionInter
{
    public function startTrans();
    public function commit();
    public function rollback();
}