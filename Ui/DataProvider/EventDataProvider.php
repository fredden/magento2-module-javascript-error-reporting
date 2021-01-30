<?php
namespace Fredden\JavaScriptErrorReporting\Ui\DataProvider;

use Fredden\JavaScriptErrorReporting\Model\ResourceModel\Event\CollectionFactory;
use Magento\Ui\DataProvider\AbstractDataProvider;

class EventDataProvider extends AbstractDataProvider
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
    }

    public function getData()
    {
        $data = parent::getData();
        if (isset($data['items']) && is_array($data['items'])) {
            foreach ($data['items'] as &$item) {
                $item['id_field_name'] = 'event_id';
            }
        }
        return $data;
    }
}
