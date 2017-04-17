<?php
namespace MessageBus;
use PHPUnit\Framework\TestCase;

class MessageBusTest extends TestCase {

	public function testSubscribe() {
		$bus = new MessageBus();

		$test = 'no';

		$bus->subscribe('TEST_EVENT', function($param) use (&$test) {
			$test = $param;
		});

		$bus->notify('TEST_EVENT', 'yes');
		$this->assertSame('yes', $test);

		$bus->notify('TEST_EVENT', 'new');
		$this->assertSame('new', $test);
	}

	public function testUnsubscribe() {
		$bus = new MessageBus();

		$test = 'no';

		$subscription = $bus->subscribe('TEST_EVENT', function($param) use (&$test) {
			$test = $param;
		});

		$bus->notify('TEST_EVENT', 'yes');
		$this->assertSame('yes', $test);

		$bus->unsubscribe($subscription);

		$bus->notify('TEST_EVENT', 'old');
		$this->assertSame('yes', $test);
	}

}
