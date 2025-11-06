<?php

namespace MediaWiki\Tests\Block;

use MediaWiki\Block\BlockErrorFormatter;
use MediaWiki\Block\CompositeBlock;
use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Block\SystemBlock;
use MediaWiki\Block\UserBlockTarget;
use MediaWiki\Context\DerivativeContext;
use MediaWiki\Context\IContextSource;
use MediaWiki\Context\RequestContext;
use MediaWiki\Message\Message;
use MediaWiki\User\UserIdentityValue;
use MediaWikiIntegrationTestCase;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\LBFactory;
use Wikimedia\Rdbms\LoadBalancer;

/**
 * @todo Can this be converted to unit tests?
 *
 * @group Blocking
 * @covers \MediaWiki\Block\BlockErrorFormatter
 */
class BlockErrorFormatterTest extends MediaWikiIntegrationTestCase {

	private function getContext(): DerivativeContext {
		$context = new DerivativeContext( RequestContext::getMain() );

		$context->setLanguage(
			$this->getServiceContainer()
				->getLanguageFactory()->getLanguage( 'qqx' )
		);

		return $context;
	}

	private function getBlockErrorFormatter( IContextSource $context ): BlockErrorFormatter {
		return $this->getServiceContainer()
			->getFormatterFactory()->getBlockErrorFormatter( $context );
	}

	protected function setUp(): void {
		parent::setUp();

		$db = $this->createMock( IDatabase::class );
		$db->method( 'getInfinity' )->willReturn( 'infinity' );
		$db->method( 'decodeExpiry' )->willReturnArgument( 0 );

		$lb = $this->createNoOpMock(
			LoadBalancer::class,
			[ 'getConnection' ]
		);
		$lb->method( 'getConnection' )->willReturn( $db );

		$lbFactory = $this->createNoOpMock(
			LBFactory::class,
			[ 'getReplicaDatabase', 'getPrimaryDatabase', 'getMainLB', ]
		);
		$lbFactory->method( 'getReplicaDatabase' )->willReturn( $db );
		$lbFactory->method( 'getPrimaryDatabase' )->willReturn( $db );
		$lbFactory->method( 'getMainLB' )->willReturn( $lb );
		$this->setService( 'DBLoadBalancerFactory', $lbFactory );
	}

	/**
	 * @dataProvider provideTestGetMessage
	 */
	public function testGetMessage( $blockClass, $blockData, $expectedKey, $expectedParams ) {
		$block = $this->makeBlock(
			$blockClass,
			$blockData
		);
		$context = $this->getContext();

		$formatter = $this->getBlockErrorFormatter( $context );
		$message = $formatter->getMessage(
			$block,
			$context->getUser(),
			$context->getLanguage(),
			'1.2.3.4'
		);

		$this->assertSame( $expectedKey, $message->getKey() );
		$this->assertSame( $expectedParams, $message->getParams() );
	}

