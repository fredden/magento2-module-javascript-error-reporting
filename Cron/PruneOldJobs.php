<?php

namespace Fredden\JavaScriptErrorReporting\Cron;

use DateInterval;
use DateTime;
use Fredden\JavaScriptErrorReporting\Model\ResourceModel\Event\CollectionFactory;
use Fredden\JavaScriptErrorReporting\Scope\Config;
use Magento\Cron\Model\Schedule;
use Magento\Cron\Model\ScheduleFactory;

class PruneOldJobs
{
    private const EVENTS_PER_RUN = 400;

    public function __construct(
        private readonly CollectionFactory $collectionFactory,
        private readonly Config $config,
        private readonly ScheduleFactory $scheduleFactory,
    ) {
    }

    public function execute(): void
    {
        $daysToKeep = $this->config->getDaysToKeep();
        if ($daysToKeep <= 0) {
            return;
        }

        $date = new DateTime();
        $date->sub(new DateInterval('P' . $daysToKeep . 'D'));

        $collection = $this->collectionFactory->create()
            ->setPageSize(self::EVENTS_PER_RUN)
            ->addFieldToFilter('created_at', [
                'lteq' => $date->format('Y-m-d H:i:s')
            ]);

        if ($collection->getSize() > self::EVENTS_PER_RUN) {
            // There are more events to delete than we'll process now; let's run
            // this job again. We don't want to loop over too many events to
            // avoid out-of-memory issues. We are scheduling this before the
            // delete loop below in case the latter runs out of memory anyway.
            $this->scheduleFactory->create()
                ->setJobCode('fredden_javascript_error_reporting_prune')
                ->setStatus(Schedule::STATUS_PENDING)
                ->setCreatedAt(date('Y-m-d H:i:s'))
                ->setScheduledAt(date('Y-m-d H:i:s'))
                ->save();
        }

        foreach ($collection as $event) {
            $event->delete();
        }
    }
}
