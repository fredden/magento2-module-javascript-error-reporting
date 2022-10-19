<?php
namespace Fredden\JavaScriptErrorReporting\Ui\DataProvider;

use Fredden\JavaScriptErrorReporting\Model\ResourceModel\Event\CollectionFactory;
use Fredden\JavaScriptErrorReporting\Scope\Config;
use Magento\Framework\DB\Select;

class SummaryDataProvider extends EventDataProvider
{
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        Config $config,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $config, $collectionFactory, $meta, $data);

        $this->collection->addExpressionFieldToSelect('count', 'COUNT(event_id)', '');
        $this->collection->addExpressionFieldToSelect('first', 'MIN(created_at)', '');
        $this->collection->addExpressionFieldToSelect('last', 'MAX(created_at)', '');

        $this->collection->getSelect()->group('hash');
        $this->collection->getSelect()->order('count ' . Select::SQL_DESC);
        $this->collection->getSelect()->limit(100);
    }
}
