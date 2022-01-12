<?php

namespace MediaWiki\Auth;

use HashConfig;
use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Tests\Unit\Auth\AuthenticationProviderTestTrait;
use Wikimedia\TestingAccessWrapper;

/**
 * @group AuthManager
 * @group Database
 * @covers \MediaWiki\Auth\CheckBlocksSecondaryAuthenticationProvider
 */
class CheckBlocksSecondaryAuthenticationProviderTest extends \MediaWikiIntegrationTestCase {
	use AuthenticationProviderTestTrait;

	public function testConstructor() {
		$provider = new CheckBlocksSecondaryAuthenticationProvider();
		$providerPriv = TestingAccessWrapper::newFromObject( $provider );
		$config = new \HashConfig( [
			'BlockDisablesLogin' => false
		] );
		$this->initProvider( $provider, $config );
		$this->assertSame( false, $providerPriv->blockDisablesLogin );

		$provider = new CheckBlocksSecondaryAuthenticationProvider(
			[ 'blockDisablesLogin' => true ]
		);
		$providerPriv = TestingAccessWrapper::newFromObject( $provider );
		$config = new \HashConfig( [
			'BlockDisablesLogin' => false
		] );
		$this->initProvider( $provider, $config );
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
		if ( $user->getId() == 0 ) {
			$user->addToDatabase();
			\TestUser::setPasswordForUser( $user, 'UTBlockeePassword' );
			$user->saveSettings();
		}
		$blockStore = $this->getServiceContainer()->getDatabaseBlockStore();
		$oldBlock = DatabaseBlock::newFromTarget( 'UTBlockee' );
		if ( $oldBlock ) {
			// An old block will prevent our new one from saving.
			$blockStore->deleteBlock( $oldBlock );
		}
		$blockOptions = [
			'address' => 'UTBlockee',
			'user' => $user->getId(),
			'by' => $this->getTestSysop()->getUser(),
			'reason' => __METHOD__,
			'expiry' => time() + 100500,
			'createAccount' => true,
		];
		$block = new DatabaseBlock( $blockOptions );
		$blockStore->insertBlock( $block );
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

		$this->initProvider( $provider,  new HashConfig(), null, $this->getServiceContainer()->getAuthManager() );

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
		$this->assertTrue( $status->hasMessage( 'blockedtext' ) );

		$status = $provider->testUserForCreation( $blockedUser, false );
		$this->assertInstanceOf( \StatusValue::class, $status );
		$this->assertFalse( $status->isOK() );
		$this->assertTrue( $status->hasMessage( 'blockedtext' ) );
	}

	public function testPartialBlock() {
		$blockOptions = [
			'address' => '127.0.0.0/24',
			'reason' => __METHOD__,
			'by' => $this->getTestSysop()->getUser(),
			'expiry' => time() + 100500,
			'createAccount' => true,
			'sitewide' => false,
		];
		$block = new DatabaseBlock( $blockOptions );
		$this->getServiceContainer()->getDatabaseBlockStore()->insertBlock( $block );

		$user = \User::newFromName( 'UTNormalUser' );
		if ( $user->getId() == 0 ) {
			$user->addToDatabase();
			\TestUser::setPasswordForUser( $user, 'UTNormalUserPassword' );
			$user->saveSettings();
		}
		\RequestContext::getMain()->setUser( $user );
		$newuser = \User::newFromName( 'RandomUser' );

		$provider = new CheckBlocksSecondaryAuthenticationProvider(
			[ 'blockDisablesLogin' => true ]
		);
		$this->initProvider( $provider,  new HashConfig(), null, $this->getServiceContainer()->getAuthManager() );

		$ret = $provider->beginSecondaryAuthentication( $user, [] );
		$this->assertEquals( AuthenticationResponse::FAIL, $ret->status );

		$status = $provider->testUserForCreation( $newuser, AuthManager::AUTOCREATE_SOURCE_SESSION );
		$this->assertInstanceOf( \StatusValue::class, $status );
		$this->assertFalse( $status->isOK() );
		$this->assertTrue( $status->hasMessage( 'blockedtext-partial' ) );

		$status = $provider->testUserForCreation( $newuser, false );
		$this->assertInstanceOf( \StatusValue::class, $status );
		$this->assertFalse( $status->isOK() );
		$this->assertTrue( $status->hasMessage( 'blockedtext-partial' ) );
	}
}
