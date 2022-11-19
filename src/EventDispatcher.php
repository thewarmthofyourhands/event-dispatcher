<?php

declare(strict_types=1);

namespace Eva\EventDispatcher;

class EventDispatcher implements EventDispatcherInterface, ListenerProviderInterface
{
    protected array $listeners = [];
    protected array $events = [];

    public function dispatch(object $event): object
    {
        foreach ($this->getListenersForEvent($event) as $priority => $listeners) {
            foreach ($listeners as $listener) {
                $listenerObject = $this->listeners[$listener['class']];
                $listenerMethod = $listener['method'];
                if (method_exists($listenerObject, $listenerMethod)) {
                    $listenerObject->{$listenerMethod}($event);
                } else {
                    $listenerObject($event);
                }
            }
        }

        return $event;
    }

    public function addListener(object $listener, string $event, null|string $method = null, int $priority = 1): void
    {
        $listenerMethod = $method ?? 'on' . $event;
        $this->events[$event][$priority][] = ['class' => $listener::class, 'method' => $listenerMethod];
        $this->listeners[$listener::class] = $listener;
    }

    public function getListenersForEvent(object $event): iterable
    {
        $listeners = $this->events[$event::class] ?? [];
        ksort($listeners, SORT_NUMERIC);

        return $listeners;
    }
}
