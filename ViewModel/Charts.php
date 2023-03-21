<?php

namespace Fredden\JavaScriptErrorReporting\ViewModel;

use Fredden\JavaScriptErrorReporting\Model\ResourceModel\Event\CollectionFactory;
use Fredden\JavaScriptErrorReporting\Scope\Config;
use Magento\Framework\DB\Select;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class Charts implements ArgumentInterface
{
    private ?array $data;
    private ?string $hash;

    public function __construct(
        private readonly CollectionFactory $collectionFactory,
        private readonly Json $json,
        private readonly Config $config,
        private readonly TimezoneInterface $timezone,
    ) {
    }

    public function setHash(?string $value): void
    {
        $this->hash = $value;
        $this->data = null;
    }

    public function getData(): array
    {
        if (isset($this->data)) {
            return $this->data;
        }

        // We are using the Select object directly here because using a collection is unreasonably slow
        $select = $this->collectionFactory->create()->getSelect();
        $select->reset(Select::COLUMNS);
        $select->columns(['timestamp' => 'UNIX_TIMESTAMP(created_at)']);
        $select->order('created_at DESC');

        if (isset($this->hash)) {
            $select->where('hash = ?', $this->hash);
        } elseif ($ignoreHashes = $this->config->getIgnoredHashes()) {
            $select->where('hash NOT IN (?)', $ignoreHashes);
        }

        $dbResult = $select->getConnection()->fetchAll($select, [], \Zend_Db::FETCH_COLUMN);

        $this->data = [];
        foreach ($dbResult as $item) {
            $this->data[] = (int) $item;
        }

        return $this->data;
    }

    public function getHourlyData(): string
    {
        return $this->getJsonData(
            3600,
            $this->config->getChartDisplayHowManyHours(),
            'jS M H:i'
        );
    }

    public function getDailyData(): string
    {
        return $this->getJsonData(
            86400,
            $this->config->getChartDisplayHowManyDays(),
            'D jS M'
        );
    }

    public function getWeeklyData(): string
    {
        return $this->getJsonData(
            604800,
            $this->config->getChartDisplayHowManyWeeks(),
            'Y-M-d'
        );
    }

    private function getJsonData(int $period, int $dataPointCount, string $dateFormat): string
    {
        $threshold = time() - ($period * $dataPointCount);

        $dataPoints = [];
        for ($i = time(); $i > $threshold; $i -= $period) {
            $key = floor($i / $period);
            $dataPoints[$key] = [
                'start' => $i - ($period + 1),
                'end' => $i,
                'count' => 0,
            ];
        }
        ksort($dataPoints);

        foreach ($this->getData() as $event) {
            $key = floor($event / $period);
            if (!isset($dataPoints[$key])) {
                break;
            }
            $dataPoints[$key]['count']++;
        }

        $labels = [];
        $values = [];
        foreach ($dataPoints as $point) {
            $start = $this->timezone->date($point['start'])->format($dateFormat);
            $end = $this->timezone->date($point['end'])->format($dateFormat);

            $labels[] = "$start - $end";
            $values[] = $point['count'];
        }

        return $this->json->serialize([
            'labels' => $labels,
            'values' => $values,
        ]);
    }
}
