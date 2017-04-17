<?php
namespace MessageBus;

class MessageBus {

	protected $listners = [];

	public function subscribe(string $event, callable $handler) {
		if (!isset($this->listners[$event])) {
			$this->listners[$event] = [];
		}

		$handlerId = count($this->listners[$event]);
		$this->listners[$event][] = $handler;
		return [$event, $handlerId];
	}

	public function notify(string $event, ...$params) {
		if (!isset($this->listners[$event])) {
			return;
		}

		foreach ($this->listners[$event] as $handler) {
			$handler(...$params);
		}
	}

	public function unsubscribe(array $subscription) {
		unset($this->listners[$subscription[0]][$subscription[1]]);
	}
}
