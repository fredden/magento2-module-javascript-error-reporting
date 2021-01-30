<?php
namespace Fredden\JavaScriptErrorReporting\Block\Adminhtml\Edit;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Escaper;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class DeleteAllButton implements ButtonProviderInterface
{
    protected $escaper;
    protected $request;
    protected $urlBuilder;

    public function __construct(
        Escaper $escaper,
        RequestInterface $request,
        UrlInterface $urlBuilder
    ) {
        $this->escaper = $escaper;
        $this->request = $request;
        $this->urlBuilder = $urlBuilder;
    }

    public function getButtonData()
    {
        $eventId = (int) $this->request->getParam('id');

        if (!$eventId) {
            return [];
        }

        $confirmationMessage = 'Are you sure you want to delete ALL events like this?';
        $escapedMessage = $this->escaper->escapeJs(
            $this->escaper->escapeHtml(
                __($confirmationMessage)
            )
        );

        $url = $this->urlBuilder->getUrl('*/*/deleteAll');

        return [
            'label' => __('Delete ALL'),
            'class' => 'delete',
            'on_click' => "deleteConfirm('{$escapedMessage}', '{$url}', {data:{id:{$eventId}}})",
            'sort_order' => 15,
        ];
    }
}
