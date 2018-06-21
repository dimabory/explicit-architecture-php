<?php

declare(strict_types=1);

/*
 * This file is part of the Explicit Architecture POC,
 * which is created on top of the Symfony Demo application.
 *
 * (c) Herberto Graça <herberto.graca@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Acme\App\Infrastructure\EventDispatcher;

use Acme\App\Core\Port\EventDispatcher\BufferedEventDispatcherInterface;
use Acme\App\Core\Port\EventDispatcher\EventInterface;
use Acme\PhpExtension\ObjectDispatcher\AbstractDispatcher;

/**
 * Conceptually, the code triggering an event must not rely on the logic that the event triggers. So that logic doesn't
 * need to, and should not, be executed in-line with the code triggering it.
 * This means that, technically, all events could be asynchronous, meaning that the logic they trigger could be
 * executed after the HTTP request has been handled and the HTTP response was sent back to the client. Ideally in
 * another process, started by a message bus/queue.
 * However, for UX reasons, sometimes we want to handle an event in the same request where it is triggered, so that we
 * make sure the user can see the expected results in the next request he makes.
 *
 * Thus, we have the need for two types of event dispatchers (which can be behind a proxy dispatcher):
 *      - A SyncEventDispatcher which dispatcher the events in the same request
 *      - An AsyncEventDispatcher, which sends the events to a message bus so they will be executed later, in parallel
 *
 * Nevertheless, despite the SyncEventDispatcher running the event listeners in the same HTTP request as the code that
 * triggered the events, it does not mean it must run those listeners in-line with the code that triggered the events.
 * Thus, this SyncEventDispatcher will put the events in a buffer, and it will flush them all to the listeners after
 * the system handles the HTTP request, and just before sending the HTTP response back to the client.
 */
final class SyncEventDispatcher extends AbstractDispatcher implements BufferedEventDispatcherInterface
{
    /**
     * @var [EventInterface,[]][]
     */
    private $eventBuffer = [];

    public function dispatch(EventInterface $event, array $metadata = []): void
    {
        $this->eventBuffer[] = [$event, $metadata];
    }

    public function flush(): void
    {
        foreach ($this->eventBuffer as [$event, $metadata]) {
            foreach ($this->getDestinationListForObject(\get_class($event)) as $listener) {
                $listener($event, $metadata);
            }
        }
    }

    public function reset(): void
    {
        $this->eventBuffer = [];
    }
}
