<?php
namespace Fsw\ErrorSieve\Model;

use Fsw\ErrorSieve\Model\Resource\Exception as ResourceException;
use Magento\Framework\App\ResourceConnection;

class Status
{
    /** @var ResourceConnection */
    protected $resourceConnection;

    /**
     * Status constructor.
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(ResourceConnection $resourceConnection)
    {
        $this->resourceConnection = $resourceConnection;
    }

    public function getStatus()
    {
        return [
            'exceptions' => $this->getUnhandledExceptionsCount(),
            'status' => $this->checkIfErrorsAreSaved()
        ];
    }

    /**
     * @return int
     */
    private function getUnhandledExceptionsCount()
    {
        $unhandledStatuses = [
            Exception::STATUS_NEW,
            Exception::STATUS_RECURRING
        ];
        $connection = $this->resourceConnection->getConnection();
        $tableName = $this->resourceConnection->getTableName(ResourceException::TABLE_NAME);

        $sql = "SELECT SUM(current_count) FROM $tableName WHERE status IN (" . implode(',', $unhandledStatuses) . ")";
        return $connection->fetchOne($sql);
    }

    private function checkIfErrorsAreSaved()
    {
        $e = new \Exception('INTEGRITY_TEST_EXCEPTION', 123);
        $line = __LINE__ - 1;
        $filename = __FILE__;

        $connection = $this->resourceConnection->getConnection();
        $tableName = $this->resourceConnection->getTableName(ResourceException::TABLE_NAME);

        $sql = "SELECT SUM(current_count) FROM $tableName WHERE filename = '$filename' AND line = $line";

        ExceptionSieve::saveException($e);

        return $connection->fetchOne($sql);
    }
}
