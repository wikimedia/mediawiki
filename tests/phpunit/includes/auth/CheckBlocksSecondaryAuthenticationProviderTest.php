<?php

namespace MediaWiki\Auth;

use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Config\HashConfig;
use MediaWiki\MainConfigNames;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Tests\Unit\Auth\AuthenticationProviderTestTrait;
use MediaWiki\User\User;
use PHPUnit\Framework\MockObject\MockObject;
use RequestContext;
use Wikimedia\TestingAccessWrapper;

/**
 * @group AuthManager
 * @group Database
 * @covers \MediaWiki\Auth\CheckBlocksSecondaryAuthenticationProvider
 */
class CheckBlocksSecondaryAuthenticationProviderTest extends \MediaWikiIntegrationTestCase {
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
		$blockStore = $this->getServiceContainer()->getDatabaseBlockStore();
		$block = new DatabaseBlock( $blockOptions + [
			'address' => $user,
			'by' => $this->getTestSysop()->getUser(),
			'reason' => __METHOD__,
			'expiry' => time() + 100500,
		] );
		$blockStore->insertBlock( $block );
		if ( $block->getType() === DatabaseBlock::TYPE_IP ) {
			// When an ip is blocked, the provided user object needs to know the ip
			// That allows BlockManager::getUserBlock to load the ip block for this user
			$request = $this->getMockBuilder( FauxRequest::class )
				->onlyMethods( [ 'getIP' ] )->getMock();
			$request->method( 'getIP' )
				->willReturn( $blockOptions['address'] );
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
		TestingAccessWrapper::newFromObject( $user )->mGlobalBlock = new DatabaseBlock( $blockOptions + [
			'address' => '10.10.10.' . $ip++,
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

	/**
	 * @dataProvider provideTestUserForCreation
	 */
	public function testTestUserForCreation(
		bool $autocreate,
		string $blockType,
		array $blockOptions,
		bool $blockDisablesLogin,
		bool $expectedStatus

	) {
		/** @var AuthManager|MockObject $authManager */
		$authManager = $this->createNoOpMock( AuthManager::class, [ 'getRequest' ] );
		$authManager->method( 'getRequest' )->willReturn( new FauxRequest() );
		$provider = new CheckBlocksSecondaryAuthenticationProvider(
			[ 'blockDisablesLogin' => $blockDisablesLogin ]
		);
		$this->initProvider( $provider, new HashConfig(), null, $authManager );

		$user = $this->getAnyBlockedUser( $blockType, $blockOptions );

		$status = $provider->testUserForCreation( $user,
			$autocreate ? AuthManager::AUTOCREATE_SOURCE_SESSION : false );
		$this->assertSame( $expectedStatus, $status->isGood() );
	}

	public static function provideTestUserForCreation() {
		// Tests for normal signup: only prevent if the user is blocked, the block is specifically
		// targeted to the username, not partial, and the block prevents account creation.
		$signupTests = [
			// block type (user/ip/global/none), block options, wgBlockDisablesLogin, expected status
			'not blocked' => [ 'none', [], true, true ],
			'blocked' => [ 'user', [], false, true ],
			'createaccount-blocked' => [ 'user', [ 'createAccount' => true ], false, false ],
			'blocked with wgBlockDisablesLogin' => [ 'user', [], true, true ],
			'ip-blocked' => [ 'ip', [], false, true ],
			'createaccount-ip-blocked' => [ 'ip', [ 'createAccount' => true ], false, true ],
			'ip-blocked with wgBlockDisablesLogin' => [ 'ip', [], true, true ],
			'partially blocked' => [ 'user', [ 'createAccount' => true, 'sitewide' => false ], true, true ],
			'globally blocked' => [ 'global-ip', [], false, true ],
			'globally blocked with wgBlockDisablesLogin' => [ 'global-ip', [], true, true ],
		];

		// Tests for autocreation: in addition, also prevent on blocks without the
		// createaccount flag if $wgBlockDisablesLogin is set, and also prevent on IP blocks
		// when either the createaccount flag or $wgBlockDisablesLogin is set.
		$autocreateTests = $signupTests;
		$autocreateTests['blocked with wgBlockDisablesLogin'][3] = false;
		$autocreateTests['createaccount-ip-blocked'][3] = false;
		$autocreateTests['ip-blocked with wgBlockDisablesLogin'][3] = false;

		foreach ( $signupTests as $name => $test ) {
			// add autocreate parameter
			yield 'signup, ' . $name => array_merge( [ false ], $test );
		}
		foreach ( $autocreateTests as $name => $test ) {
			yield 'autocreate, ' . $name => array_merge( [ true ], $test );
		}
	}

}
