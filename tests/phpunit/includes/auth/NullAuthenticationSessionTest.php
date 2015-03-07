<?php

/**
 * @group AuthManager
 * @group Database
 * @covers NullAuthenticationSession
 * @uses AuthenticationSession
 */
class NullAuthenticationSessionTest extends MediaWikiTestCase {

	public function testConstructor() {
		$session = new NullAuthenticationSession();

		$this->assertSame( 'NonPersistentSession', $session->getSessionKey() );
		$this->assertSame( 0, $session->getSessionPriority() );
	}

	public function testBasics() {
		$session = new NullAuthenticationSession();
		$privSession = TestingAccessWrapper::newFromObject( $session );

		$this->assertFalse( $privSession->canResetSessionKey() );
		$this->assertSame( array( 0, null, null ), $session->getSessionUserInfo() );
		$this->assertFalse( $session->canSetSessionUserInfo() );
	}

}
