<?php

/**
 * @group AuthManager
 * @group Database
 * @covers LegacyHookPreAuthenticationProvider
 * @uses AbstractAuthenticationProvider
 * @uses AuthManager
 */
class LegacyHookPreAuthenticationProviderTest extends MediaWikiTestCase {
	/**
	 * Get an instance of the provider
	 * @return LegacyHookPreAuthenticationProvider
	 */
	protected function getProvider() {
		$request = $this->getMock( 'FauxRequest', array( 'getIP' ) );
		$request->method( 'getIP' )->willReturn( '127.0.0.42' );

		$manager = new AuthManager( $request, ConfigFactory::getDefaultInstance()->makeConfig( 'main' ) );

		$provider = new LegacyHookPreAuthenticationProvider();
		$provider->setManager( $manager );
		$provider->setLogger( new Psr\Log\NullLogger() );
		$provider->setConfig( new HashConfig( array(
			'PasswordAttemptThrottle' => array( 'count' => 23, 'seconds' => 42 ),
		) ) );
		return $provider;
	}

	/**
	 * Sets a mock on a hook
	 * @param string $hook
	 * @param object $expect From $this->once(), $this->never(), etc.
	 * @return object $mock->expects( $expect )->method( ... ).
	 */
	protected function hook( $hook, $expect ) {
		$mock = $this->getMock( __CLASS__, array( "on$hook" ) );
		$this->mergeMwGlobalArrayValue( 'wgHooks', array(
			$hook => array( $mock ),
		) );
		return $mock->expects( $expect )->method( "on$hook" );
	}

	/**
	 * Unsets a hook
	 * @param string $hook
	 */
	protected function unhook( $hook ) {
		$this->mergeMwGlobalArrayValue( 'wgHooks', array(
			$hook => array(),
		) );
	}

	// Stubs for hooks taking reference parameters
	public function onLoginUserMigrated( $user, &$msg ) {}
	public function onAbortLogin( $user, $password, &$abort, &$msg ) {}
	public function onAbortNewAccount( $user, &$abortError, &$abortStatus ) {}
	public function onAbortAutoAccount( $user, &$abortError ) {}

