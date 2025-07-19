<?php

namespace MediaWiki\Tests\Session;

use InvalidArgumentException;
use MediaWiki\Config\HashConfig;
use MediaWiki\Config\MultiConfig;
use MediaWiki\MainConfigNames;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Session\BotPasswordSessionProvider;
use MediaWiki\Session\Session;
use MediaWiki\Session\SessionInfo;
use MediaWiki\Session\SessionManager;
use MediaWiki\Session\UserInfo;
use MediaWiki\User\BotPassword;
use MediaWikiIntegrationTestCase;
use Psr\Log\LogLevel;
use Psr\Log\NullLogger;
use TestLogger;
use Wikimedia\TestingAccessWrapper;

/**
 * @group Session
 * @group Database
 * @covers \MediaWiki\Session\BotPasswordSessionProvider
 */
class BotPasswordSessionProviderTest extends MediaWikiIntegrationTestCase {
	use SessionProviderTestTrait;

	/** @var HashConfig */
	private $config;
	/** @var string */
	private $configHash;

	private function getProvider( $name = null, $prefix = null, $isApiRequest = true ) {
		global $wgSessionProviders;

		$params = [
			'priority' => 40,
			'sessionCookieName' => $name,
			'sessionCookieOptions' => [],
			'isApiRequest' => $isApiRequest,
		];
		if ( $prefix !== null ) {
			$params['sessionCookieOptions']['prefix'] = $prefix;
		}

		$sessionProviders = array_merge( $wgSessionProviders, [
			BotPasswordSessionProvider::class => [
				'class' => BotPasswordSessionProvider::class,
				'args' => [ $params ],
				'services' => [ 'GrantsInfo' ],
			]
		] );

		$configHash = json_encode( [ $name, $prefix, $isApiRequest ] );
		if ( !$this->config || $this->configHash !== $configHash ) {
			$this->config = new HashConfig( [
				MainConfigNames::CookiePrefix => 'wgCookiePrefix',
				MainConfigNames::EnableBotPasswords => true,
				MainConfigNames::SessionProviders => $sessionProviders,
			] );
			$this->configHash = $configHash;
		}

		$this->overrideConfigValues( [
			MainConfigNames::CookiePrefix => 'wgCookiePrefix',
			MainConfigNames::EnableBotPasswords => true,
			MainConfigNames::SessionProviders => $sessionProviders,
		] );

		$manager = new SessionManager(
			new MultiConfig( [ $this->config, $this->getServiceContainer()->getMainConfig() ] ),
			new NullLogger,
			$this->getServiceContainer()->getCentralIdLookup(),
			$this->getServiceContainer()->getHookContainer(),
			$this->getServiceContainer()->getObjectFactory(),
			$this->getServiceContainer()->getProxyLookup(),
			$this->getServiceContainer()->getUrlUtils(),
			$this->getServiceContainer()->getUserNameUtils(),
			$this->getServiceContainer()->getSessionStore()
		);

		return $manager->getProvider( BotPasswordSessionProvider::class );
	}

	protected function setUp(): void {
		parent::setUp();

		$this->overrideConfigValues( [
			MainConfigNames::EnableBotPasswords => true,
			MainConfigNames::CentralIdLookupProvider => 'local',
			MainConfigNames::GrantPermissions => [
				'test' => [ 'read' => true ],
			],
		] );
	}

	public function addDBDataOnce() {
		$passwordFactory = $this->getServiceContainer()->getPasswordFactory();
		$passwordHash = $passwordFactory->newFromPlaintext( 'foobaz' );

		$sysop = static::getTestSysop()->getUser();
		$userId = $this->getServiceContainer()
			->getCentralIdLookupFactory()
			->getLookup( 'local' )
			->centralIdFromName( $sysop->getName() );

		$dbw = $this->getDb();
		$dbw->newDeleteQueryBuilder()
			->deleteFrom( 'bot_passwords' )
			->where( [ 'bp_user' => $userId, 'bp_app_id' => 'BotPasswordSessionProvider' ] )
			->caller( __METHOD__ )->execute();
		$dbw->newInsertQueryBuilder()
			->insertInto( 'bot_passwords' )
			->row( [
				'bp_user' => $userId,
				'bp_app_id' => 'BotPasswordSessionProvider',
				'bp_password' => $passwordHash->toString(),
				'bp_token' => 'token!',
				'bp_restrictions' => '{"IPAddresses":["127.0.0.0/8"]}',
				'bp_grants' => '["test"]',
			] )
			->caller( __METHOD__ )
			->execute();
	}

