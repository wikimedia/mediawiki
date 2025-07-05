<?php

namespace MediaWiki\Tests\Api;

use MediaWiki\Api\ApiErrorFormatter;
use MediaWiki\Auth\AbstractSecondaryAuthenticationProvider;
use MediaWiki\Auth\AuthenticationResponse;
use MediaWiki\Auth\UsernameAuthenticationRequest;
use MediaWiki\MainConfigNames;
use MediaWiki\Session\BotPasswordSessionProvider;
use MediaWiki\Session\SessionManager;
use MediaWiki\Session\Token;
use MediaWiki\User\BotPassword;
use MediaWiki\User\User;
use MWRestrictions;

/**
 * @group API
 * @group Database
 * @group medium
 *
 * @covers \MediaWiki\Api\ApiLogin
 */
class ApiLoginTest extends ApiTestCase {

	public static function provideEnableBotPasswords() {
		return [
			'Bot passwords enabled' => [ true ],
			'Bot passwords disabled' => [ false ],
		];
	}

	/**
	 * @dataProvider provideEnableBotPasswords
	 */
	public function testExtendedDescription( $enableBotPasswords ) {
		$this->overrideConfigValue(
			MainConfigNames::EnableBotPasswords,
			$enableBotPasswords
		);
		$ret = $this->doApiRequest( [
			'action' => 'paraminfo',
			'modules' => 'login',
			'helpformat' => 'raw',
		] );
		$this->assertSame(
			'apihelp-login-extended-description' . ( $enableBotPasswords ? '' : '-nobotpasswords' ),
			$ret[0]['paraminfo']['modules'][0]['description'][1]['key']
		);
	}

	/**
	 * Test result of attempted login with an empty username
	 */
	public function testNoName() {
		$session = [
			'wsTokenSecrets' => [ 'login' => 'foobar' ],
		];
		$ret = $this->doApiRequest( [
			'action' => 'login',
			'lgname' => '',
			'lgpassword' => $this->getTestSysop()->getPassword(),
			'lgtoken' => (string)( new Token( 'foobar', '' ) ),
		], $session );
		$this->assertSame( 'Failed', $ret[0]['login']['result'] );
	}

	/**
	 * @dataProvider provideEnableBotPasswords
	 */
	public function testDeprecatedUserLogin( $enableBotPasswords ) {
		$this->overrideConfigValue(
			MainConfigNames::EnableBotPasswords,
			$enableBotPasswords
		);

		$user = $this->getTestUser();

		$ret = $this->doApiRequest( [
			'action' => 'login',
			'lgname' => $user->getUser()->getName(),
		] );

		$this->assertSame(
			[ 'warnings' => ApiErrorFormatter::stripMarkup( wfMessage(
				'apiwarn-deprecation-login-token' )->text() ) ],
			$ret[0]['warnings']['login']
		);
		$this->assertSame( 'NeedToken', $ret[0]['login']['result'] );

		$ret = $this->doApiRequest( [
			'action' => 'login',
			'lgtoken' => $ret[0]['login']['token'],
			'lgname' => $user->getUser()->getName(),
			'lgpassword' => $user->getPassword(),
		], $ret[2] );

		$this->assertSame(
			[ 'warnings' => ApiErrorFormatter::stripMarkup( wfMessage(
				'apiwarn-deprecation-login-' . ( $enableBotPasswords ? '' : 'no' ) . 'botpw' )
				->text() ) ],
			$ret[0]['warnings']['login']
		);
		$this->assertSame(
			[
				'result' => 'Success',
				'lguserid' => $user->getUser()->getId(),
				'lgusername' => $user->getUser()->getName(),
			],
			$ret[0]['login']
		);
	}

	/**
	 * Attempts to log in with the given name and password, retrieves the returned token, and makes
	 * a second API request to actually log in with the token.
	 *
	 * @param string $name
	 * @param string $password
	 * @param array $params To pass to second request
	 * @return array Result of second doApiRequest
	 */
	private function doUserLogin( $name, $password, array $params = [] ) {
		$ret = $this->doApiRequest( [
			'action' => 'query',
			'meta' => 'tokens',
			'type' => 'login',
		] );

		$this->assertArrayNotHasKey( 'warnings', $ret );

		return $this->doApiRequest( array_merge(
			[
				'action' => 'login',
				'lgtoken' => $ret[0]['query']['tokens']['logintoken'],
				'lgname' => $name,
				'lgpassword' => $password,
			], $params
		), $ret[2] );
	}

