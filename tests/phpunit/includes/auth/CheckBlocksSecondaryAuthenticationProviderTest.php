<?php

namespace MediaWiki\Auth;

/**
 * @group AuthManager
 * @group Database
 * @covers MediaWiki\Auth\CheckBlocksSecondaryAuthenticationProvider
 */
class CheckBlocksSecondaryAuthenticationProviderTest extends \MediaWikiTestCase {

	public function testConstructor() {
		$provider = new CheckBlocksSecondaryAuthenticationProvider();
		$providerPriv = \TestingAccessWrapper::newFromObject( $provider );
		$config = new \HashConfig( array(
			'BlockDisablesLogin' => false
		) );
		$provider->setConfig( $config );
		$this->assertSame( false, $providerPriv->blockDisablesLogin );

		$provider = new CheckBlocksSecondaryAuthenticationProvider(
			array( 'blockDisablesLogin' => true )
		);
		$providerPriv = \TestingAccessWrapper::newFromObject( $provider );
		$config = new \HashConfig( array(
			'BlockDisablesLogin' => false
		) );
		$provider->setConfig( $config );
		$this->assertSame( true, $providerPriv->blockDisablesLogin );
	}

	public function testBasics() {
		$provider = new CheckBlocksSecondaryAuthenticationProvider();

		$this->assertEquals(
			AuthenticationResponse::newAbstain(),
			$provider->beginSecondaryAccountCreation( \User::newFromName( 'UTSysop' ), array() )
		);
	}

	/**
	 * @dataProvider provideGetAuthenticationRequestTypes
	 * @param string $action
	 * @param array $response
	 */
	public function testGetAuthenticationRequestTypes( $action, $response ) {
		$provider = new CheckBlocksSecondaryAuthenticationProvider();

		$this->assertSame( $response, $provider->getAuthenticationRequestTypes( $action ) );
	}

	public static function provideGetAuthenticationRequestTypes() {
		return array(
			array( AuthManager::ACTION_LOGIN, array() ),
			array( AuthManager::ACTION_CREATE, array() ),
			array( AuthManager::ACTION_CHANGE, array() ),
			array( AuthManager::ACTION_ALL, array() ),
			array( AuthManager::ACTION_LOGIN_CONTINUE, array() ),
			array( AuthManager::ACTION_CREATE_CONTINUE, array() ),
		);
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
		$blockOptions = array(
			'address' => 'UTBlockee',
			'user' => $user->getID(),
			'reason' => __METHOD__,
			'expiry' => time() + 100500,
			'createAccount' => true,
		);
		$block = new \Block( $blockOptions );
		$block->insert();
		return $user;
	}

	public function testBeginSecondaryAuthentication() {
		$unblockedUser = \User::newFromName( 'UTSysop' );
		$blockedUser = $this->getBlockedUser();

		$provider = new CheckBlocksSecondaryAuthenticationProvider(
			array( 'blockDisablesLogin' => false )
		);
		$this->assertEquals(
			AuthenticationResponse::newAbstain(),
			$provider->beginSecondaryAuthentication( $unblockedUser, array() )
		);
		$this->assertEquals(
			AuthenticationResponse::newAbstain(),
			$provider->beginSecondaryAuthentication( $blockedUser, array() )
		);

		$provider = new CheckBlocksSecondaryAuthenticationProvider(
			array( 'blockDisablesLogin' => true )
		);
		$this->assertEquals(
			AuthenticationResponse::newPass(),
			$provider->beginSecondaryAuthentication( $unblockedUser, array() )
		);
		$ret = $provider->beginSecondaryAuthentication( $blockedUser, array() );
		$this->assertEquals( AuthenticationResponse::FAIL, $ret->status );
	}

	public function testTestForAccountCreation() {
		$provider = new CheckBlocksSecondaryAuthenticationProvider(
			array( 'blockDisablesLogin' => false )
		);
		$provider->setLogger( new \Psr\Log\NullLogger() );
		$provider->setConfig( new \HashConfig() );
		$provider->setManager( AuthManager::singleton() );

		$unblockedUser = \User::newFromName( 'UTSysop' );
		$blockedUser = $this->getBlockedUser();

		$user = \User::newFromName( 'RandomUser' );

		$this->assertEquals(
			\StatusValue::newGood(),
			$provider->testForAccountCreation( $user, $unblockedUser, array() )
		);

		$status = $provider->testForAccountCreation( $user, $blockedUser, array() );
		$this->assertInstanceOf( 'StatusValue', $status );
		$this->assertFalse( $status->isOK() );
	}

	public function testTestForAutoCreation() {
		$provider = new CheckBlocksSecondaryAuthenticationProvider(
			array( 'blockDisablesLogin' => false )
		);
		$provider->setLogger( new \Psr\Log\NullLogger() );
		$provider->setConfig( new \HashConfig() );
		$provider->setManager( AuthManager::singleton() );

		$unblockedUser = \User::newFromName( 'UTSysop' );
		$blockedUser = $this->getBlockedUser();

		$user = \User::newFromName( 'RandomUser' );

		$this->assertEquals(
			\StatusValue::newGood(),
			$provider->testForAutoCreation( $unblockedUser )
		);

		$status = $provider->testForAutoCreation( $blockedUser );
		$this->assertInstanceOf( 'StatusValue', $status );
		$this->assertFalse( $status->isOK() );
	}

	public function testRangeBlock() {
		$blockOptions = array(
			'address' => '127.0.0.0/24',
			'reason' => __METHOD__,
			'expiry' => time() + 100500,
			'createAccount' => true,
		);
		$block = new \Block( $blockOptions );
		$block->insert();
		$scopeVariable = new \ScopedCallback( array( $block, 'delete' ) );

		$user = \User::newFromName( 'UTNormalUser' );
		if ( $user->getID() == 0 ) {
			$user->addToDatabase();
			\TestUser::setPasswordForUser( $user, 'UTNormalUserPassword' );
			$user->saveSettings();
		}
		$this->setMwGlobals( array( 'wgUser' => $user ) );
		$newuser = \User::newFromName( 'RandomUser' );

		$provider = new CheckBlocksSecondaryAuthenticationProvider(
			array( 'blockDisablesLogin' => true )
		);
		$provider->setLogger( new \Psr\Log\NullLogger() );
		$provider->setConfig( new \HashConfig() );
		$provider->setManager( AuthManager::singleton() );

		$ret = $provider->beginSecondaryAuthentication( $user, array() );
		$this->assertEquals( AuthenticationResponse::FAIL, $ret->status );

		$status = $provider->testForAccountCreation( $newuser, $user, array() );
		$this->assertInstanceOf( 'StatusValue', $status );
		$this->assertFalse( $status->isOK() );

		$status = $provider->testForAutoCreation( $newuser );
		$this->assertInstanceOf( 'StatusValue', $status );
		$this->assertFalse( $status->isOK() );
	}
}
