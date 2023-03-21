<?php

namespace Fredden\JavaScriptErrorReporting\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Event extends AbstractDb
{
    public function _construct()
    {
        $this->_init('fredden_javascript_error_report', 'event_id');
    }
}
