<?php
namespace Fredden\JavaScriptErrorReporting\Api;

use Fredden\JavaScriptErrorReporting\Model\EventFactory;
use Magento\Framework\Webapi\Rest\Request;

class Receiver
{
    protected $eventFactory;
    protected $request;

    public function __construct(
        EventFactory $eventFactory,
        Request $request
    ) {
        $this->eventFactory = $eventFactory;
        $this->request = $request;
    }

    /**
     * Docblock required by Magento webapi implementaion
     *
     * @return void
     */
    public function saveErrorInformation(): void
    {
        $parameters = $this->request->getBodyParams();
        if (!isset($parameters['browser']) || !isset($parameters['event'])) {
            return;
        }

        $event = $this->eventFactory->create();

        $event->setUserAgent($this->request->getHeader('user-agent', null));
        $event->setReferrer($this->request->getHeader('referer', null));

        $event->setBrowserHeight($parameters['browser']['height'] ?? 0);
        $event->setBrowserWidth($parameters['browser']['width'] ?? 0);
        $event->setUrl($parameters['browser']['url'] ?? null);

        $event->setErrorMessage($parameters['event']['message'] ?? '');
        $event->setStackTrace($parameters['event']['stack'] ?? '');
        $event->setErrorFile($parameters['event']['filename'] ?? '');
        $event->setLine($parameters['event']['lineno'] ?? 0);
        $event->setColumn($parameters['event']['colno'] ?? 0);

        $event->save();
    }
}
