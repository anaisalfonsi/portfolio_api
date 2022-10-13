<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class LogoutSubscriber implements EventSubscriberInterface
{
    private string $frontDomainName;

    public function __construct(string $frontDomainName)
    {
        $this->frontDomainName = $frontDomainName;
    }

    public static function getSubscribedEvents(): array
    {
        return [LogoutEvent::class => 'onLogout'];
    }

    public function onLogout(LogoutEvent $event): void
    {
        $response = new Response(null, 204, [
            'Access-Control-Allow-Origin' => $this->frontDomainName,
            'Access-Control-Allow-Methods' => 'GET',
            'Access-Control-Allow-Credentials' => 'true'
        ]);

        $event->setResponse($response);
    }
}