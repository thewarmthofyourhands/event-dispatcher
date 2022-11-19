<?php

declare(strict_types=1);

namespace Eva\EventDispatcher;

interface ListenerProviderInterface
{
    public function getListenersForEvent(object $event): iterable;
}
