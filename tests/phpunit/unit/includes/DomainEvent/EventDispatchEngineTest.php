<?php

namespace MediaWiki\Tests\DomainEvent;

use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\DomainEvent\DomainEvent;
use MediaWiki\DomainEvent\DomainEventIngress;
use MediaWiki\DomainEvent\DomainEventSource;
use MediaWiki\DomainEvent\DomainEventSubscriber;
use MediaWiki\DomainEvent\EventDispatchEngine;
use MediaWiki\Tests\MockDatabase;
use MediaWikiUnitTestCase;
use Wikimedia\ObjectFactory\ObjectFactory;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Services\ServiceContainer;

/**
 * @covers \MediaWiki\DomainEvent\EventDispatchEngine
 */
class EventDispatchEngineTest extends MediaWikiUnitTestCase {

	private function newEvent( string $type ): DomainEvent {
		return new class ( $type ) extends DomainEvent {
			public function __construct( string $type ) {
				parent::__construct();
				$this->declareEventType( $type );
			}
		};
	}

	private function newConnectionProvider(): IConnectionProvider {
		$conProv = $this->createNoOpMock(
			IConnectionProvider::class,
			[ 'getPrimaryDatabase' ]
		);

		$db = new MockDatabase();

		$conProv->method( 'getPrimaryDatabase' )->willReturnCallback(
			static function () use ( $db ) {
				return $db;
			}
		);

		return $conProv;
	}

	private function newDispatchEngine(): EventDispatchEngine {
		$objectFactory = new ObjectFactory(
			$this->createNoOpMock( ServiceContainer::class )
		);

		$engine = new EventDispatchEngine(
			$objectFactory
		);

		return $engine;
	}

	public function testDispatch() {
		$engine = $this->newDispatchEngine();
		$conProv = $this->newConnectionProvider();

		$callCount = 0;

		$engine->registerListener(
			'Tested',
			static function ( DomainEvent $event ) use ( &$callCount ) {
				$callCount++;
			}
		);

		$dbw = $conProv->getPrimaryDatabase();
		$dbw->begin();
		$event = $this->newEvent( 'Tested' );
		$engine->dispatch( $event, $conProv );
		$dbw->commit();

		DeferredUpdates::doUpdates();

		$this->assertSame( 1, $callCount, 'Listener should have been called' );
	}

	public function testWildcardListener() {
		$engine = $this->newDispatchEngine();
		$conProv = $this->newConnectionProvider();

		$callCount = 0;
		$engine->registerListener(
			DomainEvent::ANY, // register for any event
			static function () use ( &$callCount ) {
				$callCount++;
			}
		);

		$engine->dispatch( $this->newEvent( 'Tested1' ), $conProv );
		$engine->dispatch( $this->newEvent( 'Tested2' ), $conProv );

		DeferredUpdates::doUpdates();

		$this->assertSame( 2, $callCount, 'Listener should have been called' );
	}

	/**
	 * Assert that listeners registered in INVOKE_AFTER_COMMIT mode will
	 * not be invoked if the transaction is rolled back, but listeners
	 * registered as INVOKE_BEFORE_COMMIT are still invoked.
	 */
	public function testRollback() {
		$engine = $this->newDispatchEngine();

		$conProv = $this->newConnectionProvider();

		$callCount = 0;
		$engine->registerListener(
			'Tested',
			static function (
				DomainEvent $event,
				IConnectionProvider $conProv
			) use ( &$callCount ) {
				$callCount++;
			}
		);

		$dbw = $conProv->getPrimaryDatabase();
		$dbw->begin();
		$event = $this->newEvent( 'Tested' );
		$engine->dispatch( $event, $conProv );

		$dbw->rollback();
		DeferredUpdates::doUpdates();

		$this->assertSame( 0, $callCount, 'After-commit listener should not have been called' );
	}

	public function testRegisterSubscriber() {
		$engine = $this->newDispatchEngine();

		$trace = [];
		$fooListener = static function () use ( &$trace ) {
			$trace[] = 'fooListener';
		};

		$subscriber = $this->createMock( DomainEventSubscriber::class );
		$subscriber->method( 'registerListeners' )->willReturnCallback(
			static function ( DomainEventSource $source ) use ( $fooListener, &$trace ) {
				$trace[] = 'registerListeners';
				$source->registerListener( 'FooComplete', $fooListener );
			}
		);

		$engine->registerSubscriber( $subscriber );

		$conProv = $this->newConnectionProvider();
		$engine->dispatch( $this->newEvent( 'Xyzzy' ), $conProv );
		$engine->dispatch( $this->newEvent( 'FooComplete' ), $conProv );
		$engine->dispatch( $this->newEvent( 'Foobar' ), $conProv );
		DeferredUpdates::doUpdates();

		$this->assertSame( [ 'registerListeners', 'fooListener' ], $trace );
	}

