<?php

use Wikimedia\TestingAccessWrapper;
use MediaWiki\Block\SystemBlock;

/**
 * @covers ApiBlockInfoTrait
 */
class ApiBlockInfoTraitTest extends MediaWikiTestCase {
	/**
	 * @dataProvider provideGetBlockInfo
	 */
	public function testGetBlockInfo( $block, $expectedInfo ) {
		$mock = $this->getMockForTrait( ApiBlockInfoTrait::class );
		$info = TestingAccessWrapper::newFromObject( $mock )->getBlockInfo( $block );
		$subset = array_merge( [
			'blockid' => null,
			'blockedby' => '',
			'blockedbyid' => 0,
			'blockreason' => '',
			'blockexpiry' => 'infinite',
		], $expectedInfo );
		$this->assertArraySubset( $subset, $info );
	}

	public static function provideGetBlockInfo() {
		return [
			'Sitewide block' => [
				new Block(),
				[ 'blockpartial' => false ],
			],
			'Partial block' => [
				new Block( [ 'sitewide' => false ] ),
				[ 'blockpartial' => true ],
			],
			'System block' => [
				new SystemBlock( [ 'systemBlock' => 'proxy' ] ),
				[ 'systemblocktype' => 'proxy' ]
			],
		];
	}
}
