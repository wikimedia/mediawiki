<?php

namespace MediaWiki\Tests\Block;

use LoggedServiceOptions;
use MediaWiki\Block\AbstractBlock;
use MediaWiki\Block\Block;
use MediaWiki\Block\BlockManager;
use MediaWiki\Block\CompositeBlock;
use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Block\SystemBlock;
use MediaWiki\MainConfigNames;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Request\FauxResponse;
use MediaWiki\User\User;
use MediaWiki\User\UserIdentityValue;
use MediaWikiIntegrationTestCase;
use MWCryptHash;
use Psr\Log\NullLogger;
use TestAllServiceOptionsUsed;
use TestUser;
use Wikimedia\TestingAccessWrapper;

/**
 * @group Blocking
 * @group Database
 * @covers \MediaWiki\Block\BlockManager
 */
class BlockManagerTest extends MediaWikiIntegrationTestCase {
	use TestAllServiceOptionsUsed;

	protected User $user;
	protected User $sysopUser;
	private array $blockManagerConfig;

	protected function setUp(): void {
		parent::setUp();

		$this->user = $this->getTestUser()->getUser();
		$this->sysopUser = $this->getTestSysop()->getUser();
		$this->blockManagerConfig = [
			MainConfigNames::ApplyIpBlocksToXff => true,
			MainConfigNames::CookieSetOnAutoblock => true,
			MainConfigNames::CookieSetOnIpBlock => true,
			MainConfigNames::DnsBlacklistUrls => [],
			MainConfigNames::EnableDnsBlacklist => true,
			MainConfigNames::ProxyList => [],
			MainConfigNames::ProxyWhitelist => [],
			MainConfigNames::SecretKey => false,
			MainConfigNames::SoftBlockRanges => [],
		];
	}

	private function getBlockManager( $overrideConfig ) {
		return new BlockManager(
			...$this->getBlockManagerConstructorArgs( $overrideConfig )
		);
	}

	private function getBlockManagerConstructorArgs( $overrideConfig ) {
		$blockManagerConfig = array_merge( $this->blockManagerConfig, $overrideConfig );
		$this->overrideConfigValues( $blockManagerConfig );
		$services = $this->getServiceContainer();
		return [
			new LoggedServiceOptions(
				self::$serviceOptionsAccessLog,
				BlockManager::CONSTRUCTOR_OPTIONS,
				$services->getMainConfig()
			),
			$services->getUserFactory(),
			$services->getUserIdentityUtils(),
			new NullLogger(),
			$services->getHookContainer(),
			$services->getDatabaseBlockStore(),
			$services->getBlockTargetFactory(),
			$services->getProxyLookup()
		];
	}

	public function testGetBlock() {
		// Reset so that hooks are called
		$permissionManager = $this->getServiceContainer()->getPermissionManager();
		$permissionManager->invalidateUsersRightsCache();

		$onGetUserBlockCalled = false;
		$onGetUserBlockIP = false;
		$this->setTemporaryHook(
			'GetUserBlock',
			static function ( $user, $ip, &$block ) use ( &$onGetUserBlockCalled, &$onGetUserBlockIP ) {
				$onGetUserBlockCalled = true;
				$onGetUserBlockIP = $ip;
				return true;
			}
		);

		$blockManager = $this->getBlockManager( [] );
		$block = $blockManager->getBlock(
			$this->user,
			null,
			false
		);

		// We don't actually care about the block, just whether or not the right hooks were called
		$this->assertTrue(
			$onGetUserBlockCalled,
			'Check that HookRunner::onGetUserBlock was called'
		);
		$this->assertNull(
			$onGetUserBlockIP,
			'The `GetUserBlock` hook should have been called with null since we ' .
			'didn\'t pass a request'
		);
	}

	private function extractBlockOptions( $options ) {
		$blockOptions = $options['blockOptions'];
		if ( $options['target'] ) {
			$blockOptions['address'] = $options['target'];
		} else {
			$blockOptions['targetUser'] = $this->user;
		}
		$blockOptions['by'] ??= $this->sysopUser;
		return $blockOptions;
	}

