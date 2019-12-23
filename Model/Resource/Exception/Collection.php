<?php

namespace Fsw\ErrorSieve\Model\Resource\Exception;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init('Fsw\ErrorSieve\Model\Exception', 'Fsw\ErrorSieve\Model\Resource\Exception');
    }
}
