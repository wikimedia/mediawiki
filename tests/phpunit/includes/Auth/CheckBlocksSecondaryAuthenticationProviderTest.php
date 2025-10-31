<?php

namespace MediaWiki\Tests\Auth;

use MediaWiki\Auth\AuthenticationResponse;
use MediaWiki\Auth\AuthManager;
use MediaWiki\Auth\CheckBlocksSecondaryAuthenticationProvider;
use MediaWiki\Block\AnonIpBlockTarget;
use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Config\HashConfig;
use MediaWiki\Context\RequestContext;
use MediaWiki\MainConfigNames;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Tests\Unit\Auth\AuthenticationProviderTestTrait;
use MediaWiki\User\User;
use MediaWikiIntegrationTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Wikimedia\TestingAccessWrapper;

/**
 * @group AuthManager
 * @group Database
 * @covers \MediaWiki\Auth\CheckBlocksSecondaryAuthenticationProvider
 */
class CheckBlocksSecondaryAuthenticationProviderTest extends MediaWikiIntegrationTestCase {
	use AuthenticationProviderTestTrait;

	public function testConstructor() {
		$provider = new CheckBlocksSecondaryAuthenticationProvider();
		$providerPriv = TestingAccessWrapper::newFromObject( $provider );
		$config = new HashConfig( [
			MainConfigNames::BlockDisablesLogin => false
		] );
		$this->initProvider( $provider, $config );
		$this->assertSame( false, $providerPriv->blockDisablesLogin );

		$provider = new CheckBlocksSecondaryAuthenticationProvider(
			[ 'blockDisablesLogin' => true ]
		);
		$providerPriv = TestingAccessWrapper::newFromObject( $provider );
		$config = new HashConfig( [
			MainConfigNames::BlockDisablesLogin => false
		] );
		$this->initProvider( $provider, $config );
		$this->assertSame( true, $providerPriv->blockDisablesLogin );
	}

	public function testBasics() {
		$provider = new CheckBlocksSecondaryAuthenticationProvider();
		$user = $this->getTestSysop()->getUser();

		$this->assertEquals(
			AuthenticationResponse::newAbstain(),
			$provider->beginSecondaryAccountCreation( $user, $user, [] )
		);
	}

	/**
	 * @dataProvider provideGetAuthenticationRequests
	 * @param string $action
	 * @param array $response
	 */
	public function testGetAuthenticationRequests( $action, $response ) {
		$provider = new CheckBlocksSecondaryAuthenticationProvider();

		$this->assertEquals( $response, $provider->getAuthenticationRequests( $action, [] ) );
	}

	public static function provideGetAuthenticationRequests() {
		return [
			[ AuthManager::ACTION_LOGIN, [] ],
			[ AuthManager::ACTION_CREATE, [] ],
			[ AuthManager::ACTION_LINK, [] ],
			[ AuthManager::ACTION_CHANGE, [] ],
			[ AuthManager::ACTION_REMOVE, [] ],
		];
	}

	/**
	 * @param array $blockOptions Options for DatabaseBlock
	 * @return User
	 */
	private function getBlockedUser( array $blockOptions ): User {
		$user = $this->getMutableTestUser()->getUser();
		if ( !isset( $blockOptions['address'] ) ) {
			$blockOptions['targetUser'] = $user;
		}
		$block = $this->getServiceContainer()->getDatabaseBlockStore()
			->insertBlockWithParams( $blockOptions + [
				'by' => $this->getTestSysop()->getUser(),
				'reason' => __METHOD__,
				'expiry' => time() + 100500,
			] );
		if ( $block->getType() === DatabaseBlock::TYPE_IP ) {
			// When an ip is blocked, the provided user object needs to know the ip
			// That allows BlockManager::getUserBlock to load the ip block for this user
			$request = new FauxRequest();
			$request->setIP( $blockOptions['address'] );
			// The global request is used by User::getRequest
			RequestContext::getMain()->setRequest( $request );
			// The ip from request is only used for the global user
			RequestContext::getMain()->setUser( $user );
		}

		return $user;
	}

	/**
	 * @param array $blockOptions Options for DatabaseBlock
	 * @return User
	 */
	private function getIpBlockedUser( array $blockOptions ) {
		static $ip = 10;
		return $this->getBlockedUser( [
			'address' => '10.10.10.' . $ip++,
		] + $blockOptions );
	}

	/**
	 * @param array $blockOptions Options for DatabaseBlock
	 * @return User
	 */
	private function getGloballyIpBlockedUser( array $blockOptions ) {
		static $ip = 100;
		$user = $this->getMutableTestUser()->getUser();
		TestingAccessWrapper::newFromObject( $user )->mGlobalBlock = new DatabaseBlock(
			$blockOptions + [
				'target' => new AnonIpBlockTarget( '10.10.10.' . $ip++, false ),
				'by' => $this->getTestSysop()->getUser(),
				'reason' => __METHOD__,
				'expiry' => time() + 100500,
			] );
		return $user;
	}

	/**
	 * @param string $blockType One of 'user', 'ip', 'global-ip', 'none'
	 * @param array $blockOptions Options for DatabaseBlock
	 * @return User
	 */
	private function getAnyBlockedUser( string $blockType, array $blockOptions = [] ) {
		if ( $blockType === 'user' ) {
			$user = $this->getBlockedUser( $blockOptions );
		} elseif ( $blockType === 'ip' ) {
			$user = $this->getIpBlockedUser( $blockOptions );
		} elseif ( $blockType === 'global-ip' ) {
			$user = $this->getGloballyIpBlockedUser( $blockOptions );
		} elseif ( $blockType === 'none' ) {
			$user = $this->getTestUser()->getUser();
		} else {
			$this->fail( 'Invalid block type' );
		}
		return $user;
	}

	/**
	 * @dataProvider provideBeginSecondaryAuthentication
	 */
	public function testBeginSecondaryAuthentication(
		string $blockType,
		array $blockOptions,
		bool $blockDisablesLogin,
		string $expectedResponseStatus
	) {
		/** @var AuthManager|MockObject $authManager */
		$authManager = $this->createNoOpMock( AuthManager::class );
		$provider = new CheckBlocksSecondaryAuthenticationProvider(
			[ 'blockDisablesLogin' => $blockDisablesLogin ]
		);
		$this->initProvider( $provider, new HashConfig(), null, $authManager );

		$user = $this->getAnyBlockedUser( $blockType, $blockOptions );

		$response = $provider->beginSecondaryAuthentication( $user, [] );
		$this->assertEquals( $expectedResponseStatus, $response->status );
	}

	public static function provideBeginSecondaryAuthentication() {
		// Only fail authentication when $wgBlockDisablesLogin is set, the block is not partial,
		// and not an IP block. Global blocks could in theory go either way, but GlobalBlocking
		// extension blocks are always IP blocks so we mock them as such.
		return [
			// block type (user/ip/global/none), block options, wgBlockDisablesLogin, expected response status
			'block does not disable login' => [ 'user', [], false, AuthenticationResponse::ABSTAIN ],
			'not blocked' => [ 'none', [], true, AuthenticationResponse::PASS ],
			'partial block' => [ 'user', [ 'sitewide' => false ], true, AuthenticationResponse::PASS ],
			'ip block' => [ 'ip', [], true, AuthenticationResponse::PASS ],
			'block' => [ 'user', [], true, AuthenticationResponse::FAIL ],
			'global block' => [ 'global-ip', [], true, AuthenticationResponse::PASS ],
		];
	}

}
