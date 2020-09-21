<?php
namespace Fsw\ErrorSieve\GraphQl;

use Fsw\ErrorSieve\Model\ExceptionSieve;
//use Magento\Framework\GraphQl\Query\ErrorHandlerInterface;

class ErrorHandler //implements ErrorHandlerInterface
{
    public function handle(array $errors, callable $formatter): array
    {
        foreach ($errors as $e) {
            ExceptionSieve::saveException($e);
        }
        return array_map($formatter, $errors);
    }
}