	/**
	 * @dataProvider provideBlocksForShouldApplyCookieBlock
	 */
	public function testGetBlockFromCookieValue( $options, $expected ) {
		/** @var BlockManager $blockManager */
		$blockManager = TestingAccessWrapper::newFromObject(
			$this->getBlockManager( [
				MainConfigNames::CookieSetOnAutoblock => true,
				MainConfigNames::CookieSetOnIpBlock => true,
			] )
		);

		$block = $this->getServiceContainer()->getDatabaseBlockStore()
			->insertBlockWithParams( $this->extractBlockOptions( $options ) );

		$user = $options['registered'] ? $this->user : new User();
		$user->getRequest()->setCookie( 'BlockID', $blockManager->getCookieValue( $block ) );

		$this->assertSame( $expected, (bool)$blockManager->getBlockFromCookieValue(
			$user,
			$user->getRequest()
		) );
	}

	/**
	 * @dataProvider provideBlocksForShouldApplyCookieBlock
	 */
	public function testTrackBlockWithCookieRemovesBlocks( $options, $expectKeepCookie ) {
		/** @var BlockManager $blockManager */
		$blockManager = TestingAccessWrapper::newFromObject(
			$this->getBlockManager( [
				MainConfigNames::CookieSetOnAutoblock => true,
				MainConfigNames::CookieSetOnIpBlock => true,
			] )
		);

		$block = $this->getServiceContainer()->getDatabaseBlockStore()
			->insertBlockWithParams( $this->extractBlockOptions( $options ) );

		$user = $options['registered'] ? $this->user : new User();
		$user->getRequest()->setCookie( 'BlockID', $blockManager->getCookieValue( $block ) );

		$response = new FauxResponse;

		$blockManager->trackBlockWithCookie(
			$user,
			$response
		);

		$this->assertCount(
			$expectKeepCookie ? 0 : 1,
			$response->getCookies()
		);
	}

	public static function provideBlocksForShouldApplyCookieBlock() {
		return [
			'Autoblocking user block' => [
				[
					'target' => '',
					'registered' => true,
					'blockOptions' => [
						'enableAutoblock' => true
					],
				],
				true,
			],
			'Autoblocking user block for anonymous user' => [
				[
					'target' => '',
					'registered' => false,
					'blockOptions' => [
						'enableAutoblock' => true
					],
				],
				true,
			],
			'Non-autoblocking user block' => [
				[
					'target' => '',
					'registered' => true,
					'blockOptions' => [],
				],
				false,
			],
			'IP block for anonymous user' => [
				[
					'target' => '127.0.0.1',
					'registered' => false,
					'blockOptions' => [],
				],
				true,
			],
			'IP block for logged in user' => [
				[
					'target' => '127.0.0.1',
					'registered' => true,
					'blockOptions' => [],
				],
				false,
			],
			'IP range block for anonymous user' => [
				[
					'target' => '127.0.0.0/8',
					'registered' => false,
					'blockOptions' => [],
				],
				true,
			],
		];
	}

