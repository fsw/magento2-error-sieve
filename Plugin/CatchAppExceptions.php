<?php

namespace Fsw\ErrorSieve\Plugin;

use Exception;
use Fsw\ErrorSieve\Model\ExceptionSieve;
use Magento\Framework\App\Bootstrap;
use Magento\Framework\AppInterface;

class CatchAppExceptions
{
    public function aroundCatchException(AppInterface $subject, callable $proceed, Bootstrap $bootstrap, Exception $exception)
    {
        $e = $exception;
        do {
            ExceptionSieve::saveException($e);
        } while ($e = $e->getPrevious());
        return $proceed($bootstrap, $exception);
    }
}
