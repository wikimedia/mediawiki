<?php

namespace MediaWiki\Tests\DomainEvent;

use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\DomainEvent\DomainEvent;
use MediaWiki\DomainEvent\DomainEventSource;
use MediaWiki\DomainEvent\DomainEventSubscriber;
use MediaWiki\DomainEvent\EventDispatchEngine;
use MediaWiki\DomainEvent\EventSubscriberBase;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\StaticHookRegistry;
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

	private function newDispatchEngine( ?HookContainer $hookContainer = null ): EventDispatchEngine {
		$hookContainer ??= new HookContainer(
			new StaticHookRegistry(),
			$this->createNoOpMock( ObjectFactory::class )
		);

		$objectFactory = new ObjectFactory(
			$this->createNoOpMock( ServiceContainer::class )
		);

		$engine = new EventDispatchEngine(
			$objectFactory,
			$hookContainer
		);

		return $engine;
	}

	public function testSend() {
		$hookContainer = new HookContainer(
			new StaticHookRegistry(),
			$this->createNoOpMock( ObjectFactory::class )
		);

		$engine = $this->newDispatchEngine( $hookContainer );

		$conProv = $this->newConnectionProvider();

		$callCount = 0;
		$hookContainer->register(
			'Tested',
			static function (
				DomainEvent $event,
				IConnectionProvider $conProv
			) use ( &$callCount ) {
				$callCount++;
			}
		);

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

		$this->assertSame( 1, $callCount, 'Hook handler should have been called' );

		$dbw->commit();
		DeferredUpdates::doUpdates();

		$this->assertSame( 2, $callCount, 'Listener should have been called' );
	}

	public function testRollback() {
		$hookContainer ??= new HookContainer(
			new StaticHookRegistry(),
			$this->createNoOpMock( ObjectFactory::class )
		);

		$engine = $this->newDispatchEngine( $hookContainer );

		$conProv = $this->newConnectionProvider();

		$callCount = 0;
		$hookContainer->register(
			'Tested',
			static function (
				DomainEvent $event,
				IConnectionProvider $conProv
			) use ( &$callCount ) {
				$callCount++;
			}
		);

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

		$this->assertSame( 1, $callCount, 'Hook handler should have been called' );

		$dbw->rollback();
		DeferredUpdates::doUpdates();

		$this->assertSame( 1, $callCount, 'Listener should not have been called' );
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

		$subscriber = new class ( $trace ) extends EventSubscriberBase {
			private $trace;

			public function __construct( &$trace ) {
				$this->trace =& $trace;
			}

			public function handleFooCompleteEventAfterCommit() {
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
