<?php

namespace MediaWiki\Tests\Notification;

use MediaWiki\Notification\MiddlewareChain;
use MediaWiki\Notification\Notification;
use MediaWiki\Notification\NotificationHandler;
use MediaWiki\Notification\NotificationService;
use MediaWiki\Notification\RecipientSet;
use MediaWikiUnitTestCase;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use RuntimeException;

/**
 * @covers \MediaWiki\Notification\NotificationService
 */
class NotificationServiceTest extends MediaWikiUnitTestCase {

	protected function getEmptyMiddleware() {
		$middleware = $this->createMock( MiddlewareChain::class );
		$middleware->expects( $this->any() )
			->method( 'process' )
			->willReturnArgument( 0 );
		return $middleware;
	}

	public function testTriggersMiddleware() {
		$notification = new Notification( 'test.middleware' );
		$recipients = new RecipientSet( [] );
		$middleware = $this->createMock( MiddlewareChain::class );
		$middleware->expects( $this->once() )
			->method( 'process' )
			->willReturnCallback( function ( $batch ) {
				$envelopes = iterator_to_array( $batch );
				$this->assertCount( 1, $envelopes );
				$this->assertSame( 'test.middleware', $envelopes[0]->getNotification()->getType() );
				return $batch;
			} );

		$svc = new NotificationService(
			new NullLogger(),
			$this->createSimpleObjectFactory(),
			$middleware,
			[]
		);
		$svc->notify( $notification, $recipients );
	}

	public function testBasic() {
		$recipients = new RecipientSet( [] );

		$notifA = new Notification( 'A' );
		$handlerA = $this->createMock( NotificationHandler::class );
		$handlerA->expects( $this->once() )->method( 'notify' )->with( $notifA, $recipients );

		$notifB = new Notification( 'B' );
		$handlerB = $this->createMock( NotificationHandler::class );
		$handlerB->expects( $this->once() )->method( 'notify' )->with( $notifB, $recipients );

		$notifC = new Notification( 'C' );
		$handlerC = $this->createMock( NotificationHandler::class );
		$handlerC->expects( $this->once() )->method( 'notify' )->with( $notifC, $recipients );

		$svc = new NotificationService(
			new NullLogger(),
			$this->createSimpleObjectFactory(),
			$this->getEmptyMiddleware(),
			[
				[ 'types' => [ 'A' ], 'factory' => static fn () => $handlerA ],
				[ 'types' => [ '*' ], 'factory' => static fn () => $handlerB ],
				[ 'types' => [ 'C' ], 'factory' => static fn () => $handlerC ],
			]
		);

		$svc->notify( $notifA, $recipients );
		$svc->notify( $notifB, $recipients );
		$svc->notify( $notifC, $recipients );
	}

	public function testBadWildcard() {
		$this->expectException( RuntimeException::class );

		$recipients = new RecipientSet( [] );
		$notif = new Notification( 'A' );
		$handler = $this->createNoOpMock( NotificationHandler::class );

		$svc = new NotificationService(
			new NullLogger(),
			$this->createSimpleObjectFactory(),
			$this->getEmptyMiddleware(),
			[
				[ 'types' => [ 'A/*' ], 'factory' => static fn () => $handler ],
			]
		);

		$svc->notify( $notif, $recipients );
	}

	public function testDoubleDefinition() {
		$this->expectException( RuntimeException::class );

		$recipients = new RecipientSet( [] );
		$notif = new Notification( 'A' );
		$handler = $this->createNoOpMock( NotificationHandler::class );

		$svc = new NotificationService(
			new NullLogger(),
			$this->createSimpleObjectFactory(),
			$this->getEmptyMiddleware(),
			[
				[ 'types' => [ '*' ], 'factory' => static fn () => $handler ],
				[ 'types' => [ '*' ], 'factory' => static fn () => $handler ],
			]
		);

		$svc->notify( $notif, $recipients );
	}

	public function testMissingHandler() {
		$mockLogger = $this->createMock( LoggerInterface::class );
		$mockLogger->expects( $this->once() )->method( 'warning' );
		$recipients = new RecipientSet( [] );
		$notif = new Notification( 'A' );
		$handler = $this->createNoOpMock( NotificationHandler::class );

		$svc = new NotificationService(
			$mockLogger,
			$this->createSimpleObjectFactory(),
			$this->getEmptyMiddleware(),
			[
				[ 'types' => [ 'B' ], 'factory' => static fn () => $handler ],
			]
		);

		$svc->notify( $notif, $recipients );
	}

}
