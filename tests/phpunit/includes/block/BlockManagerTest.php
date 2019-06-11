<?php

use MediaWiki\Block\BlockManager;
use MediaWiki\Block\DatabaseBlock;

/**
 * @group Blocking
 * @group Database
 * @coversDefaultClass \MediaWiki\Block\BlockManager
 */
class BlockManagerTest extends MediaWikiTestCase {

	/** @var User */
	protected $user;

	/** @var int */
	protected $sysopId;

	protected function setUp() {
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
		$blockManagerConfig = array_merge( $this->blockManagerConfig, $overrideConfig );
		return new BlockManager(
			$this->user,
			$this->user->getRequest(),
			...array_values( $blockManagerConfig )
		);
	}

	/**
	 * @dataProvider provideGetBlockFromCookieValue
	 * @covers ::getBlockFromCookieValue
	 */
	public function testGetBlockFromCookieValue( $options, $expected ) {
		$blockManager = $this->getBlockManager( [
			'wgCookieSetOnAutoblock' => true,
			'wgCookieSetOnIpBlock' => true,
		] );

		$block = new DatabaseBlock( array_merge( [
			'address' => $options[ 'target' ] ?: $this->user,
			'by' => $this->sysopId,
		], $options[ 'blockOptions' ] ) );
		$block->insert();

		$class = new ReflectionClass( BlockManager::class );
		$method = $class->getMethod( 'getBlockFromCookieValue' );
		$method->setAccessible( true );

		$user = $options[ 'loggedIn' ] ? $this->user : new User();
		$user->getRequest()->setCookie( 'BlockID', $block->getCookieValue() );

		$this->assertSame( $expected, (bool)$method->invoke(
			$blockManager,
			$user,
			$user->getRequest()
		) );

		$block->delete();
	}

	public static function provideGetBlockFromCookieValue() {
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
		$class = new ReflectionClass( BlockManager::class );
		$method = $class->getMethod( 'isLocallyBlockedProxy' );
		$method->setAccessible( true );

		$blockManager = $this->getBlockManager( [
			'wgProxyList' => $proxyList
		] );

		$ip = '1.2.3.4';
		$this->assertSame( $expected, $method->invoke( $blockManager, $ip ) );
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
	 * @covers ::isLocallyBlockedProxy
	 */
	public function testIsLocallyBlockedProxyDeprecated() {
		$proxy = '1.2.3.4';

		$this->hideDeprecated(
			'IP addresses in the keys of $wgProxyList (found the following IP ' .
			'addresses in keys: ' . $proxy . ', please move them to values)'
		);

		$class = new ReflectionClass( BlockManager::class );
		$method = $class->getMethod( 'isLocallyBlockedProxy' );
		$method->setAccessible( true );

		$blockManager = $this->getBlockManager( [
			'wgProxyList' => [ $proxy => 'test' ]
		] );

		$ip = '1.2.3.4';
		$this->assertSame( true, $method->invoke( $blockManager, $ip ) );
	}

	/**
	 * @dataProvider provideIsDnsBlacklisted
	 * @covers ::isDnsBlacklisted
	 * @covers ::inDnsBlacklist
	 */
	public function testIsDnsBlacklisted( $options, $expected ) {
		$blockManagerConfig = array_merge( $this->blockManagerConfig, [
			'wgEnableDnsBlacklist' => true,
			'wgDnsBlacklistUrls' => $options['blacklist'],
			'wgProxyWhitelist' => $options['whitelist'],
		] );

		$blockManager = $this->getMockBuilder( BlockManager::class )
			->setConstructorArgs(
				array_merge( [
					$this->user,
					$this->user->getRequest(),
				], $blockManagerConfig ) )
			->setMethods( [ 'checkHost' ] )
			->getMock();

		$blockManager->expects( $this->any() )
			->method( 'checkHost' )
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
}
