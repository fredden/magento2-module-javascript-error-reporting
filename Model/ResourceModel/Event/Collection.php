<?php
namespace Fredden\JavaScriptErrorReporting\Model\ResourceModel\Event;

use Fredden\JavaScriptErrorReporting\Model\Event as Model;
use Fredden\JavaScriptErrorReporting\Model\ResourceModel\Event as ResourceModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    public function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
        $this->_setIdFieldName('event_id');
    }
}
