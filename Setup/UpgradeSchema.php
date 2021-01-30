<?php
namespace Fredden\JavaScriptErrorReporting\Setup;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $installer = $setup;
        $installer->startSetup();

        if (version_compare($context->getVersion(), '1.0.0', '<')) {
            $tableName = $installer->getTable('fredden_javascript_error_report');

            $table = $installer->getConnection()->newTable($tableName);

            $table->addColumn('event_id', Table::TYPE_INTEGER, null, [
                'identity' => true,
                'nullable' => false,
                'primary'  => true,
                'unsigned' => true,
            ]);

            $table->addColumn('created_at', Table::TYPE_TIMESTAMP, null, [
                'nullable' => false,
                'default' => Table::TIMESTAMP_INIT,
            ]);

            $table->addColumn('user_agent', Table::TYPE_TEXT, 1024, [
                'nullable' => false,
                'default' => '',
            ]);

            $table->addColumn('referrer', Table::TYPE_TEXT, 1024, [
                'nullable' => false,
                'default' => '',
            ]);

            $table->addColumn('url', Table::TYPE_TEXT, 1024, [
                'nullable' => false,
                'default' => '',
            ]);

            $table->addColumn('browser_width', Table::TYPE_INTEGER, null, [
                'unsigned' => true,
                'nullable' => false,
                'default' => 0,
            ]);

            $table->addColumn('browser_height', Table::TYPE_INTEGER, null, [
                'unsigned' => true,
                'nullable' => false,
                'default' => 0,
            ]);

            $table->addColumn('error_message', Table::TYPE_TEXT, 1024, [
                'nullable' => false,
                'default' => '',
            ]);

            $table->addColumn('stack_trace', Table::TYPE_TEXT, null, [
                'nullable' => true,
            ]);

            $table->addColumn('error_file', Table::TYPE_TEXT, 1024, [
                'nullable' => false,
                'default' => '',
            ]);

            $table->addColumn('line', Table::TYPE_INTEGER, null, [
                'unsigned' => true,
                'nullable' => false,
                'default' => 0,
            ]);

            $table->addColumn('column', Table::TYPE_INTEGER, null, [
                'unsigned' => true,
                'nullable' => false,
                'default' => 0,
            ]);

            $table->addColumn('hash', Table::TYPE_TEXT, 32, [
                'nullable' => false,
                'default' => '',
            ]);

            $installer->getConnection()->createTable($table);

            $installer->getConnection()->addIndex(
                $tableName,
                'FREDDEN_JAVASCRIPT_ERROR_REPORT_CREATED_AT',
                ['created_at'],
                AdapterInterface::INDEX_TYPE_INDEX
            );

            $installer->getConnection()->addIndex(
                $tableName,
                'FREDDEN_JAVASCRIPT_ERROR_REPORT_HASH',
                ['hash'],
                AdapterInterface::INDEX_TYPE_INDEX
            );
        }

        $installer->endSetup();
    }
}
