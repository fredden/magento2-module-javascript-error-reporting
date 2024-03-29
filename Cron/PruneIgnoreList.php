<?php

namespace Fredden\JavaScriptErrorReporting\Cron;

use Fredden\JavaScriptErrorReporting\Model\ResourceModel\Event\CollectionFactory;
use Fredden\JavaScriptErrorReporting\Scope\Config;
use Magento\Cron\Model\ScheduleFactory;

class PruneIgnoreList
{
    public function __construct(
        private readonly CollectionFactory $collectionFactory,
        private readonly Config $config,
    ) {
    }

    public function execute(): void
    {
        $ignoredHashes = $this->config->getIgnoredHashes();

        if (!count($ignoredHashes)) {
            return;
        }

        $collection = $this->collectionFactory->create();
        $collection->getSelect()->where('hash IN (?)', $ignoredHashes);
        $collection->getSelect()->group('hash');

        $newHashes = [];
        foreach ($collection as $event) {
            $newHashes[] = $event->getHash();
        }

        $this->config->replaceIgnoreList(implode(',', $newHashes));
    }
}
