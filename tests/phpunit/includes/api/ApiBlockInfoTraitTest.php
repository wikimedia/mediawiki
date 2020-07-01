<?php

use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Block\SystemBlock;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers ApiBlockInfoTrait
 */
class ApiBlockInfoTraitTest extends MediaWikiIntegrationTestCase {
	/**
	 * @dataProvider provideGetBlockDetails
	 */
	public function testGetBlockDetails( $block, $expectedInfo ) {
		$mock = $this->getMockForTrait( ApiBlockInfoTrait::class );
		$mock->method( 'getLanguage' )->willReturn( 'en' );
		$info = TestingAccessWrapper::newFromObject( $mock )->getBlockDetails( $block );
		$subset = array_merge( [
			'blockid' => null,
			'blockedby' => '',
			'blockedbyid' => 0,
			'blockreason' => '',
			'blockexpiry' => 'infinite',
		], $expectedInfo );
		$this->assertArraySubmapSame( $subset, $info, "Matching block details" );
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
