<?php
namespace Fredden\JavaScriptErrorReporting\Model;

use Magento\Framework\Model\AbstractModel;

class Event extends AbstractModel
{
    public function _construct()
    {
        $this->_init(ResourceModel\Event::class);
    }
}
