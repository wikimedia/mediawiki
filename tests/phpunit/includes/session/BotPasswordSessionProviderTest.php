<?php

namespace MediaWiki\Session;

use MediaWiki\MediaWikiServices;
use MediaWikiIntegrationTestCase;
use Psr\Log\LogLevel;
use Wikimedia\TestingAccessWrapper;

/**
 * @group Session
 * @group Database
 * @covers MediaWiki\Session\BotPasswordSessionProvider
 */
class BotPasswordSessionProviderTest extends MediaWikiIntegrationTestCase {

	private $config;

	private function getProvider( $name = null, $prefix = null ) {
		global $wgSessionProviders;

		$params = [
			'priority' => 40,
			'sessionCookieName' => $name,
			'sessionCookieOptions' => [],
		];
		if ( $prefix !== null ) {
			$params['sessionCookieOptions']['prefix'] = $prefix;
		}

		if ( !$this->config ) {
			$this->config = new \HashConfig( [
				'CookiePrefix' => 'wgCookiePrefix',
				'EnableBotPasswords' => true,
				'BotPasswordsDatabase' => false,
				'SessionProviders' => $wgSessionProviders + [
					BotPasswordSessionProvider::class => [
						'class' => BotPasswordSessionProvider::class,
						'args' => [ $params ],
					]
				],
			] );
		}
		$manager = new SessionManager( [
			'config' => new \MultiConfig( [ $this->config, \RequestContext::getMain()->getConfig() ] ),
			'logger' => new \Psr\Log\NullLogger,
			'store' => new TestBagOStuff,
		] );

		return $manager->getProvider( BotPasswordSessionProvider::class );
	}

	protected function setUp() : void {
		parent::setUp();

		$this->setMwGlobals( [
			'wgEnableBotPasswords' => true,
			'wgBotPasswordsDatabase' => false,
			'wgCentralIdLookupProvider' => 'local',
			'wgGrantPermissions' => [
				'test' => [ 'read' => true ],
			],
		] );
	}

	public function addDBDataOnce() {
		$passwordFactory = MediaWikiServices::getInstance()->getPasswordFactory();
		$passwordHash = $passwordFactory->newFromPlaintext( 'foobaz' );

		$sysop = static::getTestSysop()->getUser();
		$userId = \CentralIdLookup::factory( 'local' )->centralIdFromName( $sysop->getName() );

		$dbw = wfGetDB( DB_MASTER );
		$dbw->delete(
			'bot_passwords',
			[ 'bp_user' => $userId, 'bp_app_id' => 'BotPasswordSessionProvider' ],
			__METHOD__
		);
		$dbw->insert(
			'bot_passwords',
			[
				'bp_user' => $userId,
				'bp_app_id' => 'BotPasswordSessionProvider',
				'bp_password' => $passwordHash->toString(),
				'bp_token' => 'token!',
				'bp_restrictions' => '{"IPAddresses":["127.0.0.0/8"]}',
				'bp_grants' => '["test"]',
			],
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
			$provider = new BotPasswordSessionProvider( [
				'priority' => SessionInfo::MIN_PRIORITY - 1
			] );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \InvalidArgumentException $ex ) {
			$this->assertSame(
				'MediaWiki\\Session\\BotPasswordSessionProvider::__construct: Invalid priority',
				$ex->getMessage()
			);
		}

		try {
			$provider = new BotPasswordSessionProvider( [
				'priority' => SessionInfo::MAX_PRIORITY + 1
			] );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \InvalidArgumentException $ex ) {
			$this->assertSame(
				'MediaWiki\\Session\\BotPasswordSessionProvider::__construct: Invalid priority',
				$ex->getMessage()
			);
		}

		$provider = new BotPasswordSessionProvider( [
			'priority' => 40
		] );
		$priv = TestingAccessWrapper::newFromObject( $provider );
		$this->assertSame( 40, $priv->priority );
		$this->assertSame( '_BPsession', $priv->sessionCookieName );
		$this->assertSame( [], $priv->sessionCookieOptions );

		$provider = new BotPasswordSessionProvider( [
			'priority' => 40,
			'sessionCookieName' => null,
		] );
		$priv = TestingAccessWrapper::newFromObject( $provider );
		$this->assertSame( '_BPsession', $priv->sessionCookieName );

		$provider = new BotPasswordSessionProvider( [
			'priority' => 40,
			'sessionCookieName' => 'Foo',
			'sessionCookieOptions' => [ 'Bar' ],
		] );
		$priv = TestingAccessWrapper::newFromObject( $provider );
		$this->assertSame( 'Foo', $priv->sessionCookieName );
		$this->assertSame( [ 'Bar' ], $priv->sessionCookieOptions );
	}

