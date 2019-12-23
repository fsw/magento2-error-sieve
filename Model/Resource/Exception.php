<?php

namespace Fsw\ErrorSieve\Model\Resource;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Exception extends AbstractDb
{
    const TABLE_NAME = 'fsw_sieved_errors';

    protected function _construct()
    {
        $this->_init(static::TABLE_NAME, 'id');
    }
}
