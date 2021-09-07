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
    const EVENTS_PER_RUN = 40;

    protected $collectionFactory;
    protected $config;
    protected $scheduleFactory;

    public function __construct(
        CollectionFactory $collectionFactory,
        Config $config,
        ScheduleFactory $scheduleFactory
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->config = $config;
        $this->scheduleFactory = $scheduleFactory;
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
                ->setCreatedAt(strftime('%Y-%m-%d %H:%M:%S'))
                ->setScheduledAt(strftime('%Y-%m-%d %H:%M:%S'))
                ->save();
        }

        foreach ($collection as $event) {
            $event->delete();
        }
    }
}