	/**
	 * @dataProvider provideGetAuthenticationRequestTypes
	 * @param string $which
	 * @param array $response
	 */
	public function testGetAuthenticationRequestTypes( $which, $response ) {
		$provider = $this->getProvider();
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

	/**
	 * @dataProvider provideTestForAuthentication
	 * @uses PasswordAuthenticationRequest
	 * @param string $label
	 * @param string|null $username
	 * @param string|null $password
	 * @param string|null $msgForLoginUserMigrated
	 * @param int|null $abortForAbortLogin
	 * @param string|null $msgForAbortLogin
	 * @param string|null $failMsg
	 * @param array $failParams
	 */
	public function testTestForAuthentication(
		$label, $username, $password,
		$msgForLoginUserMigrated, $abortForAbortLogin, $msgForAbortLogin,
		$failMsg, $failParams = array()
	) {
		$that = $this;
		$reqs = array();
		if ( $username === null ) {
			$this->hook( 'LoginUserMigrated', $this->never() );
			$this->hook( 'AbortLogin', $this->never() );
		} else {
			if ( $password === null ) {
				$req = $this->getMockForAbstractClass( 'AuthenticationRequest' );
			} else {
				$req = new PasswordAuthenticationRequest();
				$req->password = $password;
			}
			$req->username = $username;
			$reqs[get_class( $req )] = $req;

			$h = $this->hook( 'LoginUserMigrated', $this->once() );
			if ( $msgForLoginUserMigrated !== null ) {
				$h->will( $this->returnCallback(
					function ( $user, &$msg ) use ( $that, $username, $msgForLoginUserMigrated ) {
						$that->assertInstanceOf( 'User', $user );
						$that->assertSame( $username, $user->getName() );
						$msg = $msgForLoginUserMigrated;
						return false;
					}
				) );
				$this->hook( 'AbortLogin', $this->never() );
			} else {
				$h->will( $this->returnCallback(
					function ( $user, &$msg ) use ( $that, $username ) {
						$that->assertInstanceOf( 'User', $user );
						$that->assertSame( $username, $user->getName() );
						return true;
					}
				) );
				$h2 = $this->hook( 'AbortLogin', $this->once() );
				if ( $abortForAbortLogin !== null ) {
					$h2->will( $this->returnCallback(
						function ( $user, $pass, &$abort, &$msg )
							use ( $that, $username, $password, $abortForAbortLogin, $msgForAbortLogin )
						{
							$that->assertInstanceOf( 'User', $user );
							$that->assertSame( $username, $user->getName() );
							if ( $password !== null ) {
								$that->assertSame( $password, $pass );
							} else {
								$that->assertInternalType( 'string', $pass );
							}
							$abort = $abortForAbortLogin;
							$msg = $msgForAbortLogin;
							return false;
						}
					) );
				} else {
					$h2->will( $this->returnCallback(
						function ( $user, $pass, &$abort, &$msg ) use ( $that, $username, $password ) {
							$that->assertInstanceOf( 'User', $user );
							$that->assertSame( $username, $user->getName() );
							if ( $password !== null ) {
								$that->assertSame( $password, $pass );
							} else {
								$that->assertInternalType( 'string', $pass );
							}
							return true;
						}
					) );
				}
			}
		}
		unset( $h, $h2 );

		$status = $this->getProvider()->testForAuthentication( $reqs );

		$this->unhook( 'LoginUserMigrated' );
		$this->unhook( 'AbortLogin' );

		if ( $failMsg === null ) {
			$this->assertEquals( StatusValue::newGood(), $status, "$label should succeed" );
		} else {
			$this->assertInstanceOf( 'StatusValue', $status, "$label should fail (type)" );
			$this->assertFalse( $status->isOk(), "$label should fail (ok)" );
			$errors = $status->getErrors();
			$this->assertEquals( $failMsg, $errors[0]['message'], "$label should fail (message)" );
			$this->assertEquals( $failParams, $errors[0]['params'], "$label should fail (params)" );
		}
	}

	public static function provideTestForAuthentication() {
		return array(
			array(
				'No valid requests',
				null, null, null, null, null, null
			),
			array(
				'No hook errors',
				'User', 'PaSsWoRd', null, null, null, null
			),
			array(
				'No hook errors, no password',
				'User', null, null, null, null, null
			),
			array(
				'LoginUserMigrated no message',
				'User', 'PaSsWoRd', false, null, null, 'login-migrated-generic'
			),
			array(
				'LoginUserMigrated with message',
				'User', 'PaSsWoRd', 'LUM-abort', null, null, 'LUM-abort'
			),
			array(
				'LoginUserMigrated with message and params',
				'User', 'PaSsWoRd', array( 'LUM-abort', 'foo' ), null, null, 'LUM-abort', array( 'foo' )
			),
			array(
				'AbortLogin, SUCCESS',
				'User', 'PaSsWoRd', null, LoginForm::SUCCESS, null, null
			),
			array(
				'AbortLogin, NEED_TOKEN, no message',
				'User', 'PaSsWoRd', null, LoginForm::NEED_TOKEN, null, 'nocookiesforlogin'
			),
			array(
				'AbortLogin, NEED_TOKEN, with message',
				'User', 'PaSsWoRd', null, LoginForm::NEED_TOKEN, 'needtoken', 'needtoken'
			),
			array(
				'AbortLogin, WRONG_TOKEN, no message',
				'User', 'PaSsWoRd', null, LoginForm::WRONG_TOKEN, null, 'sessionfailure'
			),
			array(
				'AbortLogin, WRONG_TOKEN, with message',
				'User', 'PaSsWoRd', null, LoginForm::WRONG_TOKEN, 'wrongtoken', 'wrongtoken'
			),
			array(
				'AbortLogin, ILLEGAL, no message',
				'User', 'PaSsWoRd', null, LoginForm::ILLEGAL, null, 'noname'
			),
			array(
				'AbortLogin, ILLEGAL, with message',
				'User', 'PaSsWoRd', null, LoginForm::ILLEGAL, 'badname', 'badname'
			),
			array(
				'AbortLogin, NO_NAME, no message',
				'User', 'PaSsWoRd', null, LoginForm::NO_NAME, null, 'noname'
			),
			array(
				'AbortLogin, NO_NAME, with message',
				'User', 'PaSsWoRd', null, LoginForm::NO_NAME, 'badname', 'badname'
			),
			array(
				'AbortLogin, WRONG_PASS, no message',
				'User', 'PaSsWoRd', null, LoginForm::WRONG_PASS, null, 'wrongpassword'
			),
			array(
				'AbortLogin, WRONG_PASS, with message',
				'User', 'PaSsWoRd', null, LoginForm::WRONG_PASS, 'badpass', 'badpass'
			),
			array(
				'AbortLogin, WRONG_PLUGIN_PASS, no message',
				'User', 'PaSsWoRd', null, LoginForm::WRONG_PLUGIN_PASS, null, 'wrongpassword'
			),
			array(
				'AbortLogin, WRONG_PLUGIN_PASS, with message',
				'User', 'PaSsWoRd', null, LoginForm::WRONG_PLUGIN_PASS, 'badpass', 'badpass'
			),
			array(
				'AbortLogin, NOT_EXISTS, no message',
				"User'", 'A', null, LoginForm::NOT_EXISTS, null, 'nosuchusershort', array( 'User&#39;' )
			),
			array(
				'AbortLogin, NOT_EXISTS, with message',
				"User'", 'A', null, LoginForm::NOT_EXISTS, 'badname', 'badname', array( 'User&#39;' )
			),
			array(
				'AbortLogin, EMPTY_PASS, no message',
				'User', 'PaSsWoRd', null, LoginForm::EMPTY_PASS, null, 'wrongpasswordempty'
			),
			array(
				'AbortLogin, EMPTY_PASS, with message',
				'User', 'PaSsWoRd', null, LoginForm::EMPTY_PASS, 'badpass', 'badpass'
			),
			array(
				'AbortLogin, RESET_PASS, no message',
				'User', 'PaSsWoRd', null, LoginForm::RESET_PASS, null, 'resetpass_announce'
			),
			array(
				'AbortLogin, RESET_PASS, with message',
				'User', 'PaSsWoRd', null, LoginForm::RESET_PASS, 'resetpass', 'resetpass'
			),
			array(
				'AbortLogin, THROTTLED, no message',
				'User', 'PaSsWoRd', null, LoginForm::THROTTLED, null, 'login-throttled',
				array( Message::durationParam( 42 ) )
			),
			array(
				'AbortLogin, THROTTLED, with message',
				'User', 'PaSsWoRd', null, LoginForm::THROTTLED, 't', 't',
				array( Message::durationParam( 42 ) )
			),
			array(
				'AbortLogin, USER_BLOCKED, no message',
				"User'", 'P', null, LoginForm::USER_BLOCKED, null, 'login-userblocked', array( 'User&#39;' )
			),
			array(
				'AbortLogin, USER_BLOCKED, with message',
				"User'", 'P', null, LoginForm::USER_BLOCKED, 'blocked', 'blocked', array( 'User&#39;' )
			),
			array(
				'AbortLogin, ABORTED, no message',
				"User'", 'P', null, LoginForm::ABORTED, null, 'login-abort-generic', array( 'User&#39;' )
			),
			array(
				'AbortLogin, ABORTED, with message',
				"User'", 'P', null, LoginForm::ABORTED, 'aborted', 'aborted', array( 'User&#39;' )
			),
			array(
				'AbortLogin, USER_MIGRATED, no message',
				'User', 'P', null, LoginForm::USER_MIGRATED, null, 'login-migrated-generic'
			),
			array(
				'AbortLogin, USER_MIGRATED, with message',
				'User', 'P', null, LoginForm::USER_MIGRATED, 'migrated', 'migrated'
			),
			array(
				'AbortLogin, USER_MIGRATED, with message and params',
				'User', 'P', null, LoginForm::USER_MIGRATED, array( 'migrated', 'foo' ), 'migrated', array( 'foo' )
			),
		);
	}

	/**
	 * @dataProvider provideTestForAccountCreation
	 * @uses PasswordAuthenticationRequest
	 * @param string $label
	 * @param string $msg
	 * @param Status|null $status
	 * @param StatusValue Result
	 */
	public function testTestForAccountCreation( $label, $msg, $status, $result ) {
		$that = $this;
		$this->hook( 'AbortNewAccount', $this->once() )
			->will( $this->returnCallback( function ( $user, &$error, &$abortStatus )
				use ( $that, $msg, $status )
			{
				$that->assertInstanceOf( 'User', $user );
				$that->assertSame( 'User', $user->getName() );
				$error = $msg;
				$abortStatus = $status;
				return $error === null && $status === null;
			} ) );

		$user = User::newFromName( 'User' );
		$creator = User::newFromName( 'UTSysop' );
		$ret = $this->getProvider()->testForAccountCreation( $user, $creator, array() );

		$this->unhook( 'AbortNewAccount' );

		$this->assertEquals( $result, $ret );
	}

	public static function provideTestForAccountCreation() {
		return array(
			array(
				'No hook errors',
				null, null, StatusValue::newGood()
			),
			array(
				'AbortNewAccount, old style',
				'foobar', null, StatusValue::newFatal( 'createaccount-hook-aborted', 'foobar' )
			),
			array(
				'AbortNewAccount, new style',
				'foobar',
				Status::newFatal( 'aborted!', 'param' ),
				StatusValue::newFatal( 'aborted!', 'param' )
			),
		);
	}

	/**
	 * @dataProvider provideTestForAutoCreation
	 * @param string $label
	 * @param string|null $error
	 * @param string|null $failMsg
	 */
	public function testTestForAutoCreation( $label, $error, $failMsg ) {
		$that = $this;
		$this->hook( 'AbortAutoAccount', $this->once() )
			->will( $this->returnCallback( function ( $user, &$abortError ) use ( $that, $error ) {
				$that->assertInstanceOf( 'User', $user );
				$that->assertSame( 'UTSysop', $user->getName() );
				$abortError = $error;
				return $error === null;
			} ) );

		$status = $this->getProvider()->testForAutoCreation( User::newFromName( 'UTSysop' ) );

		$this->unhook( 'AbortAutoAccount' );

		if ( $failMsg === null ) {
			$this->assertEquals( StatusValue::newGood(), $status, "$label should succeed" );
		} else {
			$this->assertInstanceOf( 'StatusValue', $status, "$label should fail (type)" );
			$this->assertFalse( $status->isOk(), "$label should fail (ok)" );
			$errors = $status->getErrors();
			$this->assertEquals( $failMsg, $errors[0]['message'], "$label should fail (message)" );
		}
	}

	public static function provideTestForAutoCreation() {
		return array(
			array( 'Success', null, null ),
			array( 'Fail, no message', false, 'login-abort-generic' ),
			array( 'Fail, with message', 'fail', 'fail' ),
		);
	}
}
