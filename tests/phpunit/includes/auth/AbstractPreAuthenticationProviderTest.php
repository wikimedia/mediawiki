<?php

/**
 * @group AuthManager
 * @covers AbstractPreAuthenticationProvider
 */
class AbstractPreAuthenticationProviderTest extends MediaWikiTestCase {
	/**
	 * @uses AuthManager
	 */
	public function testAbstractPreAuthenticationProvider() {
		$provider = $this->getMockForAbstractClass( 'AbstractPreAuthenticationProvider' );

		$this->assertEquals( array(), $provider->getAuthenticationRequestTypes( AuthManager::ACTION_ALL ) );
		$this->assertEquals(
			StatusValue::newGood(),
			$provider->testForAuthentication( array() )
		);
		$this->assertEquals(
			StatusValue::newGood(),
			$provider->testForAccountCreation( null, null, array() )
		);
		$this->assertEquals(
			StatusValue::newGood(),
			$provider->testForAutoCreation( null )
		);
	}
}
