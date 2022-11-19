<?php

declare(strict_types=1);

namespace Eva\EventDispatcher;

use Psr\Container\ContainerInterface;

class EventDispatcherProvider
{
    public function __construct(EventDispatcherInterface $eventDispatcher, ContainerInterface $container)
    {
        $eventDispatcherConfig = $container->getParameter('package.event_listeners') ?? [];

        foreach ($eventDispatcherConfig as $event => $listenerList) {
            if (is_string($listenerList)) {
                $eventDispatcher->addListener(
                    $container->get($listenerList),
                    $event,
                );

                continue;
            }

            foreach ($listenerList as $listenerClass => $listenerConfig) {
                $eventDispatcher->addListener(
                    $container->get($listenerClass),
                    $event,
                    $listenerConfig['method'] ?? null,
                    $listenerConfig['priority'] ?? 1,
                );
            }
        }
    }
}
