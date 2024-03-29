<?php

namespace Fredden\JavaScriptErrorReporting\Controller\Adminhtml\JavaScriptErrorReporting;

use Fredden\JavaScriptErrorReporting\Scope\Config;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;

class ResetIgnore extends Action implements HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'Fredden_JavaScriptErrorReporting::ignore';

    public function __construct(
        Context $context,
        private readonly Config $config,
    ) {
        parent::__construct($context);
    }

    public function execute()
    {
        $this->config->resetHashIgnoreList();
        $this->messageManager->addSuccessMessage(__('The ignore list has been reset.'));

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        return $resultRedirect->setPath('*/*/statistics');
    }
}
