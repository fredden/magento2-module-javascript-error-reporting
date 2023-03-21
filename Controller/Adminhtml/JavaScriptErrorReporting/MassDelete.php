<?php

namespace Fredden\JavaScriptErrorReporting\Controller\Adminhtml\JavaScriptErrorReporting;

use Fredden\JavaScriptErrorReporting\Model\ResourceModel\Event\CollectionFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Ui\Component\MassAction\Filter;

class MassDelete extends Action implements HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'Fredden_JavaScriptErrorReporting::delete';

    public function __construct(
        Context $context,
        private readonly CollectionFactory $collectionFactory,
        private readonly Filter $filter,
    ) {
        parent::__construct($context);
    }

    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $collectionSize = $collection->getSize();

        foreach ($collection as $event) {
            $event->delete();
        }

        $this->messageManager->addSuccessMessage(__('Successfully deleted %1 event(s).', $collectionSize));

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/grid');
    }
}
