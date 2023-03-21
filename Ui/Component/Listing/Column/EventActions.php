<?php

namespace Fredden\JavaScriptErrorReporting\Ui\Component\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column;

class EventActions extends Column
{
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $item[$this->getData('name')] = [
                    'view' => [
                        'label' => __('View details'),
                        'href' => $this->context->getUrl(
                            'fredden/JavaScriptErrorReporting/detail',
                            ['id' => $item[$this->getConfig()['indexField']]]
                        ),
                    ],
                ];
            }
        }

        return $dataSource;
    }
}
