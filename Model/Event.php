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
        $fileWithoutVersion = preg_replace(
            '_/static/version\d+/_',
            '/static/',
            $this->getErrorFile()
        );

        $this->setHash(md5(implode('|', [
            $this->getErrorMessage(),
            $fileWithoutVersion,
            $this->getLine(),
            $this->getColumn(),
        ])));

        return parent::beforeSave();
    }
}
