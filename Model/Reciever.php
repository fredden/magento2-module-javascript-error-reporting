<?php

namespace Fredden\JavaScriptErrorReporting\Model;

use Fredden\JavaScriptErrorReporting\Api\ReceiverInterface;
use Fredden\JavaScriptErrorReporting\Model\EventFactory;
use Magento\Framework\Webapi\Rest\Request;

class Reciever implements ReceiverInterface
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
     * @inheritDoc
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
        $event->setTimer($parameters['event']['timer'] ?? 0);

        $event->save();
    }
}
