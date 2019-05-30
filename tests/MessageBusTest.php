<?php
namespace MessageBus;
use PHPUnit\Framework\TestCase;

class MessageBusTest extends TestCase {

    public function testNullEvent() {
		$bus = new MessageBus();
		$this->assertEquals([], $bus->notify('TEST_EVENT'));
    }

	public function testSubscribe() {
		$bus = new MessageBus();

		$test = 'no';

		$bus->subscribe('TEST_EVENT', function(Event $event) use (&$test) {
			$test = $event->getParams()[0];
		});

		$bus->notify('TEST_EVENT', 'yes');
		$this->assertSame('yes', $test);

		$bus->notify('TEST_EVENT', 'new');
		$this->assertSame('new', $test);
	}

	public function testUnsubscribe() {
		$bus = new MessageBus();

		$test = 'no';

		$subscription = $bus->subscribe('TEST_EVENT', function(Event $event) use (&$test) {
			$test = $event->getParams()[0];
		});

		$bus->notify('TEST_EVENT', 'yes');
		$this->assertSame('yes', $test);

		$bus->unsubscribe($subscription);

		$bus->notify('TEST_EVENT', 'old');
		$this->assertSame('yes', $test);
	}

    public function testReturn() {
        $bus = new MessageBus();

        $bus->subscribe('TEST_EVENT', function() {
            return 'something';
        });

        $result = $bus->notify('TEST_EVENT');

        $this->assertEquals(['something'], $result);
    }

    public function testReturnMultiple() {
        $bus = new MessageBus();

        $bus->subscribe('TEST_EVENT', function() {
            return 'something';
        });

        $bus->subscribe('TEST_EVENT', function() {
            return 'something else';
        });

        $result = $bus->notify('TEST_EVENT');

        $this->assertEquals(['something', 'something else'], $result);
    }

}