	public function testBadToken() {
		$testUser = $this->getTestSysop();
		$userName = $testUser->getUser()->getName();
		$password = $testUser->getPassword();
		$testUser->getUser()->logout();

		$ret = $this->doUserLogin( $userName, $password, [ 'lgtoken' => 'invalid token' ] );

		$this->assertSame( 'WrongToken', $ret[0]['login']['result'] );
	}

	public function testLostSession() {
		$testUser = $this->getTestSysop();
		$userName = $testUser->getUser()->getName();
		$password = $testUser->getPassword();
		$testUser->getUser()->logout();

		$ret = $this->doApiRequest( [
			'action' => 'query',
			'meta' => 'tokens',
			'type' => 'login',
		] );

		$this->assertArrayNotHasKey( 'warnings', $ret );

		// Lose the session
		SessionManager::getGlobalSession()->clear();
		$ret[2] = [];

		$ret = $this->doApiRequest( [
			'action' => 'login',
			'lgtoken' => $ret[0]['query']['tokens']['logintoken'],
			'lgname' => $userName,
			'lgpassword' => $password,
			'errorformat' => 'raw',
		], $ret[2] );

		$this->assertSame( [
			'result' => 'Failed',
			'reason' => [
				'code' => 'sessionlost',
				'key' => 'authpage-cannot-login-continue',
				'params' => [],
			],
		], $ret[0]['login'] );
	}

	public function testBadPass() {
		$user = $this->getTestSysop()->getUser();
		$userName = $user->getName();
		$user->logout();

		$ret = $this->doUserLogin( $userName, 'bad', [ 'errorformat' => 'raw' ] );

		$this->assertSame( [
			'result' => 'Failed',
			'reason' => [
				'code' => 'wrongpassword',
				'key' => 'wrongpassword',
				'params' => [],
			],
		], $ret[0]['login'] );
	}

	/**
	 * @dataProvider provideEnableBotPasswords
	 */
	public function testGoodPass( $enableBotPasswords ) {
		$this->overrideConfigValue(
			MainConfigNames::EnableBotPasswords,
			$enableBotPasswords
		);

		$testUser = $this->getTestSysop();
		$userName = $testUser->getUser()->getName();
		$password = $testUser->getPassword();
		$testUser->getUser()->logout();

		$ret = $this->doUserLogin( $userName, $password );

		$this->assertSame( 'Success', $ret[0]['login']['result'] );
		$this->assertSame(
			[ 'warnings' => ApiErrorFormatter::stripMarkup( wfMessage(
				'apiwarn-deprecation-login-' . ( $enableBotPasswords ? '' : 'no' ) . 'botpw' )->
				text() ) ],
			$ret[0]['warnings']['login']
		);
	}

	/**
	 * @dataProvider provideEnableBotPasswords
	 */
	public function testUnsupportedAuthResponseType( $enableBotPasswords ) {
		$this->overrideConfigValue(
			MainConfigNames::EnableBotPasswords,
			$enableBotPasswords
		);

		$mockProvider = $this->createMock(
			AbstractSecondaryAuthenticationProvider::class );
		$mockProvider->method( 'beginSecondaryAuthentication' )->willReturn(
			AuthenticationResponse::newUI(
				[ new UsernameAuthenticationRequest ],
				// Slightly silly message here
				wfMessage( 'mainpage' )
			)
		);
		$mockProvider->method( 'getAuthenticationRequests' )
			->willReturn( [] );

		$this->mergeMwGlobalArrayValue( 'wgAuthManagerConfig', [
			'secondaryauth' => [ [
				'factory' => static function () use ( $mockProvider ) {
					return $mockProvider;
				},
			] ],
		] );

		$testUser = $this->getTestSysop();
		$userName = $testUser->getUser()->getName();
		$password = $testUser->getPassword();
		$testUser->getUser()->logout();

		$ret = $this->doUserLogin( $userName, $password );

		$this->assertSame( [ 'login' => [
			'result' => 'Aborted',
			'reason' => ApiErrorFormatter::stripMarkup( wfMessage(
				'api-login-fail-aborted' . ( $enableBotPasswords ? '' : '-nobotpw' ) )->text() ),
		] ], $ret[0] );
	}

