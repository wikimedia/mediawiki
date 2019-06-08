<?php

/**
 * @group medium
 * @covers ApiQueryUserInfo
 */
class ApiQueryUserInfoTest extends ApiTestCase {
	public function testGetBlockInfo() {
		$apiQueryUserInfo = new ApiQueryUserInfo(
			new ApiQuery( new ApiMain( $this->apiContext ), 'userinfo' ),
			'userinfo'
		);

		$block = new Block();
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
		$apiQueryUserInfo = new ApiQueryUserInfo(
			new ApiQuery( new ApiMain( $this->apiContext ), 'userinfo' ),
			'userinfo'
		);

		$block = new Block( [
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
