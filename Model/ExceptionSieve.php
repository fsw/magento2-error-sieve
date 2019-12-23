<?php

namespace Fsw\ErrorSieve\Model;

class ExceptionSieve
{
    /**
     * @param \Exception $exception
     */
    public static function saveException(\Exception $exception)
    {
        (new static())->save($exception);
    }

    private function getSourceName()
    {
        return php_sapi_name();
    }

    private function getDbConnection()
    {
        $env = require(BP . '/app/etc/env.php');
        $db = $env['db']['connection']['default'];
        return new \PDO("mysql:dbname={$db['dbname']};host={$db['host']}", $db['username'], $db['password']);
    }

    private function getSQL()
    {
        $statusNew = Exception::STATUS_NEW;
        $statusFixDeployed = Exception::STATUS_FIX_DEPLOYED;
        $statusRecurring = Exception::STATUS_RECURRING;

        return "
            INSERT INTO `fsw_sieved_errors` (
                `status`,`source`, `filename`, `line`, 
                `current_count`, `total_count`, 
                `first_message`, `first_stack`, `first_request`, `first_time`, 
                `last_message`, `last_stack`, `last_request`, `last_time`
                )
            VALUES ($statusNew, ?, ?, ?, 1, 1, ?, ?, ?, NOW(), ?, ?, ?, NOW())
            ON DUPLICATE KEY UPDATE 
            `status` = IF(`status`= $statusFixDeployed, $statusRecurring, `status`),
            `current_count` = `current_count` + 1, 
            `total_count` = `total_count` + 1, 
            `last_message` = VALUES(`last_message`), 
            `last_stack` = VALUES(`last_stack`), 
            `last_request` = VALUES(`last_request`),
            `last_time` = NOW();
        ";
    }

    private function insertToDb($source, $fileName, $lineNumber, $message, $trace, $request)
    {
        $this->getDbConnection()->prepare($this->getSQL())->execute([
            $source,
            $fileName,
            $lineNumber,
            $message,
            $trace,
            $request,
            $message,
            $trace,
            $request
        ]);
    }

    private function save(\Exception $exception)
    {
        $fileName = $exception->getFile();
        $lineNumber = $exception->getLine();

        //handle error catched by magento, TODO
        $magentoErrorHandlerPaths = [
            ['/vendor/magento/framework/App/ErrorHandler.php', 61],
            ['/vendor/magento/magento2ce/lib/internal/Magento/Framework/App/ErrorHandler.php', 61]
        ];
        foreach ($magentoErrorHandlerPaths as $magentoErrorHandler) {
            if (substr_compare($fileName, $magentoErrorHandler[0], -strlen($magentoErrorHandler[0])) === 0 && $lineNumber == $magentoErrorHandler[1]) {
                $trace = $exception->getTrace();
                $fileName = empty($trace[0]['file']) ? $fileName : $trace[0]['file'];
                $lineNumber = empty($trace[0]['line']) ? $lineNumber : $trace[0]['line'];
            }
        }

        $this->insertToDb(
            $this->getSourceName(),
            $fileName,
            $lineNumber,
            $exception->getMessage(),
            $exception->getTraceAsString(),
            json_encode($_SERVER, JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT)
        );

    }
}
