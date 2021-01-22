<?php
namespace Fredden\JavaScriptErrorReporting\Block\Adminhtml\Edit;

use Magento\Backend\Ui\Component\Control\DeleteButton as MagentoDeleteButton;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Escaper;
use Magento\Framework\UrlInterface;

class DeleteButton extends MagentoDeleteButton
{
    public function __construct(
        RequestInterface $request,
        UrlInterface $urlBuilder,
        Escaper $escaper
    ) {
        parent::__construct(
            $request,
            $urlBuilder,
            $escaper,
            $confirmationMessage = 'Are you sure you want to delete this event?',
            $idFieldName = 'id',
            $deleteRoutePath = '*/*/delete',
            $sortOrder = 20
        );
    }
}
