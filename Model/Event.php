<?php
namespace Fredden\JavaScriptErrorReporting\Model;

use Magento\Framework\Model\AbstractModel;

class Event extends AbstractModel
{
    public function _construct()
    {
        $this->_init(ResourceModel\Event::class);
    }

    public function beforeSave()
    {
        $this->setHash(md5(implode('|', [
            $this->getErrorMessage(),
            $this->getErrorFile(),
            $this->getLine(),
            $this->getColumn(),
        ])));
        return parent::beforeSave();
    }
}
