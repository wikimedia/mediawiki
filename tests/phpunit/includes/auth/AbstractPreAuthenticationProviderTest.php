<?php

namespace MediaWiki\Auth;

/**
 * @group AuthManager
 * @covers \MediaWiki\Auth\AbstractPreAuthenticationProvider
 */
class AbstractPreAuthenticationProviderTest extends \MediaWikiTestCase {
	public function testAbstractPreAuthenticationProvider() {
		$user = \User::newFromName( 'UTSysop' );

		$provider = $this->getMockForAbstractClass( AbstractPreAuthenticationProvider::class );

		$this->assertEquals(
			[],
			$provider->getAuthenticationRequests( AuthManager::ACTION_LOGIN, [] )
		);
		$this->assertEquals(
			\StatusValue::newGood(),
			$provider->testForAuthentication( [] )
		);
		$this->assertEquals(
			\StatusValue::newGood(),
			$provider->testForAccountCreation( $user, $user, [] )
		);
		$this->assertEquals(
			\StatusValue::newGood(),
			$provider->testUserForCreation( $user, AuthManager::AUTOCREATE_SOURCE_SESSION )
		);
		$this->assertEquals(
			\StatusValue::newGood(),
			$provider->testUserForCreation( $user, false )
		);
		$this->assertEquals(
			\StatusValue::newGood(),
			$provider->testForAccountLink( $user )
		);

		$res = AuthenticationResponse::newPass();
		$provider->postAuthentication( $user, $res );
		$provider->postAccountCreation( $user, $user, $res );
		$provider->postAccountLink( $user, $res );
	}
}
