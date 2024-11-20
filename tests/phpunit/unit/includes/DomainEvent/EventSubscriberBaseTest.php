<?php

namespace MediaWiki\Tests\DomainEvent;

use MediaWiki\DomainEvent\DomainEventSource;
use MediaWiki\DomainEvent\EventDispatchEngine;
use MediaWiki\DomainEvent\EventSubscriberBase;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\StaticHookRegistry;
use MediaWikiUnitTestCase;
use Wikimedia\ObjectFactory\ObjectFactory;
use Wikimedia\Services\ServiceContainer;

/**
 * @covers \MediaWiki\DomainEvent\EventSubscriberBase
 */
class EventSubscriberBaseTest extends MediaWikiUnitTestCase {

	private function newSpyEvenSource( &$trace ): DomainEventSource {
		$objectFactory = new ObjectFactory(
			$this->createNoOpMock( ServiceContainer::class )
		);

		$hookContainer = new HookContainer(
			new StaticHookRegistry(),
			$objectFactory
		);

		$dispatcher = $this->getMockBuilder( EventDispatchEngine::class )
			->setConstructorArgs( [ $objectFactory, $hookContainer ] )
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
		$source = $this->newSpyEvenSource( $trace );

		$events = [ 'Foo', 'Bar' ];

		// Pass the list of events as a constructor parameter
		$subscriber = new class ( $events ) extends EventSubscriberBase {
			public function __construct( $events ) {
				$this->initEvents( $events );
			}

			public function handleFooEventAfterCommit() {
				// no-op
			}

			public function handleBarEventAfterCommit() {
				// no-op
			}

			public function handleXyzzyEventAfterCommit() {
				// no-op
			}
		};

		$subscriber->registerListeners( $source );

		$this->assertSame( [
			[ 'Foo', [ $subscriber, 'handleFooEventAfterCommit' ] ],
			[ 'Bar', [ $subscriber, 'handleBarEventAfterCommit' ] ],
		], $trace );
	}

	public function testAutoSubscribe_init() {
		$trace = [];
		$source = $this->newSpyEvenSource( $trace );

		$events = [ 'Foo', 'Bar' ];

		// Pass nothing to the constructor, rely on initSubscriber()
		$subscriber = new class () extends EventSubscriberBase {
			public function handleFooEventAfterCommit() {
				// no-op
			}

			public function handleBarEventAfterCommit() {
				// no-op
			}

			public function handleXyzzyEventAfterCommit() {
				// no-op
			}
		};

		$subscriber->initSubscriber( [ 'events' => $events ] );
		$subscriber->registerListeners( $source );

		$this->assertSame( [
			[ 'Foo', [ $subscriber, 'handleFooEventAfterCommit' ] ],
			[ 'Bar', [ $subscriber, 'handleBarEventAfterCommit' ] ],
		], $trace );
	}
}
