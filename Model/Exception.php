<?php

namespace Fsw\ErrorSieve\Model;

use Magento\Framework\Model\AbstractModel;

class Exception extends AbstractModel
{
    const STATUS_NEW = 1;
    const STATUS_RECURRING = 2;
    const STATUS_ACKNOWLEDGED = 3;
    const STATUS_FIX_PENDING = 4;
    const STATUS_FIX_DEPLOYED = 5;

    const STATUS_MAP = [
        self::STATUS_NEW => 'NEW',
        self::STATUS_RECURRING => 'RECURRING',
        self::STATUS_ACKNOWLEDGED => 'ACKNOWLEDGED',
        self::STATUS_FIX_PENDING => 'FIX_PENDING',
        self::STATUS_FIX_DEPLOYED => 'FIX_DEPLOYED'
    ];

    protected function _construct()
    {
        parent::_construct();
        $this->_init('Fsw\ErrorSieve\Model\Resource\Exception');
    }

    /**
     * @param $string
     * @return false|int
     */
    public static function getStatusFromString($string)
    {
        return array_search($string, self::STATUS_MAP);
    }

    /**
     * @return string
     */
    public function getStatusString()
    {
        return self::STATUS_MAP[$this->getStatus()];
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        $title = $this->getLastMessage();
        $max = 60;
        if (mb_strlen($title, 'utf-8') >= $max) {
            $title = mb_substr($title, 0, $max - 5, 'utf-8') . '...';
        }
        return "[{$this->getId()}] {$title}";
    }
}