	public static function provideTestGetMessage() {
		$timestamp = '20000101000000';
		$expiry = '20010101000000';

		$databaseBlock = [
			'timestamp' => $timestamp,
			'expiry' => $expiry,
			'reason' => 'Test reason.',
		];

		$systemBlock = [
			'timestamp' => $timestamp,
			'systemBlock' => 'test',
			'reason' => new Message( 'proxyblockreason' ),
		];

		$compositeBlock = [
			'timestamp' => $timestamp,
			'originalBlocks' => [
				[ DatabaseBlock::class, $databaseBlock ],
				[ SystemBlock::class, $systemBlock ]
			]
		];

		return [
			'Database block' => [
				DatabaseBlock::class,
				$databaseBlock,
				'blockedtext',
				[
					'',
					'Test reason.',
					'1.2.3.4',
					'',
					null, // Block not inserted
					'00:00, 1 (january) 2001',
					'',
					'00:00, 1 (january) 2000',
					false,
					false,
				],
			],
			'Database block (autoblock)' => [
				DatabaseBlock::class,
				[
					'timestamp' => $timestamp,
					'expiry' => $expiry,
					'auto' => true,
				],
				'autoblockedtext',
				[
					'',
					'(blockednoreason)',
					'1.2.3.4',
					'',
					null, // Block not inserted
					'00:00, 1 (january) 2001',
					'',
					'00:00, 1 (january) 2000',
					false,
					false,
				],
			],
			'Database block (partial block)' => [
				DatabaseBlock::class,
				[
					'timestamp' => $timestamp,
					'expiry' => $expiry,
					'sitewide' => false,
				],
				'blockedtext-partial',
				[
					'',
					'(blockednoreason)',
					'1.2.3.4',
					'',
					null, // Block not inserted
					'00:00, 1 (january) 2001',
					'',
					'00:00, 1 (january) 2000',
					false,
					false,
				],
			],
			'Database block (talk page and email allowed)' => [
				DatabaseBlock::class,
				[
					'timestamp' => $timestamp,
					'expiry' => $expiry,
					'allowUsertalk' => true,
					'blockEmail' => false,
					'targetName' => 'Alice'
				],
				'blockedtext',
				[
					'',
					'(blockednoreason)',
					'1.2.3.4',
					'',
					null, // Block not inserted
					'00:00, 1 (january) 2001',
					"\u{202A}Alice\u{202C}",
					'00:00, 1 (january) 2000',
					false,
					false,
				],
			],
			'Database block (talk page only allowed)' => [
				DatabaseBlock::class,
				[
					'timestamp' => $timestamp,
					'expiry' => $expiry,
					'allowUsertalk' => true,
					'blockEmail' => true,
					'targetName' => 'Alice'
				],
				'blockedtext',
				[
					'',
					'(blockednoreason)',
					'1.2.3.4',
					'',
					null, // Block not inserted
					'00:00, 1 (january) 2001',
					//
					"\u{202A}Alice\u{202C}",
					'00:00, 1 (january) 2000',
					false,
					true,
				],
			],
			'Database block (email only allowed)' => [
				DatabaseBlock::class,
				[
					'timestamp' => $timestamp,
					'expiry' => $expiry,
					'allowUsertalk' => false,
					'blockEmail' => false,
					'targetName' => 'Alice'
				],
				'blockedtext',
				[
					'',
					'(blockednoreason)',
					'1.2.3.4',
					'',
					null, // Block not inserted
					'00:00, 1 (january) 2001',
					"\u{202A}Alice\u{202C}",
					'00:00, 1 (january) 2000',
					true,
					false,
				],
			],
			'Database block (talk page and email disallowed)' => [
				DatabaseBlock::class,
				[
					'timestamp' => $timestamp,
					'expiry' => $expiry,
					'allowUsertalk' => false,
					'blockEmail' => true,
					'targetName' => 'Alice'
				],
				'blockedtext',
				[
					'',
					'(blockednoreason)',
					'1.2.3.4',
					'',
					null, // Block not inserted
					'00:00, 1 (january) 2001',
					"\u{202A}Alice\u{202C}",
					'00:00, 1 (january) 2000',
					true,
					true,
				],
			],
			'System block (type \'test\')' => [
				SystemBlock::class,
				$systemBlock,
				'systemblockedtext',
				[
					'',
					'(proxyblockreason)',
					'1.2.3.4',
					'',
					'test',
					'(infiniteblock)',
					'',
					'00:00, 1 (january) 2000',
					false,
					false,
				],
			],
			'System block (type \'test\') with reason parameters' => [
				SystemBlock::class,
				[
					'timestamp' => $timestamp,
					'systemBlock' => 'test',
					'reason' => new Message( 'softblockrangesreason', [ '1.2.3.4' ] ),
				],
				'systemblockedtext',
				[
					'',
					'(softblockrangesreason: 1.2.3.4)',
					'1.2.3.4',
					'',
					'test',
					'(infiniteblock)',
					'',
					'00:00, 1 (january) 2000',
					false,
					false,
				],
			],
			'Composite block (original blocks not inserted)' => [
				CompositeBlock::class,
				$compositeBlock,
				'blockedtext-composite',
				[
					'',
					'(blockednoreason)',
					'1.2.3.4',
					'',
					'(blockedtext-composite-no-ids)',
					'(infiniteblock)',
					'',
					'00:00, 1 (january) 2000',
					false,
					false,
				],
			],
		];
	}

