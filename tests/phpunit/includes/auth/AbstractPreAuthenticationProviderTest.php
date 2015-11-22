<?php

namespace MediaWiki\Auth;

/**
 * @group AuthManager
 * @covers MediaWiki\Auth\AbstractPreAuthenticationProvider
 */
class AbstractPreAuthenticationProviderTest extends \MediaWikiTestCase {
	public function testAbstractPreAuthenticationProvider() {
		$provider = $this->getMockForAbstractClass(
			'MediaWiki\\Auth\\AbstractPreAuthenticationProvider'
		);

		$this->assertEquals(
			array(),
			$provider->getAuthenticationRequests( AuthManager::ACTION_LOGIN, array() )
		);
		$this->assertEquals(
			\StatusValue::newGood(),
			$provider->testForAuthentication( array() )
		);
		$this->assertEquals(
			\StatusValue::newGood(),
			$provider->testForAccountCreation( null, null, array() )
		);
		$this->assertEquals(
			\StatusValue::newGood(),
			$provider->testUserForCreation( null, true )
		);
		$this->assertEquals(
			\StatusValue::newGood(),
			$provider->testUserForCreation( null, false )
		);
		$this->assertEquals(
			\StatusValue::newGood(),
			$provider->testForAccountLink( null )
		);
	}
}
