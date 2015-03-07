<?php

/**
 * @group AuthManager
 * @group Database
 * @covers CheckBlocksSecondaryAuthenticationProvider
 * @uses AbstractAuthenticationProvider
 * @uses AuthenticationResponse
 */
class CheckBlocksSecondaryAuthenticationProviderTest extends MediaWikiTestCase {

	public function testConstructor() {
		$provider = new CheckBlocksSecondaryAuthenticationProvider();
		$providerPriv = TestingAccessWrapper::newFromObject( $provider );
		$config = new HashConfig( array(
			'BlockDisablesLogin' => false
		) );
		$provider->setConfig( $config );
		$this->assertSame( false, $providerPriv->blockDisablesLogin );

		$provider = new CheckBlocksSecondaryAuthenticationProvider( array( 'blockDisablesLogin' => true ) );
		$providerPriv = TestingAccessWrapper::newFromObject( $provider );
		$config = new HashConfig( array(
			'BlockDisablesLogin' => false
		) );
		$provider->setConfig( $config );
		$this->assertSame( true, $providerPriv->blockDisablesLogin );
	}

	public function testBasics() {
		$provider = new CheckBlocksSecondaryAuthenticationProvider();
		$this->assertSame( true, $provider->providerAllowPropertyChange( 'foo' ) );
		$this->assertEquals(
			StatusValue::newGood( 'ignored' ),
			$provider->providerCanChangeAuthenticationData(
				$this->getMockForAbstractClass( 'AuthenticationRequest' )
			)
		);

		$this->assertEquals(
			AuthenticationResponse::newAbstain(),
			$provider->beginSecondaryAccountCreation( User::newFromName( 'UTSysop' ), array() )
		);
		$this->assertEquals(
			AuthenticationResponse::newAbstain(),
			$provider->continueSecondaryAccountCreation( User::newFromName( 'UTSysop' ), array() )
		);

		$provider->autoCreatedAccount( User::newFromName( 'UTSysop' ) );
	}

	/**
	 * @dataProvider provideGetAuthenticationRequestTypes
	 * @param string $which
	 * @param array $response
	 */
	public function testGetAuthenticationRequestTypes( $which, $response ) {
		$provider = new CheckBlocksSecondaryAuthenticationProvider();

		$this->assertSame( $response, $provider->getAuthenticationRequestTypes( $which ) );
	}

	public static function provideGetAuthenticationRequestTypes() {
		return array(
			array( 'login', array() ),
			array( 'create', array() ),
			array( 'change', array() ),
			array( 'all', array() ),
			array( 'login-continue', array() ),
			array( 'create-continue', array() ),
		);
	}

	private function getBlockedUser() {
		$user = User::newFromName( 'UTBlockee' );
		if ( $user->getID() == 0 ) {
			$user->addToDatabase();
			TestUser::setPasswordForUser( $user, 'UTBlockeePassword' );
			$user->saveSettings();
		}
		$oldBlock = Block::newFromTarget( 'UTBlockee' );
		if ( $oldBlock ) {
			// An old block will prevent our new one from saving.
			$oldBlock->delete();
		}
		$blockOptions = array(
			'address' => 'UTBlockee',
			'user' => $user->getID(),
			'reason' => 'Parce que',
			'expiry' => time() + 100500,
			'createAccount' => true,
		);
		$block = new Block( $blockOptions );
		$block->insert();
		return $user;
	}

	public function testBeginSecondaryAuthentication() {
		$unblockedUser = User::newFromName( 'UTSysop' );
		$blockedUser = $this->getBlockedUser();

		$provider = new CheckBlocksSecondaryAuthenticationProvider( array( 'blockDisablesLogin' => false ) );
		$this->assertEquals(
			AuthenticationResponse::newAbstain(),
			$provider->beginSecondaryAuthentication( $unblockedUser, array() )
		);
		$this->assertEquals(
			AuthenticationResponse::newAbstain(),
			$provider->beginSecondaryAuthentication( $blockedUser, array() )
		);

		$provider = new CheckBlocksSecondaryAuthenticationProvider( array( 'blockDisablesLogin' => true ) );
		$this->assertEquals(
			AuthenticationResponse::newPass(),
			$provider->beginSecondaryAuthentication( $unblockedUser, array() )
		);
		$ret = $provider->beginSecondaryAuthentication( $blockedUser, array() );
		$this->assertEquals( AuthenticationResponse::FAIL, $ret->status );
	}

	/**
	 * @expectedException BadMethodCallException
	 * @expectedExceptionMessage CheckBlocksSecondaryAuthenticationProvider::continueSecondaryAuthentication should never be reached.
	 */
	public function testContinueSecondaryAuthentication() {
		$provider = new CheckBlocksSecondaryAuthenticationProvider( array( 'blockDisablesLogin' => true ) );
		$provider->continueSecondaryAuthentication( User::newFromName( 'UTSysop' ), array() );
	}

	/**
	 * @uses AuthManager
	 */
	public function testTestForAccountCreation() {
		$provider = new CheckBlocksSecondaryAuthenticationProvider( array( 'blockDisablesLogin' => false ) );
		$provider->setLogger( new Psr\Log\NullLogger() );
		$provider->setConfig( new HashConfig() );
		$provider->setManager( AuthManager::singleton() );

		$unblockedUser = User::newFromName( 'UTSysop' );
		$blockedUser = $this->getBlockedUser();

		$user = User::newFromName( 'RandomUser' );

		$this->assertEquals(
			StatusValue::newGood(),
			$provider->testForAccountCreation( $user, $unblockedUser, array() )
		);

		$status = $provider->testForAccountCreation( $user, $blockedUser, array() );
		$this->assertInstanceOf( 'StatusValue', $status );
		$this->assertFalse( $status->isOK() );
	}

	/**
	 * @uses AuthManager
	 */
	public function testTestForAutoCreation() {
		$provider = new CheckBlocksSecondaryAuthenticationProvider( array( 'blockDisablesLogin' => false ) );
		$provider->setLogger( new Psr\Log\NullLogger() );
		$provider->setConfig( new HashConfig() );
		$provider->setManager( AuthManager::singleton() );

		$unblockedUser = User::newFromName( 'UTSysop' );
		$blockedUser = $this->getBlockedUser();

		$user = User::newFromName( 'RandomUser' );

		$this->assertEquals(
			StatusValue::newGood(),
			$provider->testForAutoCreation( $unblockedUser )
		);

		$status = $provider->testForAutoCreation( $blockedUser );
		$this->assertInstanceOf( 'StatusValue', $status );
		$this->assertFalse( $status->isOK() );
	}
}
