<?php
namespace MessageBus;

class MessageBus {

	protected $listners = [];

    public function subscribe(string $eventName, callable $handler) {
        if (!isset($this->listners[$eventName])) {
            $this->listners[$eventName] = [];
        }

        $handlerId = count($this->listners[$eventName]);
        $this->listners[$eventName][] = $handler;
        return [$eventName, $handlerId];
    }

    public function fire(Event $event): array {
        return array_reduce(
            $this->listners[$event->getName()] ?? [],
            function(array $result, callable $handler) use ($event) : array {
                if (!$event->isStopped()) {
                    $result[] = $handler($event);
                }

                return $result;
            },
            []
        );

    }

    public function notify(string $eventName, ...$params) {
        $event = new BusEvent($eventName, $params);
        return $this->fire($event);
    }

    public function unsubscribe(array $subscription) {
        unset($this->listners[$subscription[0]][$subscription[1]]);
    }
}
