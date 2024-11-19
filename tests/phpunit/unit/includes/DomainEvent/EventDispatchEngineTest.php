<?php

namespace MediaWiki\Tests\DomainEvent;

use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\DomainEvent\DomainEvent;
use MediaWiki\DomainEvent\EventDispatchEngine;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\StaticHookRegistry;
use MediaWiki\Tests\MockDatabase;
use MediaWikiUnitTestCase;
use Wikimedia\ObjectFactory\ObjectFactory;
use Wikimedia\Rdbms\IConnectionProvider;

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

	public function testSend() {
		$hookContainer = new HookContainer(
			new StaticHookRegistry(),
			$this->createNoOpMock( ObjectFactory::class )
		);

		$conProv = $this->newConnectionProvider();

		$dispatcher = new EventDispatchEngine( $hookContainer );

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

		$dispatcher->registerListener(
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
		$dispatcher->dispatch( $event, $conProv );

		$this->assertSame( 1, $callCount, 'Hook handler should have been called' );

		$dbw->commit();
		DeferredUpdates::doUpdates();

		$this->assertSame( 2, $callCount, 'Listener should have been called' );
	}

	public function testRollback() {
		$hookContainer = new HookContainer(
			new StaticHookRegistry(),
			$this->createNoOpMock( ObjectFactory::class )
		);

		$conProv = $this->newConnectionProvider();

		$dispatcher = new EventDispatchEngine( $hookContainer );

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

		$dispatcher->registerListener(
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
		$dispatcher->dispatch( $event, $conProv );

		$this->assertSame( 1, $callCount, 'Hook handler should have been called' );

		$dbw->rollback();
		DeferredUpdates::doUpdates();

		$this->assertSame( 1, $callCount, 'Listener should not have been called' );
	}

}
