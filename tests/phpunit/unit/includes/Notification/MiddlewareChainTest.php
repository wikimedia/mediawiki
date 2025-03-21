<?php

namespace MediaWiki\Tests\Notification;

use MediaWiki\Notification\MiddlewareChain;
use MediaWiki\Notification\Notification;
use MediaWiki\Notification\NotificationEnvelope;
use MediaWiki\Notification\NotificationMiddlewareInterface;
use MediaWiki\Notification\NotificationsBatch;
use MediaWiki\Notification\RecipientSet;
use MediaWiki\User\UserIdentity;
use MediaWikiUnitTestCase;

/**
 * @covers \MediaWiki\Notification\MiddlewareChain
 */
class MiddlewareChainTest extends MediaWikiUnitTestCase {

	private function getSut( array $middlewares ): MiddlewareChain {
		$specs = [];
		foreach ( $middlewares as $middleware ) {
			$specs[] = [ 'factory' => static fn () => $middleware ];
		}
		return new MiddlewareChain( $this->createSimpleObjectFactory(), $specs );
	}

	public function testEmptyMiddlewareChainReturnsOriginalBatch() {
		$userIdentity = $this->createMock( UserIdentity::class );
		$notificationToSend = new Notification( 'test', [] );
		$recipients = new RecipientSet( [ $userIdentity ] );

		$envelopes = new NotificationsBatch(
			new NotificationEnvelope( $notificationToSend, $recipients )
		);

		$sut = $this->getSut( [] );
		$this->assertSame( $envelopes, $sut->process( $envelopes ) );
	}

	public function testExecutesInOrderAndModifiesBatch() {
		$userIdentity = $this->createMock( UserIdentity::class );
		$notificationToSend = new Notification( 'test', [] );
		$recipients = new RecipientSet( [ $userIdentity ] );

		$noOpMiddleware = $this->createMock( NotificationMiddlewareInterface::class );
		$noOpMiddleware->expects( $this->exactly( 2 ) )
			->method( 'handle' )
			->willReturnCallback( function ( NotificationsBatch $batch, callable $next ) {
				$this->assertCount( 1, $batch );
				$next();
			} );
		$emptyMiddleware = $this->createMock( NotificationMiddlewareInterface::class );
		$emptyMiddleware->expects( $this->once() )
			->method( 'handle' )
			->willReturnCallback( static function ( NotificationsBatch $batch, callable $next ) {
				foreach ( $batch as $envelope ) {
					$batch->remove( $envelope );
				}
				$next();
			} );
		$makeSureBatchIsEmptyMiddleware = $this->createMock( NotificationMiddlewareInterface::class );
		$makeSureBatchIsEmptyMiddleware->expects( $this->once() )
			->method( 'handle' )
			->willReturnCallback( function ( NotificationsBatch $batch, callable $next ) {
				$this->assertCount( 0, $batch );
				$next();
			} );

		$middlewareChain = $this->getSut( [
			$noOpMiddleware, $noOpMiddleware, $emptyMiddleware, $makeSureBatchIsEmptyMiddleware,
		] );

		$result = $middlewareChain->process( new NotificationsBatch(
			new NotificationEnvelope( $notificationToSend, $recipients )
		) );
		$this->assertCount( 0, $result );
	}

	public function testMiddlewareDoesntCallNext() {
		$userIdentity = $this->createMock( UserIdentity::class );
		$keptNotification = new Notification( 'test', [] );
		$recipients = new RecipientSet( [ $userIdentity ] );

		$suppressWelcomeMiddleware = $this->createMock( NotificationMiddlewareInterface::class );
		$suppressWelcomeMiddleware->expects( $this->once() )
			->method( 'handle' )
			->willReturnCallback( static function ( NotificationsBatch $batch, callable $next ) {
				// no op, but also don't call next
			} );

		$middlewareChain = $this->getSut( [
			$suppressWelcomeMiddleware,
		] );

		$batch = $middlewareChain->process(
			new NotificationsBatch(
				new NotificationEnvelope( $keptNotification, $recipients )
			)
		);
		$this->assertCount( 1, $batch );
		$envelopes = iterator_to_array( $batch );
		$this->assertSame( $keptNotification, $envelopes[0]->getNotification() );
	}

	public function testSupressNotification() {
		$userIdentity = $this->createMock( UserIdentity::class );
		$keptNotification = new Notification( 'test', [] );
		$removedNotification = new Notification( 'welcome', [] );
		$recipients = new RecipientSet( [ $userIdentity ] );

		$suppressWelcomeMiddleware = $this->createMock( NotificationMiddlewareInterface::class );
		$suppressWelcomeMiddleware->expects( $this->once() )
			->method( 'handle' )
			->willReturnCallback( static function ( NotificationsBatch $batch, callable $next ) {
				foreach ( $batch as $envelope ) {
					if ( $envelope->getNotification()->getType() === 'welcome' ) {
						$batch->remove( $envelope );
					}
				}
				$next();
			} );

		$middlewareChain = $this->getSut( [
			$suppressWelcomeMiddleware,
		] );

		$batch = $middlewareChain->process( new NotificationsBatch(
			new NotificationEnvelope( $keptNotification, $recipients ),
			new NotificationEnvelope( $removedNotification, $recipients )
		) );
		$this->assertCount( 1, $batch );
		foreach ( $batch as $envelope ) {
			$this->assertSame( $keptNotification, $envelope->getNotification() );
		}
	}

	/**
	 * Test case when Middleware calls NotificationService::notify() to inject new notification
	 * This can cause endless loops where Middleware triggers a notification, that triggers the
	 * middleware again.
	 *
	 * @return void
	 */
	public function testBadMiddlewareTriesToSendNotificationInsteadOfInjectingToBatch() {
		$userIdentity = $this->createMock( UserIdentity::class );
		$keptNotification = new Notification( 'test', [] );
		$recipients = new RecipientSet( [ $userIdentity ] );

		$suppressWelcomeMiddleware = $this->createMock( NotificationMiddlewareInterface::class );
		$suppressWelcomeMiddleware->expects( $this->once() )
			->method( 'handle' )
			->willReturnCallback( static function ( NotificationsBatch $batch, callable $next ) {
				// no op, but also don't call next
			} );

		$middlewareChain = $this->getSut( [
			$suppressWelcomeMiddleware,
		] );

		$batch = $middlewareChain->process(
			new NotificationsBatch(
				new NotificationEnvelope( $keptNotification, $recipients )
			)
		);
		$this->assertCount( 1, $batch );
		$envelopes = iterator_to_array( $batch );
		$this->assertSame( $keptNotification, $envelopes[0]->getNotification() );
	}

}
