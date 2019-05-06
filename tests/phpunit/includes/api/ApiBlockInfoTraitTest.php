<?php

use Wikimedia\TestingAccessWrapper;

/**
 * @covers ApiBlockInfoTrait
 */
class ApiBlockInfoTraitTest extends MediaWikiTestCase {

	public function testGetBlockInfo() {
		$block = new Block();
		$mock = $this->getMockForTrait( ApiBlockInfoTrait::class );
		$info = TestingAccessWrapper::newFromObject( $mock )->getBlockInfo( $block );
		$subset = [
			'blockid' => null,
			'blockedby' => '',
			'blockedbyid' => 0,
			'blockreason' => '',
			'blockexpiry' => 'infinite',
			'blockpartial' => false,
		];
		$this->assertArraySubset( $subset, $info );
	}

	public function testGetBlockInfoPartial() {
		$mock = $this->getMockForTrait( ApiBlockInfoTrait::class );

		$block = new Block( [
			'sitewide' => false,
		] );
		$info = TestingAccessWrapper::newFromObject( $mock )->getBlockInfo( $block );
		$subset = [
			'blockid' => null,
			'blockedby' => '',
			'blockedbyid' => 0,
			'blockreason' => '',
			'blockexpiry' => 'infinite',
			'blockpartial' => true,
		];
		$this->assertArraySubset( $subset, $info );
	}

}
