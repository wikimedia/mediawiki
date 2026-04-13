<?php

namespace MediaWiki\Tests\Auth;

use MediaWiki\Auth\AuthManager;
use MediaWiki\Auth\PreviouslyRenamedAccountAuthenticationRequest;
use MediaWiki\Auth\PreviouslyRenamedAccountPreAuthenticationProvider;
use MediaWiki\RenameUser\RenameuserSQL;
use MediaWiki\Tests\Unit\Auth\AuthenticationProviderTestTrait;
use MediaWiki\User\User;
use MediaWikiIntegrationTestCase;

/**
 * @group AuthManager
 * @group Database
 * @covers \MediaWiki\Auth\PreviouslyRenamedAccountPreAuthenticationProvider
 */
class PreviouslyRenamedAccountPreAuthenticationProviderTest extends MediaWikiIntegrationTestCase {
	use AuthenticationProviderTestTrait;

	private function getPreviouslyRenamedAccountPreAuthenticationProvider(): PreviouslyRenamedAccountPreAuthenticationProvider {
		$provider = new PreviouslyRenamedAccountPreAuthenticationProvider(
			$this->getServiceContainer()->getConnectionProvider(),
			$this->getServiceContainer()->getUserFactory(),
			[]
		);
		$this->initProvider( $provider, null, null, $this->getServiceContainer()->getAuthManager() );

		return $provider;
	}

	public function addDBDataOnce(): void {
		parent::addDBDataOnce();
		$user = User::createNew( 'Renamed user A' );
		$rename = new RenameuserSQL( $user->getName(), 'Renamed user B', $user->getId(), $this->getTestSysop()->getUser() );
		$this->assertStatusGood( $rename->renameUser() );
		User::createNew( 'Another user' );
	}

	/**
	 * @dataProvider provideGetAuthenticationRequests
	 */
	public function testGetAuthenticationRequests( $action, bool $useUsername, $expectedReqs ) {
		$provider = $this->getPreviouslyRenamedAccountPreAuthenticationProvider();
		$username = $useUsername ? $this->getTestSysop()->getUserIdentity()->getName() : null;
		$reqs = $provider->getAuthenticationRequests( $action, [ 'username' => $username ] );
		$this->assertEquals( $expectedReqs, $reqs );
	}

	public static function provideGetAuthenticationRequests() {
		return [
			[ AuthManager::ACTION_LOGIN, false, [] ],
			[ AuthManager::ACTION_CREATE, false, [] ],
			[ AuthManager::ACTION_CREATE, true,
				[ new PreviouslyRenamedAccountAuthenticationRequest() ] ],
			[ AuthManager::ACTION_CHANGE, false, [] ],
			[ AuthManager::ACTION_REMOVE, false, [] ],
		];
	}

	/**
	 * @dataProvider provideTestForAccountCreation
	 */
	public function testTestForAccountCreation(
		$username, $creatorIsSysop, $reqs, $error
	) {
		$provider = $this->getPreviouslyRenamedAccountPreAuthenticationProvider();

		$creator = $creatorIsSysop ? $this->getTestSysop()->getUser() : new User();
		$user = $this->createMock( User::class );
		$user->method( 'getName' )->willReturn( $username );
		$status = $provider->testForAccountCreation( $user, $creator, $reqs );

		if ( $error ) {
			$this->assertStatusError( $error, $status );
		} else {
			$this->assertStatusGood( $status );
		}
	}

	public static function provideTestForAccountCreation() {
		$noSkip = new PreviouslyRenamedAccountAuthenticationRequest();
		$skip = new PreviouslyRenamedAccountAuthenticationRequest();
		$skip->allowPreviouslyRenamedAccount = true;

		yield 'Previously renamed' =>
			[ 'Renamed user A', false, [], 'username-previously-renamed-account' ];

		yield 'New username' =>
			[ 'Renamed user B', false, [], false ];

		yield 'Never renamed' =>
			[ 'Another user', false, [], false ];

		yield 'Non-existent' =>
			[ 'Non-existent user', false, [], false ];

		yield 'Previously renamed, did not try to override (A)' =>
			[ 'Renamed user A', true, [], 'username-previously-renamed-account' ];

		yield 'Previously renamed, did not try to override (B)' =>
			[ 'Renamed user A', true, [ $noSkip ], 'username-previously-renamed-account' ];

		yield 'Previously renamed, can not override' =>
			[ 'Renamed user A', false, [ $skip ], 'username-previously-renamed-account' ];

		yield 'Previously renamed, can override' =>
			[ 'Renamed user A', true, [ $skip ], false ];
	}

	/**
	 * @dataProvider provideTestUserForCreation
	 */
	public function testTestUserForCreation(
		$username, $autocreate, $options, $error
	) {
		$provider = $this->getPreviouslyRenamedAccountPreAuthenticationProvider();

		$user = $this->createMock( User::class );
		$user->method( 'getName' )->willReturn( $username );

		$status = $provider->testUserForCreation( $user, $autocreate, $options );

		if ( $error ) {
			$this->assertStatusError( $error, $status );
		} else {
			$this->assertStatusGood( $status );
		}
	}

	public static function provideTestUserForCreation() {
		yield 'Previously renamed' =>
			[ 'Renamed user A', false, [], 'username-previously-renamed-account' ];

		yield 'New username' =>
			[ 'Renamed user B', false, [], false ];

		yield 'Never renamed' =>
			[ 'Another user', false, [], false ];

		yield 'Non-existent' =>
			[ 'Non-existent user', false, [], false ];

		yield 'Previously renamed, autocreate' =>
			[ 'Renamed user A', true, [], false ];

		yield 'Previously renamed, creating' =>
			[ 'Renamed user A', false, [ 'creating' => true ], false ];
	}
}
