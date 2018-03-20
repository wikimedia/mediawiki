<?php

/**
 * @group API
 * @group Database
 * @group medium
 *
 * @covers ApiQueryBlocks
 */
class ApiQueryBlocksIntegrationTest extends ApiTestCase {

	public function __construct( $name = null, array $data = [], $dataName = '' ) {
		parent::__construct( $name, $data, $dataName );

		$this->tablesUsed[] = 'ipblocks';
	}

	private function createIpBlock( $target ) {
		$options = [
			'address' => $target,
			'user' => 0,
			'by' => $this->getTestSysop()->getUser()->getId(),
			'reason' => 'Creating test block',
			'expiry' => time() + 100500,
		];

		$block = new Block( $options );
		$block->insert();

		$blockId = $block->getId();

		$this->assertNotFalse( $blockId, "Failed to create a test block for $target" );

		return $blockId;
	}

	/**
	 * T51504: Test query behavior for IP blocks and range blocks
	 *
	 * @dataProvider provideIp
	 * @param $query
	 * @param $expectedBlockTarget
	 */
	public function testQueryForIpBlocks( $query, $expectedBlockTarget ) {
		$blockId = $this->createIpBlock( $expectedBlockTarget );

		$result = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'blocks',
			'bkip' => $query,
		] );

		$this->assertEquals( $blockId, $result[0]['query']['blocks'][0]['id'] );
		$this->assertEquals( $expectedBlockTarget, $result[0]['query']['blocks'][0]['user'] );
	}

	public function provideIp() {
		// single IPv4 block
		yield [ '198.51.100.3/32', '198.51.100.3/32' ];
		yield [ '198.51.100.3', '198.51.100.3/32' ];
		yield [ '198.51.100.3/32', '198.51.100.3' ];
		yield [ '198.51.100.3', '198.51.100.3' ];

		// single IPv6 block
		yield [ '2601:2C5:280:123B:6582:29F:7619:E276/128', '2601:2C5:280:123B:6582:29F:7619:E276/128' ];
		yield [ '2601:2c5:280:123b:6582:29f:7619:e276', '2601:2C5:280:123B:6582:29F:7619:E276/128' ];
		yield [ '2601:2C5:280:123B:6582:29F:7619:E276/128', '2601:2C5:280:123B:6582:29F:7619:E276' ];
		yield [ '2601:2c5:280:123b:6582:29f:7619:e276', '2601:2C5:280:123B:6582:29F:7619:E276' ];

		// IPv4 range block
		yield [ '192.0.2.0/24', '192.0.2.0/24' ];
		yield [ '192.0.2.0/28', '192.0.2.0/24' ];

		// IPv6 range block
		yield [ '2001:DB8:0:0:0:0:0:0/32', '2001:DB8:0:0:0:0:0:0/32' ];
		yield [ '2001:DB8:0:0:0:0:0:0/38', '2001:DB8:0:0:0:0:0:0/32' ];
	}

	/**
	 * T51504: Test that range blocks are correctly excluded via show parameter
	 *
	 * @dataProvider provideRanges
	 * @internal @param string[] $targets
	 */
	public function testExcludeRangeBlocks( /* ...$targets */ ) {
		$targets = func_get_args();

		$rangeBlock = array_shift( $targets );
		$rangeBlockId = $this->createIpBlock( $rangeBlock );

		foreach ( $targets as $target ) {
			$this->createIpBlock( $target );
		}

		$result = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'blocks',
			'bkshow' => '!range'
		] );

		$this->assertNotEmpty( $result[0]['query']['blocks'] );

		foreach ( $result[0]['query']['blocks'] as $blockInfo ) {
			$this->assertNotEquals( $rangeBlockId, $blockInfo['id'] );
			$this->assertNotEquals( $rangeBlock, $blockInfo['user'] );
		}
	}

	/**
	 * T51504: Test that only range blocks are included when using show parameter to exclude others
	 *
	 * @dataProvider provideRanges
	 * @internal @param string[] $targets
	 */
	public function testIncludeOnlyRangeBlocks( /* ...$targets */ ) {
		$targets = func_get_args();

		$rangeBlock = array_shift( $targets );
		$rangeBlockId = $this->createIpBlock( $rangeBlock );

		foreach ( $targets as $target ) {
			$this->createIpBlock( $target );
		}

		$result = $this->doApiRequest( [
			'action' => 'query',
			'list' => 'blocks',
			'bkshow' => '!ip|!user'
		] );

		$this->assertEquals( $rangeBlockId, $result[0]['query']['blocks'][0]['id'] );
		$this->assertEquals( $rangeBlock, $result[0]['query']['blocks'][0]['user'] );
	}

	public function provideRanges() {
		yield [ '2001:DB8:0:0:0:0:0:0/32', '192.0.2.3' ];
		yield [ '192.0.2.0/28', '2601:2C5:280:123B:6582:29F:7619:E276' ];
	}
}
