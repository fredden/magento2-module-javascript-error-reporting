<?php

namespace Fredden\JavaScriptErrorReporting\Block\Adminhtml\Edit;

use Magento\Backend\Ui\Component\Control\DeleteButton as MagentoDeleteButton;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Escaper;
use Magento\Framework\UrlInterface;

class IgnoreButton extends MagentoDeleteButton
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
            $confirmationMessage = 'Are you sure you want to hide this error?',
            $idFieldName = 'id',
            $deleteRoutePath = '*/*/ignoreHash',
            $sortOrder = 25
        );
    }

    public function getButtonData()
    {
        $data = parent::getButtonData();
        $data['label'] = __('Ignore');
        return $data;
    }
}