	/**
	 * @dataProvider provideTestGetMessageCompositeBlocks
	 */
	public function testGetMessageCompositeBlocks( $ids, $expected ) {
		$block = $this->getMockBuilder( CompositeBlock::class )
			->onlyMethods( [ 'getIdentifier' ] )
			->getMock();
		$block->method( 'getIdentifier' )
			->willReturn( $ids );

		$context = RequestContext::getMain();

		$formatter = $this->getBlockErrorFormatter( $context );
		$this->assertContains(
			$expected,
			$formatter->getMessage(
				$block,
				$context->getUser(),
				$context->getLanguage(),
				$context->getRequest()->getIP()
			)->getParams()
		);
	}

	public static function provideTestGetMessageCompositeBlocks() {
		return [
			'All original blocks are system blocks' => [
				[ 'test', 'test' ],
				'Your IP address appears in multiple blocklists',
			],
			'One original block is a database block' => [
				[ 100, 'test' ],
				'Relevant block IDs: #100 (your IP address may also appear in a blocklist)',
			],
			'Several original blocks are database blocks' => [
				[ 100, 101, 102 ],
				'Relevant block IDs: #100, #101, #102 (your IP address may also appear in a blocklist)',
			],
		];
	}

	/**
	 * @dataProvider provideTestGetMessages
	 */
	public function testGetMessages( $blockClass, $blockData, $expectedKeys ) {
		$block = $this->makeBlock(
			$blockClass,
			$blockData
		);

		$context = $this->getContext();

		$formatter = $this->getBlockErrorFormatter( $context );
		$messages = $formatter->getMessages(
			$block,
			$context->getUser(),
			'1.2.3.4'
		);

		$this->assertSame( $expectedKeys, array_map( static function ( $message ) {
			return $message->getKey();
		}, $messages ) );
	}

	public static function provideTestGetMessages() {
		$timestamp = '20000101000000';
		$expiry = '20010101000000';

		$databaseBlock = [
			'timestamp' => $timestamp,
			'expiry' => $expiry,
			'reason' => 'Test reason.',
		];

		$systemBlock = [
			'timestamp' => $timestamp,
			'systemBlock' => 'test',
			'reason' => new Message( 'proxyblockreason' ),
		];

		$compositeBlock = [
			'timestamp' => $timestamp,
			'originalBlocks' => [
				[ DatabaseBlock::class, $databaseBlock ],
				[ SystemBlock::class, $systemBlock ]
			]
		];

		return [
			'Database block' => [
				DatabaseBlock::class,
				$databaseBlock,
				[ 'blockedtext' ],
			],

			'System block (type \'test\')' => [
				SystemBlock::class,
				$systemBlock,
				[ 'systemblockedtext' ],
			],
			'Composite block (original blocks not inserted)' => [
				CompositeBlock::class,
				$compositeBlock,
				[ 'blockedtext', 'systemblockedtext' ],
			],
		];
	}

	/**
	 * @param string $blockClass
	 * @param array $blockData
	 *
	 * @return mixed
	 */
	private function makeBlock( $blockClass, $blockData ) {
		foreach ( $blockData['originalBlocks'] ?? [] as $key => $originalBlock ) {
			$blockData['originalBlocks'][$key] = $this->makeBlock( ...$originalBlock );
		}
		// PHPUnit data providers should return plain data (T332865), so this needs to be done in the test
		// not the data provider
		if ( isset( $blockData['targetName'] ) ) {
			$blockData['target'] = new UserBlockTarget( new UserIdentityValue( 0, $blockData['targetName'] ) );
			unset( $blockData['targetName'] );
		}

		return new $blockClass( $blockData );
	}
}