	/**
	 * @dataProvider provideIsLocallyBlockedProxy
	 */
	public function testIsLocallyBlockedProxy( $proxyList, $expected ) {
		/** @var BlockManager $blockManager */
		$blockManager = TestingAccessWrapper::newFromObject(
			$this->getBlockManager( [
				MainConfigNames::ProxyList => $proxyList
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
	 */
	public function testIsDnsBlacklisted( $options, $expected ) {
		$blockManagerConfig = [
			MainConfigNames::EnableDnsBlacklist => true,
			MainConfigNames::DnsBlacklistUrls => $options['blacklist'],
			MainConfigNames::ProxyWhitelist => $options['whitelist'],
		];

		$blockManager = $this->getMockBuilder( BlockManager::class )
			->setConstructorArgs( $this->getBlockManagerConstructorArgs( $blockManagerConfig ) )
			->onlyMethods( [ 'checkHost' ] )
			->getMock();
		$blockManager->method( 'checkHost' )
			->willReturnMap( [ [
				$options['dnsblQuery'],
				$options['dnsblResponse'],
			] ] );

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

	public function testGetUniqueBlocks() {
		$blockId = 100;

		$block = $this->getMockBuilder( DatabaseBlock::class )
			->onlyMethods( [ 'getId' ] )
			->getMock();
		$block->method( 'getId' )
			->willReturn( $blockId );

		$autoblock = $this->getMockBuilder( DatabaseBlock::class )
			->onlyMethods( [ 'getParentBlockId', 'getType' ] )
			->getMock();
		$autoblock->method( 'getParentBlockId' )
			->willReturn( $blockId );
		$autoblock->method( 'getType' )
			->willReturn( Block::TYPE_AUTO );

		$autoblockWithoutParentIdMethod = $this->getMockBuilder( AbstractBlock::class )
			->onlyMethods( [ 'getType' ] )
			->getMockForAbstractClass();
		$autoblockWithoutParentIdMethod->method( 'getType' )
			->willReturn( Block::TYPE_AUTO );

		$systemBlock = new SystemBlock();
		$blocks = [ $block, $block, $autoblock, $systemBlock, $autoblockWithoutParentIdMethod ];

		$blockManager = TestingAccessWrapper::newFromObject( $this->getBlockManager( [] ) );
		$this->assertArrayEquals(
			[ $block, $systemBlock, $autoblockWithoutParentIdMethod ],
			$blockManager->getUniqueBlocks( $blocks )
		);
	}

	/**
	 * @dataProvider provideTrackBlockWithCookie
	 */
	public function testTrackBlockWithCookie( $options, $expected ) {
		$this->overrideConfigValue( MainConfigNames::CookiePrefix, '' );

		if ( is_int( $options['block'] ) ) {
			$options['block'] = $this->getTrackableBlock( $options['block'] );
		} elseif ( is_array( $options['block'] ) ) {
			$blocks = [];
			foreach ( $options['block'] as $block ) {
				if ( is_int( $block ) ) {
					$blocks[] = $this->getTrackableBlock( $block );
				} elseif ( $block === 'system' ) {
					$blocks[] = new SystemBlock();
				}
			}
			$options['block'] = new CompositeBlock( [
				'originalBlocks' => $blocks
			] );
		}

		$request = new FauxRequest();
		if ( $options['cookieSet'] ) {
			$request->setCookie( 'BlockID', 'the value does not matter' );
		}
		/** @var FauxResponse $response */
		$response = $request->response();

		$user = $this->getMockBuilder( User::class )
			->onlyMethods( [ 'getBlock', 'getRequest' ] )
			->getMock();
		$user->method( 'getBlock' )
			->willReturn( $options['block'] );
		$user->method( 'getRequest' )
			->willReturn( $request );

		// Although the block cookie is set via DeferredUpdates, in command line mode updates are
		// processed immediately
		$blockManager = $this->getBlockManager( [
			MainConfigNames::SecretKey => '',
			MainConfigNames::CookieSetOnIpBlock => true,
		] );
		$blockManager->trackBlockWithCookie( $user, $response );

		$this->assertCount( $expected['count'], $response->getCookies() );
		$this->assertEquals( $expected['value'], $response->getCookie( 'BlockID' ) );
	}

	public static function provideTrackBlockWithCookie() {
		$blockId = 123;
		return [
			'Block cookie is already set; there is a trackable block' => [
				[
					'cookieSet' => true,
					'block' => $blockId,
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
					'block' => $blockId,
				],
				[
					'count' => 1,
					'value' => $blockId,
				]
			],
			'Block cookie is not yet set; there is a composite block with a trackable block' => [
				[
					'cookieSet' => false,
					'block' => [
						'system',
						$blockId,
					],
				],
				[
					'count' => 1,
					'value' => $blockId,
				]
			],
			'Block cookie is not yet set; there is a composite block but no trackable block' => [
				[
					'cookieSet' => false,
					'block' => [
						'system',
						'system',
					],
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
			->onlyMethods( [ 'getType', 'getId' ] )
			->getMock();
		$block->method( 'getType' )
			->willReturn( DatabaseBlock::TYPE_IP );
		$block->method( 'getId' )
			->willReturn( $blockId );
		return $block;
	}

	/**
	 * @dataProvider provideSetBlockCookie
	 */
	public function testSetBlockCookie( $expiryDelta, $expectedExpiryDelta ) {
		$this->overrideConfigValue( MainConfigNames::CookiePrefix, '' );

		$request = new FauxRequest();
		$response = $request->response();

		/** @var BlockManager $blockManager */
		$blockManager = TestingAccessWrapper::newFromObject(
			$this->getBlockManager( [
				MainConfigNames::SecretKey => '',
				MainConfigNames::CookieSetOnIpBlock => true,
			] )
		);

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
	 * @dataProvider provideShouldTrackBlockWithCookie
	 */
	public function testShouldTrackBlockWithCookie( $options, $expected ) {
		$block = $this->getMockBuilder( DatabaseBlock::class )
			->onlyMethods( [ 'getType', 'isAutoblocking' ] )
			->getMock();
		$block->method( 'getType' )
			->willReturn( $options['type'] );
		if ( isset( $options['autoblocking'] ) ) {
			$block->method( 'isAutoblocking' )
				->willReturn( $options['autoblocking'] );
		}

		/** @var BlockManager $blockManager */
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
					'blockManagerConfig' => [ MainConfigNames::CookieSetOnIpBlock => true ],
				],
				true
			],
			'IP range block, anonymous user, IP block cookies enabled' => [
				[
					'type' => DatabaseBlock::TYPE_RANGE,
					'isAnon' => true,
					'blockManagerConfig' => [ MainConfigNames::CookieSetOnIpBlock => true ],
				],
				true
			],
			'IP block, anonymous user, IP block cookies disabled' => [
				[
					'type' => DatabaseBlock::TYPE_IP,
					'isAnon' => true,
					'blockManagerConfig' => [ MainConfigNames::CookieSetOnIpBlock => false ],
				],
				false
			],
			'IP block, logged in user, IP block cookies enabled' => [
				[
					'type' => DatabaseBlock::TYPE_IP,
					'isAnon' => false,
					'blockManagerConfig' => [ MainConfigNames::CookieSetOnIpBlock => true ],
				],
				false
			],
			'User block, anonymous, autoblock cookies enabled, block is autoblocking' => [
				[
					'type' => DatabaseBlock::TYPE_USER,
					'isAnon' => true,
					'blockManagerConfig' => [ MainConfigNames::CookieSetOnAutoblock => true ],
					'autoblocking' => true,
				],
				false
			],
			'User block, logged in, autoblock cookies enabled, block is autoblocking' => [
				[
					'type' => DatabaseBlock::TYPE_USER,
					'isAnon' => false,
					'blockManagerConfig' => [ MainConfigNames::CookieSetOnAutoblock => true ],
					'autoblocking' => true,
				],
				true
			],
			'User block, logged in, autoblock cookies disabled, block is autoblocking' => [
				[
					'type' => DatabaseBlock::TYPE_USER,
					'isAnon' => false,
					'blockManagerConfig' => [ MainConfigNames::CookieSetOnAutoblock => false ],
					'autoblocking' => true,
				],
				false
			],
			'User block, logged in, autoblock cookies enabled, block is not autoblocking' => [
				[
					'type' => DatabaseBlock::TYPE_USER,
					'isAnon' => false,
					'blockManagerConfig' => [ MainConfigNames::CookieSetOnAutoblock => true ],
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

	public function testClearBlockCookie() {
		$this->overrideConfigValue( MainConfigNames::CookiePrefix, '' );

		$request = new FauxRequest();
		$response = $request->response();
		$response->setCookie( 'BlockID', '100' );
		$this->assertSame( '100', $response->getCookie( 'BlockID' ) );

		BlockManager::clearBlockCookie( $response );
		$this->assertSame( '', $response->getCookie( 'BlockID' ) );
	}

	/**
	 * @dataProvider provideGetIdFromCookieValue
	 */
	public function testGetIdFromCookieValue( $options, $expected ) {
		/** @var BlockManager $blockManager */
		$blockManager = TestingAccessWrapper::newFromObject(
			$this->getBlockManager( [ MainConfigNames::SecretKey => $options['secretKey'] ] ) );
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
	 */
	public function testGetCookieValue( $options, $expected ) {
		/** @var BlockManager $blockManager */
		$blockManager = TestingAccessWrapper::newFromObject( $this->getBlockManager( [
			MainConfigNames::SecretKey => $options['secretKey']
		] ) );

		$block = $this->getMockBuilder( DatabaseBlock::class )
			->onlyMethods( [ 'getId' ] )
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
	 * @dataProvider provideGetXffBlocks
	 */
	public function testGetXffBlocks(
		$applyIpBlocksToXff,
		$proxyWhiteList,
		$isAnon,
		$expected
	) {
		$xff = '1.2.3.4, 5.6.7.8, 9.10.11.12';
		$ip = '1.2.3.4';

		$blockManagerConfig = [
			MainConfigNames::ApplyIpBlocksToXff => $applyIpBlocksToXff,
			MainConfigNames::ProxyWhitelist => $proxyWhiteList,
		];

		$blockManagerMock = $this->getMockBuilder( BlockManager::class )
			->setConstructorArgs( $this->getBlockManagerConstructorArgs( $blockManagerConfig ) )
			->onlyMethods( [ 'getBlocksForIPList' ] )
			->getMock();
		$blockManagerMock->method( 'getBlocksForIPList' )
			->willReturnCallback( function () use ( $isAnon ) {
				if ( $isAnon ) {
					return [ $this->createMock( DatabaseBlock::class ) ];
				} else {
					return [];
				}
			} );

		/** @var BlockManager $blockManager */
		$blockManager = TestingAccessWrapper::newFromObject( $blockManagerMock );

		$this->assertSame(
			$expected,
			(bool)$blockManager->getXffBlocks( $ip, $xff, $isAnon, false )
		);
	}

	public static function provideGetXffBlocks() {
		return [
			'ApplyIpBlocksToXff config is false' => [
				'applyIpBlocksToXff' => false,
				'proxyWhiteList' => [],
				'isAnon' => true,
				false,
			],
			'IP is in ProxyWhiteList' => [
				'applyIpBlocksToXff' => true,
				'proxyWhiteList' => [ '1.2.3.4' ],
				'isAnon' => true,
				false,
			],
			'User is logged in' => [
				'applyIpBlocksToXff' => true,
				'proxyWhiteList' => [],
				'isAnon' => false,
				false,
			],
			'IP is in XFF list but not in ProxyWhiteList' => [
				'applyIpBlocksToXff' => true,
				'proxyWhiteList' => [],
				'isAnon' => true,
				true,
			],
		];
	}

	/**
	 * @dataProvider provideGetSystemIpBlocks
	 */
	public function testGetSystemIpBlocks(
		$proxyWhitelist,
		$softBlockRanges,
		$isLocallyBlockedProxy,
		$isDnsBlacklisted,
		$isAnon,
		$expected
	) {
		$ip = '1.2.3.4';

		$blockManagerConfig = [
			MainConfigNames::ProxyWhitelist => $proxyWhitelist,
			MainConfigNames::SoftBlockRanges => $softBlockRanges,
			MainConfigNames::ProxyList => ( $isLocallyBlockedProxy ? [ $ip ] : [] ),
		];

		$blockManagerMock = $this->getMockBuilder( BlockManager::class )
			->setConstructorArgs( $this->getBlockManagerConstructorArgs( $blockManagerConfig ) )
			->onlyMethods( [ 'isDnsBlacklisted' ] )
			->getMock();
		$blockManagerMock->method( 'isDnsBlacklisted' )
			->willReturn( $isDnsBlacklisted );

		/** @var BlockManager $blockManager */
		$blockManager = TestingAccessWrapper::newFromObject( $blockManagerMock );

		$this->assertSame(
			$expected,
			(bool)$blockManager->getSystemIpBlocks( $ip, $isAnon )
		);
	}

	public static function provideGetSystemIpBlocks() {
		return [
			'IP is in ProxyWhiteList' => [
				'proxyWhitelist' => [ '1.2.3.4' ],
				'softBlockRanges' => [],
				'isLocallyBlockedProxy' => true,
				'isDnsBlacklisted' => true,
				'isAnon' => true,
				false,
			],
			'IP is locally blocked proxy only' => [
				'proxyWhitelist' => [],
				'softBlockRanges' => [],
				'isLocallyBlockedProxy' => true,
				'isDnsBlacklisted' => false,
				'isAnon' => false,
				true,
			],
			'IP is DNS blacklisted only, anon' => [
				'proxyWhitelist' => [],
				'softBlockRanges' => [],
				'isLocallyBlockedProxy' => false,
				'isDnsBlacklisted' => true,
				'isAnon' => true,
				true,
			],
			'IP is DNS blacklisted only, logged in' => [
				'proxyWhitelist' => [],
				'softBlockRanges' => [],
				'isLocallyBlockedProxy' => false,
				'isDnsBlacklisted' => true,
				'isAnon' => false,
				false,
			],
			'IP is in SoftBlockRanges and ProxyWhiteList, anon' => [
				'proxyWhitelist' => [ '1.2.3.4' ],
				'softBlockRanges' => [ '1.2.3.4' ],
				'isLocallyBlockedProxy' => false,
				'isDnsBlacklisted' => false,
				'isAnon' => true,
				true,
			],
			'IP is in SoftBlockRanges and ProxyWhiteList, logged in' => [
				'proxyWhitelist' => [ '1.2.3.4' ],
				'softBlockRanges' => [ '1.2.3.4' ],
				'isLocallyBlockedProxy' => false,
				'isDnsBlacklisted' => false,
				'isAnon' => false,
				false,
			],
		];
	}

	public function testGetBlocksForIPList() {
		$blockManager = $this->getBlockManager( [] );
		$block = $this->getServiceContainer()
			->getDatabaseBlockStore()
			->insertBlockWithParams( [
				'address' => '1.2.3.4',
				'by' => $this->getTestSysop()->getUser(),
			] );

		// Early return of empty array if no ips in the list
		$list = $blockManager->getBlocksForIPList( [], true, false );
		$this->assertCount(
			0,
			$list,
			'No blocks retrieved if no ips listed'
		);

		// Early return of empty array if all ips are either invalid or trusted proxies,
		// '192.168.1.1' is set to trusted in setUp();
		$list = $blockManager->getBlocksForIPList(
			[ '300.300.300.300', '192.168.1.1' ],
			true,
			false
		);
		$this->assertCount(
			0,
			$list,
			'No blocks retrieved if all ips are invalid or trusted proxies'
		);

		// Actually fetching, block was inserted above
		$list = $blockManager->getBlocksForIPList( [ '1.2.3.4' ], true, false );
		$this->assertCount(
			1,
			$list,
			'Block retrieved for the blocked ip'
		);
		$this->assertInstanceOf(
			DatabaseBlock::class,
			$list[0],
			'DatabaseBlock returned'
		);
		$this->assertSame(
			$block->getId(),
			$list[0]->getId(),
			'Block returned is the correct one'
		);
	}

	/**
	 * @coversNothing
	 */
	public function testAllServiceOptionsUsed() {
		$this->assertAllServiceOptionsUsed();
	}

	/**
	 * Test ported from DatabaseBlock
	 */
	public function testBlockedUserCanNotCreateAccount() {
		$username = 'BlockedUserToCreateAccountWith';
		$u = User::createNew( $username );
		$userId = $u->getId();
		$this->assertNotEquals( 0, $userId, 'Check user id is not 0' );
		TestUser::setPasswordForUser( $u, 'NotRandomPass' );
		unset( $u );

		$blockStore = $this->getServiceContainer()->getDatabaseBlockStore();
		$this->assertNull(
			$blockStore->newFromTarget( $username ),
			"$username should not be blocked"
		);

		// Reload user
		$userFactory = $this->getServiceContainer()->getUserFactory();
		$u = $userFactory->newFromName( $username );
		$this->assertTrue(
			$u->isDefinitelyAllowed( 'createaccount' ),
			"Our sandbox user should be able to create account before being blocked"
		);

		// Foreign perspective (blockee not on current wiki)...
		$block = $blockStore->insertBlockWithParams( [
			'address' => $username,
			'reason' => 'crosswiki block...',
			'timestamp' => wfTimestampNow(),
			'expiry' => $this->getDb()->getInfinity(),
			'createAccount' => true,
			'enableAutoblock' => true,
			'hideName' => true,
			'blockEmail' => true,
			'by' => UserIdentityValue::newExternal( 'm', 'MetaWikiUser' ),
		] );

		// Reload block from DB
		$userBlock = $blockStore->newFromTarget( $username );
		$this->assertTrue(
			(bool)$block->appliesToRight( 'createaccount' ),
			"Block object in DB should block right 'createaccount'"
		);

		$this->assertInstanceOf(
			DatabaseBlock::class,
			$userBlock,
			"'$username' block block object should be existent"
		);

		// Reload user
		$u = $userFactory->newFromName( $username );
		$this->assertFalse(
			$u->isDefinitelyAllowed( 'createaccount' ),
			"Our sandbox user '$username' should NOT be able to create account"
		);
	}

	public static function providerXff() {
		return [
			[ 'xff' => '1.2.3.4, 70.2.1.1, 60.2.1.1, 2.3.4.5',
				'count' => 2,
				'result' => 'Range Hardblock'
			],
			[ 'xff' => '1.2.3.4, 50.2.1.1, 60.2.1.1, 2.3.4.5',
				'count' => 2,
				'result' => 'Range Softblock with AC Disabled'
			],
			[ 'xff' => '1.2.3.4, 70.2.1.1, 50.1.1.1, 2.3.4.5',
				'count' => 2,
				'result' => 'Exact Softblock'
			],
			[ 'xff' => '1.2.3.4, 70.2.1.1, 50.2.1.1, 50.1.1.1, 2.3.4.5',
				'count' => 3,
				'result' => 'Exact Softblock'
			],
			[ 'xff' => '1.2.3.4, 70.2.1.1, 50.2.1.1, 2.3.4.5',
				'count' => 2,
				'result' => 'Range Hardblock'
			],
			[ 'xff' => '1.2.3.4, 70.2.1.1, 60.2.1.1, 2.3.4.5',
				'count' => 2,
				'result' => 'Range Hardblock'
			],
			[ 'xff' => '50.2.1.1, 60.2.1.1, 2.3.4.5',
				'count' => 2,
				'result' => 'Range Softblock with AC Disabled'
			],
			[ 'xff' => '1.2.3.4, 50.1.1.1, 60.2.1.1, 2.3.4.5',
				'count' => 2,
				'result' => 'Exact Softblock'
			],
			[ 'xff' => '1.2.3.4, <$A_BUNCH-OF{INVALID}TEXT\>, 60.2.1.1, 2.3.4.5',
				'count' => 1,
				'result' => 'Range Softblock with AC Disabled'
			],
			[ 'xff' => '1.2.3.4, 50.2.1.1, 2001:4860:4001:802::1003, 2.3.4.5',
				'count' => 2,
				'result' => 'Range6 Hardblock'
			],
		];
	}

	/**
	 * @dataProvider providerXff
	 */
	public function testBlocksOnXff( $xff, $exCount, $exResult ) {
		$this->setupForXff();
		$this->getServiceContainer()->getDatabaseBlockStore()
			->insertBlockWithParams( [
				'targetUser' => $this->getTestUser()->getUserIdentity(),
				'by' => $this->getTestSysop()->getUserIdentity()
			] );

		$list = array_map( 'trim', explode( ',', $xff ) );
		$manager = $this->getBlockManager( [] );
		$xffblocks = $manager->getBlocksForIPList( $list, true, false );
		$this->assertCount( $exCount, $xffblocks, 'Number of blocks for ' . $xff );
	}

	private function setupForXff() {
		$blockList = [
			[ 'target' => '70.2.0.0/16',
				'type' => DatabaseBlock::TYPE_RANGE,
				'desc' => 'Range Hardblock',
				'ACDisable' => false,
				'isHardblock' => true,
				'isAutoBlocking' => false,
			],
			[ 'target' => '2001:4860:4001:0:0:0:0:0/48',
				'type' => DatabaseBlock::TYPE_RANGE,
				'desc' => 'Range6 Hardblock',
				'ACDisable' => false,
				'isHardblock' => true,
				'isAutoBlocking' => false,
			],
			[ 'target' => '60.2.0.0/16',
				'type' => DatabaseBlock::TYPE_RANGE,
				'desc' => 'Range Softblock with AC Disabled',
				'ACDisable' => true,
				'isHardblock' => false,
				'isAutoBlocking' => false,
			],
			[ 'target' => '50.2.0.0/16',
				'type' => DatabaseBlock::TYPE_RANGE,
				'desc' => 'Range Softblock',
				'ACDisable' => false,
				'isHardblock' => false,
				'isAutoBlocking' => false,
			],
			[ 'target' => '50.1.1.1',
				'type' => DatabaseBlock::TYPE_IP,
				'desc' => 'Exact Softblock',
				'ACDisable' => false,
				'isHardblock' => false,
				'isAutoBlocking' => false,
			],
		];

		$targetFactory = $this->getServiceContainer()->getBlockTargetFactory();
		$blockStore = $this->getServiceContainer()->getDatabaseBlockStore();
		$blocker = $this->getTestUser()->getUser();
		foreach ( $blockList as $insBlock ) {
			$block = new DatabaseBlock();
			$block->setTarget( $targetFactory->newFromString( $insBlock['target'] ) );
			$block->setBlocker( $blocker );
			$block->setReason( $insBlock['desc'] );
			$block->setExpiry( 'infinity' );
			$block->isCreateAccountBlocked( $insBlock['ACDisable'] );
			$block->isHardblock( $insBlock['isHardblock'] );
			$block->isAutoblocking( $insBlock['isAutoBlocking'] );
			$blockStore->insertBlock( $block );
		}
	}

}
