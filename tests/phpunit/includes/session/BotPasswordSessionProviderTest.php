<?php

namespace MediaWiki\Session;

use Psr\Log\LogLevel;
use MediaWikiTestCase;
use User;

/**
 * @group Session
 * @group Database
 * @covers MediaWiki\Session\BotPasswordSessionProvider
 */
class BotPasswordSessionProviderTest extends MediaWikiTestCase {

	private $config;

	private function getProvider( $name = null, $prefix = null ) {
		global $wgSessionProviders;

		$params = array(
			'priority' => 40,
			'sessionCookieName' => $name,
			'sessionCookieOptions' => array(),
		);
		if ( $prefix !== null ) {
			$params['sessionCookieOptions']['prefix'] = $prefix;
		}

		if ( !$this->config ) {
			$this->config = new \HashConfig( array(
				'CookiePrefix' => 'wgCookiePrefix',
				'EnableBotPasswords' => true,
				'BotPasswordsDatabase' => false,
				'SessionProviders' => $wgSessionProviders + array(
					'MediaWiki\\Session\\BotPasswordSessionProvider' => array(
						'class' => 'MediaWiki\\Session\\BotPasswordSessionProvider',
						'args' => array( $params ),
					)
				),
			) );
		}
		$manager = new SessionManager( array(
			'config' => new \MultiConfig( array( $this->config, \RequestContext::getMain()->getConfig() ) ),
			'logger' => new \Psr\Log\NullLogger,
			'store' => new TestBagOStuff,
		) );

		return $manager->getProvider( 'MediaWiki\\Session\\BotPasswordSessionProvider' );
	}

	protected function setUp() {
		parent::setUp();

		$this->setMwGlobals( array(
			'wgEnableBotPasswords' => true,
			'wgBotPasswordsDatabase' => false,
			'wgCentralIdLookupProvider' => 'local',
			'wgGrantPermissions' => array(
				'test' => array( 'read' => true ),
			),
		) );
	}

	public function addDBData() {
		$passwordFactory = new \PasswordFactory();
		$passwordFactory->init( \RequestContext::getMain()->getConfig() );
		// A is unsalted MD5 (thus fast) ... we don't care about security here, this is test only
		$passwordFactory->setDefaultType( 'A' );
		$pwhash = $passwordFactory->newFromPlaintext( 'foobaz' );

		$userId = \CentralIdLookup::factory( 'local' )->centralIdFromName( 'UTSysop' );

		$dbw = wfGetDB( DB_MASTER );
		$dbw->delete(
			'bot_passwords',
			array( 'bp_user' => $userId, 'bp_app_id' => 'BotPasswordSessionProvider' ),
			__METHOD__
		);
		$dbw->insert(
			'bot_passwords',
			array(
				'bp_user' => $userId,
				'bp_app_id' => 'BotPasswordSessionProvider',
				'bp_password' => $pwhash->toString(),
				'bp_token' => 'token!',
				'bp_restrictions' => '{"IPAddresses":["127.0.0.0/8"]}',
				'bp_grants' => '["test"]',
			),
			__METHOD__
		);
	}

	public function testConstructor() {
		try {
			$provider = new BotPasswordSessionProvider();
			$this->fail( 'Expected exception not thrown' );
		} catch ( \InvalidArgumentException $ex ) {
			$this->assertSame(
				'MediaWiki\\Session\\BotPasswordSessionProvider::__construct: priority must be specified',
				$ex->getMessage()
			);
		}

		try {
			$provider = new BotPasswordSessionProvider( array(
				'priority' => SessionInfo::MIN_PRIORITY - 1
			) );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \InvalidArgumentException $ex ) {
			$this->assertSame(
				'MediaWiki\\Session\\BotPasswordSessionProvider::__construct: Invalid priority',
				$ex->getMessage()
			);
		}

		try {
			$provider = new BotPasswordSessionProvider( array(
				'priority' => SessionInfo::MAX_PRIORITY + 1
			) );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \InvalidArgumentException $ex ) {
			$this->assertSame(
				'MediaWiki\\Session\\BotPasswordSessionProvider::__construct: Invalid priority',
				$ex->getMessage()
			);
		}

		$provider = new BotPasswordSessionProvider( array(
			'priority' => 40
		) );
		$priv = \TestingAccessWrapper::newFromObject( $provider );
		$this->assertSame( 40, $priv->priority );
		$this->assertSame( '_BPsession', $priv->sessionCookieName );
		$this->assertSame( array(), $priv->sessionCookieOptions );

		$provider = new BotPasswordSessionProvider( array(
			'priority' => 40,
			'sessionCookieName' => null,
		) );
		$priv = \TestingAccessWrapper::newFromObject( $provider );
		$this->assertSame( '_BPsession', $priv->sessionCookieName );

		$provider = new BotPasswordSessionProvider( array(
			'priority' => 40,
			'sessionCookieName' => 'Foo',
			'sessionCookieOptions' => array( 'Bar' ),
		) );
		$priv = \TestingAccessWrapper::newFromObject( $provider );
		$this->assertSame( 'Foo', $priv->sessionCookieName );
		$this->assertSame( array( 'Bar' ), $priv->sessionCookieOptions );
	}

