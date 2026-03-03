<?php

use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @covers \MediaWiki\User\User::confirmEmail
 * @group Database
 */
class UserConfirmEmailTest extends MediaWikiIntegrationTestCase {

	public function testLogsEventWhenNewlyConfirmed() {
		$logger = new TestLogger( true, null, true );
		$this->setLogger( 'confirmemail', $logger );

		$user = $this->getMutableTestUser()->getUser();
		$user->setEmail( 'test@example.org' );
		$user->saveSettings();

		$user->confirmEmail();

		$this->assertSame( [
			[ 'info', 'Email address confirmed', [ 'event' => 'email_confirmed' ] ],
		], $logger->getBuffer() );
	}

	public function testLogsConfirmationDelayWhenTokenExpiryIsSet() {
		$logger = new TestLogger( true, null, true );
		$this->setLogger( 'confirmemail', $logger );

		$user = $this->getMutableTestUser()->getUser();
		$user->setEmail( 'test@example.org' );
		$user->saveSettings();

		ConvertibleTimestamp::setFakeTime( '20250101120000' );
		try {
			$expiration = null;
			$user->getConfirmationToken( $expiration );

			ConvertibleTimestamp::setFakeTime( '20250101120100' );
			$user->confirmEmail();
		} finally {
			ConvertibleTimestamp::setFakeTime( false );
		}

		$buffer = $logger->getBuffer();
		$this->assertCount( 1, $buffer );
		$this->assertSame( 'info', $buffer[0][0] );
		$this->assertSame( 'Email address confirmed', $buffer[0][1] );
		$this->assertSame( 'email_confirmed', $buffer[0][2]['event'] );
		$this->assertSame( 60, $buffer[0][2]['confirmation_delay_seconds'] );
	}

	public function testDoesNotLogWhenAlreadyConfirmed() {
		$logger = new TestLogger( true, null, true );
		$this->setLogger( 'confirmemail', $logger );

		$user = $this->getMutableTestUser()->getUser();
		$user->setEmail( 'test@example.org' );
		$user->setEmailAuthenticationTimestamp( wfTimestampNow() );
		$user->saveSettings();

		$user->confirmEmail();

		$this->assertSame( [], $logger->getBuffer() );
	}
}
