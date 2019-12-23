<?php

namespace Fsw\ErrorSieve\Setup;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @throws \Zend_Db_Exception
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $tableName = $setup->getTable('fsw_sieved_errors');

        $table = $setup->getConnection()->newTable($tableName);

        $table->addColumn('id', Table::TYPE_INTEGER, null, [
                'identity' => true, 'nullable' => false, 'primary'  => true, 'unsigned' => true,
            ])
            ->addColumn('source', Table::TYPE_TEXT, 255, ['nullable' => false])
            ->addColumn('filename', Table::TYPE_TEXT, 255, ['nullable' => false])
            ->addColumn('line', Table::TYPE_INTEGER, 10, ['nullable' => false])

            ->addColumn('status', Table::TYPE_INTEGER, null, ['nullable' => false])
            ->addColumn('current_count', Table::TYPE_INTEGER, null, ['nullable' => false])
            ->addColumn('total_count', Table::TYPE_INTEGER, null, ['nullable' => false])

            ->addColumn('first_time', Table::TYPE_TIMESTAMP, null, ['nullable' => true])
            ->addColumn('first_message', Table::TYPE_TEXT, null, ['nullable' => true])
            ->addColumn('first_stack', Table::TYPE_TEXT, null, ['nullable' => true])
            ->addColumn('first_request', Table::TYPE_TEXT, null, ['nullable' => true])

            ->addColumn('last_time', Table::TYPE_TIMESTAMP, null, ['nullable' => true])
            ->addColumn('last_message', Table::TYPE_TEXT, null, ['nullable' => true])
            ->addColumn('last_stack', Table::TYPE_TEXT, null, ['nullable' => true])
            ->addColumn('last_request', Table::TYPE_TEXT, null, ['nullable' => true])

            ->addColumn('developer_comment', Table::TYPE_TEXT, null, ['nullable' => true]);

        $setup->getConnection()->createTable($table);

        $setup->getConnection()->addIndex(
            $tableName,
            $setup->getIdxName(
                $tableName,
                ['source', 'filename', 'line'],
                AdapterInterface::INDEX_TYPE_UNIQUE
            ),
            ['source', 'filename', 'line'],
            AdapterInterface::INDEX_TYPE_UNIQUE
        );
        $setup->getConnection()->addIndex(
            $tableName,
            $setup->getIdxName(
                $tableName,
                ['status', 'current_count'],
                AdapterInterface::INDEX_TYPE_INDEX
            ),
            ['status', 'current_count'],
            AdapterInterface::INDEX_TYPE_INDEX
        );

        $setup->endSetup();
    }

}