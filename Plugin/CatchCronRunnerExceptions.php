<?php

namespace Fsw\ErrorSieve\Plugin;

use Fsw\CronRunner\Console\Base;
use Fsw\ErrorSieve\Model\ExceptionSieve;

class CatchCronRunnerExceptions
{
    public function beforeHandleException(Base $subject, \Throwable $exception)
    {
        ExceptionSieve::saveException($exception);
    }
}
