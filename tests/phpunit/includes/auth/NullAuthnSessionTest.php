<?php

/**
 * @group AuthManager
 * @group Database
 * @covers NullAuthnSession
 * @uses AuthnSession
 */
class NullAuthnSessionTest extends MediaWikiTestCase {

	public function testConstructor() {
		$session = new NullAuthnSession();

		$this->assertSame( 'NonPersistentSession', $session->getSessionKey() );
		$this->assertSame( 0, $session->getSessionPriority() );
	}

	public function testBasics() {
		$session = new NullAuthnSession();
		$privSession = TestingAccessWrapper::newFromObject( $session );

		$this->assertFalse( $privSession->canResetSessionKey() );
		$this->assertSame( array( 0, null, null ), $session->getSessionUserInfo() );
		$this->assertFalse( $session->canSetSessionUserInfo() );
	}

}
