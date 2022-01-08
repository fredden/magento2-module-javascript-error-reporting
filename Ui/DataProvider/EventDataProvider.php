<?php
namespace Fredden\JavaScriptErrorReporting\Ui\DataProvider;

use Fredden\JavaScriptErrorReporting\Model\ResourceModel\Event\CollectionFactory;
use Fredden\JavaScriptErrorReporting\Scope\Config;
use Magento\Ui\DataProvider\AbstractDataProvider;

class EventDataProvider extends AbstractDataProvider
{
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        Config $config,
        CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();

        $ignoredHashes = $config->getIgnoredHashes();
        if ($ignoredHashes) {
            $this->collection->getSelect()->where('hash NOT IN (?)', $ignoredHashes);
        }
    }
}