	public function testBasics() {
		$provider = $this->getProvider();

		$this->assertTrue( $provider->persistsSessionID() );
		$this->assertFalse( $provider->canChangeUser() );

		$this->assertNull( $provider->newSessionInfo() );
		$this->assertNull( $provider->newSessionInfo( 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa' ) );
	}

	public function testProvideSessionInfo() {
		$provider = $this->getProvider();
		$request = new \FauxRequest;
		$request->setCookie( '_BPsession', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 'wgCookiePrefix' );

		if ( !defined( 'MW_API' ) ) {
			$this->assertNull( $provider->provideSessionInfo( $request ) );
			define( 'MW_API', 1 );
		}

		$info = $provider->provideSessionInfo( $request );
		$this->assertInstanceOf( 'MediaWiki\\Session\\SessionInfo', $info );
		$this->assertSame( 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', $info->getId() );

		$this->config->set( 'EnableBotPasswords', false );
		$this->assertNull( $provider->provideSessionInfo( $request ) );
		$this->config->set( 'EnableBotPasswords', true );

		$this->assertNull( $provider->provideSessionInfo( new \FauxRequest ) );
	}

	public function testNewSessionInfoForRequest() {
		$provider = $this->getProvider();
		$user = \User::newFromName( 'UTSysop' );
		$request = $this->getMock( 'FauxRequest', array( 'getIP' ) );
		$request->expects( $this->any() )->method( 'getIP' )
			->will( $this->returnValue( '127.0.0.1' ) );
		$bp = \BotPassword::newFromUser( $user, 'BotPasswordSessionProvider' );

		$session = $provider->newSessionForRequest( $user, $bp, $request );
		$this->assertInstanceOf( 'MediaWiki\\Session\\Session', $session );

		$this->assertEquals( $session->getId(), $request->getSession()->getId() );
		$this->assertEquals( $user->getName(), $session->getUser()->getName() );

		$this->assertEquals( array(
			'centralId' => $bp->getUserCentralId(),
			'appId' => $bp->getAppId(),
			'token' => $bp->getToken(),
			'rights' => array( 'read' ),
		), $session->getProviderMetadata() );

		$this->assertEquals( array( 'read' ), $session->getAllowedUserRights() );
	}

	public function testCheckSessionInfo() {
		$logger = new \TestLogger( true, function ( $m ) {
			return preg_replace(
				'/^Session \[\d+\][a-zA-Z0-9_\\\\]+<(?:null|anon|[+-]:\d+:\w+)>\w+: /', 'Session X: ', $m
			);
		} );
		$provider = $this->getProvider();
		$provider->setLogger( $logger );

		$user = \User::newFromName( 'UTSysop' );
		$request = $this->getMock( 'FauxRequest', array( 'getIP' ) );
		$request->expects( $this->any() )->method( 'getIP' )
			->will( $this->returnValue( '127.0.0.1' ) );
		$bp = \BotPassword::newFromUser( $user, 'BotPasswordSessionProvider' );

		$data = array(
			'provider' => $provider,
			'id' => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
			'userInfo' => UserInfo::newFromUser( $user, true ),
			'persisted' => false,
			'metadata' => array(
				'centralId' => $bp->getUserCentralId(),
				'appId' => $bp->getAppId(),
				'token' => $bp->getToken(),
			),
		);
		$dataMD = $data['metadata'];

		foreach ( array_keys( $data['metadata'] ) as $key ) {
			$data['metadata'] = $dataMD;
			unset( $data['metadata'][$key] );
			$info = new SessionInfo( SessionInfo::MIN_PRIORITY, $data );
			$metadata = $info->getProviderMetadata();

			$this->assertFalse( $provider->checkSessionInfo( $info, $request, $metadata ) );
			$this->assertSame( array(
				array( LogLevel::INFO, "Session X: Missing metadata: $key" )
			), $logger->getBuffer() );
			$logger->clearBuffer();
		}

		$data['metadata'] = $dataMD;
		$data['metadata']['appId'] = 'Foobar';
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, $data );
		$metadata = $info->getProviderMetadata();
		$this->assertFalse( $provider->checkSessionInfo( $info, $request, $metadata ) );
		$this->assertSame( array(
			array( LogLevel::INFO, "Session X: No BotPassword for {$bp->getUserCentralId()} Foobar" ),
		), $logger->getBuffer() );
		$logger->clearBuffer();

		$data['metadata'] = $dataMD;
		$data['metadata']['token'] = 'Foobar';
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, $data );
		$metadata = $info->getProviderMetadata();
		$this->assertFalse( $provider->checkSessionInfo( $info, $request, $metadata ) );
		$this->assertSame( array(
			array( LogLevel::INFO, 'Session X: BotPassword token check failed' ),
		), $logger->getBuffer() );
		$logger->clearBuffer();

		$request2 = $this->getMock( 'FauxRequest', array( 'getIP' ) );
		$request2->expects( $this->any() )->method( 'getIP' )
			->will( $this->returnValue( '10.0.0.1' ) );
		$data['metadata'] = $dataMD;
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, $data );
		$metadata = $info->getProviderMetadata();
		$this->assertFalse( $provider->checkSessionInfo( $info, $request2, $metadata ) );
		$this->assertSame( array(
			array( LogLevel::INFO, 'Session X: Restrictions check failed' ),
		), $logger->getBuffer() );
		$logger->clearBuffer();

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, $data );
		$metadata = $info->getProviderMetadata();
		$this->assertTrue( $provider->checkSessionInfo( $info, $request, $metadata ) );
		$this->assertSame( array(), $logger->getBuffer() );
		$this->assertEquals( $dataMD + array( 'rights' => array( 'read' ) ), $metadata );
	}
}
