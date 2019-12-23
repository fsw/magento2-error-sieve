<?php
namespace Fsw\ErrorSieve\Plugin;

use Fsw\ErrorSieve\Model\ExceptionSieve;
use Magento\Framework\Logger\Handler\Exception;

class CatchLoggedExceptions
{
    public function beforeWrite(Exception $subject, $record)
    {
        if (!empty($record['context']['exception'])) {
            ExceptionSieve::saveException($record['context']['exception']);
        }
    }
}