<?php

use MediaWiki\Block\BlockErrorFormatter;
use MediaWiki\Block\CompositeBlock;
use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Block\SystemBlock;
use MediaWiki\Context\DerivativeContext;
use MediaWiki\Context\IContextSource;
use MediaWiki\Context\RequestContext;
use MediaWiki\Message\Message;
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

	/**
	 * @return DerivativeContext
	 */
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

		return new $blockClass( $blockData );
	}
}
