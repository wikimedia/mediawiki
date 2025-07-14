<?php

namespace MediaWiki\Tests\Notification;

use MediaWiki\Notification\Middleware\FilterMiddleware;
use MediaWiki\Notification\Middleware\FilterMiddlewareAction;
use MediaWiki\Notification\Notification;
use MediaWiki\Notification\NotificationEnvelope;
use MediaWiki\Notification\NotificationsBatch;
use MediaWiki\Notification\RecipientSet;
use MediaWiki\User\UserIdentity;
use MediaWikiUnitTestCase;

/**
 * @covers MediaWiki\Notification\Middleware\FilterMiddleware
 */
class FilterMiddlewareTest extends MediaWikiUnitTestCase {

	public function testRemovesNotificationTest() {
		$sut = $this->getMockForAbstractClass( FilterMiddleware::class );
		$sut->expects( $this->any() )
			->method( 'filter' )
			->willReturnCallback( static function ( NotificationEnvelope $envelope ) {
				return $envelope->getNotification()->getType() === 'remove' || $envelope->getRecipientSet()->count() === 0 ?
					FilterMiddlewareAction::REMOVE : FilterMiddlewareAction::KEEP;
			} );

		$notificationA = new Notification( 'first' );
		$notificationB = new Notification( 'remove' );
		$notificationC = new Notification( 'middle' );
		$notificationD = new Notification( 'last' );

		$someRecipients = new RecipientSet( [ $this->createMock( UserIdentity::class ) ] );
		$emptyRecipients = new RecipientSet( [] );

		$batch = new NotificationsBatch(
			new NotificationEnvelope( $notificationA, $someRecipients ),
			new NotificationEnvelope( $notificationB, $someRecipients ),
			new NotificationEnvelope( $notificationC, $someRecipients ),
			new NotificationEnvelope( $notificationD, $emptyRecipients )
		);

		$calledNext = false;
		$sut->handle( $batch, static function () use ( &$calledNext )  {
			$calledNext = true;
		} );
		$envelopes = iterator_to_array( $batch );
		$this->assertTrue( $calledNext );
		$this->assertCount( 2, $envelopes );
		$this->assertSame( 'first', $envelopes[0]->getNotification()->getType() );
		$this->assertSame( 'middle', $envelopes[1]->getNotification()->getType() );
	}
}
