<?php

namespace Fsw\ErrorSieve\Plugin;

use Fsw\ErrorSieve\Model\ExceptionSieve;
use Magento\Framework\Webapi\ErrorProcessor;

class CatchWebapiExceptions
{
    protected $saved = false;
    public function beforeMaskException(ErrorProcessor $subject, \Exception $e)
    {
        $this->saved || ExceptionSieve::saveException($e);
        $this->saved = true;
        return null;
    }
}