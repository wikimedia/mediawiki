<?php

/**
 * @covers ApiStashEdit
 * @group API
 * @group medium
 * @group Database
 */
class ApiStashEditTest extends ApiTestCase {

	public function testBasicEdit() {
		$apiResult = $this->doApiRequestWithToken(
			[
				'action' => 'stashedit',
				'title' => 'ApistashEdit_Page',
				'contentmodel' => 'wikitext',
				'contentformat' => 'text/x-wiki',
				'text' => 'Text for ' . __METHOD__ . ' page',
				'baserevid' => 0,
			]
		);
		$apiResult = $apiResult[0];
		$this->assertArrayHasKey( 'stashedit', $apiResult );
		$this->assertEquals( 'stashed', $apiResult['stashedit']['status'] );
	}

}