	/**
	 * @return array [ $username, $password ] suitable for passing to an API request for successful login
	 */
	private function setUpForBotPassword() {
		global $wgSessionProviders;

		$this->overrideConfigValues( [
			// We can't use mergeMwGlobalArrayValue because it will overwrite the existing entry
			// with index 0
			MainConfigNames::SessionProviders => array_merge( $wgSessionProviders, [
				[
					'class' => BotPasswordSessionProvider::class,
					'args' => [ [ 'priority' => 40 ] ],
					'services' => [ 'GrantsInfo' ],
				],
			] ),
			MainConfigNames::EnableBotPasswords => true,
			MainConfigNames::CentralIdLookupProvider => 'local',
			MainConfigNames::GrantPermissions => [
				'test' => [ 'read' => true ],
			],
		] );

		// Make sure our session provider is present
		$this->getServiceContainer()->resetServiceForTesting( 'SessionManager' );
		$this->assertNotNull(
			$this->getServiceContainer()->getSessionManager()->getProvider( BotPasswordSessionProvider::class )
		);

		$user = $this->getTestSysop()->getUser();
		$centralId = $this->getServiceContainer()
			->getCentralIdLookup()
			->centralIdFromLocalUser( $user );
		$this->assertNotSame( 0, $centralId );

		$password = 'ngfhmjm64hv0854493hsj5nncjud2clk';
		$passwordFactory = $this->getServiceContainer()->getPasswordFactory();
		// A is unsalted MD5 (thus fast) ... we don't care about security here, this is test only
		$passwordHash = $passwordFactory->newFromPlaintext( $password );

		$this->getDb()->newInsertQueryBuilder()
			->insertInto( 'bot_passwords' )
			->row( [
				'bp_user' => $centralId,
				'bp_app_id' => 'foo',
				'bp_password' => $passwordHash->toString(),
				'bp_token' => '',
				'bp_restrictions' => MWRestrictions::newDefault()->toJson(),
				'bp_grants' => '["test"]',
			] )
			->caller( __METHOD__ )
			->execute();

		$lgName = $user->getName() . BotPassword::getSeparator() . 'foo';

		return [ $lgName, $password ];
	}

	public function testBotPassword() {
		$ret = $this->doUserLogin( ...$this->setUpForBotPassword() );

		$this->assertSame( 'Success', $ret[0]['login']['result'] );
	}

	public function testBotPasswordThrottled() {
		// Undo high count from DevelopmentSettings.php
		$throttle = [
			[ 'count' => 5, 'seconds' => 30 ],
			[ 'count' => 100, 'seconds' => 60 * 60 * 48 ],
		];

		$this->setGroupPermissions( 'sysop', 'noratelimit', false );
		$this->overrideConfigValue(
			MainConfigNames::PasswordAttemptThrottle,
			$throttle
		);

		[ $name, $password ] = $this->setUpForBotPassword();

		for ( $i = 0; $i < $throttle[0]['count']; $i++ ) {
			$this->doUserLogin( $name, 'incorrectpasswordincorrectpassword' );
		}

		$ret = $this->doUserLogin( $name, $password );

		$this->assertSame( [
			'result' => 'Failed',
			'reason' => ApiErrorFormatter::stripMarkup( wfMessage( 'login-throttled' )->
				durationParams( $throttle[0]['seconds'] )->text() ),
		], $ret[0]['login'] );
	}

	public function testBotPasswordLocked() {
		$this->setTemporaryHook( 'UserIsLocked', static function ( User $unused, &$isLocked ) {
			$isLocked = true;
			return true;
		} );

		$ret = $this->doUserLogin( ...$this->setUpForBotPassword() );

		$this->assertSame( [
			'result' => 'Failed',
			'reason' => wfMessage( 'botpasswords-locked' )->text(),
		], $ret[0]['login'] );
	}

	public function testNoSameOriginSecurity() {
		$this->setTemporaryHook( 'RequestHasSameOriginSecurity',
			static function () {
				return false;
			}
		);

		$ret = $this->doApiRequest( [
			'action' => 'login',
			'errorformat' => 'plaintext',
		] )[0]['login'];

		$this->assertSame( [
			'result' => 'Aborted',
			'reason' => [
				'code' => 'api-login-fail-sameorigin',
				'text' => 'Cannot log in when the same-origin policy is not applied.',
			],
		], $ret );
	}
}
