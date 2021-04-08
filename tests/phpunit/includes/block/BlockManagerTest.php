<?php

use MediaWiki\Block\BlockManager;
use MediaWiki\Block\CompositeBlock;
use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Block\SystemBlock;
use MediaWiki\MediaWikiServices;
use Psr\Log\LoggerInterface;
use Wikimedia\TestingAccessWrapper;

/**
 * @group Blocking
 * @group Database
 * @coversDefaultClass \MediaWiki\Block\BlockManager
 */
class BlockManagerTest extends MediaWikiIntegrationTestCase {
	use TestAllServiceOptionsUsed;

	/** @var User */
	protected $user;

	/** @var int */
	protected $sysopId;

	protected function setUp() : void {
		parent::setUp();

		$this->user = $this->getTestUser()->getUser();
		$this->sysopId = $this->getTestSysop()->getUser()->getId();
		$this->blockManagerConfig = [
			'wgApplyIpBlocksToXff' => true,
			'wgCookieSetOnAutoblock' => true,
			'wgCookieSetOnIpBlock' => true,
			'wgDnsBlacklistUrls' => [],
			'wgEnableDnsBlacklist' => true,
			'wgProxyList' => [],
			'wgProxyWhitelist' => [],
			'wgSecretKey' => false,
			'wgSoftBlockRanges' => [],
		];
	}

	private function getBlockManager( $overrideConfig ) {
		return new BlockManager(
			...$this->getBlockManagerConstructorArgs( $overrideConfig )
		);
	}

	private function getBlockManagerConstructorArgs( $overrideConfig ) {
		$blockManagerConfig = array_merge( $this->blockManagerConfig, $overrideConfig );
		$this->setMwGlobals( $blockManagerConfig );
		$logger = $this->getMockBuilder( LoggerInterface::class )->getMock();
		$services = MediaWikiServices::getInstance();
		return [
			new LoggedServiceOptions(
				self::$serviceOptionsAccessLog,
				BlockManager::CONSTRUCTOR_OPTIONS,
				$services->getMainConfig()
			),
			$services->getPermissionManager(),
			$logger,
			$services->getHookContainer()
		];
	}

	/**
	 * @covers ::getUserBlock
	 */
	public function testGetBlock() {
		// Reset so that hooks are called
		$permissionManager = MediaWikiServices::getInstance()->getPermissionManager();
		$permissionManager->invalidateUsersRightsCache();

		// Ensure that the `UserGetRights` hook in PermissionManager is triggerred
		// when checking if the user has `ipblock-exempt`, so that CentralAuth can
		// grant `ipblock-exempt` via global groups. We also assert that, since the
		// user should have `ipblock-exempt`, that the `GetUserBlock` hook is called
		// with `$ip` as `null` since the ip should be ignored
		$onUserGetRightsCalled = false;
		$this->setTemporaryHook(
			'UserGetRights',
			function ( $user, &$rights ) use ( &$onUserGetRightsCalled ) {
				$onUserGetRightsCalled = true;
				$rights[] = 'ipblock-exempt';
				return true;
			}
		);
		$onGetUserBlockCalled = false;
		$onGetUserBlockIP = false;
		$this->setTemporaryHook(
			'GetUserBlock',
			function ( $user, $ip, &$block ) use ( &$onGetUserBlockCalled, &$onGetUserBlockIP ) {
				$onGetUserBlockCalled = true;
				$onGetUserBlockIP = $ip;
				return true;
			}
		);

		$blockManager = $this->getBlockManager( [] );
		$block = $blockManager->getUserBlock(
			$this->user,
			$this->createMock( WebRequest::class ),
			false
		);

		// We don't actually care about the block, just whether or not the right hooks were called
		$this->assertTrue( $onUserGetRightsCalled, 'Extensions should be able to grant rights' );
		$this->assertTrue(
			$onGetUserBlockCalled,
			'Sanity check: HookRunner::onGetUserBlock should have been called'
		);
		$this->assertNull(
			$onGetUserBlockIP,
			'The `GetUserBlock` hook should have been called with null since the user' .
				' was granted `ipblock-exempt` via the `UserGetRights` hook'
		);
	}

