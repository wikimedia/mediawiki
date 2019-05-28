<?php

use MediaWiki\Block\DatabaseBlock;

/**
 * @group medium
 * @covers ApiQueryUserInfo
 */
class ApiQueryUserInfoTest extends ApiTestCase {
	public function testGetBlockInfo() {
		$this->hideDeprecated( 'ApiQueryUserInfo::getBlockInfo' );

		$apiQueryUserInfo = new ApiQueryUserInfo(
			new ApiQuery( new ApiMain( $this->apiContext ), 'userinfo' ),
			'userinfo'
		);

		$block = new DatabaseBlock();
		$info = $apiQueryUserInfo->getBlockInfo( $block );
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
		$this->hideDeprecated( 'ApiQueryUserInfo::getBlockInfo' );

		$apiQueryUserInfo = new ApiQueryUserInfo(
			new ApiQuery( new ApiMain( $this->apiContext ), 'userinfo' ),
			'userinfo'
		);

		$block = new DatabaseBlock( [
			'sitewide' => false,
		] );
		$info = $apiQueryUserInfo->getBlockInfo( $block );
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
