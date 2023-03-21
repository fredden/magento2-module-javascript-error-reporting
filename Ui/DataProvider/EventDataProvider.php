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

    public function getData()
    {
        $data = parent::getData();

        if (!empty($data['items'])) {
            foreach ($data['items'] as &$item) {
                foreach (['error_file', 'url'] as $key) {
                    $item[$key] = str_replace(
                        ';',
                        ';<wbr>',
                        htmlentities(
                            $item[$key],
                            ENT_QUOTES | ENT_HTML5
                        )
                    );
                }
            }

            unset($item); // avoid side effects from $item being a reference
        }

        return $data;
    }
}
