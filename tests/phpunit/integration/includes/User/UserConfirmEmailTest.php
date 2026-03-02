<?php

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
