<?php

namespace MediaWiki\Tests\DomainEvent;

use MediaWiki\DomainEvent\DomainEventIngress;
use MediaWiki\DomainEvent\DomainEventSource;
use MediaWiki\DomainEvent\EventDispatchEngine;
use MediaWikiUnitTestCase;
use Wikimedia\ObjectFactory\ObjectFactory;
use Wikimedia\Services\ServiceContainer;

/**
 * @covers \MediaWiki\DomainEvent\DomainEventIngress
 */
class DomainEventIngressTest extends MediaWikiUnitTestCase {

	private function newSpyEventSource( &$trace ): DomainEventSource {
		$objectFactory = new ObjectFactory(
			$this->createNoOpMock( ServiceContainer::class )
		);

		$dispatcher = $this->getMockBuilder( EventDispatchEngine::class )
			->setConstructorArgs( [ $objectFactory ] )
			->onlyMethods( [ 'registerListener' ] )
			->getMock();

		$dispatcher->method( 'registerListener' )
			->willReturnCallback( static function ( ...$args ) use ( &$trace ) {
				$trace[] = $args;
			} );

		return $dispatcher;
	}

	public function testAutoSubscribe_constructor() {
		$trace = [];
		$source = $this->newSpyEventSource( $trace );

		// Pass the list of events as a constructor parameter
		$subscriber = new class extends DomainEventIngress {

			public function handleFooEvent() {
				// no-op
			}

			// Check that the AfterCommit suffix is allowed, for backwards compat
			public function handleBarEventAfterCommit() {
				// no-op
			}

			public function handleXyzzyEvent() {
				// no-op
			}
		};

		$subscriber->initSubscriber( [ 'events' => [ 'Foo', 'Bar' ] ] );
		$subscriber->registerListeners( $source );

		$this->assertSame(
			[
				[ 'Foo', [ $subscriber, 'handleFooEvent', ], [], ],
				[ 'Bar', [ $subscriber, 'handleBarEventAfterCommit', ], [], ],
			],
			$trace
		);
	}

	public function testAutoSubscribe_init() {
		$trace = [];
		$source = $this->newSpyEventSource( $trace );

		$events = [ 'Foo', 'Bar' ];

		// Pass nothing to the constructor, rely on initSubscriber()
		$subscriber = new class () extends DomainEventIngress {
			public function handleFooEvent() {
				// no-op
			}

			// Check that the AfterCommit suffix is allowed, for backwards compat
			public function handleBarEventAfterCommit() {
				// no-op
			}

			public function handleXyzzyEvent() {
				// no-op
			}
		};

		$subscriber->initSubscriber( [ 'events' => $events ] );
		$subscriber->registerListeners( $source );

		$this->assertSame(
			[
				[ 'Foo', [ $subscriber, 'handleFooEvent', ], [], ],
				[ 'Bar', [ $subscriber, 'handleBarEventAfterCommit', ], [], ],
			],
			$trace
		);
	}
}