	/**
	 * @dataProvider provideBlocksForShouldApplyCookieBlock
	 * @covers ::getBlockFromCookieValue
	 * @covers ::shouldApplyCookieBlock
	 */
	public function testGetBlockFromCookieValue( $options, $expected ) {
		$blockManager = TestingAccessWrapper::newFromObject(
			$this->getBlockManager( [
				'wgCookieSetOnAutoblock' => true,
				'wgCookieSetOnIpBlock' => true,
			] )
		);

		$block = new DatabaseBlock( array_merge( [
			'address' => $options['target'] ?: $this->user,
			'by' => $this->sysopId,
		], $options['blockOptions'] ) );
		$block->insert();

		$user = $options['loggedIn'] ? $this->user : new User();
		$user->getRequest()->setCookie( 'BlockID', $blockManager->getCookieValue( $block ) );

		$this->assertSame( $expected, (bool)$blockManager->getBlockFromCookieValue(
			$user,
			$user->getRequest()
		) );

		$block->delete();
	}

	/**
	 * @dataProvider provideBlocksForShouldApplyCookieBlock
	 * @covers ::trackBlockWithCookie
	 * @covers ::shouldApplyCookieBlock
	 */
	public function testTrackBlockWithCookieRemovesBlocks( $options, $expectKeepCookie ) {
		$blockManager = TestingAccessWrapper::newFromObject(
			$this->getBlockManager( [
				'wgCookieSetOnAutoblock' => true,
				'wgCookieSetOnIpBlock' => true,
			] )
		);

		$block = new DatabaseBlock( array_merge( [
			'address' => $options['target'] ?: $this->user,
			'by' => $this->sysopId,
		], $options['blockOptions'] ) );
		$block->insert();

		$user = $options['loggedIn'] ? $this->user : new User();
		$user->getRequest()->setCookie( 'BlockID', $blockManager->getCookieValue( $block ) );

		$blockManager->trackBlockWithCookie(
			$user,
			$user->getRequest()->response()
		);

		$this->assertCount(
			$expectKeepCookie ? 0 : 1,
			$user->getRequest()->response()->getCookies()
		);

		$block->delete();
	}

	public static function provideBlocksForShouldApplyCookieBlock() {
		return [
			'Autoblocking user block' => [
				[
					'target' => '',
					'loggedIn' => true,
					'blockOptions' => [
						'enableAutoblock' => true
					],
				],
				true,
			],
			'Autoblocking user block for anonymous user' => [
				[
					'target' => '',
					'loggedIn' => false,
					'blockOptions' => [
						'enableAutoblock' => true
					],
				],
				true,
			],
			'Non-autoblocking user block' => [
				[
					'target' => '',
					'loggedIn' => true,
					'blockOptions' => [],
				],
				false,
			],
			'IP block for anonymous user' => [
				[
					'target' => '127.0.0.1',
					'loggedIn' => false,
					'blockOptions' => [],
				],
				true,
			],
			'IP block for logged in user' => [
				[
					'target' => '127.0.0.1',
					'loggedIn' => true,
					'blockOptions' => [],
				],
				false,
			],
			'IP range block for anonymous user' => [
				[
					'target' => '127.0.0.0/8',
					'loggedIn' => false,
					'blockOptions' => [],
				],
				true,
			],
		];
	}

	/**
	 * @dataProvider provideIsLocallyBlockedProxy
	 * @covers ::isLocallyBlockedProxy
	 */
	public function testIsLocallyBlockedProxy( $proxyList, $expected ) {
		$blockManager = TestingAccessWrapper::newFromObject(
			$this->getBlockManager( [
				'wgProxyList' => $proxyList
			] )
		);

		$ip = '1.2.3.4';
		$this->assertSame( $expected, $blockManager->isLocallyBlockedProxy( $ip ) );
	}

	public static function provideIsLocallyBlockedProxy() {
		return [
			'Proxy list is empty' => [ [], false ],
			'Proxy list contains IP' => [ [ '1.2.3.4' ], true ],
			'Proxy list contains IP as value' => [ [ 'test' => '1.2.3.4' ], true ],
			'Proxy list contains range that covers IP' => [ [ '1.2.3.0/16' ], true ],
		];
	}

