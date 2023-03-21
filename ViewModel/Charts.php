<?php

namespace Fredden\JavaScriptErrorReporting\ViewModel;

use Fredden\JavaScriptErrorReporting\Model\ResourceModel\Event\CollectionFactory;
use Magento\Framework\DB\Select;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class Charts implements ArgumentInterface
{
    public function __construct(
        private readonly CollectionFactory $collectionFactory,
        private readonly Json $json,
    ) {
    }

    public function getHourlyData(): string
    {
        $collection = $this->collectionFactory->create();
        $collection->getSelect()->reset(Select::COLUMNS);
        $collection->addExpressionFieldToSelect('count', 'COUNT(event_id)', '');
        $collection->addExpressionFieldToSelect('hour', 'HOUR(created_at)', '');
        $collection->getSelect()->group('hour');

        $data = [];
        foreach ($collection as $item) {
            $data[$item->getHour()] = $item->getCount();
        }

        $labels = [];
        $values = [];
        for ($i = 0; $i < 24; $i++) {
            $labels[] = sprintf('%02d', $i);
            $values[] = $data[$i] ?? 0;
        }

        return $this->json->serialize([
            'labels' => $labels,
            'values' => $values,
        ]);
    }

    public function getDailyData(): string
    {
        $collection = $this->collectionFactory->create();
        $collection->getSelect()->reset(Select::COLUMNS);
        $collection->addExpressionFieldToSelect('count', 'COUNT(event_id)', '');
        $collection->addExpressionFieldToSelect('day', 'DAYOFWEEK(created_at)', '');
        $collection->getSelect()->group('day');

        $data = [];
        foreach ($collection as $item) {
            $data[$item->getDay()] = $item->getCount();
        }

        // There has to be a better way of getting this list
        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        $labels = [];
        $values = [];
        foreach ($days as $i => $day) {
            $labels[] = __($day);
            $values[] = $data[$i + 1] ?? 0;
        }

        return $this->json->serialize([
            'labels' => $labels,
            'values' => $values,
        ]);
    }

    public function getWeeklyData(): string
    {
        $collection = $this->collectionFactory->create();
        $collection->getSelect()->reset(Select::COLUMNS);
        $collection->addExpressionFieldToSelect('count', 'COUNT(event_id)', '');
        $collection->addExpressionFieldToSelect('week', 'WEEKOFYEAR(created_at)', '');
        $collection->getSelect()->group('week');

        $data = [];
        $start = PHP_INT_MAX;
        $end = 0;
        foreach ($collection as $item) {
            $data[$item->getWeek()] = $item->getCount();
            $start = min($start, $item->getWeek());
            $end = max($end, $item->getWeek());
        }

        $labels = [];
        $values = [];
        for ($i = $start; $i <= $end; $i++) {
            $labels[] = $i;
            $values[] = $data[$i] ?? 0;
        }

        return $this->json->serialize([
            'labels' => $labels,
            'values' => $values,
        ]);
    }
}
