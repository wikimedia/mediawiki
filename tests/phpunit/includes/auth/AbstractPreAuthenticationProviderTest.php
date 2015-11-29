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
		/** @var $provider AbstractPreAuthenticationProvider */

		$this->assertEquals(
			array(),
			$provider->getAuthenticationRequests( AuthManager::ACTION_LOGIN )
		);
		$this->assertEquals(
			array(),
			$provider->getAuthenticationRequests( AuthManager::ACTION_CREATE )
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
			$provider->testForAutoCreation( null )
		);
		$this->assertEquals(
			\StatusValue::newGood(),
			$provider->testForAccountLink( null )
		);
	}
}
