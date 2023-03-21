<?php

namespace Fredden\JavaScriptErrorReporting\Controller\Adminhtml\JavaScriptErrorReporting;

use Exception;
use Fredden\JavaScriptErrorReporting\Model\EventFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;

class Delete extends Action implements HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'Fredden_JavaScriptErrorReporting::delete';

    public function __construct(
        Context $context,
        private readonly EventFactory $eventFactory
    ) {
        parent::__construct($context);
    }

    public function execute()
    {
        $id = (int) $this->getRequest()->getParam('id');
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        if ($id) {
            $title = "";
            try {
                $event = $this->eventFactory->create();
                $event->load($id);
                $event->delete();

                $this->messageManager->addSuccessMessage(__('The event has been deleted.'));
            } catch (Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('*/*/detail', ['event_id' => $id]);
            }
        } else {
            $this->messageManager->addErrorMessage(__('Unable to find that event to delete.'));
        }

        return $resultRedirect->setPath('*/*/grid');
    }
}