	public function testRegisterSubscriber_lazy() {
		$engine = $this->newDispatchEngine();

		$trace = [];
		$fooListener = static function () use ( &$trace ) {
			$trace[] = 'fooListener';
		};

		$barListener = static function () use ( &$trace ) {
			$trace[] = 'barListener';
		};

		$subscriber = $this->createMock( DomainEventSubscriber::class );
		$subscriber->method( 'registerListeners' )->willReturnCallback(
			static function ( DomainEventSource $source ) use (
				$fooListener,
				$barListener,
				&$trace
			) {
				$trace[] = 'registerListeners';
				$source->registerListener( 'FooComplete', $fooListener );
				$source->registerListener( 'BarComplete', $barListener );
			}
		);

		$engine->registerSubscriber( [
			'factory' => static function () use ( $subscriber ) {
				return $subscriber;
			},
			'events' => [ 'FooComplete', 'BarComplete' ]
		] );

		$conProv = $this->newConnectionProvider();
		$engine->dispatch( $this->newEvent( 'Xyzzy' ), $conProv );
		DeferredUpdates::doUpdates();
		$this->assertSame( [], $trace );

		$engine->dispatch( $this->newEvent( 'FooComplete' ), $conProv );
		DeferredUpdates::doUpdates();

		// Subscriber and listener should have been called.
		$this->assertSame( [ 'registerListeners', 'fooListener' ], $trace );

		$engine->dispatch( $this->newEvent( 'BarComplete' ), $conProv );
		DeferredUpdates::doUpdates();

		// The subscriber must not get called again, even if the event is
		// triggered for the first time.
		$this->assertSame(
			[ 'registerListeners', 'fooListener', 'barListener', ],
			$trace
		);
	}

	public function testRegisterSubscriber_recursive() {
		$engine = $this->newDispatchEngine();

		$trace = [];
		$fooListener = static function () use ( &$trace ) {
			$trace[] = 'fooListener';
		};

		$subscriber1 = $this->createMock( DomainEventSubscriber::class );
		$subscriber2 = $this->createMock( DomainEventSubscriber::class );

		$subscriber1->method( 'registerListeners' )->willReturnCallback(
			static function ( DomainEventSource $source ) use (
				$subscriber2,
				&$trace
			) {
				$trace[] = 'registerListeners1';
				$source->registerSubscriber( [
					'factory' => static function () use ( $subscriber2 ) {
						return $subscriber2;
					},
					'events' => [ 'FooComplete' ]
				] );
			}
		);

		$subscriber2->method( 'registerListeners' )->willReturnCallback(
			static function ( DomainEventSource $source ) use (
				$fooListener,
				&$trace
			) {
				$trace[] = 'registerListeners2';
				$source->registerListener( 'FooComplete', $fooListener );
			}
		);

		$engine->registerSubscriber( [
			'factory' => static function () use ( $subscriber1 ) {
				return $subscriber1;
			},
			'events' => [ 'FooComplete' ]
		] );

		$conProv = $this->newConnectionProvider();

		$engine->dispatch( $this->newEvent( 'FooComplete' ), $conProv );
		DeferredUpdates::doUpdates();

		// Both subscribers should have been resolved
		$this->assertSame(
			[ 'registerListeners1', 'registerListeners2', 'fooListener', ],
			$trace
		);
	}

	public function testRegisterSubscriber_automatic() {
		$engine = $this->newDispatchEngine();

		$trace = [];

		$subscriber = new class ( $trace ) extends DomainEventIngress {
			private $trace;

			public function __construct( &$trace ) {
				$this->trace =& $trace;
			}

			public function handleFooCompleteEvent() {
				$this->trace[] = 'afterFooComplete';
			}
		};

		// The dispatcher should inject the list of events into $subscriber by
		// calling the initSubscriber() method.
		$engine->registerSubscriber( [
			'events' => [ 'FooComplete' ],
			'factory' => static function () use ( $subscriber ) {
				return $subscriber;
			}
		] );

		$conProv = $this->newConnectionProvider();
		$engine->dispatch( $this->newEvent( 'FooComplete' ), $conProv );
		DeferredUpdates::doUpdates();

		$this->assertSame( [ 'afterFooComplete' ], $trace );
	}

}
