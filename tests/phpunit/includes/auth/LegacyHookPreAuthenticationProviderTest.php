<?php

namespace MediaWiki\Auth;

/**
 * @group AuthManager
 * @group Database
 * @covers MediaWiki\Auth\LegacyHookPreAuthenticationProvider
 */
class LegacyHookPreAuthenticationProviderTest extends \MediaWikiTestCase {
	/**
	 * Get an instance of the provider
	 * @return LegacyHookPreAuthenticationProvider
	 */
	protected function getProvider() {
		$request = $this->getMock( 'FauxRequest', array( 'getIP' ) );
		$request->expects( $this->any() )->method( 'getIP' )->will( $this->returnValue( '127.0.0.42' ) );

		$manager = new AuthManager(
			$request, \ConfigFactory::getDefaultInstance()->makeConfig( 'main' )
		);

		$provider = new LegacyHookPreAuthenticationProvider();
		$provider->setManager( $manager );
		$provider->setLogger( new \Psr\Log\NullLogger() );
		$provider->setConfig( new \HashConfig( array(
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
	public function onLoginUserMigrated( $user, &$msg ) {
	}
	public function onAbortLogin( $user, $password, &$abort, &$msg ) {
	}
	public function onAbortNewAccount( $user, &$abortError, &$abortStatus ) {
	}
	public function onAbortAutoAccount( $user, &$abortError ) {
	}

	/**
	 * @dataProvider provideTestForAuthentication
	 * @param string|null $username
	 * @param string|null $password
	 * @param string|null $msgForLoginUserMigrated
	 * @param int|null $abortForAbortLogin
	 * @param string|null $msgForAbortLogin
	 * @param string|null $failMsg
	 * @param array $failParams
	 */
	public function testTestForAuthentication(
		$username, $password,
		$msgForLoginUserMigrated, $abortForAbortLogin, $msgForAbortLogin,
		$failMsg, $failParams = array()
	) {
		$reqs = array();
		if ( $username === null ) {
			$this->hook( 'LoginUserMigrated', $this->never() );
			$this->hook( 'AbortLogin', $this->never() );
		} else {
			if ( $password === null ) {
				$req = $this->getMockForAbstractClass( 'MediaWiki\\Auth\\AuthenticationRequest' );
			} else {
				$req = new PasswordAuthenticationRequest();
				$req->password = $password;
			}
			$req->username = $username;
			$reqs[get_class( $req )] = $req;

			$h = $this->hook( 'LoginUserMigrated', $this->once() );
			if ( $msgForLoginUserMigrated !== null ) {
				$h->will( $this->returnCallback(
					function ( $user, &$msg ) use ( $username, $msgForLoginUserMigrated ) {
						$this->assertInstanceOf( 'User', $user );
						$this->assertSame( $username, $user->getName() );
						$msg = $msgForLoginUserMigrated;
						return false;
					}
				) );
				$this->hook( 'AbortLogin', $this->never() );
			} else {
				$h->will( $this->returnCallback(
					function ( $user, &$msg ) use ( $username ) {
						$this->assertInstanceOf( 'User', $user );
						$this->assertSame( $username, $user->getName() );
						return true;
					}
				) );
				$h2 = $this->hook( 'AbortLogin', $this->once() );
				if ( $abortForAbortLogin !== null ) {
					$h2->will( $this->returnCallback(
						function ( $user, $pass, &$abort, &$msg )
							use ( $username, $password, $abortForAbortLogin, $msgForAbortLogin )
						{
							$this->assertInstanceOf( 'User', $user );
							$this->assertSame( $username, $user->getName() );
							if ( $password !== null ) {
								$this->assertSame( $password, $pass );
							} else {
								$this->assertInternalType( 'string', $pass );
							}
							$abort = $abortForAbortLogin;
							$msg = $msgForAbortLogin;
							return false;
						}
					) );
				} else {
					$h2->will( $this->returnCallback(
						function ( $user, $pass, &$abort, &$msg ) use ( $username, $password ) {
							$this->assertInstanceOf( 'User', $user );
							$this->assertSame( $username, $user->getName() );
							if ( $password !== null ) {
								$this->assertSame( $password, $pass );
							} else {
								$this->assertInternalType( 'string', $pass );
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
			$this->assertEquals( \StatusValue::newGood(), $status, 'should succeed' );
		} else {
			$this->assertInstanceOf( 'StatusValue', $status, 'should fail (type)' );
			$this->assertFalse( $status->isOk(), 'should fail (ok)' );
			$errors = $status->getErrors();
			$this->assertEquals( $failMsg, $errors[0]['message'], 'should fail (message)' );
			$this->assertEquals( $failParams, $errors[0]['params'], 'should fail (params)' );
		}
	}

	public static function provideTestForAuthentication() {
		return array(
			'No valid requests' => array(
				null, null, null, null, null, null
			),
			'No hook errors' => array(
				'User', 'PaSsWoRd', null, null, null, null
			),
			'No hook errors, no password' => array(
				'User', null, null, null, null, null
			),
			'LoginUserMigrated no message' => array(
				'User', 'PaSsWoRd', false, null, null, 'login-migrated-generic'
			),
			'LoginUserMigrated with message' => array(
				'User', 'PaSsWoRd', 'LUM-abort', null, null, 'LUM-abort'
			),
			'LoginUserMigrated with message and params' => array(
				'User', 'PaSsWoRd', array( 'LUM-abort', 'foo' ), null, null, 'LUM-abort', array( 'foo' )
			),
			'AbortLogin, SUCCESS' => array(
				'User', 'PaSsWoRd', null, \LoginForm::SUCCESS, null, null
			),
			'AbortLogin, NEED_TOKEN, no message' => array(
				'User', 'PaSsWoRd', null, \LoginForm::NEED_TOKEN, null, 'nocookiesforlogin'
			),
			'AbortLogin, NEED_TOKEN, with message' => array(
				'User', 'PaSsWoRd', null, \LoginForm::NEED_TOKEN, 'needtoken', 'needtoken'
			),
			'AbortLogin, WRONG_TOKEN, no message' => array(
				'User', 'PaSsWoRd', null, \LoginForm::WRONG_TOKEN, null, 'sessionfailure'
			),
			'AbortLogin, WRONG_TOKEN, with message' => array(
				'User', 'PaSsWoRd', null, \LoginForm::WRONG_TOKEN, 'wrongtoken', 'wrongtoken'
			),
			'AbortLogin, ILLEGAL, no message' => array(
				'User', 'PaSsWoRd', null, \LoginForm::ILLEGAL, null, 'noname'
			),
			'AbortLogin, ILLEGAL, with message' => array(
				'User', 'PaSsWoRd', null, \LoginForm::ILLEGAL, 'badname', 'badname'
			),
			'AbortLogin, NO_NAME, no message' => array(
				'User', 'PaSsWoRd', null, \LoginForm::NO_NAME, null, 'noname'
			),
			'AbortLogin, NO_NAME, with message' => array(
				'User', 'PaSsWoRd', null, \LoginForm::NO_NAME, 'badname', 'badname'
			),
			'AbortLogin, WRONG_PASS, no message' => array(
				'User', 'PaSsWoRd', null, \LoginForm::WRONG_PASS, null, 'wrongpassword'
			),
			'AbortLogin, WRONG_PASS, with message' => array(
				'User', 'PaSsWoRd', null, \LoginForm::WRONG_PASS, 'badpass', 'badpass'
			),
			'AbortLogin, WRONG_PLUGIN_PASS, no message' => array(
				'User', 'PaSsWoRd', null, \LoginForm::WRONG_PLUGIN_PASS, null, 'wrongpassword'
			),
			'AbortLogin, WRONG_PLUGIN_PASS, with message' => array(
				'User', 'PaSsWoRd', null, \LoginForm::WRONG_PLUGIN_PASS, 'badpass', 'badpass'
			),
			'AbortLogin, NOT_EXISTS, no message' => array(
				"User'", 'A', null, \LoginForm::NOT_EXISTS, null, 'nosuchusershort', array( 'User&#39;' )
			),
			'AbortLogin, NOT_EXISTS, with message' => array(
				"User'", 'A', null, \LoginForm::NOT_EXISTS, 'badname', 'badname', array( 'User&#39;' )
			),
			'AbortLogin, EMPTY_PASS, no message' => array(
				'User', 'PaSsWoRd', null, \LoginForm::EMPTY_PASS, null, 'wrongpasswordempty'
			),
			'AbortLogin, EMPTY_PASS, with message' => array(
				'User', 'PaSsWoRd', null, \LoginForm::EMPTY_PASS, 'badpass', 'badpass'
			),
			'AbortLogin, RESET_PASS, no message' => array(
				'User', 'PaSsWoRd', null, \LoginForm::RESET_PASS, null, 'resetpass_announce'
			),
			'AbortLogin, RESET_PASS, with message' => array(
				'User', 'PaSsWoRd', null, \LoginForm::RESET_PASS, 'resetpass', 'resetpass'
			),
			'AbortLogin, THROTTLED, no message' => array(
				'User', 'PaSsWoRd', null, \LoginForm::THROTTLED, null, 'login-throttled',
				array( \Message::durationParam( 42 ) )
			),
			'AbortLogin, THROTTLED, with message' => array(
				'User', 'PaSsWoRd', null, \LoginForm::THROTTLED, 't', 't',
				array( \Message::durationParam( 42 ) )
			),
			'AbortLogin, USER_BLOCKED, no message' => array(
				"User'", 'P', null, \LoginForm::USER_BLOCKED, null, 'login-userblocked', array( 'User&#39;' )
			),
			'AbortLogin, USER_BLOCKED, with message' => array(
				"User'", 'P', null, \LoginForm::USER_BLOCKED, 'blocked', 'blocked', array( 'User&#39;' )
			),
			'AbortLogin, ABORTED, no message' => array(
				"User'", 'P', null, \LoginForm::ABORTED, null, 'login-abort-generic', array( 'User&#39;' )
			),
			'AbortLogin, ABORTED, with message' => array(
				"User'", 'P', null, \LoginForm::ABORTED, 'aborted', 'aborted', array( 'User&#39;' )
			),
			'AbortLogin, USER_MIGRATED, no message' => array(
				'User', 'P', null, \LoginForm::USER_MIGRATED, null, 'login-migrated-generic'
			),
			'AbortLogin, USER_MIGRATED, with message' => array(
				'User', 'P', null, \LoginForm::USER_MIGRATED, 'migrated', 'migrated'
			),
			'AbortLogin, USER_MIGRATED, with message and params' => array(
				'User', 'P', null, \LoginForm::USER_MIGRATED, array( 'migrated', 'foo' ),
				'migrated', array( 'foo' )
			),
		);
	}

	/**
	 * @dataProvider provideTestForAccountCreation
	 * @param string $msg
	 * @param Status|null $status
	 * @param StatusValue Result
	 */
	public function testTestForAccountCreation( $msg, $status, $result ) {
		$this->hook( 'AbortNewAccount', $this->once() )
			->will( $this->returnCallback( function ( $user, &$error, &$abortStatus )
				use ( $msg, $status )
			{
				$this->assertInstanceOf( 'User', $user );
				$this->assertSame( 'User', $user->getName() );
				$error = $msg;
				$abortStatus = $status;
				return $error === null && $status === null;
			} ) );

		$user = \User::newFromName( 'User' );
		$creator = \User::newFromName( 'UTSysop' );
		$ret = $this->getProvider()->testForAccountCreation( $user, $creator, array() );

		$this->unhook( 'AbortNewAccount' );

		$this->assertEquals( $result, $ret );
	}

	public static function provideTestForAccountCreation() {
		return array(
			'No hook errors' => array(
				null, null, \StatusValue::newGood()
			),
			'AbortNewAccount, old style' => array(
				'foobar', null, \StatusValue::newFatal( 'createaccount-hook-aborted', 'foobar' )
			),
			'AbortNewAccount, new style' => array(
				'foobar',
				\Status::newFatal( 'aborted!', 'param' ),
				\StatusValue::newFatal( 'aborted!', 'param' )
			),
		);
	}

	/**
	 * @dataProvider provideTestUserForCreation
	 * @param string|null $error
	 * @param string|null $failMsg
	 */
	public function testTestUserForCreation( $error, $failMsg ) {
		$this->hook( 'AbortNewAccount', $this->never() );
		$this->hook( 'AbortAutoAccount', $this->once() )
			->will( $this->returnCallback( function ( $user, &$abortError ) use ( $error ) {
				$this->assertInstanceOf( 'User', $user );
				$this->assertSame( 'UTSysop', $user->getName() );
				$abortError = $error;
				return $error === null;
			} ) );

		$status = $this->getProvider()->testUserForCreation( \User::newFromName( 'UTSysop' ), true );

		$this->unhook( 'AbortNewAccount' );
		$this->unhook( 'AbortAutoAccount' );

		if ( $failMsg === null ) {
			$this->assertEquals( \StatusValue::newGood(), $status, 'should succeed' );
		} else {
			$this->assertInstanceOf( 'StatusValue', $status, 'should fail (type)' );
			$this->assertFalse( $status->isOk(), 'should fail (ok)' );
			$errors = $status->getErrors();
			$this->assertEquals( $failMsg, $errors[0]['message'], 'should fail (message)' );
		}

		$this->hook( 'AbortAutoAccount', $this->never() );
		$this->hook( 'AbortNewAccount', $this->once() )
			->will( $this->returnCallback(
				function ( $user, &$abortError, &$abortStatus ) use ( $error ) {
					$this->assertInstanceOf( 'User', $user );
					$this->assertSame( 'UTSysop', $user->getName() );
					$abortError = $error;
					return $error === null;
				}
			) );
		$status = $this->getProvider()->testUserForCreation( \User::newFromName( 'UTSysop' ), false );
		$this->unhook( 'AbortNewAccount' );
		$this->unhook( 'AbortAutoAccount' );
		if ( $failMsg === null ) {
			$this->assertEquals( \StatusValue::newGood(), $status, 'should succeed' );
		} else {
			$this->assertInstanceOf( 'StatusValue', $status, 'should fail (type)' );
			$this->assertFalse( $status->isOk(), 'should fail (ok)' );
			$errors = $status->getErrors();
			$this->assertEquals(
				'createaccount-hook-aborted', $errors[0]['message'], 'should fail (message)'
			);
		}

		if ( $error !== false ) {
			$this->hook( 'AbortAutoAccount', $this->never() );
			$this->hook( 'AbortNewAccount', $this->once() )
				->will( $this->returnCallback(
					function ( $user, &$abortError, &$abortStatus ) use ( $error ) {
						$this->assertInstanceOf( 'User', $user );
						$this->assertSame( 'UTSysop', $user->getName() );
						$abortStatus = $error ? \Status::newFatal( $error ) : \Status::newGood();
						return $error === null;
					}
			) );
			$status = $this->getProvider()->testUserForCreation( \User::newFromName( 'UTSysop' ), false );
			$this->unhook( 'AbortNewAccount' );
			$this->unhook( 'AbortAutoAccount' );
			if ( $failMsg === null ) {
				$this->assertEquals( \StatusValue::newGood(), $status, 'should succeed' );
			} else {
				$this->assertInstanceOf( 'StatusValue', $status, 'should fail (type)' );
				$this->assertFalse( $status->isOk(), 'should fail (ok)' );
				$errors = $status->getErrors();
				$this->assertEquals( $failMsg, $errors[0]['message'], 'should fail (message)' );
			}
		}
	}

	public static function provideTestUserForCreation() {
		return array(
			'Success' => array( null, null ),
			'Fail, no message' => array( false, 'login-abort-generic' ),
			'Fail, with message' => array( 'fail', 'fail' ),
		);
	}
}
