<?php

namespace MediaWiki\Auth;

use Wikimedia\TestingAccessWrapper;

/**
 * @group AuthManager
 * @group Database
 * @covers MediaWiki\Auth\CheckBlocksSecondaryAuthenticationProvider
 */
class CheckBlocksSecondaryAuthenticationProviderTest extends \MediaWikiTestCase {
	public function testConstructor() {
		$provider = new CheckBlocksSecondaryAuthenticationProvider();
		$providerPriv = TestingAccessWrapper::newFromObject( $provider );
		$config = new \HashConfig( [
			'BlockDisablesLogin' => false
		] );
		$provider->setConfig( $config );
		$this->assertSame( false, $providerPriv->blockDisablesLogin );

		$provider = new CheckBlocksSecondaryAuthenticationProvider(
			[ 'blockDisablesLogin' => true ]
		);
		$providerPriv = TestingAccessWrapper::newFromObject( $provider );
		$config = new \HashConfig( [
			'BlockDisablesLogin' => false
		] );
		$provider->setConfig( $config );
		$this->assertSame( true, $providerPriv->blockDisablesLogin );
	}

	public function testBasics() {
		$provider = new CheckBlocksSecondaryAuthenticationProvider();
		$user = \User::newFromName( 'UTSysop' );

		$this->assertEquals(
			AuthenticationResponse::newAbstain(),
			$provider->beginSecondaryAccountCreation( $user, $user, [] )
		);
	}

	/**
	 * @dataProvider provideGetAuthenticationRequests
	 * @param string $action
	 * @param array $response
	 */
	public function testGetAuthenticationRequests( $action, $response ) {
		$provider = new CheckBlocksSecondaryAuthenticationProvider();

		$this->assertEquals( $response, $provider->getAuthenticationRequests( $action, [] ) );
	}

	public static function provideGetAuthenticationRequests() {
		return [
			[ AuthManager::ACTION_LOGIN, [] ],
			[ AuthManager::ACTION_CREATE, [] ],
			[ AuthManager::ACTION_LINK, [] ],
			[ AuthManager::ACTION_CHANGE, [] ],
			[ AuthManager::ACTION_REMOVE, [] ],
		];
	}

	private function getBlockedUser() {
		$user = \User::newFromName( 'UTBlockee' );
		if ( $user->getID() == 0 ) {
			$user->addToDatabase();
			\TestUser::setPasswordForUser( $user, 'UTBlockeePassword' );
			$user->saveSettings();
		}
		$oldBlock = \Block::newFromTarget( 'UTBlockee' );
		if ( $oldBlock ) {
			// An old block will prevent our new one from saving.
			$oldBlock->delete();
		}
		$blockOptions = [
			'address' => 'UTBlockee',
			'user' => $user->getID(),
			'by' => $this->getTestSysop()->getUser()->getId(),
			'reason' => __METHOD__,
			'expiry' => time() + 100500,
			'createAccount' => true,
		];
		$block = new \Block( $blockOptions );
		$block->insert();
		return $user;
	}

	public function testBeginSecondaryAuthentication() {
		$unblockedUser = \User::newFromName( 'UTSysop' );
		$blockedUser = $this->getBlockedUser();

		$provider = new CheckBlocksSecondaryAuthenticationProvider(
			[ 'blockDisablesLogin' => false ]
		);
		$this->assertEquals(
			AuthenticationResponse::newAbstain(),
			$provider->beginSecondaryAuthentication( $unblockedUser, [] )
		);
		$this->assertEquals(
			AuthenticationResponse::newAbstain(),
			$provider->beginSecondaryAuthentication( $blockedUser, [] )
		);

		$provider = new CheckBlocksSecondaryAuthenticationProvider(
			[ 'blockDisablesLogin' => true ]
		);
		$this->assertEquals(
			AuthenticationResponse::newPass(),
			$provider->beginSecondaryAuthentication( $unblockedUser, [] )
		);
		$ret = $provider->beginSecondaryAuthentication( $blockedUser, [] );
		$this->assertEquals( AuthenticationResponse::FAIL, $ret->status );
	}

	public function testTestUserForCreation() {
		$provider = new CheckBlocksSecondaryAuthenticationProvider(
			[ 'blockDisablesLogin' => false ]
		);
		$provider->setLogger( new \Psr\Log\NullLogger() );
		$provider->setConfig( new \HashConfig() );
		$provider->setManager( AuthManager::singleton() );

		$unblockedUser = \User::newFromName( 'UTSysop' );
		$blockedUser = $this->getBlockedUser();

		$user = \User::newFromName( 'RandomUser' );

		$this->assertEquals(
			\StatusValue::newGood(),
			$provider->testUserForCreation( $unblockedUser, AuthManager::AUTOCREATE_SOURCE_SESSION )
		);
		$this->assertEquals(
			\StatusValue::newGood(),
			$provider->testUserForCreation( $unblockedUser, false )
		);

		$status = $provider->testUserForCreation( $blockedUser, AuthManager::AUTOCREATE_SOURCE_SESSION );
		$this->assertInstanceOf( \StatusValue::class, $status );
		$this->assertFalse( $status->isOK() );
		$this->assertTrue( $status->hasMessage( 'cantcreateaccount-text' ) );

		$status = $provider->testUserForCreation( $blockedUser, false );
		$this->assertInstanceOf( \StatusValue::class, $status );
		$this->assertFalse( $status->isOK() );
		$this->assertTrue( $status->hasMessage( 'cantcreateaccount-text' ) );
	}

	public function testRangeBlock() {
		$blockOptions = [
			'address' => '127.0.0.0/24',
			'reason' => __METHOD__,
			'by' => $this->getTestSysop()->getUser()->getId(),
			'expiry' => time() + 100500,
			'createAccount' => true,
		];
		$block = new \Block( $blockOptions );
		$block->insert();
		$scopeVariable = new \Wikimedia\ScopedCallback( [ $block, 'delete' ] );

		$user = \User::newFromName( 'UTNormalUser' );
		if ( $user->getID() == 0 ) {
			$user->addToDatabase();
			\TestUser::setPasswordForUser( $user, 'UTNormalUserPassword' );
			$user->saveSettings();
		}
		$this->setMwGlobals( [ 'wgUser' => $user ] );
		$newuser = \User::newFromName( 'RandomUser' );

		$provider = new CheckBlocksSecondaryAuthenticationProvider(
			[ 'blockDisablesLogin' => true ]
		);
		$provider->setLogger( new \Psr\Log\NullLogger() );
		$provider->setConfig( new \HashConfig() );
		$provider->setManager( AuthManager::singleton() );

		$ret = $provider->beginSecondaryAuthentication( $user, [] );
		$this->assertEquals( AuthenticationResponse::FAIL, $ret->status );

		$status = $provider->testUserForCreation( $newuser, AuthManager::AUTOCREATE_SOURCE_SESSION );
		$this->assertInstanceOf( \StatusValue::class, $status );
		$this->assertFalse( $status->isOK() );
		$this->assertTrue( $status->hasMessage( 'cantcreateaccount-range-text' ) );

		$status = $provider->testUserForCreation( $newuser, false );
		$this->assertInstanceOf( \StatusValue::class, $status );
		$this->assertFalse( $status->isOK() );
		$this->assertTrue( $status->hasMessage( 'cantcreateaccount-range-text' ) );
	}
}