	/**
	 * @dataProvider provideIsDnsBlacklisted
	 * @covers ::isDnsBlacklisted
	 * @covers ::inDnsBlacklist
	 */
	public function testIsDnsBlacklisted( $options, $expected ) {
		$blockManagerConfig = [
			'wgEnableDnsBlacklist' => true,
			'wgDnsBlacklistUrls' => $options['blacklist'],
			'wgProxyWhitelist' => $options['whitelist'],
		];

		$blockManager = $this->getMockBuilder( BlockManager::class )
			->setConstructorArgs( $this->getBlockManagerConstructorArgs( $blockManagerConfig ) )
			->setMethods( [ 'checkHost' ] )
			->getMock();
		$blockManager->method( 'checkHost' )
			->will( $this->returnValueMap( [ [
				$options['dnsblQuery'],
				$options['dnsblResponse'],
			] ] ) );

		$this->assertSame(
			$expected,
			$blockManager->isDnsBlacklisted( $options['ip'], $options['checkWhitelist'] )
		);
	}

	public static function provideIsDnsBlacklisted() {
		$dnsblFound = [ '127.0.0.2' ];
		$dnsblNotFound = false;
		return [
			'IP is blacklisted' => [
				[
					'blacklist' => [ 'dnsbl.test' ],
					'ip' => '127.0.0.1',
					'dnsblQuery' => '1.0.0.127.dnsbl.test',
					'dnsblResponse' => $dnsblFound,
					'whitelist' => [],
					'checkWhitelist' => false,
				],
				true,
			],
			'IP is blacklisted; blacklist has key' => [
				[
					'blacklist' => [ [ 'dnsbl.test', 'key' ] ],
					'ip' => '127.0.0.1',
					'dnsblQuery' => 'key.1.0.0.127.dnsbl.test',
					'dnsblResponse' => $dnsblFound,
					'whitelist' => [],
					'checkWhitelist' => false,
				],
				true,
			],
			'IP is blacklisted; blacklist is array' => [
				[
					'blacklist' => [ [ 'dnsbl.test' ] ],
					'ip' => '127.0.0.1',
					'dnsblQuery' => '1.0.0.127.dnsbl.test',
					'dnsblResponse' => $dnsblFound,
					'whitelist' => [],
					'checkWhitelist' => false,
				],
				true,
			],
			'IP is not blacklisted' => [
				[
					'blacklist' => [ 'dnsbl.test' ],
					'ip' => '1.2.3.4',
					'dnsblQuery' => '4.3.2.1.dnsbl.test',
					'dnsblResponse' => $dnsblNotFound,
					'whitelist' => [],
					'checkWhitelist' => false,
				],
				false,
			],
			'Blacklist is empty' => [
				[
					'blacklist' => [],
					'ip' => '127.0.0.1',
					'dnsblQuery' => '1.0.0.127.dnsbl.test',
					'dnsblResponse' => $dnsblFound,
					'whitelist' => [],
					'checkWhitelist' => false,
				],
				false,
			],
			'IP is blacklisted and whitelisted; whitelist is not checked' => [
				[
					'blacklist' => [ 'dnsbl.test' ],
					'ip' => '127.0.0.1',
					'dnsblQuery' => '1.0.0.127.dnsbl.test',
					'dnsblResponse' => $dnsblFound,
					'whitelist' => [ '127.0.0.1' ],
					'checkWhitelist' => false,
				],
				true,
			],
			'IP is blacklisted and whitelisted; whitelist is checked' => [
				[
					'blacklist' => [ 'dnsbl.test' ],
					'ip' => '127.0.0.1',
					'dnsblQuery' => '1.0.0.127.dnsbl.test',
					'dnsblResponse' => $dnsblFound,
					'whitelist' => [ '127.0.0.1' ],
					'checkWhitelist' => true,
				],
				false,
			],
		];
	}

