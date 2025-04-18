<?php

namespace MediaWiki\Tests\Notification;

use MediaWiki\Notification\Middleware\SuppressNotificationByTypeMiddleware;
use MediaWiki\Notification\Notification;
use MediaWiki\Notification\NotificationEnvelope;
use MediaWiki\Notification\NotificationsBatch;
use MediaWiki\Notification\RecipientSet;
use MediaWikiUnitTestCase;

/**
 * @covers MediaWiki\Notification\Middleware\SuppressNotificationByTypeMiddleware
 */
class SuppressNotificationByTypeMiddlewareTest extends MediaWikiUnitTestCase {

	public function testRemovesNotificationTest() {
		$typeToRemove = 'test';
		$sut = new SuppressNotificationByTypeMiddleware( $typeToRemove );
		$notificationA = new Notification( 'first' );
		$notificationB = new Notification( $typeToRemove );
		$notificationC = new Notification( 'last' );
		$recipients = new RecipientSet( [] );
		$test = $this;

		$batch = new NotificationsBatch(
			new NotificationEnvelope( $notificationA, $recipients ),
			new NotificationEnvelope( $notificationB, $recipients ),
			new NotificationEnvelope( $notificationC, $recipients )
		);

		$sut->handle( $batch, static function () use ( $test, $batch ) {
			$test->assertCount( 3, $batch );
		} );
		$envelopes = iterator_to_array( $batch );
		$this->assertCount( 2, $envelopes );
		$test->assertSame( 'first', $envelopes[0]->getNotification()->getType() );
		$test->assertSame( 'last', $envelopes[1]->getNotification()->getType() );
	}
}