	public function testConstructor() {
		$grantsInfo = $this->getServiceContainer()->getGrantsInfo();

		try {
			$provider = new BotPasswordSessionProvider( $grantsInfo );
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame(
				'MediaWiki\\Session\\BotPasswordSessionProvider::__construct: priority must be specified',
				$ex->getMessage()
			);
		}

		try {
			$provider = new BotPasswordSessionProvider(
				$grantsInfo,
				[
					'priority' => SessionInfo::MIN_PRIORITY - 1
				]
			);
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame(
				'MediaWiki\\Session\\BotPasswordSessionProvider::__construct: Invalid priority',
				$ex->getMessage()
			);
		}

		try {
			$provider = new BotPasswordSessionProvider(
				$grantsInfo,
				[
					'priority' => SessionInfo::MAX_PRIORITY + 1
				]
			);
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame(
				'MediaWiki\\Session\\BotPasswordSessionProvider::__construct: Invalid priority',
				$ex->getMessage()
			);
		}

		$provider = new BotPasswordSessionProvider(
			$grantsInfo,
			[ 'priority' => 40 ]
		);
		$priv = TestingAccessWrapper::newFromObject( $provider );
		$this->assertSame( 40, $priv->priority );
		$this->assertSame( '_BPsession', $priv->sessionCookieName );
		$this->assertSame( [], $priv->sessionCookieOptions );

		$provider = new BotPasswordSessionProvider(
			$grantsInfo,
			[
				'priority' => 40,
				'sessionCookieName' => null,
			]
		);
		$priv = TestingAccessWrapper::newFromObject( $provider );
		$this->assertSame( '_BPsession', $priv->sessionCookieName );

		$provider = new BotPasswordSessionProvider(
			$grantsInfo,
			[
				'priority' => 40,
				'sessionCookieName' => 'Foo',
				'sessionCookieOptions' => [ 'Bar' ],
			]
		);
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
		$request = new FauxRequest;
		$request->setCookie( '_BPsession', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 'wgCookiePrefix' );

		$provider = $this->getProvider( null, null, false );
		$this->assertNull( $provider->provideSessionInfo( $request ) );

		$provider = $this->getProvider();

		$info = $provider->provideSessionInfo( $request );
		$this->assertInstanceOf( SessionInfo::class, $info );
		$this->assertSame( 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', $info->getId() );

		$this->config->set( MainConfigNames::EnableBotPasswords, false );
		$this->assertNull( $provider->provideSessionInfo( $request ) );
		$this->config->set( MainConfigNames::EnableBotPasswords, true );

		$this->assertNull( $provider->provideSessionInfo( new FauxRequest ) );
	}

	public function testNewSessionInfoForRequest() {
		$provider = $this->getProvider();
		$user = static::getTestSysop()->getUser();
		$request = new FauxRequest();
		$request->setIP( '127.0.0.1' );
		$bp = BotPassword::newFromUser( $user, 'BotPasswordSessionProvider' );

		$session = $provider->newSessionForRequest( $user, $bp, $request );
		$this->assertInstanceOf( Session::class, $session );

		$this->assertEquals( $session->getId(), $request->getSession()->getId() );
		$this->assertEquals( $user->getName(), $session->getUser()->getName() );

		$this->assertEquals( [
			'centralId' => $bp->getUserCentralId(),
			'appId' => $bp->getAppId(),
			'token' => $bp->getToken(),
			'rights' => [ 'read' ],
			'restrictions' => $bp->getRestrictions()->toJson(),
		], $session->getProviderMetadata() );

		$this->assertEquals( [ 'read' ], $session->getAllowedUserRights() );
	}

	public function testCheckSessionInfo() {
		$logger = new TestLogger( true );
		$provider = $this->getProvider();
		$this->initProvider( $provider, $logger );

		$user = static::getTestSysop()->getUser();
		$request = new FauxRequest();
		$request->setIP( '127.0.0.1' );
		$bp = BotPassword::newFromUser( $user, 'BotPasswordSessionProvider' );

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

		foreach ( $data['metadata'] as $key => $_ ) {
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

		$request2 = new FauxRequest();
		$request2->setIP( '10.0.0.1' );
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
		$logger = new TestLogger( true );
		$provider = $this->getProvider();
		$this->initProvider( $provider, $logger );

		$backend = TestUtils::getDummySessionBackend();
		$backendPriv = TestingAccessWrapper::newFromObject( $backend );

		try {
			$provider->getAllowedUserRights( $backend );
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException $ex ) {
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
