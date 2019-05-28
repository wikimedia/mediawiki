<?php

use Wikimedia\TestingAccessWrapper;
use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Block\SystemBlock;

/**
 * @covers ApiBlockInfoTrait
 */
class ApiBlockInfoTraitTest extends MediaWikiTestCase {
	/**
	 * @dataProvider provideGetBlockDetails
	 */
	public function testGetBlockDetails( $block, $expectedInfo ) {
		$mock = $this->getMockForTrait( ApiBlockInfoTrait::class );
		$info = TestingAccessWrapper::newFromObject( $mock )->getBlockDetails( $block );
		$subset = array_merge( [
			'blockid' => null,
			'blockedby' => '',
			'blockedbyid' => 0,
			'blockreason' => '',
			'blockexpiry' => 'infinite',
		], $expectedInfo );
		$this->assertArraySubset( $subset, $info );
	}

	public static function provideGetBlockDetails() {
		return [
			'Sitewide block' => [
				new DatabaseBlock(),
				[ 'blockpartial' => false ],
			],
			'Partial block' => [
				new DatabaseBlock( [ 'sitewide' => false ] ),
				[ 'blockpartial' => true ],
			],
			'System block' => [
				new SystemBlock( [ 'systemBlock' => 'proxy' ] ),
				[ 'systemblocktype' => 'proxy' ]
			],
		];
	}
}