	public function testBasics() {
		$provider = $this->getProvider();

		$this->assertTrue( $provider->persistsSessionId() );
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
		$this->assertInstanceOf( SessionInfo::class, $info );
		$this->assertSame( 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', $info->getId() );

		$this->config->set( 'EnableBotPasswords', false );
		$this->assertNull( $provider->provideSessionInfo( $request ) );
		$this->config->set( 'EnableBotPasswords', true );

		$this->assertNull( $provider->provideSessionInfo( new \FauxRequest ) );
	}

	public function testNewSessionInfoForRequest() {
		$provider = $this->getProvider();
		$user = static::getTestSysop()->getUser();
		$request = $this->getMockBuilder( \FauxRequest::class )
			->setMethods( [ 'getIP' ] )->getMock();
		$request->expects( $this->any() )->method( 'getIP' )
			->will( $this->returnValue( '127.0.0.1' ) );
		$bp = \BotPassword::newFromUser( $user, 'BotPasswordSessionProvider' );

		$session = $provider->newSessionForRequest( $user, $bp, $request );
		$this->assertInstanceOf( Session::class, $session );

		$this->assertEquals( $session->getId(), $request->getSession()->getId() );
		$this->assertEquals( $user->getName(), $session->getUser()->getName() );

		$this->assertEquals( [
			'centralId' => $bp->getUserCentralId(),
			'appId' => $bp->getAppId(),
			'token' => $bp->getToken(),
			'rights' => [ 'read' ],
		], $session->getProviderMetadata() );

		$this->assertEquals( [ 'read' ], $session->getAllowedUserRights() );
	}

	public function testCheckSessionInfo() {
		$logger = new \TestLogger( true );
		$provider = $this->getProvider();
		$provider->setLogger( $logger );

		$user = static::getTestSysop()->getUser();
		$request = $this->getMockBuilder( \FauxRequest::class )
			->setMethods( [ 'getIP' ] )->getMock();
		$request->expects( $this->any() )->method( 'getIP' )
			->will( $this->returnValue( '127.0.0.1' ) );
		$bp = \BotPassword::newFromUser( $user, 'BotPasswordSessionProvider' );

		$data = [
			'provider' => $provider,
			'id' => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
			'userInfo' => UserInfo::newFromUser( $user, true ),
			'persisted' => false,
			'metadata' => [
				'centralId' => $bp->getUserCentralId(),
				'appId' => $bp->getAppId(),
				'token' => $bp->getToken(),
			],
		];
		$dataMD = $data['metadata'];

		foreach ( array_keys( $data['metadata'] ) as $key ) {
			$data['metadata'] = $dataMD;
			unset( $data['metadata'][$key] );
			$info = new SessionInfo( SessionInfo::MIN_PRIORITY, $data );
			$metadata = $info->getProviderMetadata();

			$this->assertFalse( $provider->refreshSessionInfo( $info, $request, $metadata ) );
			$this->assertSame( [
				[ LogLevel::INFO, 'Session "{session}": Missing metadata: {missing}' ]
			], $logger->getBuffer() );
			$logger->clearBuffer();
		}

		$data['metadata'] = $dataMD;
		$data['metadata']['appId'] = 'Foobar';
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, $data );
		$metadata = $info->getProviderMetadata();
		$this->assertFalse( $provider->refreshSessionInfo( $info, $request, $metadata ) );
		$this->assertSame( [
			[ LogLevel::INFO, 'Session "{session}": No BotPassword for {centralId} {appId}' ],
		], $logger->getBuffer() );
		$logger->clearBuffer();

		$data['metadata'] = $dataMD;
		$data['metadata']['token'] = 'Foobar';
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, $data );
		$metadata = $info->getProviderMetadata();
		$this->assertFalse( $provider->refreshSessionInfo( $info, $request, $metadata ) );
		$this->assertSame( [
			[ LogLevel::INFO, 'Session "{session}": BotPassword token check failed' ],
		], $logger->getBuffer() );
		$logger->clearBuffer();

