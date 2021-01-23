<?php
namespace Fredden\JavaScriptErrorReporting\Ui\DataProvider;

use Fredden\JavaScriptErrorReporting\Model\ResourceModel\Event\CollectionFactory;
use Magento\Framework\DB\Select;
use Magento\Ui\DataProvider\AbstractDataProvider;

class SummaryDataProvider extends AbstractDataProvider
{
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();

        $this->collection->addExpressionFieldToSelect('count', 'COUNT(event_id)', '');
        $this->collection->addExpressionFieldToSelect('first', 'MIN(created_at)', '');
        $this->collection->addExpressionFieldToSelect('last', 'MAX(created_at)', '');
        $this->collection->getSelect()->group('hash');
        $this->collection->getSelect()->order('count ' . Select::SQL_DESC);
        $this->collection->getSelect()->limit(100);
    }
}
