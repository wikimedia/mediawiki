<?php

namespace MediaWiki\Auth;

use FauxRequest;
use HashConfig;
use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Tests\Unit\Auth\AuthenticationProviderTestTrait;
use PHPUnit\Framework\MockObject\MockObject;
use User;
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
		$config = new \HashConfig( [
			'BlockDisablesLogin' => false
		] );
		$this->initProvider( $provider, $config );
		$this->assertSame( false, $providerPriv->blockDisablesLogin );

		$provider = new CheckBlocksSecondaryAuthenticationProvider(
			[ 'blockDisablesLogin' => true ]
		);
		$providerPriv = TestingAccessWrapper::newFromObject( $provider );
		$config = new \HashConfig( [
			'BlockDisablesLogin' => false
		] );
		$this->initProvider( $provider, $config );
		$this->assertSame( true, $providerPriv->blockDisablesLogin );
	}

	public function testBasics() {
		$provider = new CheckBlocksSecondaryAuthenticationProvider();
		$user = \User::newFromName( 'UTSysop' );

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
		$wrappedUser = TestingAccessWrapper::newFromObject( $user );
		$block = new DatabaseBlock( $blockOptions + [
			'address' => $user,
			'by' => $this->getTestSysop()->getUser(),
			'reason' => __METHOD__,
			'expiry' => time() + 100500,
		] );
		$wrappedUser->mBlock = $block;
		$wrappedUser->mBlockedby = $block->getByName();
		$wrappedUser->mBlockreason = $block->getReason();
		$wrappedUser->mHideName = false;

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
		$this->initProvider( $provider,  new HashConfig(), null, $authManager );

		$user = $this->getAnyBlockedUser( $blockType, $blockOptions );

		$response = $provider->beginSecondaryAuthentication( $user, [] );
		$this->assertEquals( $expectedResponseStatus, $response->status );
	}

	public function provideBeginSecondaryAuthentication() {
		// all blocks prevent login on $wgBlockDisablesLogin wikis; global blocks are not handled
		return [
			// block type (user/ip/global/none), block options, wgBlockDisablesLogin, expected response status
			'block does not disable login' => [ 'user', [], false, AuthenticationResponse::ABSTAIN ],
			'not blocked' => [ 'none', [], true, AuthenticationResponse::PASS ],
			'partial block' => [ 'user', [ 'sitewide' => false ], true, AuthenticationResponse::FAIL ],
			'ip block' => [ 'ip', [], true, AuthenticationResponse::FAIL ],
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
		$this->initProvider( $provider,  new HashConfig(), null, $authManager );

		$user = $this->getAnyBlockedUser( $blockType, $blockOptions );

		$status = $provider->testUserForCreation( $user,
			$autocreate ? AuthManager::AUTOCREATE_SOURCE_SESSION : false );
		$this->assertSame( $expectedStatus, $status->isGood() );
	}

	public function provideTestUserForCreation() {
		// blocks with createAccount flag prevent account signup, other blocks are ignored
		$signupTests = [
			// block type (user/ip/global/none), block options, wgBlockDisablesLogin, expected status
			'not blocked' => [ 'none', [], true, true ],
			'blocked' => [ 'user', [], false, true ],
			'createaccount-blocked' => [ 'user', [ 'createAccount' => true ], false, false ],
			'blocked with wgBlockDisablesLogin' => [ 'user', [], true, true ],
			'ip-blocked' => [ 'ip', [], false, true ],
			'createaccount-ip-blocked' => [ 'ip', [ 'createAccount' => true ], false, false ],
			'ip-blocked with wgBlockDisablesLogin' => [ 'ip', [], true, true ],
			'partially blocked' => [ 'user', [ 'createAccount' => true, 'sitewide' => false ], true, false ],
			'globally blocked' => [ 'global-ip', [], false, true ],
			'globally blocked with wgBlockDisablesLogin' => [ 'global-ip', [], true, true ],
		];

		$autocreateTests = $signupTests;

		foreach ( $signupTests as $name => $test ) {
			// add autocreate parameter
			yield 'signup, ' . $name => array_merge( [ false ], $test );
		}
		foreach ( $autocreateTests as $name => $test ) {
			yield 'autocreate, ' . $name => array_merge( [ true ], $test );
		}
	}

}