	/**
	 * @covers ::getUniqueBlocks
	 */
	public function testGetUniqueBlocks() {
		$blockId = 100;

		$blockManager = TestingAccessWrapper::newFromObject( $this->getBlockManager( [] ) );

		$block = $this->getMockBuilder( DatabaseBlock::class )
			->setMethods( [ 'getId' ] )
			->getMock();
		$block->method( 'getId' )
			->willReturn( $blockId );

		$autoblock = $this->getMockBuilder( DatabaseBlock::class )
			->setMethods( [ 'getParentBlockId', 'getType' ] )
			->getMock();
		$autoblock->method( 'getParentBlockId' )
			->willReturn( $blockId );
		$autoblock->method( 'getType' )
			->willReturn( DatabaseBlock::TYPE_AUTO );

		$blocks = [ $block, $block, $autoblock, new SystemBlock() ];

		$this->assertCount( 2, $blockManager->getUniqueBlocks( $blocks ) );
	}

	/**
	 * @dataProvider provideTrackBlockWithCookie
	 * @covers ::trackBlockWithCookie
	 */
	public function testTrackBlockWithCookie( $options, $expected ) {
		$this->setMwGlobals( 'wgCookiePrefix', '' );

		$request = new FauxRequest();
		if ( $options['cookieSet'] ) {
			$request->setCookie( 'BlockID', 'the value does not matter' );
		}
		/** @var FauxResponse $response */
		$response = $request->response();

		$user = $this->getMockBuilder( User::class )
			->setMethods( [ 'getBlock', 'getRequest' ] )
			->getMock();
		$user->method( 'getBlock' )
			->willReturn( $options['block'] );
		$user->method( 'getRequest' )
			->willReturn( $request );

		// Although the block cookie is set via DeferredUpdates, in command line mode updates are
		// processed immediately
		$blockManager = $this->getBlockManager( [
			'wgSecretKey' => '',
			'wgCookieSetOnIpBlock' => true,
		] );
		$blockManager->trackBlockWithCookie( $user, $response );

		$this->assertCount( $expected['count'], $response->getCookies() );
		$this->assertEquals( $expected['value'], $response->getCookie( 'BlockID' ) );
	}

	public function provideTrackBlockWithCookie() {
		$blockId = 123;
		return [
			'Block cookie is already set; there is a trackable block' => [
				[
					'cookieSet' => true,
					'block' => $this->getTrackableBlock( $blockId ),
				],
				[
					'count' => 1,
					'value' => $blockId,
				]
			],
			'Block cookie is already set; there is no block' => [
				[
					'cookieSet' => true,
					'block' => null,
				],
				[
					// Cookie is cleared by setting it to empty value
					'count' => 1,
					'value' => '',
				]
			],
			'Block cookie is not yet set; there is no block' => [
				[
					'cookieSet' => false,
					'block' => null,
				],
				[
					'count' => 0,
					'value' => null,
				]
			],
			'Block cookie is not yet set; there is a trackable block' => [
				[
					'cookieSet' => false,
					'block' => $this->getTrackableBlock( $blockId ),
				],
				[
					'count' => 1,
					'value' => $blockId,
				]
			],
			'Block cookie is not yet set; there is a composite block with a trackable block' => [
				[
					'cookieSet' => false,
					'block' => new CompositeBlock( [
						'originalBlocks' => [
							new SystemBlock(),
							$this->getTrackableBlock( $blockId ),
						]
					] ),
				],
				[
					'count' => 1,
					'value' => $blockId,
				]
			],
			'Block cookie is not yet set; there is a composite block but no trackable block' => [
				[
					'cookieSet' => false,
					'block' => new CompositeBlock( [
						'originalBlocks' => [
							new SystemBlock(),
							new SystemBlock(),
						]
					] ),
				],
				[
					'count' => 0,
					'value' => null,
				]
			],
		];
	}

	private function getTrackableBlock( $blockId ) {
		$block = $this->getMockBuilder( DatabaseBlock::class )
			->setMethods( [ 'getType', 'getId' ] )
			->getMock();
		$block->method( 'getType' )
			->willReturn( DatabaseBlock::TYPE_IP );
		$block->method( 'getId' )
			->willReturn( $blockId );
		return $block;
	}

