<?php

namespace Fredden\JavaScriptErrorReporting\Controller\Adminhtml\JavaScriptErrorReporting;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

class Statistics extends Action
{
    const ADMIN_RESOURCE = 'Fredden_JavaScriptErrorReporting::view';

    public function __construct(
        private readonly PageFactory $resultPageFactory,
        Context $context
    ) {
        parent::__construct($context);
    }

    public function execute(): Page
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Fredden_JavaScriptErrorReporting::view');
        $resultPage->getConfig()->getTitle()->prepend(__('JavaScript Error Reporting'));
        return $resultPage;
    }
}
