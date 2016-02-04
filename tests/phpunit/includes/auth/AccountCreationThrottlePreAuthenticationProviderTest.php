<?php

namespace MediaWiki\Auth;

/**
 * @group AuthManager
 * @group Database
 * @covers MediaWiki\Auth\AccountCreationThrottlePreAuthenticationProvider
 */
class AccountCreationThrottlePreAuthenticationProviderTest extends \MediaWikiTestCase {
	public function testConstructor() {
		$provider = new AccountCreationThrottlePreAuthenticationProvider();
		$providerPriv = \TestingAccessWrapper::newFromObject( $provider );
		$config = new \HashConfig( array(
			'AccountCreationThrottle' => 123
		) );
		$provider->setConfig( $config );
		$this->assertSame( 123, $providerPriv->throttle );

		$provider = new AccountCreationThrottlePreAuthenticationProvider( array( 'throttle' => 42 ) );
		$providerPriv = \TestingAccessWrapper::newFromObject( $provider );
		$config = new \HashConfig( array(
			'AccountCreationThrottle' => 123
		) );
		$provider->setConfig( $config );
		$this->assertSame( 42, $providerPriv->throttle );
	}

	/**
	 * @dataProvider provideTestForAccountCreation
	 * @param string $creatorname
	 * @param bool $succeed
	 * @param bool $hook
	 */
	public function testTestForAccountCreation( $creatorname, $succeed, $hook ) {
		$provider = new AccountCreationThrottlePreAuthenticationProvider(
			array( 'throttle' => 2, 'cache' => new \HashBagOStuff() )
		);
		$provider->setLogger( new \Psr\Log\NullLogger() );
		$provider->setConfig( new \HashConfig() );
		$provider->setManager( AuthManager::singleton() );

		$user = \User::newFromName( 'RandomUser' );
		$creator = \User::newFromName( $creatorname );
		if ( $hook ) {
			$mock = $this->getMock( 'stdClass', array( 'onExemptFromAccountCreationThrottle' ) );
			$mock->expects( $this->any() )->method( 'onExemptFromAccountCreationThrottle' )
				->will( $this->returnValue( false ) );
			$this->mergeMwGlobalArrayValue( 'wgHooks', array(
				'ExemptFromAccountCreationThrottle' => array( $mock ),
			) );
		}

		$this->assertEquals(
			\StatusValue::newGood(),
			$provider->testForAccountCreation( $user, $creator, array() ),
			'attempt #1'
		);
		$this->assertEquals(
			\StatusValue::newGood(),
			$provider->testForAccountCreation( $user, $creator, array() ),
			'attempt #2'
		);
		$this->assertEquals(
			$succeed ? \StatusValue::newGood() : \StatusValue::newFatal( 'acct_creation_throttle_hit', 2 ),
			$provider->testForAccountCreation( $user, $creator, array() ),
			'attempt #3'
		);
	}

	public static function provideTestForAccountCreation() {
		return array(
			'Normal user' => array( 'NormalUser', false, false ),
			'Sysop' => array( 'UTSysop', true, false ),
			'Normal user with hook' => array( 'NormalUser', true, true ),
		);
	}

}
