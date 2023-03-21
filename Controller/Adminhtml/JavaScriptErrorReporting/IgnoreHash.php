<?php

namespace Fredden\JavaScriptErrorReporting\Controller\Adminhtml\JavaScriptErrorReporting;

use Exception;
use Fredden\JavaScriptErrorReporting\Model\EventFactory;
use Fredden\JavaScriptErrorReporting\Scope\Config;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;

class IgnoreHash extends Action implements HttpPostActionInterface
{
    const ADMIN_RESOURCE = 'Fredden_JavaScriptErrorReporting::ignore';

    public function __construct(
        Context $context,
        private readonly Config $config,
        private readonly EventFactory $eventFactory,
    ) {
        parent::__construct($context);
    }

    public function execute()
    {
        $id = (int) $this->getRequest()->getParam('id');
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        if ($id) {
            try {
                $event = $this->eventFactory->create();
                $event->load($id);

                $this->config->addHashToIgnore($event->getHash());

                $this->messageManager->addSuccessMessage(__('The event has been hidden from the summary table.'));
            } catch (Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            }
        } else {
            $this->messageManager->addErrorMessage(__('Unable to find that event to hide.'));
        }

        return $resultRedirect->setPath('*/*/statistics');
    }
}
