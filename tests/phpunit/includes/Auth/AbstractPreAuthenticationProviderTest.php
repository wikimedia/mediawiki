<?php

namespace MediaWiki\Tests\Auth;

use MediaWiki\Auth\AbstractPreAuthenticationProvider;
use MediaWiki\Auth\AuthenticationResponse;
use MediaWiki\Auth\AuthManager;
use MediaWiki\User\User;
use MediaWikiIntegrationTestCase;
use StatusValue;

/**
 * @group AuthManager
 * @covers \MediaWiki\Auth\AbstractPreAuthenticationProvider
 */
class AbstractPreAuthenticationProviderTest extends MediaWikiIntegrationTestCase {
	public function testAbstractPreAuthenticationProvider() {
		$user = $this->createMock( User::class );

		$provider = $this->getMockForAbstractClass( AbstractPreAuthenticationProvider::class );

		$this->assertEquals(
			[],
			$provider->getAuthenticationRequests( AuthManager::ACTION_LOGIN, [] )
		);
		$this->assertEquals(
			StatusValue::newGood(),
			$provider->testForAuthentication( [] )
		);
		$this->assertEquals(
			StatusValue::newGood(),
			$provider->testForAccountCreation( $user, $user, [] )
		);
		$this->assertEquals(
			StatusValue::newGood(),
			$provider->testUserForCreation( $user, AuthManager::AUTOCREATE_SOURCE_SESSION )
		);
		$this->assertEquals(
			StatusValue::newGood(),
			$provider->testUserForCreation( $user, false )
		);
		$this->assertEquals(
			StatusValue::newGood(),
			$provider->testForAccountLink( $user )
		);

		$res = AuthenticationResponse::newPass();
		$provider->postAuthentication( $user, $res );
		$provider->postAccountCreation( $user, $user, $res );
		$provider->postAccountLink( $user, $res );
	}
}