	/**
	 * @dataProvider provideSetBlockCookie
	 * @covers ::setBlockCookie
	 */
	public function testSetBlockCookie( $expiryDelta, $expectedExpiryDelta ) {
		$this->setMwGlobals( [
			'wgCookiePrefix' => '',
		] );

		$request = new FauxRequest();
		$response = $request->response();

		$blockManager = $this->getBlockManager( [
			'wgSecretKey' => '',
			'wgCookieSetOnIpBlock' => true,
		] );

		$now = wfTimestamp();

		$block = new DatabaseBlock( [
			'expiry' => $expiryDelta === '' ? '' : $now + $expiryDelta
		] );
		$blockManager->setBlockCookie( $block, $response );
		$cookies = $response->getCookies();

		$this->assertEqualsWithDelta(
			$now + $expectedExpiryDelta,
			$cookies['BlockID']['expire'],
			60 // Allow actual to be up to 60 seconds later than expected
		);
	}

	public static function provideSetBlockCookie() {
		// Maximum length of a block cookie, defined in BlockManager::setBlockCookie
		$maxExpiryDelta = ( 24 * 60 * 60 );

		$longExpiryDelta = ( 48 * 60 * 60 );
		$shortExpiryDelta = ( 12 * 60 * 60 );

		return [
			'Block has indefinite expiry' => [
				'',
				$maxExpiryDelta,
			],
			'Block expiry is later than maximum cookie block expiry' => [
				$longExpiryDelta,
				$maxExpiryDelta,
			],
			'Block expiry is sooner than maximum cookie block expiry' => [
				$shortExpiryDelta,
				$shortExpiryDelta,
			],
		];
	}

	/**
	 * @covers ::shouldTrackBlockWithCookie
	 */
	public function testShouldTrackBlockWithCookieSystemBlock() {
		$blockManager = TestingAccessWrapper::newFromObject( $this->getBlockManager( [] ) );
		$this->assertFalse( $blockManager->shouldTrackBlockWithCookie(
			new SystemBlock(),
			true
		) );
	}

	/**
	 * @dataProvider provideShouldTrackBlockWithCookie
	 * @covers ::shouldTrackBlockWithCookie
	 */
	public function testShouldTrackBlockWithCookie( $options, $expected ) {
		$block = $this->getMockBuilder( DatabaseBlock::class )
			->setMethods( [ 'getType', 'isAutoblocking' ] )
			->getMock();
		$block->method( 'getType' )
			->willReturn( $options['type'] );
		if ( isset( $options['autoblocking'] ) ) {
			$block->method( 'isAutoblocking' )
				->willReturn( $options['autoblocking'] );
		}

		$blockManager = TestingAccessWrapper::newFromObject(
			$this->getBlockManager( $options['blockManagerConfig'] )
		);

		$this->assertSame(
			$expected,
			$blockManager->shouldTrackBlockWithCookie( $block, $options['isAnon'] )
		);
	}

	public static function provideShouldTrackBlockWithCookie() {
		return [
			'IP block, anonymous user, IP block cookies enabled' => [
				[
					'type' => DatabaseBlock::TYPE_IP,
					'isAnon' => true,
					'blockManagerConfig' => [ 'wgCookieSetOnIpBlock' => true ],
				],
				true
			],
			'IP range block, anonymous user, IP block cookies enabled' => [
				[
					'type' => DatabaseBlock::TYPE_RANGE,
					'isAnon' => true,
					'blockManagerConfig' => [ 'wgCookieSetOnIpBlock' => true ],
				],
				true
			],
			'IP block, anonymous user, IP block cookies disabled' => [
				[
					'type' => DatabaseBlock::TYPE_IP,
					'isAnon' => true,
					'blockManagerConfig' => [ 'wgCookieSetOnIpBlock' => false ],
				],
				false
			],
			'IP block, logged in user, IP block cookies enabled' => [
				[
					'type' => DatabaseBlock::TYPE_IP,
					'isAnon' => false,
					'blockManagerConfig' => [ 'wgCookieSetOnIpBlock' => true ],
				],
				false
			],
			'User block, anonymous, autoblock cookies enabled, block is autoblocking' => [
				[
					'type' => DatabaseBlock::TYPE_USER,
					'isAnon' => true,
					'blockManagerConfig' => [ 'wgCookieSetOnAutoblock' => true ],
					'autoblocking' => true,
				],
				false
			],
			'User block, logged in, autoblock cookies enabled, block is autoblocking' => [
				[
					'type' => DatabaseBlock::TYPE_USER,
					'isAnon' => false,
					'blockManagerConfig' => [ 'wgCookieSetOnAutoblock' => true ],
					'autoblocking' => true,
				],
				true
			],
			'User block, logged in, autoblock cookies disabled, block is autoblocking' => [
				[
					'type' => DatabaseBlock::TYPE_USER,
					'isAnon' => false,
					'blockManagerConfig' => [ 'wgCookieSetOnAutoblock' => false ],
					'autoblocking' => true,
				],
				false
			],
			'User block, logged in, autoblock cookies enabled, block is not autoblocking' => [
				[
					'type' => DatabaseBlock::TYPE_USER,
					'isAnon' => false,
					'blockManagerConfig' => [ 'wgCookieSetOnAutoblock' => true ],
					'autoblocking' => false,
				],
				false
			],
			'Block type is autoblock' => [
				[
					'type' => DatabaseBlock::TYPE_AUTO,
					'isAnon' => true,
					'blockManagerConfig' => [],
				],
				false
			]
		];
	}

