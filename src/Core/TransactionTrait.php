<?php
namespace PhpEasyData\Core;

use PhpEasyData\Inter\TransactionInter;

/**
 * 是否支持事务
 * Trait TransactionTrait
 * @package PhpEasyData\Core
 * @property TransactionInter $driver
 */
trait TransactionTrait{
    /**
     * 启动事务
     * @access public
     * @return void
     */
    public function startTrans()
    {
        return $this->driver->startTrans();
    }

    /**
     * 提交事务
     * @access public
     * @return boolean
     */
    public function commit()
    {
        return $this->driver->commit();
    }

    /**
     * 事务回滚
     * @access public
     * @return boolean
     */
    public function rollback()
    {
        return $this->driver->rollback();
    }
}
