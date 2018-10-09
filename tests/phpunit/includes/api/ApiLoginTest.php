<?php

use MediaWiki\MediaWikiServices;
use MediaWiki\Session\BotPasswordSessionProvider;
use MediaWiki\Session\SessionManager;
use Wikimedia\TestingAccessWrapper;

/**
 * @group API
 * @group Database
 * @group medium
 *
 * @covers ApiLogin
 */
class ApiLoginTest extends ApiTestCase {

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
			'lgpassword' => self::$users['sysop']->getPassword(),
			'lgtoken' => (string)( new MediaWiki\Session\Token( 'foobar', '' ) ),
		], $session );
		$this->assertSame( 'Failed', $ret[0]['login']['result'] );
	}

	private function doUserLogin( $name, $password ) {
		$ret = $this->doApiRequest( [
			'action' => 'login',
			'lgname' => $name,
		] );

		$this->assertSame( 'NeedToken', $ret[0]['login']['result'] );

		return $this->doApiRequest( [
			'action' => 'login',
			'lgtoken' => $ret[0]['login']['token'],
			'lgname' => $name,
			'lgpassword' => $password,
		], $ret[2] );
	}

	public function testBadPass() {
		$user = self::$users['sysop'];
		$userName = $user->getUser()->getName();
		$user->getUser()->logout();

		$ret = $this->doUserLogin( $userName, 'bad' );

		$this->assertSame( 'Failed', $ret[0]['login']['result'] );
	}

	public function testGoodPass() {
		$user = self::$users['sysop'];
		$userName = $user->getUser()->getName();
		$password = $user->getPassword();
		$user->getUser()->logout();

		$ret = $this->doUserLogin( $userName, $password );

		$this->assertSame( 'Success', $ret[0]['login']['result'] );
	}

	/**
	 * @group Broken
	 */
	public function testGotCookie() {
		$this->markTestIncomplete( "The server can't do external HTTP requests, "
			. "and the internal one won't give cookies" );

		global $wgServer, $wgScriptPath;

		$user = self::$users['sysop'];
		$userName = $user->getUser()->getName();
		$password = $user->getPassword();

		$req = MWHttpRequest::factory(
			self::$apiUrl . '?action=login&format=json',
			[
				'method' => 'POST',
				'postData' => [
					'lgname' => $userName,
					'lgpassword' => $password,
				],
			],
			__METHOD__
		);
		$req->execute();

		$content = json_decode( $req->getContent() );

		$this->assertSame( 'NeedToken', $content->login->result );

		$req->setData( [
			'lgtoken' => $content->login->token,
			'lgname' => $userName,
			'lgpassword' => $password,
		] );
		$req->execute();

		$cj = $req->getCookieJar();
		$serverName = parse_url( $wgServer, PHP_URL_HOST );
		$this->assertNotEquals( false, $serverName );
		$serializedCookie = $cj->serializeToHttpRequest( $wgScriptPath, $serverName );
		$this->assertRegExp(
			'/_session=[^;]*; .*UserID=[0-9]*; .*UserName=' . $userName . '; .*Token=/',
			$serializedCookie
		);
	}

	public function testBotPassword() {
		global $wgSessionProviders;

		$this->setMwGlobals( [
			// We can't use mergeMwGlobalArrayValue because it will overwrite the existing entry
			// with index 0
			'wgSessionProviders' => array_merge( $wgSessionProviders, [
				[
					'class' => BotPasswordSessionProvider::class,
					'args' => [ [ 'priority' => 40 ] ],
				],
			] ),
			'wgEnableBotPasswords' => true,
			'wgBotPasswordsDatabase' => false,
			'wgCentralIdLookupProvider' => 'local',
			'wgGrantPermissions' => [
				'test' => [ 'read' => true ],
			],
		] );

		// Make sure our session provider is present
		$manager = TestingAccessWrapper::newFromObject( SessionManager::singleton() );
		if ( !isset( $manager->sessionProviders[BotPasswordSessionProvider::class] ) ) {
			$tmp = $manager->sessionProviders;
			$manager->sessionProviders = null;
			$manager->sessionProviders = $tmp + $manager->getProviders();
		}
		$this->assertNotNull(
			SessionManager::singleton()->getProvider( BotPasswordSessionProvider::class ),
			'sanity check'
		);

		$user = self::$users['sysop'];
		$centralId = CentralIdLookup::factory()->centralIdFromLocalUser( $user->getUser() );
		$this->assertNotSame( 0, $centralId, 'sanity check' );

		$password = 'ngfhmjm64hv0854493hsj5nncjud2clk';
		$passwordFactory = MediaWikiServices::getInstance()->getPasswordFactory();
		// A is unsalted MD5 (thus fast) ... we don't care about security here, this is test only
		$passwordHash = $passwordFactory->newFromPlaintext( $password );

		$dbw = wfGetDB( DB_MASTER );
		$dbw->insert(
			'bot_passwords',
			[
				'bp_user' => $centralId,
				'bp_app_id' => 'foo',
				'bp_password' => $passwordHash->toString(),
				'bp_token' => '',
				'bp_restrictions' => MWRestrictions::newDefault()->toJson(),
				'bp_grants' => '["test"]',
			],
			__METHOD__
		);

		$lgName = $user->getUser()->getName() . BotPassword::getSeparator() . 'foo';

		$ret = $this->doUserLogin( $lgName, $password );

		$this->assertSame( 'Success', $ret[0]['login']['result'] );
	}

	public function testLoginWithNoSameOriginSecurity() {
		$this->setTemporaryHook( 'RequestHasSameOriginSecurity',
			function () {
				return false;
			}
		);

		$ret = $this->doApiRequest( [
			'action' => 'login',
		] )[0]['login'];

		$this->assertSame( [
			'result' => 'Aborted',
			'reason' => 'Cannot log in when the same-origin policy is not applied.',
		], $ret );
	}
}