	/**
	 * @covers ::clearBlockCookie
	 */
	public function testClearBlockCookie() {
		$this->setMwGlobals( [
			'wgCookiePrefix' => '',
		] );

		$request = new FauxRequest();
		$response = $request->response();
		$response->setCookie( 'BlockID', '100' );
		$this->assertSame( '100', $response->getCookie( 'BlockID' ) );

		BlockManager::clearBlockCookie( $response );
		$this->assertSame( '', $response->getCookie( 'BlockID' ) );
	}

	/**
	 * @dataProvider provideGetIdFromCookieValue
	 * @covers ::getIdFromCookieValue
	 */
	public function testGetIdFromCookieValue( $options, $expected ) {
		$blockManager = $this->getBlockManager( [
			'wgSecretKey' => $options['secretKey']
		] );
		$this->assertEquals(
			$expected,
			$blockManager->getIdFromCookieValue( $options['cookieValue'] )
		);
	}

	public static function provideGetIdFromCookieValue() {
		$blockId = 100;
		$secretKey = '123';
		$hmac = MWCryptHash::hmac( $blockId, $secretKey, false );
		return [
			'No secret key is set' => [
				[
					'secretKey' => '',
					'cookieValue' => $blockId,
					'calculatedHmac' => MWCryptHash::hmac( $blockId, '', false ),
				],
				$blockId,
			],
			'Secret key is set and stored hmac is correct' => [
				[
					'secretKey' => $secretKey,
					'cookieValue' => $blockId . '!' . $hmac,
					'calculatedHmac' => $hmac,
				],
				$blockId,
			],
			'Secret key is set and stored hmac is incorrect' => [
				[
					'secretKey' => $secretKey,
					'cookieValue' => $blockId . '!xyz',
					'calculatedHmac' => $hmac,
				],
				null,
			],
		];
	}

	/**
	 * @dataProvider provideGetCookieValue
	 * @covers ::getCookieValue
	 */
	public function testGetCookieValue( $options, $expected ) {
		$blockManager = $this->getBlockManager( [
			'wgSecretKey' => $options['secretKey']
		] );

		$block = $this->getMockBuilder( DatabaseBlock::class )
			->setMethods( [ 'getId' ] )
			->getMock();
		$block->method( 'getId' )
			->willReturn( $options['blockId'] );

		$this->assertEquals(
			$expected,
			$blockManager->getCookieValue( $block )
		);
	}

	public static function provideGetCookieValue() {
		$blockId = 100;
		return [
			'Secret key not set' => [
				[
					'secretKey' => '',
					'blockId' => $blockId,
					'hmac' => MWCryptHash::hmac( $blockId, '', false ),
				],
				$blockId,
			],
			'Secret key set' => [
				[
					'secretKey' => '123',
					'blockId' => $blockId,
					'hmac' => MWCryptHash::hmac( $blockId, '123', false ),
				],
				$blockId . '!' . MWCryptHash::hmac( $blockId, '123', false ) ],
		];
	}

	/**
	 * @coversNothing
	 */
	public function testAllServiceOptionsUsed() {
		$this->assertAllServiceOptionsUsed( [ 'ApplyIpBlocksToXff', 'SoftBlockRanges' ] );
	}
}
