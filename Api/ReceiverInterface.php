<?php
namespace Fredden\JavaScriptErrorReporting\Api;

interface ReceiverInterface
{
    /**
     * Docblock required by Magento webapi implementaion
     *
     * @return void
     */
    public function saveErrorInformation(): void;
}