		$request2 = $this->getMockBuilder( \FauxRequest::class )
			->setMethods( [ 'getIP' ] )->getMock();
		$request2->expects( $this->any() )->method( 'getIP' )
			->will( $this->returnValue( '10.0.0.1' ) );
		$data['metadata'] = $dataMD;
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, $data );
		$metadata = $info->getProviderMetadata();
		$this->assertFalse( $provider->refreshSessionInfo( $info, $request2, $metadata ) );
		$this->assertSame( [
			[ LogLevel::INFO, 'Session "{session}": Restrictions check failed' ],
		], $logger->getBuffer() );
		$logger->clearBuffer();

		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, $data );
		$metadata = $info->getProviderMetadata();
		$this->assertTrue( $provider->refreshSessionInfo( $info, $request, $metadata ) );
		$this->assertSame( [], $logger->getBuffer() );
		$this->assertEquals( $dataMD + [ 'rights' => [ 'read' ] ], $metadata );
	}

	public function testGetAllowedUserRights() {
		$logger = new \TestLogger( true );
		$provider = $this->getProvider();
		$provider->setLogger( $logger );

		$backend = TestUtils::getDummySessionBackend();
		$backendPriv = TestingAccessWrapper::newFromObject( $backend );

		try {
			$provider->getAllowedUserRights( $backend );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \InvalidArgumentException $ex ) {
			$this->assertSame( 'Backend\'s provider isn\'t $this', $ex->getMessage() );
		}

		$backendPriv->provider = $provider;
		$backendPriv->providerMetadata = [ 'rights' => [ 'foo', 'bar', 'baz' ] ];
		$this->assertSame( [ 'foo', 'bar', 'baz' ], $provider->getAllowedUserRights( $backend ) );
		$this->assertSame( [], $logger->getBuffer() );

		$backendPriv->providerMetadata = [ 'foo' => 'bar' ];
		$this->assertSame( [], $provider->getAllowedUserRights( $backend ) );
		$this->assertSame( [
			[
				LogLevel::DEBUG,
				'MediaWiki\\Session\\BotPasswordSessionProvider::getAllowedUserRights: ' .
					'No provider metadata, returning no rights allowed'
			]
		], $logger->getBuffer() );
		$logger->clearBuffer();

		$backendPriv->providerMetadata = [ 'rights' => 'bar' ];
		$this->assertSame( [], $provider->getAllowedUserRights( $backend ) );
		$this->assertSame( [
			[
				LogLevel::DEBUG,
				'MediaWiki\\Session\\BotPasswordSessionProvider::getAllowedUserRights: ' .
					'No provider metadata, returning no rights allowed'
			]
		], $logger->getBuffer() );
		$logger->clearBuffer();

		$backendPriv->providerMetadata = null;
		$this->assertSame( [], $provider->getAllowedUserRights( $backend ) );
		$this->assertSame( [
			[
				LogLevel::DEBUG,
				'MediaWiki\\Session\\BotPasswordSessionProvider::getAllowedUserRights: ' .
					'No provider metadata, returning no rights allowed'
			]
		], $logger->getBuffer() );
		$logger->clearBuffer();
	}
}
