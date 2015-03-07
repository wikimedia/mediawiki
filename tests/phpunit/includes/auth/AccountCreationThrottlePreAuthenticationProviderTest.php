<?php

/**
 * @group AuthManager
 * @group Database
 * @covers AccountCreationThrottlePreAuthenticationProvider
 * @uses AbstractAuthenticationProvider
 */
class AccountCreationThrottlePreAuthenticationProviderTest extends MediaWikiTestCase {
	/**
	 * @uses AuthManager
	 */
	public function testConstructor() {
		$provider = $this->getMockForAbstractClass( 'AccountCreationThrottlePreAuthenticationProvider' );
		$providerPriv = TestingAccessWrapper::newFromObject( $provider );
		$config = new HashConfig( array(
			'AccountCreationThrottle' => 123
		) );
		$provider->setConfig( $config );
		$this->assertSame( 123, $providerPriv->throttle );

		$provider = $this->getMockForAbstractClass(
			'AccountCreationThrottlePreAuthenticationProvider', array( array( 'throttle' => 42 ) )
		);
		$providerPriv = TestingAccessWrapper::newFromObject( $provider );
		$config = new HashConfig( array(
			'AccountCreationThrottle' => 123
		) );
		$provider->setConfig( $config );
		$this->assertSame( 42, $providerPriv->throttle );
	}

	/**
	 * @dataProvider provideGetAuthenticationRequestTypes
	 * @param string $which
	 * @param array $response
	 */
	public function testGetAuthenticationRequestTypes( $which, $response ) {
		$provider = $this->getMockForAbstractClass( 'AccountCreationThrottlePreAuthenticationProvider' );

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

	public function testTestForAuthentication() {
		$provider = $this->getMockForAbstractClass( 'AccountCreationThrottlePreAuthenticationProvider' );
		$this->assertEquals(
			StatusValue::newGood(),
			$provider->testForAuthentication( array() )
		);
	}

	/**
	 * @dataProvider provideTestForAccountCreation
	 * @uses AuthManager
	 * @param string $label
	 * @param string $creatorname
	 * @param bool $succeed
	 * @param bool $hook
	 */
	public function testTestForAccountCreation( $label, $creatorname, $succeed, $hook ) {
		$provider = $this->getMockForAbstractClass(
			'AccountCreationThrottlePreAuthenticationProvider',
			array(
				array( 'throttle' => 2, 'cache' => new HashBagOStuff() )
			)
		);
		$provider->setLogger( new Psr\Log\NullLogger() );
		$provider->setConfig( new HashConfig() );
		$provider->setManager( AuthManager::singleton() );

		$user = User::newFromName( 'RandomUser' );
		$creator = User::newFromName( $creatorname );
		if ( $hook ) {
			$mock = $this->getMock( 'stdClass', array( 'onExemptFromAccountCreationThrottle' ) );
			$mock->method( 'onExemptFromAccountCreationThrottle' )->willReturn( false );
			$this->mergeMwGlobalArrayValue( 'wgHooks', array(
				'ExemptFromAccountCreationThrottle' => array( $mock ),
			) );
		}

		$this->assertEquals(
			StatusValue::newGood(),
			$provider->testForAccountCreation( $user, $creator, array() ),
			$label . ', attempt #1'
		);
		$this->assertEquals(
			StatusValue::newGood(),
			$provider->testForAccountCreation( $user, $creator, array() ),
			$label . ', attempt #2'
		);
		$this->assertEquals(
			$succeed ? StatusValue::newGood() : StatusValue::newFatal( 'acct_creation_throttle_hit', 2 ),
			$provider->testForAccountCreation( $user, $creator, array() ),
			$label . ', attempt #3'
		);
	}

	public static function provideTestForAccountCreation() {
		return array(
			array( 'Normal user', 'NormalUser', false, false ),
			array( 'Sysop', 'UTSysop', true, false ),
			array( 'Normal user with hook', 'NormalUser', true, true ),
		);
	}

	public function testTestForAutoCreation() {
		$provider = $this->getMockForAbstractClass( 'AccountCreationThrottlePreAuthenticationProvider' );
		$this->assertEquals(
			StatusValue::newGood(),
			$provider->testForAutoCreation( User::newFromName( 'UTSysop' ) )
		);
	}
}
