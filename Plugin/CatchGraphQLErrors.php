<?php

namespace Fsw\ErrorSieve\Plugin;

use Fsw\ErrorSieve\Model\ExceptionSieve;
use Magento\Framework\GraphQl\Query\QueryProcessor;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Schema;

class CatchGraphQLErrors
{
    public function aroundProcess(
        QueryProcessor $subject,
        \Closure $process,
        Schema $schema,
        string $source,
        ContextInterface $contextValue = null,
        array $variableValues = null,
        string $operationName = null
    ) {
        //return $process($schema, $source, $contextValue, $variableValues, $operationName);
        $rootValue = null;
        return \GraphQL\GraphQL::executeQuery(
            $schema,
            $source,
            $rootValue,
            $contextValue,
            $variableValues,
            $operationName
        )->setErrorsHandler(function(array $errors, callable $formatter) {
            foreach ($errors as $e) {
                ExceptionSieve::saveException($e);
            }
            return array_map($formatter, $errors);
        })->toArray(false);
    }

}