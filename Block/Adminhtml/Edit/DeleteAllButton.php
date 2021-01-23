<?php
namespace Fredden\JavaScriptErrorReporting\Block\Adminhtml\Edit;

use Magento\Backend\Ui\Component\Control\DeleteButton as MagentoDeleteButton;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Escaper;
use Magento\Framework\UrlInterface;

class DeleteAllButton extends MagentoDeleteButton
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
            $confirmationMessage = 'Are you sure you want to delete ALL events like this?',
            $idFieldName = 'id',
            $deleteRoutePath = '*/*/deleteAll',
            $sortOrder = 15
        );
    }

    public function getButtonData()
    {
        $data = parent::getButtonData();
        $data['label'] = __('Delete ALL');
        return $data;
    }
}
